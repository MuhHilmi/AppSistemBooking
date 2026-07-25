@props(['field'])

<div
    class="overflow-hidden rounded-3xl bg-white shadow-sm transition duration-300 hover:-translate-y-2 hover:shadow-xl"
>
    {{-- Image --}}
    <img
        src="{{ $field->thumbnail_url }}"
        alt="{{ $field->name }}"
        class="h-56 w-full object-cover"
    />

    <div class="p-6">
        {{-- Type --}}
        <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
            {{ $field->sport_type_label }}
        </span>

        {{-- Title --}}
        <h3 class="mt-4 text-2xl font-bold text-slate-900">{{ $field->name }}</h3>

        {{-- Capacity & Venue --}}
        <div class="mt-4 flex items-center justify-between">
            <span class="text-slate-500">👥 {{ $field->capacity }} orang</span>
            <span class="text-slate-500">
                📍 {{ $field->venue->name ?? $field->venue->city ?? '-' }}
            </span>
        </div>

        {{-- Price --}}
        <div class="mt-6">
            <span class="text-3xl font-bold text-green-600">
                Rp{{ $field->price_formatted }}
            </span>
            <span class="text-slate-500"> / Jam </span>
        </div>

        {{-- Button --}}
        <button
            type="button"
            x-on:click="openModal(@js($field))"
            class="mt-8 block w-full rounded-xl bg-green-600 py-3 text-center font-semibold text-white transition hover:bg-green-700"
        >
            Detail Lapang
        </button>
    </div>
</div>
