@extends ('layouts.dashboard')

@section ('title', 'Detail Pelanggan')

@section ('content')
    <div class="max-w-5xl mx-auto px-4 py-6">
        <a href="{{ route('owner.customers.index') }}" class="bg-slate-300 hover:bg-slate-400 transition px-4 py-2 rounded text-sm"><- Kembali</a>

        {{-- Profil --}}
        <div class="bg-white rounded-xl shadow p-6 mt-4 mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">{{ $customer->name }}</h1>
                    <p class="text-gray-500 mt-1">{{ $customer->phone }}</p>
                </div>
                @if ($customer->is_verified)
                    <span class="inline-flex w-fit items-center rounded-full bg-green-100 px-3 py-1.5 text-sm font-semibold text-green-700">Terverifikasi</span>
                @else
                    <span class="inline-flex w-fit items-center rounded-full bg-gray-100 px-3 py-1.5 text-sm font-semibold text-gray-600">Belum Verifikasi</span>
                @endif
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-2 gap-4 mt-6">
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-xs uppercase tracking-wide text-gray-400">Total Booking</p>
                    <p class="text-xl font-bold text-gray-800 mt-1">{{ $summary['bookings_count'] }}x</p>
                </div>
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-xs uppercase tracking-wide text-gray-400">Total Belanja</p>
                    <p class="text-xl font-bold text-green-600 mt-1">Rp{{ number_format($summary['total_spent'], 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        {{-- Riwayat Booking --}}
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <h2 class="font-semibold text-gray-800">Riwayat Booking</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                        <tr>
                            <th class="px-5 py-3">Kode Booking</th>
                            <th class="px-5 py-3">Lapangan</th>
                            <th class="px-5 py-3">Jadwal</th>
                            <th class="px-5 py-3">Total</th>
                            <th class="px-5 py-3">Metode</th>
                            <th class="px-5 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($bookings as $booking)
                            <tr class="hover:bg-gray-50">
                                <td class="px-5 py-4 font-medium text-gray-800">{{ $booking->booking_code }}</td>
                                <td class="px-5 py-4">
                                    <div class="text-gray-800">{{ $booking->field->name ?? '-' }}</div>
                                    <div class="text-xs text-gray-500">{{ $booking->field->venue->name ?? '-' }}</div>
                                </td>
                                <td class="px-5 py-4 text-gray-800">
                                    {{ \Carbon\Carbon::parse($booking->booking_date)->translatedFormat('d M Y') }}
                                    <div class="text-xs text-gray-500">
                                        {{ substr($booking->start_time, 0, 5) }} - {{ substr($booking->end_time, 0, 5) }}
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-gray-800">Rp{{ number_format($booking->total_price, 0, ',', '.') }}</td>
                                <td class="px-5 py-4 text-gray-800">{{ ucfirst($booking->payment_method ?? '-') }}</td>
                                <td class="px-5 py-4"><x-booking-status-badge :status="$booking->status" /></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-12 text-center text-gray-500">Belum ada riwayat booking.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6">{{ $bookings->links() }}</div>
    </div>
@endsection
