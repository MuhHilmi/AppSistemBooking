<?php

namespace App\Console\Commands;

use App\Services\MembershipService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('membership:expire-points')]
#[Description('Hanguskan batch poin yang sudah lewat masa berlaku')]
class MembershipExpirePointsCommand extends Command
{
    public function __construct(protected MembershipService $membershipService)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $count = $this->membershipService->expirePoints();
        $this->info("Batch poin kedaluwarsa yang dihanguskan: {$count}.");

        return self::SUCCESS;
    }
}
