@extends('layouts.customer')

@section('content')

<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow">
        <div class="border-b p-6">
            <h1 class="text-2xl font-bold">
                Detail Booking
            </h1>
        </div>
        <div class="p-6 space-y-6">
            <div class="grid md:grid-cols-2">
                <div>
                    <h3 class="text-gray-500">
                        Kode Booking
                    </h3>
                    <p class="font-bold text-lg">
                        {{ $booking->booking_code }}
                    </p>
                </div>
                <div>
                    <h3 class="text-gray-500 mb-2">
                        Status
                    </h3>
                    @php
                        $statusColor = match($booking->status){
                            'pending_payment'
                                => 'bg-yellow-100 text-yellow-700',
                            'confirmed'
                                => 'bg-green-100 text-green-700',
                            'canceled'
                                => 'bg-red-100 text-red-700',
                            'waiting_payment_method'
                                => 'bg-yellow-100 text-yellow-700',
                            'paid'
                                => 'bg-emerald-100 text-emerald-700',
                            default
                                => 'bg-gray-100 text-gray-700',
                        };
                    @endphp
                    <span class="px-4 py-2 rounded-full {{ $statusColor }}">
                        {{-- {{ ucwords(str_replace('_',' ',$booking->status)) }} --}}
                        @switch($booking->status)
                            @case('waiting_payment_method')
                                Pilih metode pembayaran
                                @break
                            @case('pending_payment')
                                Menunggu pembayaran
                                @break
                            @default
                                Status tidak diketahui
                        @endswitch
                    </span>
                </div>
            </div>
            <div class="border-t pt-6">
                <h2 class="font-semibold mb-3">
                    Nama Customer
                </h2>
                <p>
                    {{ $booking->customer->name }}
                </p>
            </div>
            <div class="grid md:grid-cols-2 border-t pt-6">
                <div>
                    <h2 class="font-semibold mb-3">
                        Venue
                    </h2>
                    <p>
                        {{ $booking->field->venue->name }}
                    </p>
                </div>
                <div>
                    <h2 class="font-semibold mb-3">
                        Lapangan
                    </h2>
                    <p>
                        {{ $booking->field->name }}
                    </p>
                </div>
            </div>
            <div class="grid md:grid-cols-3 gap-6 border-t pt-6">
                <div>
                    <h3 class="text-gray-500">
                        Tanggal
                    </h3>
                    <p>
                        {{ \Carbon\Carbon::parse($booking->booking_date)->translatedFormat('d F Y') }}
                    </p>
                </div>
                <div>
                    <h3 class="text-gray-500">
                        Jam
                    </h3>
                    <p>
                        {{ substr($booking->start_time,0,5) }}
                        -
                        {{ substr($booking->end_time,0,5) }}
                    </p>
                </div>
                <div>
                    <h3 class="text-gray-500">
                        Durasi
                    </h3>
                    <p>
                        {{ $booking->duration }} Jam
                    </p>
                </div>
            </div>
            <div class="border-t pt-6">
                <h2 class="text-gray-500">
                    Total Harga
                </h2>
                <p class="text-3xl font-bold text-indigo-600">
                    Rp{{ number_format($booking->total_price) }}
                </p>
            </div>
            @if($booking->notes)
            <div class="border-t pt-6">
                <h2 class="font-semibold mb-3">
                    Catatan
                </h2>
                <p>
                    {{ $booking->notes }}
                </p>
            </div>
            @endif
            <div class="border-t p-6 flex justify-between">
                <a
                    href="{{ route('customer.bookings.index') }}"
                    class="px-5 py-2 rounded-lg border">
                    Kembali
                </a>
                <div class="flex flex-cols gap-4 items-center">
                    @if ($booking->status == 'waiting_payment_method')
                        <div>
                            <a href="{{ route('customer.bookings.payment', $booking) }}" class="px-4 py-2 rounded-lg bg-green-600 text-white font-semibold">Lanjutkan Pembayaran</a>
                        </div>
                        <form action="{{ route('customer.bookings.cancel', $booking) }}" method="post" class="inline">
                            @csrf
                            @method('PATCH')
                            <button onclick="return confirm('Batalkan booking ini?')" class="px-4 py-2 rounded-lg bg-red-600 text-white">
                                Batalkan Booking
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
