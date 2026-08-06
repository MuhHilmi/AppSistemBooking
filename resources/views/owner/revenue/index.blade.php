@extends ('layouts.dashboard')

@section ('title', 'Pendapatan')

@section ('content')
    <div class="max-w-7xl mx-auto px-4 py-6">
        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Pendapatan</h1>
                <p class="text-gray-500 mt-1">
                    {{ $range['from']->translatedFormat('d M Y') }} &mdash; {{ $range['to']->translatedFormat('d M Y') }}
                </p>
            </div>
            <a href="{{ route('owner.revenue.export', request()->query()) }}"
                class="inline-flex items-center justify-center gap-2 bg-green-600 hover:bg-green-700 text-white px-5 py-3 rounded-lg font-semibold shadow">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3v12m0 0l-4-4m4 4l4-4" stroke-linecap="round" stroke-linejoin="round"/><path d="M4 17v2a2 2 0 002 2h12a2 2 0 002-2v-2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Download Laporan
            </a>
        </div>

        {{-- Filter --}}
        <div class="bg-white rounded-xl shadow p-5 mb-6">
            <form method="GET" id="filter-form" class="space-y-4">
                <div class="flex flex-wrap gap-2">
                    @foreach (['week' => '1 Minggu', 'month' => '1 Bulan', 'year' => '1 Tahun', 'custom' => 'Custom'] as $value => $label)
                        <label class="cursor-pointer">
                            <input type="radio" name="range" value="{{ $value }}" class="peer hidden" id="range-{{ $value }}"
                                {{ $range['type'] === $value ? 'checked' : '' }}
                                onchange="document.getElementById('custom-range-fields').classList.toggle('hidden', this.value !== 'custom'); if (this.value !== 'custom') document.getElementById('filter-form').submit();">
                            <span class="inline-block px-4 py-2 rounded-lg text-sm font-semibold border border-gray-300 text-gray-600 peer-checked:bg-green-600 peer-checked:text-white peer-checked:border-green-600 transition">
                                {{ $label }}
                            </span>
                        </label>
                    @endforeach
                </div>

                <div id="custom-range-fields" class="flex flex-wrap items-end gap-3 {{ $range['type'] === 'custom' ? '' : 'hidden' }}">
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Dari Tanggal</label>
                        <input type="date" name="date_from" value="{{ request('date_from', $range['type'] === 'custom' ? $range['from']->toDateString() : '') }}"
                            class="rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-green-500 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Sampai Tanggal</label>
                        <input type="date" name="date_to" value="{{ request('date_to', $range['type'] === 'custom' ? $range['to']->toDateString() : '') }}"
                            class="rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-green-500 focus:ring-green-500">
                    </div>
                    <button type="submit" name="range" value="custom" class="bg-gray-800 hover:bg-gray-900 text-white rounded-lg px-5 py-2 text-sm font-semibold">
                        Terapkan
                    </button>
                </div>
            </form>
        </div>

        {{-- Ringkasan --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-xl shadow p-5">
                <p class="text-xs uppercase tracking-wide text-gray-400">Total Pendapatan</p>
                <p class="text-2xl font-bold text-green-600 mt-1">Rp{{ number_format($totalRevenue, 0, ',', '.') }}</p>
            </div>
            <div class="bg-white rounded-xl shadow p-5">
                <p class="text-xs uppercase tracking-wide text-gray-400">Jumlah Transaksi</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">{{ $totalBookings }}</p>
            </div>
            <div class="bg-white rounded-xl shadow p-5">
                <p class="text-xs uppercase tracking-wide text-gray-400">Rata-rata / Booking</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">Rp{{ number_format($averagePerBooking, 0, ',', '.') }}</p>
            </div>
        </div>

        {{-- Grafik --}}
        <div class="bg-white rounded-xl shadow p-6 mb-6">
            <h2 class="font-semibold text-gray-800 mb-4">Tren Pendapatan</h2>
            @if ($totalBookings > 0)
                <div class="relative" style="height: 320px;">
                    <canvas id="revenue-chart"></canvas>
                </div>
            @else
                <p class="text-center text-gray-400 py-16">Belum ada pendapatan pada rentang ini.</p>
            @endif
        </div>

        {{-- Breakdown metode pembayaran --}}
        @if ($paymentBreakdown->isNotEmpty())
            <div class="bg-white rounded-xl shadow p-6">
                <h2 class="font-semibold text-gray-800 mb-4">Berdasarkan Metode Pembayaran</h2>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    @foreach ($paymentBreakdown as $method => $total)
                        <div class="flex items-center justify-between rounded-lg border border-gray-100 bg-gray-50 px-4 py-3">
                            <span class="text-sm font-medium text-gray-600">{{ ucfirst($method ?? '-') }}</span>
                            <span class="text-sm font-bold text-gray-800">Rp{{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endsection

@if ($totalBookings > 0)
    @push ('script')
        @vite('resources/js/app.js')
        <script>
            // charts.js dimuat sebagai <script type="module">, yang bersifat deferred dan
            // dieksekusi setelah parsing HTML selesai. Event 'load' menunggu semua script
            // (termasuk module) selesai dieksekusi, jadi window.Chart dipastikan sudah tersedia
            // sebelum dipakai di sini.
            window.addEventListener('load', function () {
                const ctx = document.getElementById('revenue-chart');

                new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($chartLabels),
                    datasets: [{
                        label: 'Pendapatan',
                        data: @json($chartTotals),
                        borderColor: '#16a34a',
                        backgroundColor: 'rgba(22, 163, 74, 0.1)',
                        fill: true,
                        tension: 0.3,
                        pointRadius: 3,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: (item) => 'Rp' + item.parsed.y.toLocaleString('id-ID')
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: (value) => 'Rp' + value.toLocaleString('id-ID')
                            }
                        }
                    }
                }
                });
            });
        </script>
    @endpush
@endif
