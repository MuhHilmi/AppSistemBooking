<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use App\Models\OtpVerification;
use Illuminate\Console\Command;

#[Signature('app:clear-expired-otp')]
#[Description('Command description')]
class ClearExpiredOtp extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        OtpVerification::where(
            'expired_at',
            '<',
            now()
        )->delete();
    }
}
