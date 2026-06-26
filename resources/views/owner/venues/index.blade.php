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

@foreach ($venues as $venue)
    <div class="grid grid-rows-2 gap-2 items-center">
        <div>
            <h1>{{ $venue->name }}</h1>
            <p>{{ $venue->address }}</p>
            <div class="flex flex-row gap-4">
                <div>
                    <a href="{{ route('venues.show', $venue) }}" class="inline-flex items-center py-2 px-4 bg-green-500 hover:bg-green-700 transition rounded-md font-semibold text-white">
                        View
                    </a>
                </div>
                <div>
                    <a
                        href="{{ route('venues.edit', $venue) }}"
                        class="inline-flex items-center py-2 px-4 bg-gray-500 hover:bg-gray-700 transition rounded-md font-semibold text-white">
                        Edit
                    </a>
                </div>
                <div>
                    <form action="{{ route('venues.destroy', $venue) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Apakah anda yakin untuk menghapus?')" class="inline-flex items-center py-2 px-4 bg-red-500 hover:bg-red-700 transition rounded-md font-semibold text-white">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach

@endsection