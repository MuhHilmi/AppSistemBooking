<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Services\MembershipService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerPointSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(MembershipService $membershipService): void
    {
        // Cara ke-1 yang memberikan ke customer tertentu.
        $customer = Customer::where('email', 'customer@customer.com')->first();
        if ($customer) {
            $membershipService->creditPoints(
                $customer,
                500,
                referenceType: 'seeder',
                referenceId: 'demo-points',
                note: 'Poin demo dari seeder',
            );
        }

        // Cara ke-2 yang memberikan poin ke seluruh customer.
        // Customer::all()->each(function (Customer $customer) use ($membershipService) {
        //     $membershipService->creditPoints(
        //         $customer,
        //         rand(50, 300),
        //         referenceType: 'seeder',
        //         referenceId: 'demo-points',
        //         note: 'Poin demo dari seeder',
        //     );
        // });
    }
}
