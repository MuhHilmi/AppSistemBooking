{{-- Popup Detail Lapang --}}
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
        class="relative max-h-[90vh] w-full max-w-lg overflow-y-auto rounded-xl bg-white shadow-2xl"
        @click.outside="closeModal()"
    >
        <template x-if="selectedField">
            <div
                x-data="{
                    days: { 1: 'Senin', 2: 'Selasa', 3: 'Rabu', 4: 'Kamis', 5: 'Jumat', 6: 'Sabtu', 7: 'Minggu' }
                }"
            >
                {{-- Image + Close --}}
                <div class="relative">
                    <template x-if="selectedField.thumbnail">
                        <img
                            :src="'{{ asset('storage') }}/' + selectedField.thumbnail"
                            :alt="selectedField.name"
                            class="h-56 w-full rounded-t-xl object-cover"
                        />
                    </template>
                    <template x-if="!selectedField.thumbnail">
                        <div class="h-32 w-full rounded-t-xl bg-gray-100"></div>
                    </template>

                    <button
                        type="button"
                        x-on:click="closeModal()"
                        class="absolute right-4 top-4 flex h-9 w-9 items-center justify-center rounded-full bg-white/90 text-gray-700 shadow-md transition hover:bg-white"
                        aria-label="Tutup"
                    >
                        ✕
                    </button>
                </div>

                <div class="p-6">
                    {{-- Title --}}
                    <p class="text-sm font-medium text-gray-500" x-text="selectedField.venue?.name"></p>
                    <h3 class="text-2xl font-bold" x-text="'Lapang ' + selectedField.name"></h3>
                    <span
                        class="mt-2 inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700"
                        x-text="selectedField.sport_type"
                        style="text-transform: capitalize;"
                    ></span>

                    {{-- Description (opsional, jika field->description tersedia) --}}
                    <template x-if="selectedField.description">
                        <p class="mt-4 leading-7 text-gray-600" x-text="selectedField.description"></p>
                    </template>

                    {{-- Info Grid --}}
                    <div class="mt-5 grid grid-cols-2 gap-4">
                        <div class="rounded-lg bg-gray-50 p-4">
                            <p class="text-xs font-medium uppercase tracking-wide text-gray-400">Harga</p>
                            <p class="mt-1 text-lg font-bold text-green-600">
                                Rp<span x-text="Number(selectedField.price_per_hour).toLocaleString('id-ID')"></span>
                                <span class="text-sm font-normal text-gray-500">/ Jam</span>
                            </p>
                        </div>

                        <template x-if="selectedField.capacity">
                            <div class="rounded-lg bg-gray-50 p-4">
                                <p class="text-xs font-medium uppercase tracking-wide text-gray-400">Kapasitas</p>
                                <p class="mt-1 text-lg font-bold text-gray-900">
                                    <span x-text="selectedField.capacity"></span> orang
                                </p>
                            </div>
                        </template>
                    </div>

                    {{-- Jam Operasional (opsional, jika relasi operatingSchedules di-eager-load) --}}
                    <template x-if="selectedField.operating_schedules && selectedField.operating_schedules.length">
                        <div class="mt-6">
                            <h4 class="font-bold mb-2">Jam Operasional</h4>
                            <template x-for="schedule in selectedField.operating_schedules" :key="schedule.id">
                                <div class="flex justify-between py-2 border-b text-sm">
                                    <span x-text="days[schedule.day_of_week]"></span>
                                    <template x-if="schedule.is_open">
                                        <span x-text="schedule.open_time.substring(0,5) + ' - ' + schedule.close_time.substring(0,5)"></span>
                                    </template>
                                    <template x-if="!schedule.is_open">
                                        <span class="text-red-600">Tutup</span>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </template>

                    {{-- Actions --}}
                    <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                        <a
                            x-bind:href="'{{ route('customer.bookings.create', ['field' => 'FIELD_ID']) }}'.replace('FIELD_ID', selectedField.id)"
                            class="flex-1 rounded-lg bg-green-600 py-3 text-center font-semibold text-white transition hover:bg-green-700"
                        >
                            Booking Sekarang
                        </a>

                        <button
                            type="button"
                            x-on:click="closeModal()"
                            class="flex-1 rounded-lg border border-gray-300 py-3 text-center font-semibold text-gray-700 transition hover:border-gray-400"
                        >
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </div>
</div>
