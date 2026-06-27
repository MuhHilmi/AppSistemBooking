@extends('layouts.dashboard')

@section('content')

<div class="flex justify-between mb-6">
    <form action="{{ route('fields.update', $field) }}" method="post" enctype="multipart/form-data" class="space-y-4">
        @csrf
        @method('PUT')
        <div>
            <label for="venue_id">Venue</label>
            <select name="venue_id" id="venue_id" required>
                <option value="">Pilih Venue</option>
                @foreach($venues as $venue)
                    <option value="{{ $venue->id }}" {{ $field->venue_id == $venue->id ? 'selected' : '' }}>
                        {{ $venue->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="name">Nama Lapangan</label>
            <input type="text" name="name" id="name" value="{{ $field->name }}" required>
        </div>
        <div>
            <label for="sport_type">Tipe Lapang</label>
            <select name="sport_type" id="sport_type" required>
                <option value="futsal" {{ $field->sport_type == 'futsal' ? 'selected' : '' }}>Futsal</option>
                <option value="badminton" {{ $field->sport_type == 'badminton' ? 'selected' : '' }}>Badminton</option>
                <option value="basket" {{ $field->sport_type == 'basket' ? 'selected' : '' }}>Basket</option>
                <option value="tennis" {{ $field->sport_type == 'tennis' ? 'selected' : '' }}>Tennis</option>
                <option value="voli" {{ $field->sport_type == 'voli' ? 'selected' : '' }}>Voli</option>
            </select>
        </div>
        <div>
            <label for="description">Deskripsi</label>
            <textarea name="description" id="description" cols="20" rows="2">{{ $field->description }}</textarea>
        </div>
        <div>
            <label for="thumbnail">Photo</label>
            <input type="file" name="thumbnail" id="thumbnail" accept="image/*">
            @if($field->thumbnail)
                <p class="mt-2 text-sm text-gray-600">Foto saat ini:</p>
                <img src="{{ asset('storage/' . $field->thumbnail) }}" alt="Current photo" class="max-w-xs h-auto rounded">
            @endif
        </div>
        <div>
            <label for="price_per_hour">Harga per Jam</label>
            <input type="number" name="price_per_hour" id="price_per_hour" value="{{ $field->price_per_hour }}" required min="0">
        </div>
        <div>
            <label for="capacity">Kapasitas</label>
            <input type="number" name="capacity" id="capacity" value="{{ $field->capacity }}" required min="1">
        </div>
        <div>
            <label for="status">Status</label>
            <select name="status" id="status" required>
                <option value="1" {{ $field->status == 1 ? 'selected' : '' }}>Aktif</option>
                <option value="0" {{ $field->status == 0 ? 'selected' : '' }}>Nonaktif</option>
            </select>
        </div>
        <div>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                Simpan Perubahan
            </button>
            <a href="{{ route('fields.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 ml-2">Batal</a>
        </div>
    </form>
</div>

@endsection