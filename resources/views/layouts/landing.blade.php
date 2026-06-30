<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- SEO --}}
    <title>@yield('title', 'Booking Lapangan')</title>

    <meta name="description"
        content="@yield('description', 'Platform booking lapangan olahraga online yang mudah, cepat, dan terpercaya.')">

    <meta name="keywords"
        content="booking lapangan, futsal, badminton, basket, sewa lapangan, booking online">

    <meta name="author" content="Booking Lapangan">

    {{-- Theme --}}
    <meta name="theme-color" content="#16a34a">

    {{-- Font --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

    @stack('styles')
</head>

<body
    class="font-[Inter] bg-white text-slate-800 antialiased selection:bg-green-600 selection:text-white">

    {{-- Background Decoration --}}
    <div class="fixed inset-0 -z-50 overflow-hidden">

        <div
            class="absolute -top-60 left-0 h-[500px] w-[500px] rounded-full bg-green-400/20 blur-3xl">
        </div>

        <div
            class="absolute bottom-0 right-0 h-[500px] w-[500px] rounded-full bg-emerald-300/20 blur-3xl">
        </div>

    </div>

    {{-- Main Content --}}
    <main>

        @yield('content')

    </main>

    {{-- Scroll To Top --}}
    <button
        id="scrollTop"
        class="fixed bottom-8 right-8 hidden h-12 w-12 rounded-full bg-green-600 text-white shadow-lg transition hover:bg-green-700">

        ↑

    </button>

    @stack('scripts')

</body>

</html>

