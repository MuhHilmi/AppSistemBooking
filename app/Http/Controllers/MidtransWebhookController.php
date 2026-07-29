<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MidtransWebhookController extends Controller
{
    public function __construct(protected MidtransService $midtrans)
    {
    }

    /**
     * Endpoint yang didaftarkan sebagai "Payment Notification URL" di dashboard Midtrans.
     * Dipanggil oleh server Midtrans (server-to-server), bukan oleh browser customer.
     */
    public function handle(Request $request)
    {
        try {
            $notification = $this->midtrans->readNotification();
        } catch (\Exception $e) {
            Log::error('Midtrans notification tidak valid: ' . $e->getMessage());
            return response()->json(['message' => 'Invalid notification'], 400);
        }

        $orderId = $notification->order_id;

        // order_id yang kita kirim = booking_code (lihat MidtransService::createSnapTokenForBooking)
        $booking = Booking::where('midtrans_order_id', $orderId)
            ->orWhere('booking_code', $orderId)
            ->first();

        if (!$booking) {
            Log::warning("Midtrans notification: booking dengan order_id {$orderId} tidak ditemukan.");
            return response()->json(['message' => 'Booking not found'], 404);
        }

        $booking->applyMidtransStatus(
            transactionStatus: $notification->transaction_status,
            fraudStatus: $notification->fraud_status ?? null,
            paymentType: $notification->payment_type ?? null,
            transactionId: $notification->transaction_id ?? null,
        );

        return response()->json(['message' => 'OK']);
    }
}
