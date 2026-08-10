@extends('layouts.customer')

@section('title', 'Membership')

@php
    $tierColors = [
        'rookie' => ['bg' => '#EEF0EC', 'text' => '#5B685F', 'ring' => '#D7DBD2'],
        'pro' => ['bg' => 'var(--primary-tint)', 'text' => 'var(--primary-dark)', 'ring' => 'var(--primary)'],
        'league' => ['bg' => 'var(--amber-tint)', 'text' => '#8A5510', 'ring' => 'var(--amber)'],
    ];
    $currentTierColor = $tierColors[$membership->tier->code] ?? $tierColors['rookie'];

    // progres menuju syarat tier berikutnya (pakai yang mana saja yang lebih dekat: booking atau spend)
    $nextTier = $tiers->firstWhere('level', $membership->tier->level + 1);
@endphp

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    @if (session('success'))
        <div class="rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700">
            {{ session('error') }}
        </div>
    @endif

    {{-- Kartu Tier & Poin --}}
    <div class="rounded-2xl border border-[var(--line)] bg-white shadow-sm p-6">
        <div class="flex items-start justify-between flex-wrap gap-4">
            <div>
                <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-600"
                    style="background: {{ $currentTierColor['bg'] }}; color: {{ $currentTierColor['text'] }};">
                    Tier {{ $membership->tier->name }}
                </span>
                <p class="mt-3 text-sm text-[var(--ink-soft)]">Poin kamu saat ini</p>
                <p class="font-display text-4xl font-700 tabular">{{ number_format($membership->current_point, 0, ',', '.') }}</p>
            </div>
            <div class="text-right text-xs text-[var(--ink-soft)]">
                <p>Evaluasi tier berikutnya</p>
                <p class="font-600 text-[var(--ink)]">{{ \Carbon\Carbon::parse($membership->cycle_end_at)->translatedFormat('d M Y') }}</p>
            </div>
        </div>

        @if ($nextTier)
            @php
                $bookingProgress = $nextTier->min_booking > 0
                    ? min(100, round(($membership->qualification_booking_count / $nextTier->min_booking) * 100))
                    : 100;
                $spendProgress = $nextTier->min_spend > 0
                    ? min(100, round(($membership->qualification_spend / $nextTier->min_spend) * 100))
                    : 100;
                $progress = max($bookingProgress, $spendProgress);
            @endphp
            <div class="mt-5">
                <div class="flex justify-between text-xs text-[var(--ink-soft)] mb-1">
                    <span>Progres menuju tier {{ $nextTier->name }}</span>
                    <span>{{ $progress }}%</span>
                </div>
                <div class="w-full h-2 rounded-full bg-[var(--bg)] overflow-hidden">
                    <div class="h-full rounded-full" style="width: {{ $progress }}%; background: var(--primary);"></div>
                </div>
                <p class="mt-2 text-xs text-[var(--ink-soft)]">
                    {{ $membership->qualification_booking_count }}/{{ $nextTier->min_booking }} booking
                    atau Rp{{ number_format($membership->qualification_spend, 0, ',', '.') }}/Rp{{ number_format($nextTier->min_spend, 0, ',', '.') }}
                    dalam {{ $nextTier->evaluation_period_days }} hari terakhir.
                </p>
            </div>
        @else
            <p class="mt-5 text-xs text-[var(--ink-soft)]">Kamu sudah berada di tier tertinggi. Pertahankan aktivitas booking supaya tidak turun tier.</p>
        @endif
    </div>

    {{-- Kartu Streak --}}
    <div class="rounded-2xl border border-[var(--line)] bg-white shadow-sm p-6">
        <div class="flex items-center justify-between flex-wrap gap-3">
            <div>
                <h2 class="font-display font-600 text-lg">Booking Streak</h2>
                <p class="text-sm text-[var(--ink-soft)]">Booking minimal 1x per minggu untuk menjaga streak-mu.</p>
            </div>
            <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-600
                @if(($streak->streak_status ?? 'inactive') === 'active') bg-[var(--primary-tint)] text-[var(--primary-dark)]
                @elseif(($streak->streak_status ?? 'inactive') === 'warning') bg-[var(--amber-tint)] text-[#8A5510]
                @else bg-[#EEF0EC] text-[var(--ink-soft)]
                @endif">
                {{ $streak->current_streak ?? 0 }} minggu berturut-turut
            </span>
        </div>

        <div class="mt-4 flex items-center gap-2">
            @foreach ([2, 4, 8, 12] as $milestone)
                @php $reached = ($streak->current_streak ?? 0) >= $milestone; @endphp
                <div class="flex-1 text-center">
                    <div class="w-full h-2 rounded-full {{ $reached ? '' : 'bg-[var(--bg)]' }}"
                        style="{{ $reached ? 'background: var(--amber);' : '' }}"></div>
                    <p class="mt-1 text-[10px] text-[var(--ink-soft)]">{{ $milestone }}mgg</p>
                </div>
            @endforeach
        </div>
        <p class="mt-2 text-xs text-[var(--ink-soft)]">Terpanjang: {{ $streak->longest_streak ?? 0 }} minggu • Booking terakhir: {{ $streak->last_booking_date ? \Carbon\Carbon::parse($streak->last_booking_date)->translatedFormat('d M Y') : '-' }}</p>
    </div>

    {{-- Perbandingan Tier & Benefit --}}
    <div class="rounded-2xl border border-[var(--line)] bg-white shadow-sm p-6">
        <h2 class="font-display font-600 text-lg mb-4">Benefit per Tier</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-[var(--ink-soft)] border-b border-[var(--line)]">
                        <th class="py-2 pr-4 font-500">Benefit</th>
                        @foreach ($tiers as $tier)
                            <th class="py-2 px-3 font-600 text-center {{ $tier->id === $membership->tier_id ? 'text-[var(--primary-dark)]' : '' }}">
                                {{ $tier->name }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @php
                        $allBenefitNames = $tiers->flatMap(fn($t) => $t->benefits->pluck('name'))->unique();
                    @endphp
                    @foreach ($allBenefitNames as $benefitName)
                        <tr class="border-b border-[var(--line)] last:border-0">
                            <td class="py-2.5 pr-4">{{ $benefitName }}</td>
                            @foreach ($tiers as $tier)
                                <td class="py-2.5 px-3 text-center">
                                    @if ($tier->benefits->contains('name', $benefitName))
                                        <span style="color: var(--primary);">&#10003;</span>
                                    @else
                                        <span class="text-[var(--line)]">&mdash;</span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Tukar Poin --}}
    <div class="rounded-2xl border border-[var(--line)] bg-white shadow-sm p-6">
        <h2 class="font-display font-600 text-lg mb-1">Tukar Poin</h2>
        <p class="text-sm text-[var(--ink-soft)] mb-4">Berlaku untuk semua tier, selama poin kamu cukup.</p>

        <div class="space-y-3">
            @forelse ($redeemableBenefits as $benefit)
                @php $canAfford = $membership->current_point >= $benefit->point_cost; @endphp
                <div class="flex items-center justify-between gap-4 rounded-xl border border-[var(--line)] p-4">
                    <div>
                        <p class="font-600">{{ $benefit->name }}</p>
                        <p class="text-xs text-[var(--ink-soft)] mt-0.5">{{ $benefit->description }}</p>
                    </div>
                    <div class="text-right shrink-0">
                        <p class="font-600 tabular mb-2">{{ number_format($benefit->point_cost, 0, ',', '.') }} poin</p>
                        <form method="POST" action="{{ route('customer.membership.redeem', $benefit) }}">
                            @csrf
                            <button type="submit" {{ $canAfford ? '' : 'disabled' }}
                                class="rounded-lg px-4 py-2 text-xs font-600 transition
                                    {{ $canAfford ? 'bg-[var(--primary)] text-white hover:bg-[var(--primary-dark)]' : 'bg-[var(--bg)] text-[var(--ink-soft)] cursor-not-allowed' }}">
                                Tukar
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="text-sm text-[var(--ink-soft)]">Belum ada benefit yang bisa ditukar poin saat ini.</p>
            @endforelse
        </div>
    </div>

    {{-- Riwayat Poin --}}
    <div class="rounded-2xl border border-[var(--line)] bg-white shadow-sm p-6">
        <h2 class="font-display font-600 text-lg mb-4">Riwayat Poin</h2>
        <div class="divide-y divide-[var(--line)]">
            @forelse ($pointHistory as $trx)
                <div class="py-3 flex items-center justify-between gap-4">
                    <div>
                        <p class="text-sm font-500">{{ $trx->note ?? ucfirst($trx->type) }}</p>
                        <p class="text-xs text-[var(--ink-soft)]">{{ $trx->created_at->translatedFormat('d M Y, H:i') }}</p>
                    </div>
                    <span class="tabular font-600 text-sm {{ $trx->amount >= 0 ? 'text-[var(--primary-dark)]' : 'text-[var(--danger)]' }}">
                        {{ $trx->amount >= 0 ? '+' : '' }}{{ number_format($trx->amount, 0, ',', '.') }}
                    </span>
                </div>
            @empty
                <p class="text-sm text-[var(--ink-soft)] py-3">Belum ada riwayat poin.</p>
            @endforelse
        </div>
    </div>

    {{-- Riwayat Penukaran --}}
    @if ($redemptionHistory->isNotEmpty())
        <div class="rounded-2xl border border-[var(--line)] bg-white shadow-sm p-6">
            <h2 class="font-display font-600 text-lg mb-4">Riwayat Penukaran</h2>
            <div class="divide-y divide-[var(--line)]">
                @foreach ($redemptionHistory as $redemption)
                    <div class="py-3 flex items-center justify-between gap-4">
                        <div>
                            <p class="text-sm font-500">{{ $redemption->benefit->name }}</p>
                            <p class="text-xs text-[var(--ink-soft)]">{{ $redemption->redeemed_at?->translatedFormat('d M Y, H:i') }}</p>
                        </div>
                        <span class="inline-flex items-center rounded-full px-2.5 py-1 text-[11px] font-600
                            @if($redemption->status === 'used') bg-[var(--primary-tint)] text-[var(--primary-dark)]
                            @elseif($redemption->status === 'pending') bg-[var(--amber-tint)] text-[#8A5510]
                            @else bg-[#EEF0EC] text-[var(--ink-soft)]
                            @endif">
                            {{ ucfirst($redemption->status) }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

</div>
@endsection
