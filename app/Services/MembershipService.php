<?php

namespace App\Services;

use App\Models\Benefit;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\CustomerMembership;
use App\Models\MembershipTier;
use App\Models\PointTransaction;
use App\Models\TierHistory;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class MembershipService
{
    /**
     * Berapa rupiah setara 1 poin dasar (sebelum dikalikan multiplier tier).
     */
    private const RUPIAH_PER_POINT = 10000;

    /**
     * Masa berlaku 1 batch poin hasil earning, dalam bulan.
     */
    private const POINT_EXPIRY_MONTHS = 6;

    /**
     * Target streak mingguan (booking minimal 1x per minggu).
     */
    private const STREAK_MILESTONES = [
        2 => 10,
        4 => 25,
        8 => 60,
        12 => 100,
    ];

    /**
     * Dipanggil saat booking berstatus "completed": beri poin, update
     * qualification tier, dan update booking streak.
     */
    public function awardPointsForBooking(Booking $booking): void
    {
        DB::transaction(function () use ($booking) {
            $customer = $booking->customer()->lockForUpdate()->first();
            $membership = $customer->membership()->lockForUpdate()->first();

            if (! $membership) {
                // Jaga-jaga untuk akun lama yang dibuat sebelum fitur membership ada.
                $membership = $this->createDefaultMembership($customer);
            }

            $tier = $membership->tier;

            $basePoint = intdiv((int) $booking->total_price, self::RUPIAH_PER_POINT);
            $earnedPoint = (int) floor($basePoint * (float) $tier->point_multiplier);

            if ($earnedPoint > 0) {
                $this->creditPoints(
                    $customer,
                    $earnedPoint,
                    referenceType: 'booking',
                    referenceId: $booking->booking_code,
                    note: "Poin booking {$booking->booking_code}",
                );
            }

            // Update progres kualifikasi tier untuk cycle berjalan
            $membership->increment('qualification_booking_count');
            $membership->increment('qualification_spend', $booking->total_price);

            $this->recordStreak($customer, Carbon::parse($booking->booking_date));
            $this->evaluateTier($customer->fresh());
        });
    }

    /**
     * Tambah poin ke saldo customer (batch baru dengan masa expired).
     */
    public function creditPoints(
        Customer $customer,
        int $amount,
        ?string $referenceType = null,
        ?string $referenceId = null,
        ?string $note = null,
    ): PointTransaction {
        $transaction = PointTransaction::create([
            'customer_id' => $customer->id,
            'type' => 'earn',
            'amount' => $amount,
            'remaining_amount' => $amount,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            'note' => $note,
            'expired_at' => now()->addMonths(self::POINT_EXPIRY_MONTHS),
        ]);

        $customer->membership()->increment('current_point', $amount);

        return $transaction;
    }

    /**
     * Tukar poin customer dengan benefit tertentu. Poin dipotong secara FIFO
     * dari batch earning yang paling dulu kedaluwarsa.
     *
     * @throws \RuntimeException jika benefit tidak bisa ditukar poin atau poin tidak cukup
     */
    public function redeemBenefit(Customer $customer, Benefit $benefit): \App\Models\BenefitRedemption
    {
        if (! $benefit->isRedeemable()) {
            throw new \RuntimeException('Benefit ini tidak bisa ditukar dengan poin.');
        }

        $pointCost = $benefit->point_cost;

        return DB::transaction(function () use ($customer, $benefit, $pointCost) {
            $membership = $customer->membership()->lockForUpdate()->first();

            if ($membership->current_point < $pointCost) {
                throw new \RuntimeException('Poin tidak cukup untuk menukar benefit ini.');
            }

            $redeemTransaction = PointTransaction::create([
                'customer_id' => $customer->id,
                'type' => 'redeem',
                'amount' => -$pointCost,
                'reference_type' => 'benefit',
                'reference_id' => (string) $benefit->id,
                'note' => "Tukar poin: {$benefit->name}",
            ]);

            $this->deductFifo($customer, $pointCost);

            $membership->decrement('current_point', $pointCost);

            return \App\Models\BenefitRedemption::create([
                'customer_id' => $customer->id,
                'benefit_id' => $benefit->id,
                'point_transaction_id' => $redeemTransaction->id,
                'points_used' => $pointCost,
                'status' => 'pending',
                'redeemed_at' => now(),
            ]);
        });
    }

    /**
     * Kurangi remaining_amount dari batch earn tertua terlebih dahulu (FIFO),
     * supaya poin yang lebih dulu kedaluwarsa yang lebih dulu terpakai.
     */
    private function deductFifo(Customer $customer, int $amount): void
    {
        $batches = PointTransaction::where('customer_id', $customer->id)
            ->where('type', 'earn')
            ->where('remaining_amount', '>', 0)
            ->orderBy('expired_at')
            ->lockForUpdate()
            ->get();

        foreach ($batches as $batch) {
            if ($amount <= 0) {
                break;
            }

            $take = min($amount, $batch->remaining_amount);
            $batch->decrement('remaining_amount', $take);
            $amount -= $take;
        }
    }

    /**
     * Jalankan expired harian: batch poin yang lewat expired_at dan masih
     * punya remaining_amount akan dihanguskan.
     */
    public function expirePoints(): int
    {
        $expiredBatches = PointTransaction::where('type', 'earn')
            ->where('remaining_amount', '>', 0)
            ->whereDate('expired_at', '<', now())
            ->get();

        $count = 0;

        foreach ($expiredBatches as $batch) {
            DB::transaction(function () use ($batch, &$count) {
                $amount = $batch->remaining_amount;

                PointTransaction::create([
                    'customer_id' => $batch->customer_id,
                    'type' => 'expire',
                    'amount' => -$amount,
                    'reference_type' => 'point_transaction',
                    'reference_id' => (string) $batch->id,
                    'note' => 'Poin kedaluwarsa',
                ]);

                $batch->update(['remaining_amount' => 0]);

                CustomerMembership::where('customer_id', $batch->customer_id)
                    ->decrement('current_point', $amount);

                $count++;
            });
        }

        return $count;
    }

    /**
     * Update booking streak mingguan customer berdasarkan tanggal booking baru.
     * Toleransi 1 minggu bolong (status jadi "warning") sebelum streak reset.
     */
    public function recordStreak(Customer $customer, Carbon $bookingDate): void
    {
        $streak = $customer->streak()->lockForUpdate()->first();

        if (! $streak) {
            $streak = $customer->streak()->create([
                'current_streak' => 0,
                'longest_streak' => 0,
                'streak_status' => 'inactive',
            ]);
        }

        $bookingWeek = $bookingDate->copy()->startOfWeek();

        if (! $streak->last_booking_date) {
            $streak->update([
                'current_streak' => 1,
                'longest_streak' => max(1, $streak->longest_streak),
                'last_booking_date' => $bookingDate,
                'streak_status' => 'active',
            ]);
            return;
        }

        $lastWeek = Carbon::parse($streak->last_booking_date)->startOfWeek();
        $weekGap = $lastWeek->diffInWeeks($bookingWeek);

        if ($weekGap === 0) {
            // Booking kedua di minggu yang sama, tidak menambah streak tapi
            // tetap update tanggal terakhir booking.
            $streak->update(['last_booking_date' => $bookingDate]);
            return;
        }

        if ($weekGap === 1) {
            // Booking minggu berikutnya berturut-turut.
            $newStreak = $streak->current_streak + 1;
            $streak->update([
                'current_streak' => $newStreak,
                'longest_streak' => max($newStreak, $streak->longest_streak),
                'last_booking_date' => $bookingDate,
                'streak_status' => 'active',
            ]);

            $this->awardStreakMilestone($customer, $newStreak);
            return;
        }

        if ($weekGap === 2) {
            // Toleransi 1 minggu bolong: streak tidak reset, tapi ditandai warning.
            $streak->update([
                'last_booking_date' => $bookingDate,
                'streak_status' => 'warning',
            ]);
            return;
        }

        // Lebih dari 1 minggu bolong: streak reset dan mulai lagi dari 1.
        $streak->update([
            'current_streak' => 1,
            'last_booking_date' => $bookingDate,
            'streak_status' => 'active',
        ]);
    }

    private function awardStreakMilestone(Customer $customer, int $streakWeeks): void
    {
        if (! isset(self::STREAK_MILESTONES[$streakWeeks])) {
            return;
        }

        $this->creditPoints(
            $customer,
            self::STREAK_MILESTONES[$streakWeeks],
            referenceType: 'streak',
            referenceId: (string) $streakWeeks,
            note: "Bonus streak {$streakWeeks} minggu berturut-turut",
        );
    }

    /**
     * Evaluasi tier customer. Hanya benar-benar mengubah tier / reset cycle
     * ketika cycle_end_at sudah lewat (evaluasi periodik), kecuali $force.
     */
    public function evaluateTier(Customer $customer, bool $force = false): void
    {
        $membership = $customer->membership()->lockForUpdate()->first();

        if (! $membership) {
            return;
        }

        if (! $force && Carbon::today()->lt($membership->cycle_end_at)) {
            return;
        }

        $eligibleTier = MembershipTier::orderByDesc('level')
            ->get()
            ->first(function (MembershipTier $tier) use ($membership) {
                return $membership->qualification_booking_count >= $tier->min_booking
                    || $membership->qualification_spend >= $tier->min_spend;
            }) ?? MembershipTier::defaultTier();

        $currentTier = $membership->tier;

        if ($eligibleTier->id !== $currentTier->id) {
            $reason = $eligibleTier->level > $currentTier->level ? 'upgrade' : 'downgrade';

            TierHistory::create([
                'customer_id' => $customer->id,
                'from_tier_id' => $currentTier->id,
                'to_tier_id' => $eligibleTier->id,
                'reason' => $reason,
                'changed_at' => now(),
            ]);

            $membership->tier_id = $eligibleTier->id;
        }

        // Mulai cycle evaluasi baru
        $membership->qualification_booking_count = 0;
        $membership->qualification_spend = 0;
        $membership->cycle_start_at = Carbon::today();
        $membership->cycle_end_at = Carbon::today()->addDays($eligibleTier->evaluation_period_days);
        $membership->save();
    }

    private function createDefaultMembership(Customer $customer): CustomerMembership
    {
        $defaultTier = MembershipTier::defaultTier();

        return $customer->membership()->create([
            'tier_id' => $defaultTier->id,
            'current_point' => 0,
            'qualification_booking_count' => 0,
            'qualification_spend' => 0,
            'cycle_start_at' => Carbon::today(),
            'cycle_end_at' => Carbon::today()->addDays($defaultTier->evaluation_period_days),
        ]);
    }
}
