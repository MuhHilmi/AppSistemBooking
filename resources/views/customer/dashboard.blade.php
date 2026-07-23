@extends('layouts.customer')

@section('title', 'Beranda')

@section('header-actions')
    <button aria-label="Notifikasi" class="w-9 h-9 flex items-center justify-center text-[var(--ink-soft)]">
        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8a6 6 0 10-12 0c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.7 21a2 2 0 01-3.4 0"/></svg>
    </button>
@endsection

@section('content')

    <p class="text-[13px] text-[var(--ink-soft)] mb-0.5">Halo,</p>
    <p class="font-display font-600 text-[19px] mb-5">{{ auth('customer')->user()->name ?? 'Pelanggan' }}</p>

    {{-- ===== Booking mendatang / perlu perhatian ===== --}}
    @if($upcomingBooking)
        @php
            $isUnpaid = in_array($upcomingBooking->status, ['waiting_payment_method', 'pending_payment']);
            $isPaid = $upcomingBooking->status === 'paid';
        @endphp

        <div class="rounded-xl p-4 mb-5 {{ $isUnpaid ? 'bg-[var(--amber-tint)]' : 'bg-[var(--primary-tint)]' }}">
            <div class="flex justify-between items-start mb-3">
                <div>
                    <p class="text-[11px] font-600 uppercase tracking-wide mb-1 {{ $isUnpaid ? 'text-[var(--amber)]' : 'text-[var(--primary-dark)]' }}">
                        @if($isUnpaid) Menunggu pembayaran
                        @elseif($isPaid) Menunggu verifikasi
                        @else Terkonfirmasi
                        @endif
                    </p>
                    <p class="font-500 text-[15px] leading-tight">{{ $upcomingBooking->field->name ?? '—' }} · {{ $upcomingBooking->field->venue->name ?? '—' }}</p>
                    <p class="text-[12px] text-[var(--ink-soft)] tabular mt-0.5">
                        {{ \Carbon\Carbon::parse($upcomingBooking->booking_date)->translatedFormat('d M') }},
                        {{ \Carbon\Carbon::parse($upcomingBooking->start_time)->format('H:i') }}–{{ \Carbon\Carbon::parse($upcomingBooking->end_time)->format('H:i') }}
                    </p>
                </div>
            </div>

            <div class="flex items-center justify-between">
                @if($isUnpaid && $upcomingBooking->payment_due_at)
                    <span class="text-[12px] {{ $isUnpaid ? 'text-[var(--amber)]' : 'text-[var(--primary-dark)]' }}">
                        Bayar sebelum {{ \Carbon\Carbon::parse($upcomingBooking->payment_due_at)->format('H:i') }}
                    </span>
                @else
                    <span class="text-[12px] text-[var(--primary-dark)]">Kode: {{ $upcomingBooking->booking_code }}</span>
                @endif

                @if($isUnpaid)
                    <a href="{{ route('customer.bookings.payment', $upcomingBooking->id) }}" class="px-3.5 py-1.5 rounded-lg bg-[var(--amber)] text-white text-[12.5px] font-600">
                        Bayar sekarang
                    </a>
                @else
                    <a href="{{ route('customer.bookings.show', $upcomingBooking->id) }}" class="px-3.5 py-1.5 rounded-lg bg-[var(--primary)] text-white text-[12.5px] font-600">
                        Lihat detail
                    </a>
                @endif
            </div>
        </div>
    @endif

    {{-- ===== Aksi cepat ===== --}}
    <div class="grid grid-cols-2 gap-2.5 mb-6">
        {{-- TODO: belum ada route browse/search lapang di web.php — arahkan ke '/' sementara, ganti ke route pencarian begitu dibuat --}}
        <a href="{{ route('customer.bookings.index') }}" class="bg-[var(--surface)] border border-[var(--line)] rounded-xl p-3.5 flex flex-col gap-2">
            <svg class="w-[18px] h-[18px] text-[var(--ink)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/></svg>
            <span class="text-[13px] font-500">Cari lapang</span>
        </a>
        <a href="{{ route('customer.bookings.history') }}" class="bg-[var(--surface)] border border-[var(--line)] rounded-xl p-3.5 flex flex-col gap-2">
            <svg class="w-[18px] h-[18px] text-[var(--ink)]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3v18h18"/><path d="M7 15l4-5 3 3 5-7"/></svg>
            <span class="text-[13px] font-500">Riwayat booking</span>
        </a>
    </div>

    {{-- ===== Booking lagi ===== --}}
    @if($frequentFields->isNotEmpty())
        <p class="text-[13px] font-500 mb-2">Booking lagi</p>
        <div class="flex gap-2 overflow-x-auto mb-6 pb-0.5 -mx-5 px-5">
            @foreach($frequentFields as $field)
                <a href="{{ route('customer.bookings.create', $field->id) }}" class="shrink-0 bg-[var(--surface)] border border-[var(--line)] rounded-lg px-3 py-2 text-[12.5px] font-500 whitespace-nowrap">
                    {{ $field->name }}
                </a>
            @endforeach
        </div>
    @endif

    {{-- ===== Riwayat terbaru ===== --}}
    <p class="text-[13px] font-500 mb-2">Riwayat terbaru</p>
    <div class="space-y-2">
        @forelse($recentBookings as $booking)
            @php
                $statusMap = [
                    'completed' => ['label' => 'Selesai', 'class' => 'bg-[var(--primary-tint)] text-[var(--primary-dark)]'],
                    'canceled' => ['label' => 'Dibatalkan', 'class' => 'bg-[var(--danger-tint)] text-[var(--danger)]'],
                    'paid' => ['label' => 'Dibayar', 'class' => 'bg-blue-100 text-blue-700'],
                    'confirmed' => ['label' => 'Dikonfirmasi', 'class' => 'bg-green-100 text-green-700'],
                    'waiting_payment_method' => ['label' => 'Menunggu Metode Pembayaran', 'class' => 'bg-yellow-100 text-yellow-700'],
                    'pending_payment' => ['label' => 'Menunggu Pembayaran', 'class' => 'bg-yellow-100 text-yellow-700'],
                ];
                $statusInfo = $statusMap[$booking->status] ?? ['label' => ucfirst($booking->status), 'class' => 'bg-gray-100 text-gray-700'];
            @endphp
            <a href="{{ route('customer.bookings.show', $booking->id) }}" class="bg-[var(--surface)] border border-[var(--line)] rounded-lg px-3.5 py-2.5 flex items-center justify-between">
                <div>
                    <p class="text-[13px] font-500">{{ $booking->field->name ?? '—' }}</p>
                    <p class="text-[11.5px] text-[var(--ink-soft)] tabular mt-0.5">
                        {{ \Carbon\Carbon::parse($booking->booking_date)->translatedFormat('d M') }},
                        {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }}–{{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}
                    </p>
                </div>
                <span class="text-[11px] font-500 px-2 py-1 rounded-md {{ $statusInfo['class'] }}">
                    {{ $statusInfo['label'] }}
                </span>
            </a>
        @empty
            <p class="text-[13px] text-[var(--ink-soft)] text-center py-8">Belum ada riwayat booking.</p>
        @endforelse
    </div>

@endsection
