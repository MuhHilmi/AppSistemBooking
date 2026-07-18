<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BookingController;
use App\Models\Booking;
use Illuminate\Validation\Rule;

class BookingPaymentController extends Controller
{
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
                    'status' => 'confirmed',
                    'payment_due_at' => null
                ]);
                break;
            
            case 'transfer':
                $booking->update([
                    'payment_method' => 'transfer',
                    'status' => 'pending_payment',
                    'payment_due_at' => now()->addMinutes(30)
                ]);
                break;
            case 'qris':
                $booking->update([
                    'payment_method' => 'qris',
                    'status' => 'pending_payment',
                    'payment_due_at' => now()->addMinutes(30)
                ]);
                break;
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
