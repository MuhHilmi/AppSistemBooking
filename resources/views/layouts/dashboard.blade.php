<!DOCTYPE html>
<html>
<head>
    <title>{{ $title ?? 'Booking Lapangan' }}</title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])
</head>

<body class="bg-gray-100">

    <div class="flex">

        <x-sidebar />

        <main class="flex-1 p-6">
            @yield('content')
        </main>

    </div>

</body>
</html>