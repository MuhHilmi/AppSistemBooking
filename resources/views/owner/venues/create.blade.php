@extends ('layouts.dashboard')

@section ('content')

<div class="max-w-5xl mx-auto px-4 py-6">

    {{-- Header --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">
                Tambah Venue
            </h1>
            <p class="text-gray-500 mt-1">
                Tambahkan tempat baru Anda.
            </p>
        </div>
        <a href="{{ route('venues.index') }}" class="px-5 py-2 rounded-lg border border-gray-300 hover:bg-gray-200 transition">
            Kembali
        </a>
    </div>

    {{-- Error --}}
    @if ($errors->any())
        <div class="mb-6 rounded-lg bg-red-100 border border-red-300 p-4">
            <h3 class="font-semibold text-red-700 mb-2">
                Terjadi kesalahan:
            </h3>
            <ul class="list-disc list-inside text-red-600">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('venues.store') }}" method="post" class="space-y-4" enctype="multipart/form-data">
        {{-- Form --}}
        @csrf
        <div class="grid lg:grid-cols-3 gap-6">

            <div class="lg:col-span-2 bg-white rounded-xl shadow p-6">
                <div class="grid md:grid-cols-2 gap-5">
                    {{-- Nama Venue --}}
                    <div>
                        <label class="font-semibold" for="name">Nama Venue</label>
                        <input type="text" name="name" id="name" class="w-full border rounded-lg mt-2 p-3">
                    </div>
                    <div>
                        <label for="phone" class="font-semibold">No. HandPhone</label>
                        <input type="number" name="phone" id="phone" class="w-full border rounded-lg mt-2 p-3">
                    </div>
                    <div>
                        <label for="open_time" class="font-semibold">Jam Buka</label>
                        <input type="time" name="open_time" id="open_time" class="w-full border rounded-lg mt-2 p-3">
                    </div>
                    <div>
                        <label for="close_time" class="font-semibold">Jam Tutup</label>
                        <input type="time" name="close_time" id="close_time" class="w-full border rounded-lg mt-2 p-3">
                    </div>
                </div>
                <div class="mt-6">
                    <label for="address" class="font-semibold">Alamat</label>
                    <input type="text" name="address" id="address" class="w-full border rounded-lg mt-2 p-3">
                </div>
                <div class="mt-6">
                    <label for="description" class="font-semibold">Deskripsi</label>
                    <textarea name="description" id="description" rows="6" class="w-full border rounded-lg mt-2 p-3">{{ old('description') }}</textarea>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow p-6">
                <h3 class="font-semibold text-lg mb-4">
                    Thumbnail
                </h3>
                <img
                    id="preview"
                    src="https://placehold.co/600x400?text=Thumbnail"
                    class="rounded-lg border w-full h-60 object-cover">
                <input
                    type="file"
                    name="photo"
                    id="photo"
                    class="mt-5 w-full">
                <button
                    class="w-full mt-6 bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg">
                    Simpan Tempat
                </button>
            </div>
        </div>
    </form>
</div>

<script>
document.getElementById('photo').addEventListener('change', function(e){
        const file = e.target.files[0];
        if(file){
            document.getElementById('preview').src = URL.createObjectURL(file);
        }
    });
</script>

@endsection
