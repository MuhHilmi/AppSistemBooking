<section id="hero" class="relative overflow-hidden">
    {{-- Background --}}
    <div
        class="absolute inset-0 bg-gradient-to-br from-green-50 via-white to-emerald-100 -z-20"
    ></div>

    {{-- Decorative Blur --}}
    <div
        class="absolute -top-32 -left-20 h-96 w-96 rounded-full bg-green-300/20 blur-3xl -z-10"
    ></div>

    <div
        class="absolute bottom-0 right-0 h-96 w-96 rounded-full bg-emerald-400/20 blur-3xl -z-10"
    ></div>

    <div class="container-custom">
        <div class="grid items-center gap-16 lg:grid-cols-2 min-h-[90vh]">
            {{-- LEFT CONTENT --}}
            <div>
                <span
                    class="inline-flex items-center rounded-full bg-green-100 px-4 py-2 text-sm font-semibold text-green-700"
                >
                    🚀 Booking Lapangan Kini Lebih Mudah
                </span>

                <h1 class="mt-6 text-5xl font-black leading-tight text-slate-900 lg:text-7xl">
                    Booking
                    <span class="text-green-600"> Lapangan </span>

                    Tanpa
                    <br />

                    Ribet.
                </h1>

                <p
                    class="mt-8 max-w-xl text-lg leading-8 text-slate-600"
                >Temukan lapangan futsal, badminton, basket, tenis, dan berbagai olahraga lainnya. Pilih jadwal yang tersedia, lakukan pembayaran, dan nikmati pengalaman booking yang cepat, aman, dan praktis.</p>

                <div class="mt-10 flex flex-wrap gap-4">
                    <a
                        href="#fields"
                        class="rounded-xl bg-green-600 px-8 py-4 font-semibold text-white shadow-xl transition hover:-translate-y-1 hover:bg-green-700"
                    >
                        Booking Sekarang
                    </a>

                    <a
                        href="#features"
                        class="rounded-xl border border-slate-300 bg-white px-8 py-4 font-semibold transition hover:border-green-600 hover:text-green-600"
                    >
                        Pelajari Lebih Lanjut
                    </a>
                </div>

                {{-- Statistics --}}
                <div class="mt-16 grid grid-cols-3 gap-8">
                    <div>
                        <h3 class="text-3xl font-bold text-green-600">300+</h3>

                        <p class="mt-2 text-sm text-slate-500">Lapangan</p>
                    </div>

                    <div>
                        <h3 class="text-3xl font-bold text-green-600">4.500+</h3>

                        <p class="mt-2 text-sm text-slate-500">Booking</p>
                    </div>

                    <div>
                        <h3 class="text-3xl font-bold text-green-600">98%</h3>

                        <p class="mt-2 text-sm text-slate-500">Kepuasan</p>
                    </div>
                </div>
            </div>

            {{-- RIGHT CONTENT --}}
            <div class="relative">
                {{-- Main Mockup --}}
                <div class="overflow-hidden rounded-3xl border border-white/60 bg-white shadow-2xl">
                    <img
                        src="{{ asset('/img/field/FutsalArenaorStadium.jpeg') }}"
                        alt="Dashboard"
                        class="w-full"
                    />
                </div>

                {{-- Floating Card 1 --}}
                <div class="absolute -left-8 top-12 rounded-2xl bg-white p-5 shadow-xl">
                    <div class="flex items-center gap-3">
                        <div
                            class="flex h-12 w-12 items-center justify-center rounded-full bg-green-100 text-2xl"
                        >
                            ⚽
                        </div>

                        <div>
                            <p class="font-bold">Booking Berhasil</p>

                            <p class="text-sm text-slate-500">Lapangan Futsal A</p>
                        </div>
                    </div>
                </div>

                {{-- Floating Card 2 --}}
                <div class="absolute -right-6 bottom-12 rounded-2xl bg-white p-5 shadow-xl">
                    <div class="flex items-center gap-3">
                        <div
                            class="flex h-12 w-12 items-center justify-center rounded-full bg-yellow-100"
                        >
                            ⭐
                        </div>

                        <div>
                            <h3 class="font-bold">Rating 4.9</h3>

                            <p class="text-sm text-slate-500">12.000+ Review</p>
                        </div>
                    </div>
                </div>

                {{-- Floating Card 3 --}}
                <div
                    class="absolute left-20 -bottom-8 rounded-2xl bg-green-600 px-6 py-4 text-white shadow-xl"
                >
                    <p class="text-sm">✔ Jadwal Real-Time</p>
                </div>
            </div>
        </div>
    </div>
</section>
