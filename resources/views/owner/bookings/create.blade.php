@extends ('layouts.dashboard')

@section ('title', 'Buat Booking')

@section ('content')
    <div class="max-w-3xl mx-auto px-4 py-6">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Buat Booking</h1>
            <p class="text-gray-500 mt-1">Booking manual untuk customer yang datang langsung (walk-in). Pembayaran cash.</p>
        </div>

        @if ($errors->any())
            <div class="mb-6 bg-red-100 border border-red-300 text-red-700 p-4 rounded-lg">
                {{ $errors->first() }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 bg-red-100 border border-red-300 text-red-700 p-4 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        @if ($fields->isEmpty())
            <div class="bg-white rounded-xl shadow p-12 text-center">
                <h2 class="text-xl font-semibold text-gray-700">Belum Ada Lapangan Aktif</h2>
                <p class="text-gray-500 mt-2">Tambahkan lapangan terlebih dahulu sebelum membuat booking.</p>
                <a href="{{ route('owner.fields.create') }}" class="inline-block mt-5 bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg">
                    Tambah Lapangan
                </a>
            </div>
        @else
            <form action="{{ route('owner.bookings.store') }}" method="POST" id="booking-form" class="space-y-6">
                @csrf

                {{-- Lapangan --}}
                <div class="bg-white rounded-xl shadow p-6">
                    <label for="field_id" class="block font-medium text-gray-700 mb-2">Pilih Lapangan</label>
                    <select name="field_id" id="field_id" required
                        class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                        <option value="">-- Pilih Lapangan --</option>
                        @foreach ($fields as $field)
                            <option value="{{ $field->id }}" data-price="{{ $field->price_per_hour }}"
                                {{ (int) $selectedFieldId === $field->id ? 'selected' : '' }}>
                                {{ $field->name }} — {{ $field->venue->name }} (Rp{{ number_format($field->price_per_hour, 0, ',', '.') }}/jam)
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Tanggal & Slot --}}
                <div class="bg-white rounded-xl shadow p-6">
                    <label for="booking-date" class="block font-medium text-gray-700 mb-2">Pilih Tanggal</label>
                    <input type="date" id="booking-date" name="booking_date" min="{{ now()->toDateString() }}" required
                        class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">

                    <input type="hidden" id="selected-start-time" name="start_time">
                    <input type="hidden" id="selected-end-time" name="end_time">

                    <div id="slot-container" class="mt-4">
                        <p class="text-sm text-gray-400">Pilih lapangan dan tanggal untuk melihat slot yang tersedia.</p>
                    </div>
                </div>

                {{-- Customer --}}
                <div class="bg-white rounded-xl shadow p-6" x-data="{ mode: 'existing', customerId: '', customerLabel: '', query: '', results: [] }">
                    <label class="block font-medium text-gray-700 mb-2">Customer</label>

                    <div class="flex gap-4 mb-4 text-sm">
                        <label class="flex items-center gap-2">
                            <input type="radio" x-model="mode" value="existing"> Customer Terdaftar
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="radio" x-model="mode" value="new"> Customer Baru
                        </label>
                    </div>

                    {{-- Customer terdaftar --}}
                    <div x-show="mode === 'existing'" class="relative">
                        <input type="text" x-model="query" placeholder="Cari nama atau nomor HP..."
                            x-on:input.debounce.400ms="
                                if (query.length < 2) { results = []; return; }
                                fetch('{{ route('owner.bookings.customers.search') }}?q=' + encodeURIComponent(query))
                                    .then(r => r.json())
                                    .then(data => results = data)
                            "
                            class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">

                        <input type="hidden" name="customer_id" :value="customerId">

                        <div x-show="results.length > 0" class="mt-2 border border-gray-200 rounded-lg divide-y divide-gray-100 max-h-56 overflow-y-auto">
                            <template x-for="customer in results" :key="customer.id">
                                <button type="button"
                                    x-on:click="customerId = customer.id; customerLabel = customer.name + ' — ' + customer.phone; query = customerLabel; results = []"
                                    class="w-full text-left px-4 py-2.5 hover:bg-gray-50 text-sm">
                                    <span x-text="customer.name"></span>
                                    <span class="text-gray-400" x-text="'(' + customer.phone + ')'"></span>
                                </button>
                            </template>
                        </div>

                        <p class="mt-2 text-xs" :class="customerId ? 'text-green-600' : 'text-gray-400'" x-text="customerId ? 'Customer dipilih: ' + customerLabel : 'Belum ada customer dipilih.'"></p>
                    </div>

                    {{-- Customer baru --}}
                    <div x-show="mode === 'new'" class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label for="customer_name" class="block text-sm text-gray-600 mb-1">Nama</label>
                            <input type="text" id="customer_name" name="customer_name"
                                class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                        </div>
                        <div>
                            <label for="customer_phone" class="block text-sm text-gray-600 mb-1">Nomor HP</label>
                            <input type="text" id="customer_phone" name="customer_phone"
                                class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                        </div>
                    </div>
                </div>

                {{-- Catatan & Pembayaran --}}
                <div class="bg-white rounded-xl shadow p-6">
                    <label for="notes" class="block font-medium text-gray-700 mb-2">Catatan (opsional)</label>
                    <textarea name="notes" id="notes" rows="3"
                        class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500"></textarea>

                    <div class="mt-4 flex items-center gap-2 rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-800">
                        <span class="font-semibold">Metode Pembayaran: Cash</span>
                        <span class="text-green-600">— booking akan langsung berstatus Terkonfirmasi.</span>
                    </div>
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('owner.bookings.index') }}" class="px-6 py-3 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold">
                        Buat Booking
                    </button>
                </div>
            </form>
        @endif
    </div>
@endsection

@push ('script')
    <script>
        const fieldSelect = document.getElementById('field_id');
        const dateInput = document.getElementById('booking-date');
        const container = document.getElementById('slot-container');
        const startTimeInput = document.getElementById('selected-start-time');
        const endTimeInput = document.getElementById('selected-end-time');
        const form = document.getElementById('booking-form');
        const slotsUrlTemplate = '{{ route('owner.bookings.slots', ['field' => '__FIELD_ID__']) }}';

        async function loadSlots() {
            const fieldId = fieldSelect.value;
            const date = dateInput.value;

            startTimeInput.value = '';
            endTimeInput.value = '';

            if (!fieldId || !date) {
                return;
            }

            container.innerHTML = '<p class="text-sm text-gray-400">Memuat slot...</p>';

            const url = slotsUrlTemplate.replace('__FIELD_ID__', fieldId) + '?date=' + date;
            const response = await fetch(url);
            const data = await response.json();
            renderSlots(data.slots);
        }

        fieldSelect.addEventListener('change', loadSlots);
        dateInput.addEventListener('change', loadSlots);

        function renderSlots(slots) {
            container.innerHTML = '';

            if (slots.length === 0) {
                container.innerHTML = `
                    <div class="bg-red-100 text-red-700 rounded-lg p-4 text-sm">
                        Tidak ada slot tersedia pada tanggal ini.
                    </div>
                `;
                return;
            }

            slots.forEach((slot) => {
                if (slot.available) {
                    container.innerHTML += `
                        <button type="button" class="slot-btn w-full rounded-lg border border-green-500 p-3 mb-2 hover:bg-green-50 transition" data-start="${slot.start}" data-end="${slot.end}" onclick="selectSlot(this)">
                            ${slot.start} - ${slot.end}
                        </button>
                    `;
                } else if (slot.reason === 'past') {
                    container.innerHTML += `
                        <div class="text-center w-full rounded-lg border border-gray-300 bg-gray-100 text-gray-500 p-3 mb-2 text-sm">
                            ${slot.start} - ${slot.end} (Waktu sudah lewat)
                        </div>
                    `;
                } else {
                    container.innerHTML += `
                        <div class="text-center w-full rounded-lg border border-red-300 bg-red-50 text-red-600 p-3 mb-2 text-sm">
                            ${slot.start} - ${slot.end} (Sudah dibooking)
                        </div>
                    `;
                }
            });
        }

        function selectSlot(button) {
            document.querySelectorAll('.slot-btn').forEach((btn) => {
                btn.classList.remove('bg-green-50');
            });
            button.classList.add('bg-green-50');

            startTimeInput.value = button.getAttribute('data-start');
            endTimeInput.value = button.getAttribute('data-end');
        }

        form.addEventListener('submit', function (e) {
            if (!startTimeInput.value || !endTimeInput.value) {
                e.preventDefault();
                alert('Silakan pilih slot waktu terlebih dahulu');
            }
        });

        if (fieldSelect.value && dateInput.value) {
            loadSlots();
        }
    </script>
@endpush
