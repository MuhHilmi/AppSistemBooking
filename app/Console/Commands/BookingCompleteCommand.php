<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Services\MembershipService;
use Carbon\Carbon;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('booking:complete')]
#[Description('Tandai booking confirmed yang jam mainnya sudah lewat sebagai completed, lalu beri poin membership')]
class BookingCompleteCommand extends Command
{
    public function __construct(protected MembershipService $membershipService)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $bookings = Booking::where('status', 'confirmed')->get()->filter(function (Booking $booking) {
            $end = Carbon::parse($booking->booking_date->format('Y-m-d').' '.$booking->end_time);
            return $end->isPast();
        });

        foreach ($bookings as $booking) {
            $booking->update(['status' => 'completed']);
            $this->membershipService->awardPointsForBooking($booking);
        }

        $this->info("Booking diselesaikan & poin diberikan: {$bookings->count()}.");

        return self::SUCCESS;
    }
}
