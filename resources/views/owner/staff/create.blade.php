@extends ('layouts.dashboard')

@section ('title', 'Tambah Penjaga')

@section ('content')
    <div class="max-w-2xl mx-auto px-4 py-6">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Tambah Penjaga</h1>
            <p class="text-gray-500 mt-1">Akun ini hanya bisa mengakses venue yang Anda pilih di bawah. Password akan digenerate otomatis oleh sistem.</p>
        </div>

        @if ($errors->any())
            <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow p-6">
            <form method="POST" action="{{ route('owner.staff.store') }}" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Penjaga</label>
                    <input type="text" name="name" value="{{ old('name') }}"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-green-500 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-green-500 focus:ring-green-500">
                    <p class="mt-1 text-xs text-gray-500">Dipakai penjaga untuk login lewat halaman login owner yang sama.</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Venue yang Dijaga</label>
                    <select name="venue_id" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-green-500 focus:ring-green-500">
                        <option value="">-- Pilih venue --</option>
                        @foreach ($venues as $venue)
                            <option value="{{ $venue->id }}" {{ old('venue_id') == $venue->id ? 'selected' : '' }}>{{ $venue->name }}</option>
                        @endforeach
                    </select>
                    @if ($venues->isEmpty())
                        <p class="mt-1 text-xs text-red-600">Anda belum punya venue. Tambah venue dulu sebelum membuat akun penjaga.</p>
                    @endif
                </div>

                <div class="pt-2 flex items-center gap-3">
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white rounded-lg px-6 py-2.5 text-sm font-semibold" {{ $venues->isEmpty() ? 'disabled' : '' }}>
                        Buat Akun Penjaga
                    </button>
                    <a href="{{ route('owner.staff.index') }}" class="text-sm text-gray-600 hover:text-gray-800">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
