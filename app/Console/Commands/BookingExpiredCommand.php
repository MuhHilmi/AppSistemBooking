<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use App\Models\Booking;
use Carbon\Carbon;

#[Signature('booking:expire')]
#[Description('Cancel expired pending payment bookings')]
class BookingExpiredCommand extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expiredBookings = Booking::where('status', 'pending_payment')
            ->where('created_at', '<', now()->subMinutes(30))
            ->get();
        foreach ($expiredBookings as $booking) {
            $booking->update([
                'status' => 'canceled',
                'canceled_by' => 'system',
                'cancel_reason' => 'payment_timeout',
                'canceled_at' => now(),
            ]);
        }
        $this->info('Expired bookings processed successfully.');
        return self::SUCCESS;
    }
}
