@extends('layouts.guest')

@section('content')

<div class="min-h-screen flex items-center justify-center bg-gray-50">

    <div class="w-full max-w-md bg-white rounded-2xl shadow-lg p-8">

        <div class="text-center mb-8">

            <h1 class="text-3xl font-bold text-indigo-600">
                Booking Lapangan
            </h1>

            <p class="text-gray-500">
                Masuk ke akun pelanggan
            </p>

        </div>

        <form method="POST"
              action="{{ route('customer.login') }}"
              class="space-y-4">

            @csrf

            <div>

                <label class="block text-sm mb-1">
                    Nomor HP
                </label>

                <input
                    type="text"
                    name="phone"
                    class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500"
                    placeholder="08xxxxxxxxxx">

            </div>

            <div>

                <label class="block text-sm mb-1">
                    Password
                </label>

                <input
                    type="password"
                    name="password"
                    class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500">

            </div>

            <button
                class="w-full bg-indigo-600 text-white py-3 rounded-lg hover:bg-indigo-700">

                Login

            </button>

        </form>

        <p class="text-center mt-5 text-sm">

            Belum punya akun?

            <a href="/customer/register"
               class="text-indigo-600 font-semibold">

                Daftar

            </a>

        </p>

    </div>

</div>

@endsection