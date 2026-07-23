@extends('layouts.dashboard')

@section('title', 'Dashboard')

{{-- @section('header-actions')
    <a href="{{ route('owner.bookings.index') }}"
       class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-[var(--primary)] text-white text-[13.5px] font-600 hover:bg-[var(--primary-dark)] transition">
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M12 5v14M5 12h14"/></svg>
        Booking baru
    </a>
@endsection --}}

@section('content')

    {{-- ===== Ringkasan ===== --}}
    <div class="grid grid-cols-4 gap-4 mb-6">
        <div class="bg-[var(--surface)] border border-[var(--line)] rounded-xl p-5">
            <p class="text-[12.5px] text-[var(--ink-soft)] font-500 mb-1.5">Booking hari ini</p>
            <p class="font-display font-700 text-[28px] tabular leading-none">{{ $todayCount }}</p>
        </div>
        <div class="bg-[var(--surface)] border border-[var(--line)] rounded-xl p-5">
            <p class="text-[12.5px] text-[var(--ink-soft)] font-500 mb-1.5">Pendapatan hari ini</p>
            <p class="font-display font-700 text-[28px] tabular leading-none">Rp{{ number_format($todayRevenue, 0, ',', '.') }}</p>
        </div>
        <div class="bg-[var(--surface)] border border-[var(--line)] rounded-xl p-5">
            <p class="text-[12.5px] text-[var(--ink-soft)] font-500 mb-1.5">Okupansi hari ini</p>
            <div class="flex items-end gap-2">
                <p class="font-display font-700 text-[28px] tabular leading-none">{{ $occupancyRate }}%</p>
            </div>
            <div class="mt-2 h-1.5 rounded-full bg-[var(--line)] overflow-hidden">
                <div class="h-full rounded-full bg-[var(--primary)]" style="width: {{ min($occupancyRate, 100) }}%"></div>
            </div>
        </div>
        <div class="bg-[var(--surface)] border border-[var(--line)] rounded-xl p-5">
            <p class="text-[12.5px] text-[var(--ink-soft)] font-500 mb-1.5">Lapang aktif</p>
            <p class="font-display font-700 text-[28px] tabular leading-none">{{ $activeFieldsCount }}</p>
        </div>
    </div>

    <div class="grid grid-cols-5 gap-5 mb-6">

        {{-- ===== Jadwal hari ini (signature: scoreboard style) ===== --}}
        <div class="col-span-3 bg-[var(--surface)] border border-[var(--line)] rounded-xl overflow-hidden">
            <div class="px-5 py-4 border-b border-[var(--line)] flex items-center justify-between">
                <p class="font-display font-600 text-[14.5px]">Jadwal hari ini</p>
                <div class="flex items-center gap-4 text-[11.5px] text-[var(--ink-soft)]">
                    <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-[var(--primary)]"></span>Terisi</span>
                    <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-[var(--amber)]"></span>Menunggu</span>
                </div>
            </div>

            <div class="divide-y divide-[var(--line)]">
                @forelse($todaySchedule as $row)
                    <div class="px-5 py-3.5 flex items-start gap-4">
                        <div class="w-32 shrink-0 border-l-[3px] border-[var(--primary)] pl-2.5 pt-0.5">
                            <p class="font-600 text-[13.5px] leading-tight">{{ $row['field'] }}</p>
                            <p class="text-[11.5px] text-[var(--ink-soft)] uppercase tracking-wide">{{ $row['sport_type'] }}</p>
                        </div>
                        <div class="flex-1 flex flex-wrap gap-1.5 pt-0.5">
                            @forelse($row['bookings'] as $b)
                                @php
                                    $isPending = in_array($b->status, ['waiting_payment_method', 'pending_payment']);
                                @endphp
                                <span class="tabular text-[12px] font-500 px-2.5 py-1 rounded-md
                                    {{ $isPending ? 'bg-[var(--amber-tint)] text-[var(--amber)]' : 'bg-[var(--primary-tint)] text-[var(--primary-dark)]' }}">
                                    {{ \Carbon\Carbon::parse($b->start_time)->format('H:i') }}–{{ \Carbon\Carbon::parse($b->end_time)->format('H:i') }}
                                </span>
                            @empty
                                <span class="text-[12.5px] text-[var(--ink-soft)] pt-1">Belum ada booking</span>
                            @endforelse
                        </div>
                    </div>
                @empty
                    <p class="px-5 py-8 text-center text-[13px] text-[var(--ink-soft)]">Belum ada lapang aktif.</p>
                @endforelse
            </div>
        </div>

        {{-- ===== Perlu perhatian ===== --}}
        <div class="col-span-2 bg-[var(--surface)] border border-[var(--line)] rounded-xl overflow-hidden">
            <div class="px-5 py-4 border-b border-[var(--line)]">
                <p class="font-display font-600 text-[14.5px]">Perlu dikonfirmasi</p>
            </div>
            <div class="divide-y divide-[var(--line)]">
                @forelse($needsAttention as $booking)
                    <div class="px-5 py-3 flex items-center justify-between gap-3">
                        <div class="min-w-0">
                            <p class="font-500 text-[13.5px] truncate">{{ $booking->customer->name ?? '—' }} · {{ $booking->field->name ?? '—' }}</p>
                            <p class="text-[12px] text-[var(--ink-soft)] tabular">
                                {{ \Carbon\Carbon::parse($booking->booking_date)->translatedFormat('d M') }},
                                {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }}–{{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}
                            </p>
                        </div>
                        <span class="shrink-0 text-[11.5px] font-600 px-2.5 py-1 rounded-md
                            {{ $booking->status === 'paid' ? 'bg-[var(--primary-tint)] text-[var(--primary-dark)]' : 'bg-[var(--amber-tint)] text-[var(--amber)]' }}">
                            {{ $booking->status === 'paid' ? 'Perlu verifikasi' : 'Menunggu bayar' }}
                        </span>
                    </div>
                @empty
                    <p class="px-5 py-8 text-center text-[13px] text-[var(--ink-soft)]">Tidak ada booking yang perlu perhatian.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ===== Pendapatan per kategori ===== --}}
    <div class="bg-[var(--surface)] border border-[var(--line)] rounded-xl p-5">
        <p class="font-display font-600 text-[14.5px] mb-4">Pendapatan per kategori lapang (minggu ini)</p>

        @php $maxRevenue = $revenueBySport->max('total') ?: 1; @endphp

        <div class="space-y-3.5">
            @forelse($revenueBySport as $row)
                <div>
                    <div class="flex items-center justify-between text-[13px] mb-1.5">
                        <span class="font-500 capitalize">{{ $row->sport_type }}</span>
                        <span class="text-[var(--ink-soft)] tabular">Rp{{ number_format($row->total, 0, ',', '.') }}</span>
                    </div>
                    <div class="h-2 rounded-full bg-[var(--line)] overflow-hidden">
                        <div class="h-full rounded-full bg-[var(--primary)]" style="width: {{ ($row->total / $maxRevenue) * 100 }}%"></div>
                    </div>
                </div>
            @empty
                <p class="text-[13px] text-[var(--ink-soft)] text-center py-6">Belum ada pendapatan minggu ini.</p>
            @endforelse
        </div>
    </div>

@endsection
