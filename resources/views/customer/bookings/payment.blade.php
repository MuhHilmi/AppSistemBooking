@extends('layouts.customer')

@section('title', 'Booking | Payment')

@section('content')
<div class="mx-auto max-w-4xl px-4 py-8">

    {{-- Step indicator --}}
    {{-- <ol class="mb-6 flex items-center text-sm font-medium text-gray-500">
        <li class="flex items-center text-gray-400">
            <span class="flex h-6 w-6 items-center justify-center rounded-full bg-gray-100 text-xs">✓</span>
            <span class="ml-2 hidden sm:inline">Booking Dibuat</span>
        </li>
        <li class="mx-3 h-px w-8 bg-gray-200"></li>
        <li class="flex items-center text-indigo-600">
            <span class="flex h-6 w-6 items-center justify-center rounded-full bg-indigo-600 text-xs text-white">2</span>
            <span class="ml-2 hidden sm:inline">Pilih Pembayaran</span>
        </li>
        <li class="mx-3 h-px w-8 bg-gray-200"></li>
        <li class="flex items-center text-gray-400">
            <span class="flex h-6 w-6 items-center justify-center rounded-full bg-gray-100 text-xs">3</span>
            <span class="ml-2 hidden sm:inline">Selesai</span>
        </li>
    </ol> --}}

    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
        {{-- Header --}}
        <div class="border-b border-gray-200 px-6 py-5">
            <h1 class="text-2xl font-bold text-gray-900">
                Pilih Metode Pembayaran
            </h1>
            <p class="mt-1 text-sm text-gray-500">
                Selesaikan booking dengan memilih salah satu metode di bawah ini.
            </p>
        </div>

        <div class="space-y-6 p-6">
            @if ($errors->any())
                <div class="rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                    {{ $errors->first() }}
                </div>
            @endif

            {{-- Reservasi countdown --}}
            <div class="flex items-center justify-between rounded-lg border border-amber-200 bg-amber-50 px-5 py-3">
                <span class="text-sm font-medium text-amber-800">
                    Selesaikan sebelum reservasi berakhir
                </span>
                <span id="reservation-countdown" data-deadline="{{ $booking->reservation_expires_at?->toIso8601String() }}"
                    class="font-mono text-lg font-bold text-amber-700">
                </span>
            </div>

            {{-- Booking Summary --}}
            @include('customer.bookings.partials.booking-summary')

            {{-- Payment Method --}}
            <form action="{{ route('customer.bookings.payment.store', $booking) }}" method="POST">
                @csrf
                <fieldset>
                    <legend class="mb-3 font-semibold text-gray-800">Metode Pembayaran</legend>

                    <div class="grid gap-3 sm:grid-cols-3">
                        {{-- Cash --}}
                        <label class="group relative flex cursor-pointer flex-col gap-3 rounded-lg border border-gray-200 p-4 transition hover:border-indigo-300 has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50 has-[:checked]:ring-1 has-[:checked]:ring-indigo-600">
                            <input type="radio" name="payment_method" value="cash" class="absolute right-4 top-4 h-4 w-4 text-indigo-600 focus:ring-indigo-600" {{ old('payment_method') === 'cash' ? 'checked' : '' }}>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400 group-has-[:checked]:text-indigo-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <rect x="2.5" y="6" width="19" height="12" rx="2"/>
                                <circle cx="12" cy="12" r="2.5"/>
                                <path d="M6 9v0M18 15v0" stroke-linecap="round"/>
                            </svg>
                            <div>
                                <p class="font-medium text-gray-900">Cash</p>
                                <p class="mt-0.5 text-xs text-gray-500">Bayar langsung di tempat.</p>
                            </div>
                        </label>

                        {{-- Transfer --}}
                        <label class="group relative flex cursor-pointer flex-col gap-3 rounded-lg border border-gray-200 p-4 transition hover:border-indigo-300 has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50 has-[:checked]:ring-1 has-[:checked]:ring-indigo-600">
                            <input type="radio" name="payment_method" value="transfer" class="absolute right-4 top-4 h-4 w-4 text-indigo-600 focus:ring-indigo-600" {{ old('payment_method') === 'transfer' ? 'checked' : '' }}>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400 group-has-[:checked]:text-indigo-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M3 10l9-6 9 6" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M5 10v8M10 10v8M14 10v8M19 10v8" stroke-linecap="round"/>
                                <path d="M3 20h18" stroke-linecap="round"/>
                            </svg>
                            <div>
                                <p class="font-medium text-gray-900">Transfer Bank</p>
                                <p class="mt-0.5 text-xs text-gray-500">Batas waktu 30 menit setelah dipilih.</p>
                            </div>
                        </label>

                        {{-- QRIS --}}
                        <label class="group relative flex cursor-pointer flex-col gap-3 rounded-lg border border-gray-200 p-4 transition hover:border-indigo-300 has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50 has-[:checked]:ring-1 has-[:checked]:ring-indigo-600">
                            <input type="radio" name="payment_method" value="qris" class="absolute right-4 top-4 h-4 w-4 text-indigo-600 focus:ring-indigo-600" {{ old('payment_method') === 'qris' ? 'checked' : '' }}>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400 group-has-[:checked]:text-indigo-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <rect x="3" y="3" width="6" height="6" rx="1"/>
                                <rect x="15" y="3" width="6" height="6" rx="1"/>
                                <rect x="3" y="15" width="6" height="6" rx="1"/>
                                <path d="M15 15h2.5M15 18.5h6M20.5 15v6" stroke-linecap="round"/>
                            </svg>
                            <div>
                                <p class="font-medium text-gray-900">QRIS</p>
                                <p class="mt-0.5 text-xs text-gray-500">Batas waktu 30 menit setelah dipilih.</p>
                            </div>
                        </label>
                    </div>
                </fieldset>

                {{-- Submit Button --}}
                <div class="mt-6 flex flex-col gap-3 sm:flex-row">
                    <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-5 py-3 font-medium text-white transition hover:bg-indigo-700">
                        Lanjutkan
                    </button>
                    <a href="{{ route('customer.bookings.show', $booking) }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-5 py-3 font-medium text-gray-700 transition hover:bg-gray-100">
                        Kembali ke Detail Booking
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('script')
    <script>
        const countdownEl = document.getElementById('reservation-countdown');
        const deadlineValue = countdownEl.dataset.deadline;

        if (deadlineValue) {
            const deadline = new Date(deadlineValue);

            function updateReservationCountdown() {
                const distance = deadline - new Date();

                if (distance <= 0) {
                    countdownEl.innerHTML = 'Waktu habis';
                    setTimeout(() => window.location.reload(), 2000);
                    return;
                }

                const minutes = Math.floor(distance / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                countdownEl.innerHTML = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
            }

            updateReservationCountdown();
            setInterval(updateReservationCountdown, 1000);
        }
    </script>
@endpush
