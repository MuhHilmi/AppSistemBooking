<div
    class="bg-white rounded-xl shadow overflow-hidden hover:-translate-y-2 hover:shadow-lg transition"
>
    @if ($field -> thumbnail)
        <img
            src="{{ asset('storage/'.$field->thumbnail) }}"
            alt="{{ $field->venue->name }}"
            class="w-full h-48 object-cover"
        />
    @endif
    <div class="p-5">
        <h2 class="text-xl font-semibold">{{ $field -> venue -> name }}</h2>
        <p class="text-lg">Lapang {{ $field -> name }}</p>
        <p class="text-gray-500">{{ ucwords($field -> sport_type) }}</p>
        <div class="mt-4">
            <span class="text-2xl font-bold">
                Rp{{ number_format($field -> price_per_hour) }}
            </span>
            <span class="text-gray-500"> / jam </span>
        </div>
        <a
            href="{{ route('customer.bookings.create', $field) }}"
            class="mt-5 inline-flex w-full justify-center rounded-lg bg-indigo-600 px-4 py-3 text-white hover:bg-indigo-700"
            >Booking</a
        >
    </div>
    @if ($fields -> isEmpty())
        <div class="col-span-full">
            <div class="bg-white rounded-xl p-10 text-center">
                <h2 class="text-xl font-semibold">Belum ada lapang yang tersedia</h2>
            </div>
        </div>
    @endif
</div>
