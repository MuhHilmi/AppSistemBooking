@extends ('layouts.dashboard')

@section ('content')
    <div class="container mx-auto px-4 py-6">
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-3xl font-bold text-gray-800">Tempat Usaha</h1>

            <a
                href="{{ route('owner.venues.create') }}"
                class="rounded-lg bg-indigo-600 px-5 py-2 text-white transition hover:bg-indigo-700"
            >
                + Tambah Tempat
            </a>
        </div>

        @if (session('success'))
            <div class="mb-6 rounded-lg border border-green-200 bg-green-100 p-4 text-green-700">
                {{ session('success') }}
            </div>
        @endif

        @if ($venues->count())
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($venues as $venue)
                    <div
                        class="overflow-hidden rounded-xl bg-white shadow-md transition duration-300 hover:-translate-y-1 hover:shadow-xl"
                    >
                        {{-- Gambar --}}
                        <img
                            src="{{ 'storage/'.$venue->photo ?? 'https://via.placeholder.com/600x300' }}"
                            class="h-48 w-full object-cover"
                            alt="{{ $venue->name }}"
                        />

                        <div class="p-5">
                            <h2 class="mb-2 text-xl font-bold text-gray-800">{{ $venue->name }}</h2>

                            <p class="mb-3 text-sm text-gray-600 line-clamp-2">
                                {{ $venue->description }}
                            </p>

                            <div class="mb-4 space-y-2 text-sm text-gray-500">
                                <div>📍 {{ $venue->address }}</div>

                                <div>📞 {{ $venue->phone }}</div>
                            </div>

                            <div class="flex gap-2">
                                <a
                                    href="{{ route('owner.venues.show',$venue->id) }}"
                                    class="flex-1 rounded-lg bg-blue-500 py-2 text-center text-white hover:bg-blue-600"
                                >
                                    Detail
                                </a>

                                <a
                                    href="{{ route('owner.venues.edit',$venue->id) }}"
                                    class="flex-1 rounded-lg bg-yellow-500 py-2 text-center text-white hover:bg-yellow-600"
                                >
                                    Edit
                                </a>

                                <form
                                    action="{{ route('owner.venues.destroy',$venue->id) }}"
                                    method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus?');"
                                >
                                    @csrf
                                    @method ('DELETE')

                                    <button
                                        class="rounded-lg bg-red-500 px-4 py-2 text-white hover:bg-red-600"
                                    >
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                @endforeach
            </div>

        @else
            <div class="rounded-xl bg-white py-16 text-center shadow">
                <p class="text-lg text-gray-500">Belum ada tempat usaha.</p>
            </div>

        @endif
    </div>

@endsection
