@extends ('layouts.dashboard')

@section ('content')
    <div class="max-w-5xl mx-auto px-4 py-6">
        {{-- Header --}}
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Edit Lapangan</h1>
                <p class="text-gray-500 mt-1">Perbarui informasi lapangan.</p>
            </div>
            <a
                href="{{ route('fields.index') }}"
                class="px-5 py-2 rounded-lg border border-gray-300 hover:bg-gray-200 transition"
            >
                Kembali
            </a>
        </div>

        {{-- Error --}}
        @if ($errors->any())
            <div class="mb-6 rounded-lg bg-red-100 border border-red-300 p-4">
                <h3 class="font-semibold text-red-700 mb-2">Terjadi kesalahan:</h3>
                <ul class="list-disc list-inside text-red-600">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form
            action="{{ route('fields.update', $field) }}"
            method="POST"
            enctype="multipart/form-data"
        >
            @csrf
            @method ('PUT')
            <div class="grid lg:grid-cols-3 gap-6">
                {{-- Form --}}
                <div class="lg:col-span-2 bg-white rounded-xl shadow p-6">
                    <div class="grid md:grid-cols-2 gap-5">
                        {{-- Venue --}}
                        <div>
                            <label class="font-semibold"> Venue </label>
                            <select name="venue_id" class="w-full border rounded-lg mt-2 p-3">
                                <option value="">Pilih Venue</option>
                                @foreach ($venues as $venue)
                                    <option
                                        value="{{ $venue->id }}"
                                        @selected (old('venue_id', $field->venue_id) == $venue->id)
                                    >
                                        {{ $venue->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Nama --}}
                        <div>
                            <label class="font-semibold"> Nama Lapangan </label>
                            <input
                                type="text"
                                name="name"
                                value="{{ old('name', $field->name) }}"
                                class="w-full border rounded-lg mt-2 p-3"
                            />
                        </div>

                        {{-- Jenis --}}
                        <div>
                            <label class="font-semibold"> Jenis Olahraga </label>
                            <select name="sport_type" class="w-full border rounded-lg mt-2 p-3">
                                <option value="">Pilih</option>
                                @foreach ([
                                'futsal',
                                'badminton',
                                'basket',
                                'mini Soccer',
                                'tenis',
                                'voli'
                            ] as $sport)
                                    <option
                                        value="{{ $sport }}"
                                        @selected (old('sport_type', $field->sport_type) == $sport)
                                    >
                                        {{ $sport }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Harga --}}
                        <div>
                            <label class="font-semibold"> Harga per Jam </label>
                            <input
                                type="number"
                                name="price_per_hour"
                                value="{{ old('price_per_hour', $field->price_per_hour) }}"
                                class="w-full border rounded-lg mt-2 p-3"
                            />
                        </div>

                        {{-- Kapasitas --}}
                        <div>
                            <label class="font-semibold"> Kapasitas </label>
                            <input
                                type="number"
                                name="capacity"
                                value="{{ old('capacity', $field->capacity) }}"
                                class="w-full border rounded-lg mt-2 p-3"
                            />
                        </div>

                        {{-- Status --}}
                        <div>
                            <label class="font-semibold"> Status </label>
                            <select name="status" class="w-full border rounded-lg mt-2 p-3">
                                <option value="1" @selected (old('status', $field->status) == 1)>
                                    Aktif
                                </option>
                                <option value="0" @selected (old('status', $field->status) == 0)>
                                    Nonaktif
                                </option>
                            </select>
                        </div>
                    </div>

                    {{-- Deskripsi --}}
                    <div class="mt-6">
                        <label class="font-semibold"> Deskripsi </label>
                        <textarea
                            name="description"
                            rows="6"
                            class="w-full border rounded-lg mt-2 p-3"
                            >{{ old('description', $field->description) }}</textarea
                        >
                    </div>
                </div>

                {{-- Thumbnail --}}
                <div class="bg-white rounded-xl shadow p-6">
                    <h3 class="font-semibold text-lg mb-4">Thumbnail</h3>
                    <img
                        id="preview"
                        src="{{ $field->thumbnail
                        ? asset('storage/'.$field->thumbnail)
                        : 'https://placehold.co/600x400?text=Thumbnail' }}"
                        class="rounded-lg border w-full h-60 object-cover"
                    />
                    <input type="file" id="thumbnail" name="thumbnail" class="mt-5 w-full" />
                    <p class="text-sm text-gray-500 mt-2">Kosongkan jika tidak ingin mengganti gambar.</p>
                    <button
                        type="submit"
                        class="w-full mt-6 bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg"
                    >
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- Preview Thumbnail --}}
    <script>
        document.getElementById('thumbnail').addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file) {
                document.getElementById('preview').src = URL.createObjectURL(file);
            }
        });
    </script>

@endsection
