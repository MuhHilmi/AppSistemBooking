<nav
    x-data="{ open: false, scrolled: false }"
    x-init="
        window.addEventListener('scroll', () => {
            scrolled = window.scrollY > 20;
        })
    "
    :class="scrolled ? 'bg-white/90 backdrop-blur-lg shadow-lg' : 'bg-transparent'"
    class="fixed top-0 left-0 right-0 z-50 transition-all duration-300"
>
    <div class="container-custom">
        <div class="flex items-center justify-between h-20">
            {{-- Logo --}}
            <a href="/" class="flex items-center gap-3">
                <div
                    class="flex h-11 w-11 items-center justify-center rounded-xl bg-green-600 text-white font-bold text-xl shadow-md"
                >
                    B
                </div>

                <div>
                    <h1 class="font-bold text-lg text-slate-800">Booking Lapangan</h1>

                    <p class="text-xs text-slate-500">Sport Reservation</p>
                </div>
            </a>

            {{-- Desktop Menu --}}
            <div class="hidden lg:flex items-center gap-10">
                <a href="#hero" class="hover:text-green-600 transition"> Beranda </a>

                <a href="#how-it-works" class="hover:text-green-600 transition"> Cara Booking </a>

                <a href="#features" class="hover:text-green-600 transition"> Fitur </a>

                <a href="#fields" class="hover:text-green-600 transition"> Lapangan </a>

                <a href="#testimonial" class="hover:text-green-600 transition"> Testimoni </a>

                <a href="#contact" class="hover:text-green-600 transition"> Kontak </a>
            </div>

            {{-- Desktop Button --}}
            <div class="hidden lg:flex items-center gap-3">
                @if (Route::has('customer.login'))
                    @auth ('customer')
                        <a
                            href="{{ route('customer.bookings.dashboardView') }}"
                            class="rounded-xl px-6 py-3 border border-slate-300 hover:border-green-500 hover:text-green-600 transition"
                        >
                            Dashboard
                        </a>
                    @else
                        <a
                            href="{{ route('customer.login') }}"
                            class="rounded-xl px-6 py-3 border border-slate-300 hover:border-green-500 hover:text-green-600 transition"
                        >
                            Login
                        </a>
                    @endauth
                @endif

                <a
                    href="{{ route('customer.register') }}"
                    class="rounded-xl bg-green-600 text-white px-6 py-3 font-medium shadow-lg hover:bg-green-700 transition"
                >
                    Booking Sekarang
                </a>
            </div>

            {{-- Mobile Button --}}
            <button @click="open = !open" class="lg:hidden">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="w-8 h-8"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16"
                    />
                </svg>
            </button>
        </div>
    </div>

    {{-- Mobile Menu --}}
    <div x-show="open" x-transition class="lg:hidden bg-white border-t">
        <div class="container-custom py-5 space-y-5">
            <a href="#hero" class="block"> Beranda </a>

            <a href="#features" class="block"> Fitur </a>

            <a href="#fields" class="block"> Lapangan </a>

            <a href="#testimonial" class="block"> Testimoni </a>

            <a href="#contact" class="block"> Kontak </a>

            <hr />

            <a href="{{ route('login') }}" class="block text-center rounded-lg border py-3">
                Login
            </a>

            <a href="{{ route('register') }}" class="block text-center rounded-lg bg-green-600 py-3 text-white">
                Booking Sekarang
            </a>
        </div>
    </div>
</nav>
