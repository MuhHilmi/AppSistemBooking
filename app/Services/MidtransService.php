<?php

namespace App\Services;

use App\Models\Booking;
use Midtrans\Config;
use Midtrans\Notification;
use Midtrans\Snap;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    public function createSnapToken(array $params)
    {
        return Snap::getSnapToken($params);
    }

    /**
     * Buat Snap token untuk sebuah booking, lalu simpan token & order_id ke booking.
     * Membatasi metode pembayaran yang muncul di Snap sesuai pilihan customer
     * (transfer -> Virtual Account, qris -> QRIS/GoPay).
     */
    public function createSnapTokenForBooking(Booking $booking): string
    {
        $booking->loadMissing('field.venue', 'customer');

        // order_id Midtrans harus unik per transaksi.
        // Pakai booking_code + suffix supaya tetap unik jika booking pernah dibatalkan & diulang.
        $orderId = $booking->midtrans_order_id ?? $booking->booking_code;

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $booking->total_price,
            ],
            'customer_details' => [
                'first_name' => $booking->customer->name,
                'phone' => $booking->customer->phone,
            ],
            'item_details' => [
                [
                    'id' => 'BOOKING-' . $booking->id,
                    'price' => (int) $booking->total_price,
                    'quantity' => 1,
                    'name' => 'Sewa ' . $booking->field->name . ' (' . $booking->duration . ' jam)',
                ],
            ],
            'enabled_payments' => match ($booking->payment_method) {
                'transfer' => ['bca_va', 'bni_va', 'bri_va', 'permata_va', 'other_va'],
                'qris' => ['other_qris', 'gopay'],
                default => null,
            },
        ];

        // Buang key enabled_payments kalau null, biar Snap tampilkan semua metode
        if ($params['enabled_payments'] === null) {
            unset($params['enabled_payments']);
        }

        $snapToken = $this->createSnapToken($params);

        $booking->update([
            'snap_token' => $snapToken,
            'midtrans_order_id' => $orderId,
        ]);

        return $snapToken;
    }

    /**
     * Baca & verifikasi notifikasi dari Midtrans (dipanggil dari webhook).
     */
    public function readNotification(): Notification
    {
        return new Notification();
    }
}
