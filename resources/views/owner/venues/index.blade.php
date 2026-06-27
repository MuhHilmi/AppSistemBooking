@extends('layouts.dashboard')

@section('content')

<div class="flex justify-between mb-6">
    <h1 class="text-2xl font-bold">
        Tempat Usaha
    </h1>
    <a href="{{ route('venues.create') }}"
    class="bg-indigo-600 text-white px-4 py-2 rounded">
        Tambah Tempat
    </a>
</div>

<div class="container mx-auto px-4 py-5">
    @if(session('success'))
        <div class="mb-4 rounded-lg bg-green-100 p-4 text-center text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if($venues->count())
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach($venues as $venue)
                @include('owner.venues.partial.venue-partial', ['item' => $venue])
            @endforeach
        </div>
    @else
        <div class="py-10 text-center text-gray-500">
            Belum ada venue.
        </div>
    @endif
</div>

@endsection