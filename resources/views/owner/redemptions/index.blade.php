@extends('layouts.dashboard')

@section('title', 'Katalog Tukar Poin')

@section('content')
    <div class="max-w-6xl mx-auto px-4 py-6">
        <div class="mb-6 flex items-start justify-between gap-4 flex-wrap">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Katalog Tukar Poin</h1>
                <p class="text-gray-500 mt-1">Atur item apa saja yang bisa customer tukar dengan poin membership, dan berapa poin yang dibutuhkan.</p>
            </div>
            <a href="{{ route('owner.redemptions.create') }}" class="bg-green-600 hover:bg-green-700 text-white rounded-lg px-5 py-2.5 text-sm font-semibold whitespace-nowrap">
                + Tambah Item
            </a>
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
                            <th class="px-5 py-3">Nama Item</th>
                            <th class="px-5 py-3">Venue</th>
                            <th class="px-5 py-3">Tipe</th>
                            <th class="px-5 py-3">Poin Dibutuhkan</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($items as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-5 py-4">
                                    <p class="font-medium text-gray-800">{{ $item->name }}</p>
                                    @if ($item->description)
                                        <p class="text-xs text-gray-500 mt-0.5">{{ \Illuminate\Support\Str::limit($item->description, 60) }}</p>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-gray-600">{{ $item->venue->name ?? '-' }}</td>
                                <td class="px-5 py-4 text-gray-600 capitalize">{{ str_replace('_', ' ', $item->type) }}</td>
                                <td class="px-5 py-4 font-semibold text-gray-800">{{ number_format($item->point_cost, 0, ',', '.') }} poin</td>
                                <td class="px-5 py-4">
                                    @if ($item->is_active)
                                        <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-1 text-xs font-semibold text-green-700">Aktif</span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-600">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-right space-x-3">
                                    <a href="{{ route('owner.redemptions.edit', $item) }}" class="text-green-600 hover:text-green-700 font-semibold text-xs">Edit</a>
                                    <form action="{{ route('owner.redemptions.toggle-active', $item) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-amber-600 hover:text-amber-700 font-semibold text-xs">
                                            {{ $item->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                        </button>
                                    </form>
                                    <form action="{{ route('owner.redemptions.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('Hapus item ini secara permanen?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-700 font-semibold text-xs">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-12 text-center text-gray-500">
                                    Belum ada item tukar poin. Klik "+ Tambah Item" untuk membuat item pertama.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
