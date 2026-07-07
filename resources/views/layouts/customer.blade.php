<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />

    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Booking Lapangan</title>

    @vite ([
        'resources/css/app.css',
        'resources/js/app.js'
    ])
</head>

<body class="bg-gray-100">
    <div class="flex min-h-screen">
        <aside class="w-64 bg-white shadow flex flex-col justify-between">
            <div>
                <div class="p-5 border-b">
                    <h1 class="text-xl font-bold text-indigo-600">Booking Lapangan</h1>
                </div>

                <div class="p-4 space-y-2">
                    <a href="/" class="block p-3 rounded-lg hover:bg-indigo-50"> Halaman Utama </a>

                    <a href="/customer/dashboard" class="block p-3 rounded-lg hover:bg-indigo-50">
                        Dashboard
                    </a>

                    <a href="/customer/bookings" class="block p-3 rounded-lg hover:bg-indigo-50">
                        Cari Lapangan
                    </a>

                    <a href="#" class="block p-3 rounded-lg hover:bg-indigo-50"> Booking Saya </a>

                    <a href="#" class="block p-3 rounded-lg hover:bg-indigo-50">
                        Riwayat Booking
                    </a>

                    <a href="#" class="block p-3 rounded-lg hover:bg-indigo-50"> Profil </a>
                </div>
            </div>
            <div>
                <form action="{{ route('customer.logout') }}" method="POST">
                    @csrf
                    <button
                        type="submit"
                        class="px-4 py-2 mb-6 bg-red-500 text-white rounded-lg mx-4 hover:bg-red-600 hover:text-gray-200 transition"
                    >
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <main class="flex-1 p-8">
            @yield ('content')
        </main>
    </div>

    @stack ('script')
</body>
</html>
