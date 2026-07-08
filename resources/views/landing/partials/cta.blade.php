<section id="contact" class="relative overflow-hidden py-24">
    {{-- Background --}}
    <div class="absolute inset-0 bg-gradient-to-r from-green-600 to-emerald-600"></div>

    {{-- Decorative Blur --}}
    <div class="absolute -top-24 -left-24 h-72 w-72 rounded-full bg-white/10 blur-3xl"></div>

    <div class="absolute -bottom-20 -right-20 h-80 w-80 rounded-full bg-white/10 blur-3xl"></div>

    <div class="container-custom relative z-10">
        <div
            class="mx-auto max-w-5xl rounded-[2rem] border border-white/20 bg-white/10 p-10 text-center backdrop-blur-lg lg:p-16"
        >
            {{-- Badge --}}
            <span
                class="inline-flex rounded-full bg-white/20 px-4 py-2 text-sm font-semibold text-white"
            >
                🚀 Mulai Sekarang
            </span>

            {{-- Heading --}}
            <h2 class="mt-6 text-4xl font-extrabold leading-tight text-white lg:text-5xl">
                Siap Booking Lapangan Favorit Anda?
            </h2>

            {{-- Description --}}
            <p
                class="mx-auto mt-6 max-w-3xl text-lg leading-8 text-green-100"
            >Temukan berbagai pilihan lapangan olahraga dengan jadwal yang selalu diperbarui secara real-time. Booking kapan saja, di mana saja, dengan proses yang cepat, aman, dan nyaman.</p>

            {{-- Button --}}
            <div class="mt-10 flex flex-col justify-center gap-4 sm:flex-row">
                <a
                    href="{{ route('register') }}"
                    class="rounded-xl bg-white px-8 py-4 font-semibold text-green-700 transition hover:bg-slate-100"
                >
                    Booking Sekarang
                </a>

                <a
                    href="#contact"
                    class="rounded-xl border border-white px-8 py-4 font-semibold text-white transition hover:bg-white hover:text-green-700"
                >
                    Hubungi Kami
                </a>
            </div>

            {{-- Bottom Feature --}}
            <div class="mt-12 grid gap-6 text-sm text-green-100 sm:grid-cols-3">
                <div class="flex items-center justify-center gap-2">
                    ✅

                    <span>Tanpa Biaya Tersembunyi</span>
                </div>

                <div class="flex items-center justify-center gap-2">
                    🕒

                    <span>Booking 24 Jam</span>
                </div>

                <div class="flex items-center justify-center gap-2">
                    🔒

                    <span>Pembayaran Aman</span>
                </div>
            </div>
        </div>
    </div>
</section>
