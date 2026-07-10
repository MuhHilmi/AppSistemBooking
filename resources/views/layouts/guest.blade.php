<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        {{ config('app.name', 'Sistem Booking Lapangan') }}
    </title>

    <meta name="description" content="Sistem Booking Lapangan">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-emerald-50">
    <main class="min-h-screen">
        @isset($slot)
        {{ $slot }}
        @endisset

            @yield('content')
    </main>
</body>

</html>
