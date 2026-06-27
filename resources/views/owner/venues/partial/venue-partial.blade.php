<div class="p-4 rounded-md bg-slate-300 hover:bg-slate-400 transition">
    <h1 class="text-center font-semibold uppercase text-2xl">
        {{ $venue->name }}
    </h1>
    <p class="text-gray-500">
        Alamat:
        {{ $venue->address }}
    </p>
    <p>
        {{ $venue->description }}
    </p>
    <div class="flex flex-row justify-end mt-4 gap-4">
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