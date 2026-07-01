@extends ('layouts.dashboard')

@section ('content')
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            @if ($venue->photo)
                <img
                    src="{{ asset('storage/' . $venue->photo) }}"
                    alt="{{ $venue->name }}"
                    class="w-full h-64 object-cover"
                />
            @else
                <div class="w-full h-64 bg-gray-200 flex items-center justify-center">
                    <span class="text-gray-500">Tidak ada foto</span>
                </div>
            @endif

            <div class="p-6">
                <h1 class="text-2xl font-bold text-gray-900 mb-4">{{ $venue->name }}</h1>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Alamat</label>
                        <p class="text-gray-900">{{ $venue->address }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500"
                            >Nomor Handphone</label
                        >
                        <p class="text-gray-900">{{ $venue->phone }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500"
                            >Jam Operasional</label
                        >
                        <p class="text-gray-900">
                            {{ \Carbon\Carbon::parse($venue->open_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($venue->close_time)->format('H:i') }}
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500">Jumlah Lapang</label>
                        {{-- <p class="text-gray-900">{{ $venue->fields->count() }}</p> --}}
                    </div>
                </div>

                <div class="mt-6 flex gap-4">
                    <a
                        href="{{ route('venues.index') }}"
                        class="inline-block bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600"
                    >
                        Kembali
                    </a>
                    <a
                        href="{{ route('venues.edit', $venue->id) }}"
                        class="inline-block bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700"
                    >
                        Edit
                    </a>
                </div>
            </div>
        </div>
    </div>

@endsection
