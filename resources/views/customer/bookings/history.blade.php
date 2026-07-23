@extends('layouts.customer')

@section('title', 'Riwayat Booking')

@section('content')
    @php
        $filter = [
            'all' => 'Semua',
            'pending_payment' => 'Menunggu Bayar',
            'avtive' => 'Aktif',
            'completed' => 'Selesai',
            'canceled' => 'Dibatalkan'
        ];

        $statusMeta = [
            'waiting_payment_method' => ['label' => 'Pilih pembayaran', 'bg' => 'bg-[var(--amber-tint)]', 'text' => 'text-[var(--amber)]'],
            'pending_payment' => ['label' => 'Menunggu bayar', 'bg' => 'bg-[var(--amber-tint)]', 'text' => 'text-[var(--amber)]'],
            'paid' => ['label' => 'Menunggu verifikasi', 'bg' => 'bg-[var(--amber-tint)]', 'text' => 'text-[var(--amber)]'],
            'confirmed' => ['label' => 'Terkonfirmasi', 'bg' => 'bg-[var(--primary-tint)]', 'text' => 'text-[var(--primary)]'],
            'completed' => ['label' => 'Selesai', 'bg' => 'bg-[var(--primary-tint)]', 'text' => 'text-[var(--primary)]'],
            'canceled' => ['label' => 'Dibatalkan', 'bg' => 'bg-[var(--danger-tint)]', 'text' => 'text-[var(--danger)]'],
        ];
    @endphp

    <div class="flex gap-2 overflow-x-auto mb-6 pb-0.5 -mx-5 md:mx-0 px-5 md:px-0">
        @foreach ($filter as $value => $label)
            <a href="{{ route('customer.bookings.history', $value === 'all' ? [] : ['status' => $value]) }}" class="shrink-0 px-3.5 py-2 rounded-lg text-[13px] font-500 whitespace-nowrap {{ $activeFilter === $value ? 'bg-[var(--primary)] text-white' : 'bg-[var(--surface)] border border-[var(--line)] text-[var(--ink-soft)]' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    @forelse ($groupedBookings as $groupLabel => $bookingsInGroup)
        <p class="text-[11.5px] font-600 uppercase tracking-wide text-[var(--ink-soft)] mb-2 mt-5 first:mt-0">{{ $groupLabel }}</p>

        <div class="space-y-2.5 md:grid md:grid-cols-2 md:gap-3 md:space-y-0">
            @foreach ($bookingsInGroup as $booking)
                @php
                    $meta = $statusMeta[$booking->status] ?? ['label' => $booking->status, 'bg' => 'bg-[var(--surface)]', 'text' => 'text-[var(--ink-soft)]'];
                @endphp

                <a href="{{ route('customer.bookings.show', $booking->id) }}" class="block bg-[var(--surface)] border border-[var(--line)] rounded-xl px-4 py-3.5">
                    <div class="flex justify-between items-start gap-3">
                        <div class="min-w-0">
                            <p class="font-500 text-[14px] truncate">{{ $booking->field->name ?? '-' }}</p>
                            <p class="text-[12.5px] text-[var(--ink-soft)] mt-0.5 truncate">{{ $booking->field->venue->name ?? '-' }}</p>
                            <p class="text-[12px] text-[var(--ink-soft)] tabular mt-1.5">{{ \Carbon\Carbon::parse($booking->booking_date)->translatedFormat('d M') }}, {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }}-{{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}</p>
                        </div>
                        <span class="shrink-0 text-[11.5px] font-500 px-2.5 py-1 rounded-md whitespace-nowrap {{ $meta['bg'] }} {{ $meta['text'] }}">{{ $meta['label'] }}</span>
                    </div>
                </a>
            @endforeach
        </div>
    @empty
        <div class="text-center py-16">
            <p class="text-[14px] font-500 mb-1">Belum ada booking</p>
            <p class="text-[13px] text-[var(--ink-soft)]">Riwayat booking kamu akan muncul disini.</p>
        </div>
    @endforelse

    @if ($bookings->hasPages())
        <div class="mt-6">
            {{ $bookings->links() }}
        </div>
    @endif
@endsection
