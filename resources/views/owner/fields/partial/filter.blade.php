<div class="bg-white rounded-xl shadow p-5 mb-6">
    <form method="GET">
        <div class="grid md:grid-cols-5 gap-4">
            {{-- Search --}}
            @include ('owner.fields.components.search-input')

            {{-- Jenis --}}
            @include ('owner.fields.components.select-type')

            {{-- Status --}}
            @include ('owner.fields.components.select-status')

            {{-- Venue --}}
            @include ('owner.fields.components.select-venue')

            <button class="bg-green-600 hover:bg-green-700 text-white rounded-lg">Filter</button>
        </div>
    </form>
</div>
