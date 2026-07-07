<div class="bg-white rounded-xl shadow p-5 mb-6">
    <form method="GET">
        <div class="grid md:grid-cols-3 gap-4">
            {{-- Cari --}}
            @include ('customer.bookings.partials.search-input')

            {{-- Jenis --}}
            @include ('customer.bookings.partials.search-type')
            <button class="bg-blue-600 hover:bg-blue-700 text-white rounded-lg">Filter</button>
        </div>
    </form>
</div>
