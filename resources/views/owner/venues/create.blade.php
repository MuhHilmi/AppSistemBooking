@extends ('layouts.dashboard')

@section ('content')
    <div class="flex justify-between mb-6">
        <form action="{{ route('venues.store') }}" method="post" class="space-y-4">
            @csrf
            <div>
                <label for="name">Nama Tempat</label>
                <input type="text" name="name" id="name" required />
            </div>
            <div>
                <label for="address">Alamat</label>
                <textarea name="address" id="address" cols="21" rows="2" required></textarea>
            </div>
            <div>
                <label for="phone">No. HandPhone</label>
                <input type="number" name="phone" id="phone" required />
            </div>
            <div>
                <label for="open_time">Jam Buka</label>
                <input type="time" name="open_time" id="open_time" />
            </div>
            <div>
                <label for="close_time">Jam Tutup</label>
                <input type="time" name="close_time" id="close_time" required />
            </div>
            <div>
                <label for="description">Deskripsi</label>
                <textarea name="description" id="description" cols="20" rows="2"></textarea>
            </div>
            <div>
                <label for="photo">Photo</label>
                <input type="file" name="photo" id="photo" accept="image/*" />
            </div>
            <div>
                <button
                    type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
                >
                    Simpan Tempat
                </button>
            </div>
        </form>
    </div>

@endsection
