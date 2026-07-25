<section id="fields" class="bg-slate-50 py-24">
    <div class="container-custom">
        {{-- Heading --}}
        <div class="mx-auto max-w-3xl text-center">
            <span class="inline-flex rounded-full bg-green-100 px-4 py-2 text-sm font-semibold text-green-700">
                Lapangan Populer
            </span>
            <h2 class="mt-5 text-4xl font-bold text-slate-900">Temukan Lapangan Favorit Anda</h2>
            <p class="mt-5 text-lg text-slate-600">
                Pilih berbagai jenis lapangan olahraga dengan jadwal yang selalu diperbarui secara real-time.
            </p>
        </div>

        {{-- Cards + Detail Popup (max 6 fields, passed in from LandingController@index) --}}
        <div class="mt-20">
            <x-fields.grid :fields="$fields" />
        </div>

        {{-- CTA --}}
        <div class="mt-16 text-center">
            <a href="{{ route('fields.index') }}" class="inline-flex rounded-xl border border-green-600 px-8 py-4 font-semibold text-green-600 transition hover:bg-green-600 hover:text-white">
                Lihat Semua Lapangan
            </a>
        </div>
    </div>
</section>
