@extends('layouts.dashboard')

@section('content')

<h2>Edit Venue</h2>

<form action="{{ route('venues.update', $venue) }}" 
        method="POST" 
        enctype="multipart/form-data">

    @csrf
    @method('PUT')

    <div class="mb-3">
        <label>Nama Venue</label>
        <input
            type="text"
            name="name"
            value="{{ old('name', $venue->name) }}"
            class="form-control">
    </div>

    <div class="mb-3">
        <label>Alamat</label>
        <textarea
            name="address"
            class="form-control">{{ old('address', $venue->address) }}</textarea>
    </div>

    <div class="mb-3">
        <label>No. Telepon</label>
        <input
            type="text"
            name="phone"
            value="{{ old('phone', $venue->phone) }}"
            class="form-control">
    </div>

    <div class="mb-3">
        <label>Jam Buka</label>
        <input
            type="time"
            name="open_time"
            value="{{ old('open_time', $venue->open_time) }}"
            class="form-control">
    </div>

    <div class="mb-3">
        <label>Jam Tutup</label>
        <input
            type="time"
            name="close_time"
            value="{{ old('close_time', $venue->close_time) }}"
            class="form-control">
    </div>

    <div class="mb-3">
        <label>Status</label>

        <select name="status" class="form-control">

            <option value="1"
                {{ $venue->status=='1' ? 'selected':'' }}>
                Active
            </option>

            <option value="0"
                {{ $venue->status=='0' ? 'selected':'' }}>
                Inactive
            </option>

        </select>
    </div>

    <div class="mb-3">
        <label>Foto</label>

        <input
            type="file"
            name="photo"
            class="form-control">

        @if($venue->photo)

            <img
                src="{{ asset('storage/'.$venue->photo) }}"
                width="150">

        @endif

    </div>

    <a href="{{ route('venues.index') }}" class="inline-flex items-center px-5 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition">
        Close
    </a>

    <button type="submit" value="update" class="inline-flex items-center px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
        Update
    </button>

</form>

@endsection