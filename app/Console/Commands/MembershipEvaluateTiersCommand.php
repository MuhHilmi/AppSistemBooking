<?php

namespace App\Console\Commands;

use App\Models\Customer;
use App\Services\MembershipService;
use Carbon\Carbon;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('membership:evaluate-tiers')]
#[Description('Evaluasi naik/turun tier untuk semua customer yang siklus evaluasinya sudah berakhir')]
class MembershipEvaluateTiersCommand extends Command
{
    public function __construct(protected MembershipService $membershipService)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $customerIds = Customer::whereHas('membership', function ($query) {
            $query->whereDate('cycle_end_at', '<=', Carbon::today());
        })->pluck('id');

        foreach ($customerIds as $customerId) {
            $this->membershipService->evaluateTier(Customer::find($customerId));
        }

        $this->info("Membership yang dievaluasi: {$customerIds->count()}.");

        return self::SUCCESS;
    }
}
