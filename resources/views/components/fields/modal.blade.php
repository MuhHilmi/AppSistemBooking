{{-- Detail Modal --}}
<div
    x-show="showModal"
    x-cloak
    class="fixed inset-0 z-[60] flex items-center justify-center p-4"
    style="display: none;"
>
    {{-- Backdrop --}}
    <div
        x-show="showModal"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        x-on:click="closeModal()"
        class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"
    ></div>

    {{-- Modal Panel --}}
    <div
        x-show="showModal"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95 translate-y-4"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 translate-y-4"
        class="relative max-h-[90vh] w-full max-w-2xl overflow-y-auto rounded-3xl bg-white shadow-2xl"
        @click.outside="closeModal()"
    >
        <template x-if="selectedField">
            <div>
                {{-- Image + Close --}}
                <div class="relative">
                    <img
                        :src="selectedField.thumbnail_url"
                        :alt="selectedField.name"
                        class="h-64 w-full rounded-t-3xl object-cover"
                    />

                    <button
                        type="button"
                        x-on:click="closeModal()"
                        class="absolute right-4 top-4 flex h-10 w-10 items-center justify-center rounded-full bg-white/90 text-slate-700 shadow-md transition hover:bg-white"
                        aria-label="Tutup"
                    >
                        ✕
                    </button>

                    <span
                        class="absolute left-4 top-4 rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700"
                        x-text="selectedField.sport_type_label"
                    ></span>
                </div>

                <div class="p-8">
                    {{-- Title --}}
                    <h3 class="text-2xl font-bold text-slate-900" x-text="selectedField.name"></h3>

                    {{-- Venue / Location --}}
                    <p class="mt-2 flex flex-wrap items-center gap-1 text-slate-500">
                        <img src="{{ asset('img/icon/png/placeholder.png') }}" alt="Icon" class="h-6 w-6"> <span x-text="selectedField.venue?.name"></span>
                        <template x-if="selectedField.venue?.address">
                            <span>&mdash; <span x-text="selectedField.venue.address"></span></span>
                        </template>
                    </p>

                    {{-- Description --}}
                    <p class="mt-5 leading-7 text-slate-600" x-text="selectedField.description"></p>

                    {{-- Info Grid --}}
                    <div class="mt-6 grid grid-cols-2 gap-4">
                        <div class="rounded-2xl bg-slate-50 p-4">
                            <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Harga</p>
                            <p class="mt-1 text-lg font-bold text-green-600">
                                Rp<span x-text="selectedField.price_formatted"></span>
                                <span class="text-sm font-normal text-slate-500">/ Jam</span>
                            </p>
                        </div>

                        <div class="rounded-2xl bg-slate-50 p-4">
                            <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Kapasitas</p>
                            <p class="mt-1 text-lg font-bold text-slate-900">
                                <span x-text="selectedField.capacity"></span> orang
                            </p>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                        <a
                            x-bind:href="'{{ route('customer.bookings.create', ['field' => 'FIELD_ID']) }}'.replace('FIELD_ID', selectedField.id)"
                            class="flex-1 rounded-xl bg-green-600 py-3 text-center font-semibold text-white transition hover:bg-green-700"
                        >
                            Booking Sekarang
                        </a>

                        <button
                            type="button"
                            x-on:click="closeModal()"
                            class="flex-1 rounded-xl border border-slate-300 py-3 text-center font-semibold text-slate-700 transition hover:border-slate-400"
                        >
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </div>
</div>
