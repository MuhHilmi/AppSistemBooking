@extends('layouts.dashboard')

@section('content')

<div class="max-w-5xl mx-auto px-4 py-6">

    {{-- Header --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">
                Edit Venue
            </h1>
            <p class="text-gray-500 mt-1">
                Ubah venue Anda sesuai dengan kebutuhan.
            </p>
        </div>
        <a href="{{ route('venues.index') }}" class="px-5 py-2 rounded-lg border border-gray-300 hover:bg-gray-200 transition">
            Kembali
        </a>
    </div>

    {{-- Error --}}
    @if ($errors->any())
        <div class="mb-6 rounded-lg bg-red-100 border border-red-300 p-4">
            <h3 class="font-semibold text-red-700 mb-2">
                Terjadi kesalahan:
            </h3>
            <ul class="list-disc list-inside text-red-600">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form --}}
    <form action="{{ route('venues.update', $venue) }}" method="POST" enctype="multipart/form-data" class="space-y-4">

        @csrf
        @method('PUT')

        <div class="grid lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 bg-white rounded-xl shadow p-6">
                <div class="grid md:grid-cols-2 gap-5">
                    <div>
                        <label class="font-semibold">Nama Venue</label>
                        <input type="text" name="name" value="{{ old('name', $venue->name) }}" class="w-full border rounded-lg mt-2 p-3">
                    </div>

                    <div>
                        <label class="font-semibold">No. Telepon</label>
                        <input type="text" name="phone" value="{{ old('phone', $venue->phone) }}" class="w-full border rounded-lg mt-2 p-3">
                    </div>

                    <div>
                        <label class="font-semibold">Jam Buka</label>
                        <input type="time" name="open_time" value="{{ old('open_time', $venue->open_time) }}" class="w-full border rounded-lg mt-2 p-3">
                    </div>

                    <div>
                        <label class="font-semibold">Jam Tutup</label>
                        <input type="time" name="close_time" value="{{ old('close_time', $venue->close_time) }}" class="w-full border rounded-lg mt-2 p-3">
                    </div>
                </div>
                <div class="mt-6">
                    <label class="font-semibold">Status</label>
                    <select name="status" class="w-full border rounded-lg mt-2 p-3">
                        <option value="1" {{ $venue->status=='1' ? 'selected':'' }}>
                            Active
                        </option>
                        <option value="0" {{ $venue->status=='0' ? 'selected':'' }}>
                            Inactive
                        </option>
                    </select>
                </div>
                <div class="mt-6">
                    <label class="font-semibold">Alamat</label>
                    <textarea name="address" class="w-full border rounded-lg mt-2 p-3">{{ old('address', $venue->address) }}</textarea>
                </div>

            </div>
            <div class="bg-white rounded-xl shadow p-6">
                <h3 class="font-semibold text-lg mb-4">
                    Photo
                </h3>
                @if($venue->photo)
                    <img src="{{ asset('storage/'.$venue->photo) }}" class="rounded-lg border w-full h-60 object-cover">
                @endif
                <input type="file" name="photo" class="form-control">
                <div class="flex flex-col justify-center gap-4 mt-6">
                    <button type="submit" value="update" class="inline-flex items-center px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                        Update
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>


@endsection