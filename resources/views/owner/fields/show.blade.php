@extends ('layouts.dashboard')

@section ('content')
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            @if ($field->thumbnail)
                <img
                    src="{{ asset('storage/' . $field->thumbnail) }}"
                    alt="{{ $field->name }}"
                    class="w-full h-64 object-cover"
                />
            @else
                <div class="w-full h-64 bg-gray-200 flex items-center justify-center">
                    <span class="text-gray-500">Tidak ada foto</span>
                </div>
            @endif

            <div class="p-6">
                <h1 class="text-2xl font-bold text-gray-900 mb-4">{{ $field->name }}</h1>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Venue</label>
                        <p class="text-gray-900">{{ $field->venue->name }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500">Tipe Lapang</label>
                        <p class="text-gray-900">{{ $field->sport_type }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500">Deskripsi</label>
                        <p class="text-gray-900">{{ $field->description }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500">Harga per Jam</label>
                        <p class="text-gray-900">Rp {{ number_format($field->price_per_hour,0,',','.') }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500">Kapasitas</label>
                        <p class="text-gray-900">{{ $field->capacity }} Orang</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-500">Status</label>
                        <p class="text-gray-900">{{ $field->status ? 'Aktif' : 'Nonaktif' }}</p>
                    </div>
                </div>

                <div class="mt-6 flex gap-4">
                    <a
                        href="{{ route('fields.index') }}"
                        class="inline-block bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600"
                    >
                        Kembali
                    </a>
                    <a
                        href="{{ route('fields.edit', $field->id) }}"
                        class="inline-block bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700"
                    >
                        Edit
                    </a>
                </div>
            </div>
        </div>
    </div>

@endsection
