<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class Booking extends Model
{
    protected $fillable = [
        'booking_code',
        'customer_id',
        'field_id',
        'booking_date',
        'start_time',
        'end_time',
        'duration',
        'price_per_hour',
        'total_price',
        'status',
        'payment_method',
        'snap_token',
        'midtrans_order_id',
        'midtrans_transaction_id',
        'midtrans_payment_type',
        'reservation_expires_at',
        'payment_due_at',
        'checked_in_at',
        'canceled_by',
        'cancel_reason',
        'canceled_at',
        'notes',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'reservation_expires_at' => 'datetime',
        'payment_due_at' => 'datetime',
        'checked_in_at' => 'datetime',
        'canceled_at' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function field()
    {
        return $this->belongsTo(Field::class);
    }

    /**
     * Generate kode booking yang dijamin unik (booking_code kolomnya UNIQUE).
     *
     * Menggunakan retry loop dengan database transaction untuk mencegah race
     * condition: dua request concurrent tidak akan menghasilkan kode yang sama
     * karena insert dilakukan secara atomic dan duplicate key akan dicoba ulang.
     *
     * @param  int  $maxRetries  Maksimal percobaan sebelum gagal.
     * @return string Kode booking yang unik.
     *
     * @throws QueryException jika gagal setelah maxRetries.
     */
    public static function generateBookingCode(int $maxRetries = 5): string
    {
        $attempt = 0;

        while ($attempt < $maxRetries) {
            $attempt++;

            $code = 'BK-'.now()->format('YmdHis').'-'.str_pad((string) random_int(0, 999), 3, '0', STR_PAD_LEFT);

            try {
                DB::transaction(function () use ($code): void {
                    self::create(['booking_code' => $code]);
                });

                return $code;
            } catch (QueryException $e) {
                // Jika duplicate key pada booking_code, coba ulang dengan kode baru.
                if ($e->getCode() === '23000' || str_contains($e->getMessage(), 'booking_code')) {
                    continue;
                }

                throw $e;
            }
        }

        throw new \Exception('Gagal generate booking code unik setelah '.$maxRetries.' percobaan.');
    }

    /**
     * Terapkan status transaksi dari Midtrans (dipakai oleh webhook maupun polling manual)
     * ke status booking. Dipanggil dengan payload dari Notification atau Transaction::status().
     */
    public function applyMidtransStatus(
        string $transactionStatus,
        ?string $fraudStatus,
        ?string $paymentType,
        ?string $transactionId
    ): void {
        // Jangan proses ulang booking yang sudah final
        if (in_array($this->status, ['confirmed', 'completed', 'canceled'])) {
            return;
        }

        $status = match (true) {
            $transactionStatus === 'capture' && $fraudStatus === 'accept' => 'paid',
            $transactionStatus === 'capture' && $fraudStatus === 'challenge' => 'pending_payment',
            $transactionStatus === 'settlement' => 'paid',
            in_array($transactionStatus, ['deny', 'cancel']) => 'canceled',
            $transactionStatus === 'expire' => 'canceled',
            $transactionStatus === 'pending' => 'pending_payment',
            default => $this->status,
        };

        $attributes = [
            'status' => $status,
            'midtrans_transaction_id' => $transactionId,
            'midtrans_payment_type' => $paymentType,
        ];

        if ($status === 'canceled') {
            $attributes['canceled_by'] = 'system';
            $attributes['cancel_reason'] = 'payment_timeout';
            $attributes['canceled_at'] = now();
        }

        $this->update($attributes);
    }
}
