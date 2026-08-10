@extends ('layouts.dashboard')

@section ('title', 'Data Pelanggan')

@section ('content')
    <div class="max-w-7xl mx-auto px-4 py-6">
        {{-- Header --}}
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Data Pelanggan</h1>
            <p class="text-gray-500 mt-1">Customer yang pernah booking di venue Anda.</p>
        </div>

        {{-- Filter --}}
        <div class="bg-white rounded-xl shadow p-5 mb-6">
            <form method="GET" class="flex gap-3">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama / no. HP pelanggan"
                    class="flex-1 rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-green-500 focus:ring-green-500">
                <button class="bg-green-600 hover:bg-green-700 text-white rounded-lg px-5 py-2 text-sm font-semibold">
                    Cari
                </button>
                @if (request('search'))
                    <a href="{{ route('owner.customers.index') }}" class="rounded-lg border border-gray-300 px-5 py-2 text-sm font-semibold text-gray-600 hover:bg-gray-50">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        {{-- Table --}}
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                        <tr>
                            <th class="px-5 py-3">Nama</th>
                            <th class="px-5 py-3">No. HP</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3">Jumlah Booking</th>
                            <th class="px-5 py-3">Total Belanja</th>
                            <th class="px-5 py-3">Booking Terakhir</th>
                            <th class="px-5 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($customers as $customer)
                            <tr class="hover:bg-gray-50">
                                <td class="px-5 py-4 font-medium text-gray-800">{{ $customer->name }}</td>
                                <td class="px-5 py-4 text-gray-600">{{ $customer->phone }}</td>
                                <td class="px-5 py-4">
                                    @if ($customer->is_verified)
                                        <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-1 text-xs font-semibold text-green-700">Terverifikasi</span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-600">Belum Verifikasi</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-gray-800">{{ $customer->bookings_count }}x</td>
                                <td class="px-5 py-4 text-gray-800">Rp{{ number_format($customer->total_spent ?? 0, 0, ',', '.') }}</td>
                                <td class="px-5 py-4 text-gray-600">
                                    {{ $customer->last_booking_date ? \Carbon\Carbon::parse($customer->last_booking_date)->translatedFormat('d M Y') : '-' }}
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <a href="{{ route('owner.customers.show', $customer) }}" class="text-green-600 hover:text-green-700 font-semibold text-xs">
                                        Lihat Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-12 text-center text-gray-500">
                                    @if (request('search'))
                                        Tidak ada pelanggan yang cocok dengan pencarian.
                                    @else
                                        Belum ada pelanggan yang booking di venue Anda.
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6">{{ $customers->links() }}</div>
    </div>
@endsection
