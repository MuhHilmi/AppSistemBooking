<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') — Kelola Lapang</title>

    {{-- Asumsi: Tailwind sudah dikompilasi lewat Vite di project ini --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg: #F4F5F1;
            --surface: #FFFFFF;
            --ink: #16241D;
            --ink-soft: #5B685F;
            --primary: #16A34A;
            --primary-dark: #15803D;
            --primary-tint: #DCFCE7;
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
    <div class="flex min-h-screen">

        {{-- Sidebar --}}
        <aside class="w-64 shrink-0 bg-[var(--primary-dark)] text-white flex flex-col h-screen sticky top-0 overflow-y-auto">
            <div class="flex items-center gap-2.5 px-6 h-16 border-b border-white/10">
                <div class="w-8 h-8 rounded-md bg-[var(--primary)] flex items-center justify-center font-display font-700 text-sm">L</div>
                <span class="font-display font-600 text-[15px] tracking-tight">Kelola Lapang</span>
            </div>

            <nav class="flex-1 px-3 py-5 space-y-0.5">
                <p class="px-3 text-[11px] uppercase tracking-wider text-white/40 font-600 mb-2">Utama</p>

                <a href="{{ route('owner.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[14px] font-500 {{ request()->routeIs('owner.dashboard') ? 'bg-white/10 text-white hover:text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }} transition">
                    <img src="{{ asset('img/icon/dashboard.svg') }}" alt="Icon Dashboard" class="w-4 h-4">
                    Dashboard
                </a>
                <a href="{{ route('owner.bookings.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[14px] font-500 {{ request()->routeIs('owner.bookings.*') ? 'bg-white/10 text-white hover:text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }} transition">
                    <img src="{{ asset('img/icon/booking.svg') }}" alt="Icon Booking" class="w-4 h-4">
                    Booking
                </a>
                <a href="{{ route('owner.venues.index')}}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[14px] font-500 {{ request()->routeIs('owner.venues.index') ? 'bg-white/10 text-white hover:text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }} transition">
                    <img src="{{ asset('img/icon/home.svg') }}" alt="Icon Venue" class="w-4 h-4">
                    Venue
                </a>
                <a href="{{ route('owner.fields.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[14px] font-500 {{ request()->routeIs('owner.fields.index') ? 'bg-white/10 text-white hover:text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }} transition">
                    <img src="{{ asset('img/icon/soccer-field.svg') }}" alt="Icon Lapang" class="w-4 h-4">
                    Lapangan
                </a>
                <a href="{{ route('owner.operating-schedules.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[14px] font-500 {{ request()->routeIs('owner.operating-schedules.index') ? 'bg-white/10 text-white hover:text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }} transition">
                    <img src="{{ asset('img/icon/calendar-clock.svg') }}" alt="Icon Lapang" class="w-4 h-4">
                    Jadwal Lapang
                </a>
                <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[14px] font-500 text-white/70 hover:bg-white/10 hover:text-white transition">
                    <img src="{{ asset('img/icon/user.svg') }}" alt="Icon Pelanggan" class="w-4 h-4">
                    Pelanggan
                </a>

                <p class="px-3 text-[11px] uppercase tracking-wider text-white/40 font-600 mt-6 mb-2">Laporan</p>
                <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[14px] font-500 text-white/70 hover:bg-white/10 hover:text-white transition">
                    <img src="{{ asset('img/icon/graph.svg') }}" alt="Icon Pendapatan" class="w-4 h-4">
                    Pendapatan
                </a>
                <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[14px] font-500 text-white/70 hover:bg-white/10 hover:text-white transition">
                    <img src="{{ asset('img/icon/settings.svg') }}" alt="Icon Pengaturan" class="w-4 h-4">
                    Pengaturan
                </a>
            </nav>

            <div class="px-3 py-4 border-t border-white/10">
                <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[14px] font-500 {{ request()->routeIs('profile.*') ? 'bg-white/10 text-white hover:text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }} transition">
                    <div class="w-7 h-7 rounded-full bg-[var(--primary)] flex items-center justify-center text-[12px] font-600">{{ substr(auth()->user()->name ?? 'O', 0, 1) }}</div>
                    <span class="truncate">{{ auth()->user()->name ?? 'Owner' }}</span>
                </a>
                <form action="{{ route('logout')}}" method="post">
                    @csrf
                    <button type="submit" class="mt-2 w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-[14px] font-semibold text-white/70 hover:bg-red-500 hover:text-white transition">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            id="Layer_1"
                            data-name="Layer 1"
                            viewBox="0 0 24 24"
                            width="15"
                            height="15"
                            class="rotate-180">
                                <path d="M22.763,10.232l-4.95-4.95L16.4,6.7,20.7,11H6.617v2H20.7l-4.3,4.3,1.414,1.414,4.95-4.95a2.5,2.5,0,0,0,0-3.536Z"
                                    fill="#fff"/>
                                <path d="M10.476,21a1,1,0,0,1-1,1H3a1,1,0,0,1-1-1V3A1,1,0,0,1,3,2H9.476a1,1,0,0,1,1,1V8.333h2V3a3,3,0,0,0-3-3H3A3,3,0,0,0,0,3V21a3,3,0,0,0,3,3H9.476a3,3,0,0,0,3-3V15.667h-2Z"
                                    fill="#fff"/>
                            </svg>
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        {{-- Main --}}
        <div class="flex-1 flex flex-col min-w-0">
            <header class="h-16 flex items-center justify-between px-8 bg-[var(--surface)] border-b border-[var(--line)]">
                <div>
                    <h1 class="font-display font-600 text-[17px] leading-tight">@yield('title', 'Dashboard')</h1>
                    <p class="text-[12.5px] text-[var(--ink-soft)]">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
                </div>
                <div class="flex items-center gap-3">
                    @yield('header-actions')
                </div>
            </header>

            <main class="flex-1 p-8 max-w-[1400px] w-full mx-auto">
                @yield('content')
            </main>
        </div>
    </div>
    @stack ('script')
</body>
</html>
