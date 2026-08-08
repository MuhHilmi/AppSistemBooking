<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Carbon\Carbon;

class Customer extends Authenticatable
{
    protected $fillable = [
        'name',
        'phone',
        'password',
        'is_verified'
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    protected static function booted(): void
    {
        // Setiap akun baru otomatis punya membership (mulai dari tier terendah)
        // dan streak, persis seperti Alfagift: tidak ada proses "join member" terpisah.
        static::created(function (Customer $customer) {
            $defaultTier = MembershipTier::defaultTier();

            $customer->membership()->create([
                'tier_id' => $defaultTier->id,
                'current_point' => 0,
                'qualification_booking_count' => 0,
                'qualification_spend' => 0,
                'cycle_start_at' => Carbon::today(),
                'cycle_end_at' => Carbon::today()->addDays($defaultTier->evaluation_period_days),
            ]);

            $customer->streak()->create([
                'current_streak' => 0,
                'longest_streak' => 0,
                'streak_status' => 'inactive',
            ]);

            TierHistory::create([
                'customer_id' => $customer->id,
                'from_tier_id' => null,
                'to_tier_id' => $defaultTier->id,
                'reason' => 'initial',
                'changed_at' => now(),
            ]);
        });
    }

    public function otps()
    {
        return $this->hasMany(
            OtpVerification::class,
            'customers_id'
        );
    }

    public function bookings() {
        return $this->hasMany(Booking::class);
    }

    public function reviews() {
        return $this->hasMany(Review::class);
    }

    public function membership()
    {
        return $this->hasOne(CustomerMembership::class);
    }

    public function streak()
    {
        return $this->hasOne(CustomerStreak::class);
    }

    public function pointTransactions()
    {
        return $this->hasMany(PointTransaction::class);
    }

    public function tierHistories()
    {
        return $this->hasMany(TierHistory::class);
    }

    public function benefitRedemptions()
    {
        return $this->hasMany(BenefitRedemption::class);
    }
}
