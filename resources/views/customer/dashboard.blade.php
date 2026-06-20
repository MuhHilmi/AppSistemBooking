@extends('layouts.customer')

@section('content')

<div class="space-y-6">

    <div>

        <h1 class="text-3xl font-bold">
            Selamat Datang 👋
        </h1>

        <p class="text-gray-500">
            Cari dan booking lapangan favoritmu
        </p>

    </div>

    <div class="grid md:grid-cols-3 gap-6">

        <div class="bg-white rounded-xl shadow p-5">

            <p class="text-gray-500">
                Booking Aktif
            </p>

            <h2 class="text-3xl font-bold mt-2">
                2
            </h2>

        </div>

        <div class="bg-white rounded-xl shadow p-5">

            <p class="text-gray-500">
                Total Booking
            </p>

            <h2 class="text-3xl font-bold mt-2">
                15
            </h2>

        </div>

        <div class="bg-white rounded-xl shadow p-5">

            <p class="text-gray-500">
                Menunggu Pembayaran
            </p>

            <h2 class="text-3xl font-bold mt-2">
                1
            </h2>

        </div>

    </div>

</div>

@endsection