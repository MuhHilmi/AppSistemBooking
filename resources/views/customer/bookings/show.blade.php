@extends ('layouts.customer')

@section ('content')
    <div class="max-w-3xl mx-auto py-8">
        <div class="bg-white rounded-xl shadow p-6">
            <h1 class="text-3xl font-bold mb-6">Detail Booking</h1>

            @if (session('success'))
                <div
                    class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6"
                    role="alert"
                >
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div
                    class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6"
                    role="alert"
                >
                    {{ session('error') }}
                </div>
            @endif

            <div class="space-y-6">
                <div>
                    <h2 class="text-xl font-bold mb-2">{{ $booking->field->venue->name }}</h2>
                    <p class="text-lg font-semibold">Lapang: {{ $booking -> field -> name }}</p>
                    <p class="text-gray-600">Tipe Lapang: {{ ucwords($booking->field->sport_type) }}</p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <h3 class="font-bold">Informasi Booking</h3>
                        <p class="text-gray-500">Tanggal: {{ \Carbon\Carbon::parse($booking->booking_date)->format('d F Y') }}</p>
                        <p class="text-gray-500">Waktu: {{ $booking->start_time }} - {{ $booking->end_time }}</p>
                        <p class="text-gray-500">Durasi: {{ \Carbon\Carbon::parse($booking->start_time)->diffInHours(\Carbon\Carbon::parse($booking->end_time)) }} jam</p>
                    </div>

                    <div>
                        <h3 class="font-bold">Informasi Pembayaran</h3>
                        <p class="text-gray-500">Harga per Jam: Rp{{ number_format($booking->field->price_per_hour) }}</p>
                        <p class="text-gray-500">Total Bayar: Rp{{ number_format($booking->total_price) }}</p>
                        <p class="text-gray-500">
                            Status:
                            <span
                                class="px-2 py-1 rounded-full text-xs font-medium
                                {{ $booking->status == 'confirmed' ? 'bg-green-100 text-green-800' : ($booking->status == 'pending_payment' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}
                            "
                            >
                                {{ ucfirst($booking->status) }}
                            </span>
                        </p>
                    </div>
                </div>

                <div class="mt-6">
                    @if ($booking->status == 'pending_payment')
                        <a
                            {{-- href="{{ route('customer.payments.create', $booking) }}"  --}}
                            href="#"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded"
                        >
                            Lanjutkan Pembayaran
                        </a>
                    @elseif ($booking->status == 'confirmed')
                        <span class="bg-green-100 text-green-800 font-bold py-1 px-3 rounded">
                            Booking Confirmed
                        </span>
                    @else
                        <span class="bg-red-100 text-red-800 font-bold py-1 px-3 rounded">
                            Booking Dibatalkan
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
