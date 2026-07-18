@extends('layouts.customer')

@section('title', 'Booking | Payment')

@section('content')
    <div class="mx-auto px-6 py-3 rounded-lg shadow max-w-7xl bg-white">
        <div>
            <div class="mb-4">
                <h3 class="font-semibold">Kode Booking</h3>
                <p>{{ $booking->booking_code }}</p>
            </div>
            <div class="mb-4">
                <h3 class="font-semibold">Lapangan</h3>
                <p>{{ $booking->field->name }}</p>
            </div>
            <div class="mb-4">
                <h3 class="font-semibold">Tanggal</h3>
                <p>{{ $booking->booking_date }}</p>
            </div>
            <div class="mb-4">
                <h3 class="font-semibold">Jam</h3>
                <p>{{ $booking->start_time }} - {{ $booking->end_time }}</p>
                <small>Durasi : {{ $booking->duration }} jam</small>
            </div>
            <div class="mb-4">
                <h3 class="font-semibold">Total</h3>
                <p>{{ $booking->total_price }}</p>
            </div>
        </div>
        <div class="border border-dashed mb-4 border-black"></div>
        <div class="flex flex-row justify-between items-center gap-4">
            <div>
                <h3>Pilih metode pembayaran</h3>
                <div class="flex felx-row gap-4 mt-2">
                    <button type="button" class="font-semibold h-10 w-24 bg-blue-500 text-white hover:bg-blue-600 hover:-translate-y-1 rounded-lg shadow transition">Cash</button>
                    <button type="button" class="font-semibold h-10 w-24 bg-green-500 text-white hover:bg-green-600 hover:-translate-y-1 rounded-lg shadow transition">QRIS</button>
                    <button type="button" class="font-semibold h-10 w-24 bg-orange-500 text-white hover:bg-orange-600 hover:-translate-y-1 rounded-lg shadow transition disable">Transfer</button>
                </div>
            </div>
            <div>
                <button class="px-4 py-2 rounded-lg bg-slate-500 text-white hover:bg-slate-600 transition" type="button">Lanjutkan Pembayaran</button>
            </div>
        </div>
    </div>
@endsection