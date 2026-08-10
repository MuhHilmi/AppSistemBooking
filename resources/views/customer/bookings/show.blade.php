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
                            @case('confirmed')
                                Terkonfirmasi
                                @break
                            @case('paid')
                                Sudah dibayar
                                @break
                            @case('completed')
                                Selesai
                                @break
                            @case('canceled')
                                Dibatalkan
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
            @if(in_array($booking->status, ['paid', 'confirmed', 'completed']) && $booking->payment_method)
            <div class="border-t pt-6">
                <h2 class="font-semibold mb-3">
                    Informasi Pembayaran
                </h2>
                <div class="grid md:grid-cols-3 gap-6 text-sm">
                    <div>
                        <h3 class="text-gray-500">Metode</h3>
                        <p class="font-medium">
                            @switch($booking->payment_method)
                                @case('cash') Cash @break
                                @case('transfer') Transfer Bank @break
                                @case('qris') QRIS @break
                                @default {{ ucfirst($booking->payment_method) }}
                            @endswitch
                        </p>
                    </div>
                    @if($booking->midtrans_payment_type)
                    <div>
                        <h3 class="text-gray-500">Channel Pembayaran</h3>
                        <p class="font-medium">{{ str_replace('_', ' ', ucfirst($booking->midtrans_payment_type)) }}</p>
                    </div>
                    @endif
                    @if($booking->midtrans_transaction_id)
                    <div>
                        <h3 class="text-gray-500">ID Transaksi</h3>
                        <p class="font-mono text-xs">{{ $booking->midtrans_transaction_id }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif
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
                    @if ($booking->status == 'pending_payment')
                        <div>
                            <a href="{{ route('customer.bookings.payment.pending', $booking) }}" class="px-4 py-2 rounded-lg bg-blue-600 text-white font-semibold">Cek Pembayaran</a>
                        </div>
                    @endif
                    @if (in_array($booking->status, ['confirmed', 'completed']))
                        <a href="{{ route('customer.bookings.receipt', $booking) }}" target="_blank"
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 text-white font-semibold text-sm hover:bg-indigo-700">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3v12m0 0l-4-4m4 4l4-4" stroke-linecap="round" stroke-linejoin="round"/><path d="M4 17v2a2 2 0 002 2h12a2 2 0 002-2v-2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            Lihat &amp; Download Struk
                        </a>
                        <a href="{{ route('customer.reviews.edit', $booking->field_id) }}"
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-amber-300 bg-amber-50 text-amber-700 font-semibold text-sm hover:bg-amber-100">
                            ★ {{ $hasReview ? 'Ubah Review' : 'Beri Review' }}
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
