@extends ('layouts.dashboard')

@section ('title', 'Riwayat Booking')

@section ('content')
    <div class="max-w-7xl mx-auto px-4 py-6">
        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Riwayat Booking</h1>
                <p class="text-gray-500 mt-1">Seluruh riwayat booking customer di venue Anda.</p>
            </div>
            <a href="{{ route('owner.bookings.create') }}"
                class="bg-green-600 hover:bg-green-700 text-white px-5 py-3 rounded-lg font-semibold shadow text-center">
                + Buat Booking
            </a>
        </div>

        {{-- Alert --}}
        @if (session('success'))
            <div class="mb-6 bg-green-100 border border-green-300 text-green-700 p-4 rounded-lg">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="mb-6 bg-red-100 border border-red-300 text-red-700 p-4 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        {{-- Filter --}}
        <div class="bg-white rounded-xl shadow p-5 mb-6">
            <form method="GET" class="grid md:grid-cols-6 gap-4">
                <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Cari nama / no. HP customer"
                    class="md:col-span-2 rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-green-500 focus:ring-green-500">

                <select name="status" class="rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-green-500 focus:ring-green-500">
                    <option value="all">Semua Status</option>
                    <option value="waiting_payment_method" {{ ($filters['status'] ?? '') === 'waiting_payment_method' ? 'selected' : '' }}>Pilih Metode Bayar</option>
                    <option value="pending_payment" {{ ($filters['status'] ?? '') === 'pending_payment' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                    <option value="confirmed" {{ ($filters['status'] ?? '') === 'confirmed' ? 'selected' : '' }}>Terkonfirmasi</option>
                    <option value="paid" {{ ($filters['status'] ?? '') === 'paid' ? 'selected' : '' }}>Sudah Dibayar</option>
                    <option value="completed" {{ ($filters['status'] ?? '') === 'completed' ? 'selected' : '' }}>Selesai</option>
                    <option value="canceled" {{ ($filters['status'] ?? '') === 'canceled' ? 'selected' : '' }}>Dibatalkan</option>
                </select>

                <select name="field_id" class="rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-green-500 focus:ring-green-500">
                    <option value="">Semua Lapangan</option>
                    @foreach ($fields as $field)
                        <option value="{{ $field->id }}" {{ (string) ($filters['field_id'] ?? '') === (string) $field->id ? 'selected' : '' }}>
                            {{ $field->name }}
                        </option>
                    @endforeach
                </select>

                <input type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}"
                    class="rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-green-500 focus:ring-green-500">

                <button class="bg-green-600 hover:bg-green-700 text-white rounded-lg px-4 py-2 text-sm font-semibold">
                    Filter
                </button>
            </form>
        </div>

        {{-- Table --}}
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                        <tr>
                            <th class="px-5 py-3">Kode Booking</th>
                            <th class="px-5 py-3">Customer</th>
                            <th class="px-5 py-3">Lapangan</th>
                            <th class="px-5 py-3">Jadwal</th>
                            <th class="px-5 py-3">Total</th>
                            <th class="px-5 py-3">Metode</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($bookings as $booking)
                            <tr class="hover:bg-gray-50">
                                <td class="px-5 py-4 font-medium text-gray-800">{{ $booking->booking_code }}</td>
                                <td class="px-5 py-4">
                                    <div class="font-medium text-gray-800">{{ $booking->customer->name ?? '-' }}</div>
                                    <div class="text-xs text-gray-500">{{ $booking->customer->phone ?? '-' }}</div>
                                </td>
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
                                <td class="px-5 py-4 text-right">
                                    @if ($booking->payment_method === 'cash' && $booking->status === 'pending_payment')
                                        <form method="POST" action="{{ route('owner.bookings.confirm-cash', $booking) }}"
                                            onsubmit="return confirm('Konfirmasi bahwa customer sudah membayar cash?');">
                                            @csrf
                                            @method('PATCH')
                                            <button class="bg-green-600 hover:bg-green-700 text-white text-xs font-semibold px-3 py-2 rounded-lg">
                                                Konfirmasi Cash
                                            </button>
                                        </form>
                                    @elseif ($booking->status === 'paid')
                                        <form action="{{ route('owner.bookings.confirm-transfer', $booking) }}" method="POST">
                                            @csrf
                                            <button class="bg-green-600 hover:bg-green-700 text-white text-xs font-semibold px-3 py-2 rounded-lg" type="submit" onclick="return confirm('Konfirmasi pembayaran ini? Pastikan pembayaran sudah masuk dan Customer sudah datang.')">
                                                Konfirmasi
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-gray-300 text-xs">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-5 py-12 text-center text-gray-500">
                                    Belum ada riwayat booking.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6">{{ $bookings->withQueryString()->links() }}</div>
    </div>
@endsection
