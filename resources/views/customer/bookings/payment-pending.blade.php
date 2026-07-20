@extends('layouts.customer')

@section('title', 'Booking | Pending Payment')

@section('content')
<div class="mx-auto max-w-4xl px-4 py-8">
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
        {{-- Header --}}
        <div class="border-b border-gray-200 px-6 py-5">
            <h1 class="text-2xl font-bold text-gray-900">
                Menunggu Pembayaran
            </h1>
            <p class="mt-1 text-sm text-gray-500">
                Silakan selesaikan pembayaran sebelum batas waktu berakhir.
            </p>
        </div>
        <div class="space-y-6 p-6">
            {{-- Ringkasan Booking --}}
            @include('customer.bookings.partials.booking-summary')
            {{-- Informasi Pembayaran --}}
            <div class="overflow-hidden rounded-lg border border-gray-200">
                <div class="border-b border-gray-200 bg-gray-50 px-5 py-3">
                    <h2 class="font-semibold text-gray-800">
                        Informasi Pembayaran
                    </h2>
                </div>
                <div class="divide-y divide-gray-100">
                    <div class="flex items-center justify-between px-5 py-4">
                        <span class="text-sm font-medium text-gray-500">
                            Metode Pembayaran
                        </span>
                        <span class="font-medium text-gray-900">
                            @switch($booking->payment_method)
                                @case('cash')
                                    Cash
                                    @break
                                @case('transfer')
                                    Transfer Bank
                                    @break
                                @case('qris')
                                    QRIS
                                    @break
                                @default
                                    -
                            @endswitch
                        </span>
                    </div>
                    <div class="flex items-center justify-between px-5 py-4">
                        <span class="text-sm font-medium text-gray-500">
                            Total Pembayaran
                        </span>
                        <span class="text-xl font-bold text-indigo-600">
                            Rp{{ number_format($booking->total_price, 0, ',', '.') }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between px-5 py-4">
                        <span class="text-sm font-medium text-gray-500">
                            Batas Pembayaran
                        </span>
                        <span id="payment-countdown" data-deadline="{{ $booking->payment_due_at->toIso8601String() }}"
                            class="text-lg font-bold text-red-600">
                        </span>
                    </div>
                    <div class="flex items-center justify-between px-5 py-4">
                        <span class="text-sm font-medium text-gray-500">
                            Status
                        </span>
                        <x-booking-status-badge :status="$booking->status" />
                    </div>
                </div>
            </div>

            {{-- Placeholder Midtrans --}}
            <div class="rounded-lg border border-dashed border-indigo-300 bg-indigo-50 p-5">
                <h3 class="font-semibold text-indigo-900">
                    Informasi Pembayaran
                </h3>
                <p class="mt-2 text-sm text-indigo-700">
                    Informasi pembayaran akan ditampilkan di sini setelah integrasi Midtrans dilakukan.
                </p>
            </div>

            {{-- Action Button --}}
            <div class="flex flex-col gap-3 sm:flex-row">
                <a href="{{ route('customer.bookings.payment.pending', $booking) }}" class="inline-flex items-center justify-center rounded-lg bg-amber-500 px-5 py-3 font-medium text-white transition hover:bg-amber-600">
                    Refresh Status
                </a>
                <a href="{{ route('customer.bookings.show', $booking) }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-5 py-3 font-medium text-gray-700 transition hover:bg-gray-100">
                    Kembali ke Detail Booking
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        const countdown = document.getElementById('payment-countdown')
        const deadline = new Date(countdown.dataset.deadline)

        function updateCountdown() {
            const now = new Date();
            const distance = deadline - now;

            if (distance <= 0) {
                countdown.innerHTML = 'Waktu pembayaran telah habis. Halaman akan diperbaharui.';
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            }

            return;
        }

        const hours = Math.floor(distance / (1000 * 60 * 60))
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        if (hours > 0) {
            countdown.innerHTML = `${String(hours).padStart(2,'0')}:${String(minutes).padStart(2,'0')}:${String(seconds).padStart(2,'0')}`;
        } else {
            countdown.innerHTML = `${String(minutes).padStart(2,'0')}:${String(seconds).padStart(2,'0')}`;
        }

        updateCountdown();
        setInterval(() => {
            updateCountdown()
        }, 1000);
    </script>
@endpush
