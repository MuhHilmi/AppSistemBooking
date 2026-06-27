@extends('layouts.dashboard')

@section('content')

<div class="flex justify-between mb-6">
    <form action="{{ route('fields.store') }}" method="post" enctype="multipart/form-data" class="space-y-4">
        @csrf
        <div>
            <label for="venue_id">Venue</label>
            <select name="venue_id" id="venue_id" required>
                <option value="">Pilih Venue</option>
                @foreach($venues as $venue)
                    <option value="{{ $venue->id }}">{{ $venue->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="name">Nama Lapangan</label>
            <input type="text" name="name" id="name" required>
        </div>
        <div>
            <label for="sport_type">Tipe Lapang</label>
            <select name="sport_type" id="sport_type" required>
                <option value="futsal">Futsal</option>
                <option value="badminton">Badminton</option>
                <option value="basket">Basket</option>
                <option value="tennis">Tennis</option>
                <option value="voli">Voli</option>
            </select>
        </div>
        <div>
            <label for="description">Deskripsi</label>
            <textarea name="description" id="description" cols="20" rows="2"></textarea>
        </div>
        <div>
            <label for="thumbnail">Photo</label>
            <input type="file" name="thumbnail" id="thumbnail" accept="image/*">
        </div>
        <div>
            <label for="price_per_hour">Harga per Jam</label>
            <input type="number" name="price_per_hour" id="price_per_hour" required min="0">
        </div>
        <div>
            <label for="capacity">Kapasitas</label>
            <input type="number" name="capacity" id="capacity" required min="1">
        </div>
        <div>
            <label for="status">Status</label>
            <select name="status" id="status" required>
                <option value="1">Aktif</option>
                <option value="0">Nonaktif</option>
            </select>
        </div>
        <div>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                Simpan Data
            </button>
        </div>
    </form>
</div>

@endsection