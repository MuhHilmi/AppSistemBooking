@extends('layouts.dashboard')

@section('title', 'Edit Item Tukar Poin')

@section('content')
    <div class="max-w-2xl mx-auto px-4 py-6">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Edit Item Tukar Poin</h1>
            <p class="text-gray-500 mt-1">Ubah detail atau berapa poin yang dibutuhkan untuk item "{{ $item->name }}".</p>
        </div>

        @if ($errors->any())
            <div class="mb-6 bg-red-100 border border-red-300 text-red-700 p-4 rounded-lg">
                <p class="font-semibold mb-1">Periksa kembali isian Anda:</p>
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow p-6">
            <form method="POST" action="{{ route('owner.redemptions.update', $item) }}" class="space-y-5">
                @csrf
                @method('PUT')
                @include('owner.redemptions._form', ['item' => $item])

                <div class="pt-2 flex items-center gap-3">
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white rounded-lg px-6 py-2.5 text-sm font-semibold">
                        Simpan Perubahan
                    </button>
                    <a href="{{ route('owner.redemptions.index') }}" class="text-sm text-gray-600 hover:text-gray-800">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
