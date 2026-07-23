<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Beranda')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg: #F4F5F1;
            --surface: #FFFFFF;
            --ink: #16241D;
            --ink-soft: #5B685F;
            --primary: #1F6F54;
            --primary-dark: #14503C;
            --primary-tint: #E7F1EC;
            --amber: #C97A1A;
            --amber-tint: #FBEEDE;
            --danger: #C1473A;
            --danger-tint: #F9EAE8;
            --line: #E3E6E0;
        }
        body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--ink); }
        .font-display { font-family: 'Space Grotesk', sans-serif; }
        .tabular { font-variant-numeric: tabular-nums; }
    </style>
</head>
<body class="min-h-screen">
    <header class="w-full flex items-center justify-center bg-[var(--surface)] border-b border-[var(--line)] sticky top-0 z-10">
        <div class="w-full max-w-screen-xl h-14 md:h-16 flex items-center justify-between px-5 md:px-8">
            <div class="flex items-center gap-8">
                <h1 class="font-display font-600 text-[16px] md:text-[17px]">@yield('title', 'Beranda')</h1>
            </div>

            <nav class="hidden md:flex items-center gap-1">
                <x-nav-link route="customer.dashboard">Beranda</x-nav-link>
                <x-nav-link route="customer.bookings.index">Cari Lapang</x-nav-link>
                <x-nav-link route="customer.bookings.history">Riwayat Booking</x-nav-link>
            </nav>

            <div class="flex items-center gap-3">
                @yield('header-actions')
                {{-- TODO: web.php belum punya route profil untuk guard customer
                    (profile.edit ada tapi di bawah middleware('auth') / guard default).
                    Ganti href ini setelah route customer.profile dibuat. --}}
                <span class="hidden md:flex w-8 h-8 rounded-full bg-[var(--primary-tint)] items-center justify-center text-[12px] font-600 text-[var(--primary-dark)]">
                    {{ strtoupper(substr(auth('customer')->user()->name ?? 'P', 0, 1)) }}
                </span>
            </div>
        </div>
    </header>
    <div class="max-w-md md:max-w-5xl mx-auto min-h-screen bg-[var(--bg)] flex flex-col">

        <main class="flex-1 px-5 md:px-8 py-5 md:py-8 pb-24 md:pb-8">
            @yield('content')
        </main>

        <nav class="md:hidden fixed bottom-0 left-0 right-0 max-w-md mx-auto bg-[var(--surface)] border-t border-[var(--line)] flex items-center justify-around h-16">
            <a href="{{ route('customer.dashboard') }}" class="flex flex-col items-center justify-center gap-1 h-14 w-14 rounded-lg {{ request()->routeIs('customer.dashboard') ? 'bg-green-50 text-[var(--primary)]' : 'text-[var(--ink-soft)]' }}">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 11l9-8 9 8"/><path d="M5 10v10h14V10"/></svg>
                <span class="text-[10.5px] font-500">Beranda</span>
            </a>
            <a href="{{ route('customer.bookings.index') }}" class="flex flex-col items-center justify-center gap-1 h-14 w-14 rounded-lg {{ request()->routeIs('customer.bookings.index') ? 'bg-green-50 text-[var(--primary)]' : 'text-[var(--ink-soft)]' }}">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/></svg>
                <span class="text-[10.5px] font-500">Cari</span>
            </a>
            <a href="{{ route('customer.bookings.history') }}" class="flex flex-col items-center justify-center gap-1 h-14 w-14 rounded-lg {{ request()->routeIs('customer.bookings.history') ? 'bg-green-50 text-[var(--primary)]' : 'text-[var(--ink-soft)]' }}">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="17" rx="2"/><path d="M8 2v4M16 2v4M3 9h18"/></svg>
                <span class="text-[10.5px] font-500">Booking</span>
            </a>
            <a href="#" class="flex flex-col items-center justify-center gap-1 h-14 w-14 rounded-lg {{ request()->routeIs('customer.dashboard') ? 'bg-green-50 text-[var(--primary)]' : 'text-[var(--ink-soft)]' }}">
                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4.4 3.6-7 8-7s8 2.6 8 7"/></svg>
                <span class="text-[10.5px] font-500">Profil</span>
            </a>
        </nav>
    </div>

    @stack ('script')
</body>
</html>
