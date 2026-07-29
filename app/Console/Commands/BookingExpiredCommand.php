<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use App\Models\Booking;
use App\Services\MidtransService;
use Carbon\Carbon;

#[Signature('booking:expire')]
#[Description('Cancel expired reservations and pending payment bookings')]
class BookingExpiredCommand extends Command
{
    public function __construct(protected MidtransService $midtrans)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expiredReservations = Booking::where('status', 'waiting_payment_method')
            ->whereNotNull('reservation_expires_at')
            ->where('reservation_expires_at', '<', now())
            ->get();

        foreach ($expiredReservations as $booking) {
            $booking->update([
                'status' => 'canceled',
                'canceled_by' => 'system',
                'cancel_reason' => 'reservation_timeout',
                'canceled_at' => now(),
            ]);
        }

        $expiredPayments = Booking::where('status', 'pending_payment')
            ->whereNotNull('payment_due_at')
            ->where('payment_due_at', '<', now())
            ->get();

        foreach ($expiredPayments as $booking) {
            // Batalkan juga transaksi di Midtrans (VA/QRIS) supaya tidak bisa dibayar lagi
            // setelah booking dianggap expired di sistem kita.
            if ($booking->midtrans_order_id) {
                try {
                    \Midtrans\Transaction::cancel($booking->midtrans_order_id);
                } catch (\Exception $e) {
                    // Transaksi mungkin sudah settlement/expired/tidak ditemukan di sisi Midtrans, aman diabaikan.
                    report($e);
                }
            }

            $booking->update([
                'status' => 'canceled',
                'canceled_by' => 'system',
                'cancel_reason' => 'payment_timeout',
                'canceled_at' => now(),
            ]);
        }

        $this->info(sprintf(
            'Expired bookings processed successfully. Reservations: %d, Payments: %d.',
            $expiredReservations->count(),
            $expiredPayments->count(),
        ));

        return self::SUCCESS;
    }
}
