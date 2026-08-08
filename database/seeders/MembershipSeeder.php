<?php

namespace Database\Seeders;

use App\Models\Benefit;
use App\Models\MembershipTier;
use Illuminate\Database\Seeder;

class MembershipSeeder extends Seeder
{
    public function run(): void
    {
        $rookie = MembershipTier::updateOrCreate(
            ['code' => 'rookie'],
            [
                'name' => 'Rookie',
                'level' => 1,
                'min_booking' => 0,
                'min_spend' => 0,
                'evaluation_period_days' => 90,
                'booking_advance_days' => 3,
                'point_multiplier' => 1.00,
            ]
        );

        $pro = MembershipTier::updateOrCreate(
            ['code' => 'pro'],
            [
                'name' => 'Pro',
                'level' => 2,
                'min_booking' => 4,
                'min_spend' => 400000,
                'evaluation_period_days' => 90,
                'booking_advance_days' => 7,
                'point_multiplier' => 1.25,
            ]
        );

        $league = MembershipTier::updateOrCreate(
            ['code' => 'league'],
            [
                'name' => 'League',
                'level' => 3,
                'min_booking' => 8,
                'min_spend' => 1000000,
                'evaluation_period_days' => 90,
                'booking_advance_days' => 14,
                'point_multiplier' => 1.50,
            ]
        );

        $benefits = [
            [
                'code' => 'priority-slot',
                'name' => 'Prioritas booking slot ramai',
                'type' => 'priority_booking',
                'value_type' => 'boolean',
                'value' => '1',
                'description' => 'Dapat booking slot ramai lebih awal dari member lain.',
            ],
            [
                'code' => 'flex-reschedule',
                'name' => 'Reschedule/cancel fleksibel',
                'type' => 'flexible_reschedule',
                'value_type' => 'text',
                'value' => 'unlimited',
                'description' => 'Reschedule atau cancel booking tanpa batas.',
            ],
            [
                'code' => 'free-drink',
                'name' => 'Gratis minuman',
                'type' => 'free_item',
                'value_type' => 'text',
                'value' => '1 minuman',
                'description' => 'Tukar poin otomatis gratis 1 minuman di venue.',
            ],
            [
                'code' => 'birthday-voucher',
                'name' => 'Voucher ulang tahun',
                'type' => 'voucher',
                'value_type' => 'fixed_amount',
                'value' => '50000',
                'description' => 'Voucher spesial di bulan ulang tahun member.',
            ],
            [
                'code' => 'exclusive-promo',
                'name' => 'Akses promo eksklusif',
                'type' => 'exclusive_promo',
                'value_type' => 'boolean',
                'value' => '1',
                'description' => 'Akses ke flash sale & promo khusus member.',
            ],
            [
                'code' => 'dedicated-cs',
                'name' => 'Dedicated customer support',
                'type' => 'dedicated_support',
                'value_type' => 'boolean',
                'value' => '1',
                'description' => 'Jalur CS prioritas khusus member League.',
            ],
        ];

        foreach ($benefits as $benefitData) {
            Benefit::updateOrCreate(['code' => $benefitData['code']], $benefitData);
        }

        $priority = Benefit::where('code', 'priority-slot')->first();
        $flexReschedule = Benefit::where('code', 'flex-reschedule')->first();
        $freeDrink = Benefit::where('code', 'free-drink')->first();
        $birthdayVoucher = Benefit::where('code', 'birthday-voucher')->first();
        $exclusivePromo = Benefit::where('code', 'exclusive-promo')->first();
        $dedicatedCs = Benefit::where('code', 'dedicated-cs')->first();

        // Rookie: hanya voucher ulang tahun kecil
        $rookie->benefits()->syncWithoutDetaching([
            $birthdayVoucher->id => ['usage_limit' => 1, 'limit_period' => 'year'],
        ]);

        // Pro: prioritas slot, reschedule fleksibel, gratis minuman 1x/bulan, promo sebagian, voucher lebih besar
        $pro->benefits()->syncWithoutDetaching([
            $priority->id => ['usage_limit' => null, 'limit_period' => null],
            $flexReschedule->id => ['usage_limit' => null, 'limit_period' => null],
            $freeDrink->id => ['usage_limit' => 1, 'limit_period' => 'month'],
            $exclusivePromo->id => ['usage_limit' => null, 'limit_period' => null],
            $birthdayVoucher->id => ['usage_limit' => 1, 'limit_period' => 'year'],
        ]);

        // League: semua benefit, gratis minuman 2x/bulan, dedicated CS
        $league->benefits()->syncWithoutDetaching([
            $priority->id => ['usage_limit' => null, 'limit_period' => null],
            $flexReschedule->id => ['usage_limit' => null, 'limit_period' => null],
            $freeDrink->id => ['usage_limit' => 2, 'limit_period' => 'month'],
            $exclusivePromo->id => ['usage_limit' => null, 'limit_period' => null],
            $dedicatedCs->id => ['usage_limit' => null, 'limit_period' => null],
            $birthdayVoucher->id => ['usage_limit' => 1, 'limit_period' => 'year'],
        ]);
    }
}
