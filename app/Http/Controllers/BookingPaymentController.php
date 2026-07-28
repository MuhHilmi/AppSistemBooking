<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BookingController;
use App\Models\Booking;
use App\Services\MidtransService;
use Illuminate\Validation\Rule;

class BookingPaymentController extends Controller
{
    public function __construct(protected MidtransService $midtrans)
    {
        //
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Booking $booking)
    {
        abort_unless($booking->customer_id == auth('customer')->id(), 403);

        if ($booking->status !== 'waiting_payment_method') {
            return back()->with('error', 'Booking sedang diproses');
        }

        $request->validate([
            'payment_method' => ['required', Rule::in(['cash', 'transfer', 'qris']),],
        ]);

        switch ($request->payment_method) {
            case 'cash':
                $booking->update([
                    'payment_method' => 'cash',
                    'status' => 'pending_payment',
                    'payment_due_at' => null, // cash dikonfirmasi manual oleh owner, tidak ada batas waktu otomatis
                ]);
                break;
            case 'transfer':
            case 'qris':
                $booking->update([
                    'payment_method' => $request->payment_method,
                    'status' => 'pending_payment',
                    'payment_due_at' => now()->addMinutes(30)
                ]);

                try {
                    $this->midtrans->createSnapTokenForBooking($booking);
                } catch (\Exception $e) {
                    report($e);
                    return back()->with('error', 'Gagal membuat transaksi pembayaran. Silakan coba lagi.');
                }
                break;
        }

        if ($booking->status === 'pending_payment') {
            return redirect()->route('customer.bookings.payment.pending', $booking)->with('success', 'Metode pembayaran berhasil dipilih.');
        }

        return redirect()->route('customer.bookings.show', $booking)->with('success', 'Metode pembayaran berhasil dipilih.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Booking $booking)
    {
        abort_unless($booking->customer_id == auth('customer')->id(), 403);

        if ($booking->status !== 'waiting_payment_method') {
            return redirect()->route('customer.bookings.show', $booking);
        }

        return view('customer.bookings.payment', compact('booking'));
    }

    public function pending(Booking $booking)
    {
        abort_unless($booking->customer_id == auth('customer')->id(), 403);

        if ($booking->status !== 'pending_payment') {
            return redirect()->route('customer.bookings.show', $booking);
        }

        $booking->load('field.venue', 'customer');

        return view('customer.bookings.payment-pending', compact('booking'));
    }

    /**
     * Endpoint ringan untuk polling status booking dari halaman payment-pending
     * (dipakai untuk auto-refresh begitu integrasi Midtrans/webhook sudah aktif).
     */
    public function checkStatus(Booking $booking)
    {
        abort_unless($booking->customer_id == auth('customer')->id(), 403);

        // Fallback: kalau masih pending & sudah pernah dapat order_id dari Midtrans,
        // tanya langsung ke Midtrans (berguna kalau webhook belum/tidak sampai, misal saat testing lokal).
        if ($booking->status === 'pending_payment' && $booking->midtrans_order_id) {
            try {
                $transaction = \Midtrans\Transaction::status($booking->midtrans_order_id);

                $booking->applyMidtransStatus(
                    transactionStatus: $transaction->transaction_status,
                    fraudStatus: $transaction->fraud_status ?? null,
                    paymentType: $transaction->payment_type ?? null,
                    transactionId: $transaction->transaction_id ?? null,
                );
            } catch (\Exception $e) {
                report($e);
            }
        }

        return response()->json([
            'status' => $booking->status,
            'is_final' => in_array($booking->status, ['confirmed', 'paid', 'completed', 'canceled']),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
