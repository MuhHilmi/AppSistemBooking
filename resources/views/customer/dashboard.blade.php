@extends ('layouts.customer')

@section ('content')
    <div class="space-y-6">
        <div>
            <h1 class="text-3xl font-bold">Selamat Datang, {{ Auth::guard('customer') -> user() -> name }} 👋</h1>
            <p class="text-gray-500">Cari dan booking lapangan favoritmu</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-xl shadow p-6">
                <p class="text-gray-500">Booking Aktif</p>
                <h2 class="text-4xl font-bold">
                    {{ $activeBookings }}
                </h2>
            </div>
            <div class="bg-white rounded-xl shadow p-6">
                <p class="text-gray-500">
                    Pembayaran Tertunda
                </p>
                <h2 class="text-4xl font-bold text-yellow-500">
                    {{ $pendingBookings }}
                </h2>
            </div>
            <div class="bg-white rounded-xl shadow p-6">
                <p class="text-gray-500">
                    Konfirmasi
                </p>
                <h2 class="text-4xl font-bold text-green-600">
                    {{ $confirmedBookings }}
                </h2>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow mt-8">
        <div class="p-6 border-b">
            <h2 class="text-xl font-semibold">
                Booking Terbaru
            </h2>
        </div>
        <div class="divide-y">
            @foreach($latestBookings as $booking)
                <div class="p-5 flex justify-between items-center">
                    <div>
                        <h3 class="font-semibold">
                            {{ $booking->booking_code }}
                        </h3>
                        <p>
                            {{ $booking->field->venue->name }}
                        </p>
                        <p>
                            {{ $booking->field->name }}
                        </p>
                    </div>
                    <div>
                        <span>
                            {{ $booking->booking_date }}
                        </span>
                    </div>
                    <div>
                        {{ ucfirst(str_replace('_',' ',$booking->status)) }}
                    </div>
                    <a href="{{ route('customer.bookings.show',$booking) }}" class="text-indigo-600 bg-indigo-50 px-4 py-2 rounded-md hover:bg-indigo-100 transition">
                        Detail
                    </a>
                </div>
            @endforeach
        </div>
    </div>

@endsection
