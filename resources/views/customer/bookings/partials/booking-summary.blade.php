<div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
    {{-- Header --}}
    <div class="border-b border-gray-200 px-6 py-4">
        <h2 class="text-lg font-semibold text-gray-800">Ringkasan Booking</h2>
    </div>
    {{-- Content --}}
    <div class="p-6">
        <dl class="divide-y divide-gray-100">
            <div class="flex items-start justify-between py-3">
                <dt class="w-1/3 text-sm font-medium text-gray-500">
                    Kode Booking
                </dt>
                <dd class="w-2/3 text-right text-sm font-semibold text-gray-900">
                    {{ $booking->booking_code }}
                </dd>
            </div>
            <div class="flex items-start justify-between py-3">
                <dt class="w-1/3 text-sm font-medium text-gray-500">
                    Venue
                </dt>
                <dd class="w-2/3 text-right text-sm font-semibold text-gray-900">
                    {{ $booking->field->venue->name }}
                </dd>
            </div>
            <div class="flex items-start justify-between py-3">
                <dt class="w-1/3 text-sm font-medium text-gray-500">
                    Lapangan
                </dt>
                <dd class="w-2/3 text-right text-sm font-semibold text-gray-900">
                    {{ $booking->field->name }}
                </dd>
            </div>
            <div class="flex items-start justify-between py-3">
                <dt class="w-1/3 text-sm font-medium text-gray-500">
                    Tanggal
                </dt>
                <dd class="w-2/3 text-right text-sm font-semibold text-gray-900">
                    {{ $booking->booking_date->translatedFormat('d F Y') }}
                </dd>
            </div>
            <div class="flex items-start justify-between py-3">
                <dt class="w-1/3 text-sm font-medium text-gray-500">
                    Jam
                </dt>
                <dd class="w-2/3 text-right text-sm font-semibold text-gray-900">
                    {{ $booking->start_time }} - {{ $booking->end_time }}
                </dd>
            </div>
            <div class="flex items-start justify-between py-3">
                <dt class="w-1/3 text-sm font-medium text-gray-500">
                    Durasi
                </dt>
                <dd class="w-2/3 text-right text-sm font-semibold text-gray-900">
                    {{ $booking->duration }} Jam
                </dd>
            </div>
            <div class="flex items-start justify-between py-3">
                <dt class="w-1/3 text-sm font-medium text-gray-500">
                    Total
                </dt>
                <dd class="w-2/3 text-right text-sm font-semibold text-gray-900">
                    Rp.{{ number_format($booking->total_price, 0, ',', '.') }}
                </dd>
            </div>
        </dl>
    </div>
</div>