@extends ('layouts.dashboard')

@section ('title', 'Kelola Penjaga')

@section ('header-actions')
    <a href="{{ route('owner.staff.create') }}" class="bg-green-600 hover:bg-green-700 text-white rounded-lg px-5 py-2.5 text-sm font-semibold">
        + Tambah Penjaga
    </a>
@endsection

@section ('content')
    <div class="max-w-7xl mx-auto px-4 py-6">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Kelola Penjaga</h1>
            <p class="text-gray-500 mt-1">Akun staf yang menjaga venue Anda sehari-hari. Setiap penjaga hanya bisa mengakses 1 venue yang di-assign.</p>
        </div>

        @if (session('success'))
            <div class="mb-6 rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        @if (session('generated_password'))
            <div class="mb-6 rounded-lg border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800">
                <p class="font-semibold mb-1">Password akun untuk {{ session('generated_password_for') }}:</p>
                <p class="font-mono text-base tracking-wide bg-white inline-block px-3 py-1.5 rounded border border-amber-200">{{ session('generated_password') }}</p>
                <p class="mt-2 text-xs">Sampaikan password ini ke penjaga secara langsung/manual. Password ini tidak akan ditampilkan lagi setelah halaman ini ditutup. Penjaga akan diminta mengganti password saat login pertama.</p>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                        <tr>
                            <th class="px-5 py-3">Nama</th>
                            <th class="px-5 py-3">Email</th>
                            <th class="px-5 py-3">Venue</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($staff as $person)
                            <tr class="hover:bg-gray-50">
                                <td class="px-5 py-4 font-medium text-gray-800">{{ $person->name }}</td>
                                <td class="px-5 py-4 text-gray-600">{{ $person->email }}</td>
                                <td class="px-5 py-4 text-gray-600">{{ $person->venue->name ?? '-' }}</td>
                                <td class="px-5 py-4">
                                    @if ($person->is_active)
                                        <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-1 text-xs font-semibold text-green-700">Aktif</span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-600">Nonaktif</span>
                                    @endif
                                    @if ($person->must_change_password)
                                        <span class="inline-flex items-center rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-700 ml-1">Belum login</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-right space-x-3">
                                    <a href="{{ route('owner.staff.edit', $person) }}" class="text-green-600 hover:text-green-700 font-semibold text-xs">Edit</a>
                                    <form action="{{ route('owner.staff.toggle-active', $person) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-amber-600 hover:text-amber-700 font-semibold text-xs">
                                            {{ $person->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                        </button>
                                    </form>
                                    <form action="{{ route('owner.staff.destroy', $person) }}" method="POST" class="inline" onsubmit="return confirm('Hapus akun penjaga ini secara permanen?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-700 font-semibold text-xs">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-12 text-center text-gray-500">
                                    Belum ada akun penjaga. Klik "+ Tambah Penjaga" untuk membuat akun staf pertama.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
