@if ($fields->count())
    <div class="grid lg:grid-cols-3 md:grid-cols-2 gap-6">
        @foreach ($fields as $field)
            <div class="bg-white rounded-xl shadow overflow-hidden">
                {{-- Thumbnail --}}
                <img
                    src="{{ $field->thumbnail
                        ? asset('storage/'.$field->thumbnail)
                        : asset('images/no-image.png') }}"
                    class="h-52 w-full object-cover"
                />
                <div class="p-5">
                    {{-- Status --}}
                    <div class="flex justify-between items-center">
                        <span
                            class="text-sm px-3 py-1 rounded-full
                            {{ $field->status
                                ? 'bg-green-100 text-green-700'
                                : 'bg-red-100 text-red-700' }}"
                        >
                            {{ $field->status
                                ? 'Aktif'
                                : 'Nonaktif' }}
                        </span>
                        <span class="text-gray-400 text-sm"> {{ $field->venue->name }} </span>
                    </div>
                    <h2 class="text-xl font-bold mt-3">{{ $field->name }}</h2>
                    <p class="text-gray-500">{{ ucwords($field->sport_type) }}</p>
                    <div class="mt-4 space-y-2">
                        <div class="flex justify-between">
                            <span>Harga/Jam</span>
                            <span class="font-semibold">
                                Rp {{ number_format($field->price_per_hour,0,',','.') }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span>Kapasitas</span>
                            <span> {{ $field->capacity }} Orang </span>
                        </div>
                    </div>
                    {{-- Button --}}
                    <div class="grid grid-cols-3 gap-2 mt-6">
                        <a
                            href="{{ route('fields.show',$field) }}"
                            class="bg-gray-100 hover:bg-gray-200 rounded-lg py-2 text-center"
                        >
                            Detail
                        </a>
                        <a
                            href="{{ route('fields.edit',$field) }}"
                            class="bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg py-2 text-center"
                        >
                            Edit
                        </a>
                        <form
                            action="{{ route('fields.destroy',$field) }}"
                            method="POST"
                            onsubmit="return confirm('Hapus lapangan ini?');"
                        >
                            @csrf
                            @method ('DELETE')
                            <button
                                class="w-full bg-red-600 hover:bg-red-700 text-white rounded-lg py-2"
                            >
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="mt-8">{{ $fields->withQueryString()->links() }}</div>
@else
    <div class="bg-white rounded-xl shadow p-12 text-center">
        <h2 class="text-2xl font-semibold text-gray-700">Belum Ada Lapangan</h2>
        <p class="text-gray-500 mt-2">Silakan tambahkan lapangan pertama Anda.</p>
        <a
            href="{{ route('fields.create') }}"
            class="inline-block mt-5 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg"
        >
            Tambah Lapangan
        </a>
    </div>
@endif
