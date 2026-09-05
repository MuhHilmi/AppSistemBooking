@extends('layouts.dashboard')

@section('title', 'Verifikasi Tukar Poin')

@section('content')
    <div class="max-w-6xl mx-auto px-4 py-6">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Verifikasi Tukar Poin</h1>
            <p class="text-gray-500 mt-1">Tandai penukaran poin sebagai "sudah diklaim" setelah customer mengambil item di venue.</p>
        </div>

        @if (session('success'))
            <div class="mb-6 bg-green-100 border border-green-300 text-green-700 p-4 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-xl shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                        <tr>
                            <th class="px-5 py-3">Customer</th>
                            <th class="px-5 py-3">Item</th>
                            <th class="px-5 py-3">Poin</th>
                            <th class="px-5 py-3">Waktu Tukar</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($claims as $claim)
                            <tr class="hover:bg-gray-50">
                                <td class="px-5 py-4 font-medium text-gray-800">{{ $claim->customer->name ?? '-' }}</td>
                                <td class="px-5 py-4 text-gray-600">{{ $claim->benefit->name ?? '-' }}</td>
                                <td class="px-5 py-4 text-gray-600">{{ number_format($claim->points_used, 0, ',', '.') }} poin</td>
                                <td class="px-5 py-4 text-gray-600">{{ $claim->redeemed_at?->translatedFormat('d M Y, H:i') }}</td>
                                <td class="px-5 py-4">
                                    @if ($claim->status === 'used')
                                        <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-1 text-xs font-semibold text-green-700">Sudah diklaim</span>
                                    @elseif ($claim->status === 'pending')
                                        <span class="inline-flex items-center rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-700">Menunggu klaim</span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-600">{{ ucfirst($claim->status) }}</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-right">
                                    @if ($claim->status === 'pending')
                                        <form action="{{ route('owner.redemption-claims.mark-used', $claim) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-green-600 hover:text-green-700 font-semibold text-xs">Tandai Sudah Diklaim</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-12 text-center text-gray-500">
                                    Belum ada penukaran poin dari customer.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4">
            {{ $claims->links() }}
        </div>
    </div>
@endsection
