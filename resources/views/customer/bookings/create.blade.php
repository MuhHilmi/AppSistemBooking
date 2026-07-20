@extends ('layouts.customer')

@section ('content')
    <div class="max-w-3xl mx-auto py-8">
        <h1 class="text-3xl font-bold mb-8">Booking Lapangan</h1>
        <div class="bg-white rounded-xl shadow p-6 mb-8">
            <h2 class="text-xl font-bold">{{ $field->venue->name }}</h2>
            <p class="text-gray-500">{{ $field->sport_type }}</p>
            <p class="mt-3 text-indigo-600 text-xl font-semibold">Rp{{ number_format($field->price_per_hour) }}/Jam</p>
        </div>
        <div class="bg-white rounded-xl shadow p-6 mt-8">
            <h3 class="font-bold mb-4">Jam Operasional</h3>
            @php
                $days = [
                    1 => 'Senin',
                    2 => 'Selasa',
                    3 => 'Rabu',
                    4 => 'Kamis',
                    5 => 'Jumat',
                    6 => 'Sabtu',
                    7 => 'Minggu',
                ];
            @endphp
            @foreach ($field->operatingSchedules as $schedule)
                <div class="flex justify-between py-2 border-b">
                    <span> {{ $days[$schedule->day_of_week] }} </span>
                    @if ($schedule->is_open)
                        <span>
                            {{ substr($schedule->open_time,0,5) }} - {{ substr($schedule->close_time,0,5) }}
                        </span>
                    @else
                        <span class="text-red-600"> Tutup </span>
                    @endif
                </div>
            @endforeach
        </div>
        <form
            action="{{ route('customer.bookings.store', $field) }}"
            method="POST"
            id="booking-form"
        >
            @csrf
            <input type="hidden" name="field_id" value="{{ $field->id }}" />
            <input type="hidden" id="selected-start-time" name="start_time" />
            <input type="hidden" id="selected-end-time" name="end_time" />
            {{-- <input type="hidden" name="booking_date" id="booking_date_hidden"> --}}
            <div class="mt-6">
                <label for="notes">Catatan</label>
                <textarea name="notes" id="notes" rows="4" class="w-full rounded-lg border"></textarea>
            </div>
            <div class="mt-4">
                <label class="block font-medium mb-2"> Pilih Tanggal </label>
                <input
                    type="date"
                    id="booking-date"
                    name="booking_date"
                    min="{{ now()->toDateString() }}"
                    class="w-full rounded-lg border-gray-300"
                />
                <div id="slot-container" class="mt-8"></div>
            </div>
            {{-- <div>
                <input
                    type="hidden"
                    name="start_time"
                    id="start-time">

                <input
                    type="hidden"
                    name="end_time"
                    id="end-time">
            </div> --}}
            <div class="flex justify-end mt-8">
                <button type="submit" class="px-6 py-3 bg-indigo-600 text-white rounded-lg">
                    Booking
                </button>
            </div>
        </form>
    </div>
@endsection

@push ('script')
    <script>
        const dateInput = document.getElementById('booking-date');
        const container = document.getElementById('slot-container');
        const startTimeInput = document.getElementById('selected-start-time');
        const endTimeInput = document.getElementById('selected-end-time');
        const form = document.getElementById('booking-form');

        dateInput.addEventListener('change', async function () {
            const date = this.value;
            if (!date) {
                return;
            }

            const response = await fetch('{{ route('customer.bookings.slots', $field) }}' + '?date=' + date);
            const data = await response.json();
            renderSlots(data.slots);
        });

        function renderSlots(slots) {
            container.innerHTML = '';
            if (slots.length === 0) {
                container.innerHTML = `
                    <div class="bg-red-100 text-red-700 rounded-lg p-4">
                        Tidak ada slot tersedia.
                    </div>
                `;

                return;
            }

            slots.forEach((slot) => {
                if (slot.available) {
                    container.innerHTML += `
                        <button
                            type="button"
                            class="slot-btn w-full rounded-lg border border-green-500 p-3 mb-2 hover:bg-indigo-50 hover:text-black transition"
                            data-start="${slot.start}"
                            data-end="${slot.end}"
                            onclick="selectSlot(this)"
                        >
                            ${slot.start} - ${slot.end}
                        </button>
                    `;
                } else {
                    container.innerHTML += `
                    <div
                        class="text-center w-full rounded-lg border border-red-300 bg-red-50 text-red-600 p-3 mb-2">
                        ${slot.start} - ${slot.end}<br>
                        (Sudah dibooking)
                    </div>
                    `;
                }
            });
        }

        // document.addEventListener('click', function(e){
        //     if(!e.target.classList.contains('slot-btn')){
        //         return;
        //     }
        //     document.querySelectorAll('.slot-btn').forEach(button => {
        //         button.classList.remove('bg-indigo-600', 'text-white');
        //     });
        //     e.target.classList.add('bg-indigo-600', 'text-white');
        //     document.getElementById('start-time').value = e.target.dataset.start;
        //     document.getElementById('end-time').value = e.target.dataset.end;
        // });

        function selectSlot(button) {
            // Remove active class from all buttons
            document.querySelectorAll('.slot-btn').forEach((btn) => {
                btn.classList.remove('bg-green-50', 'border-green-500');
                btn.classList.add('border-green-500');
            });

            // Add active class to selected button
            button.classList.add('bg-green-50', 'border-green-500');
            button.classList.remove('border-green-500');

            // Set the hidden inputs
            startTimeInput.value = button.getAttribute('data-start');
            endTimeInput.value = button.getAttribute('data-end');
        }

        // Form validation
        form.addEventListener('submit', function (e) {
            if (!startTimeInput.value || !endTimeInput.value) {
                e.preventDefault();
                alert('Silakan pilih slot waktu terlebih dahulu');
            }
        });
    </script>
@endpush
