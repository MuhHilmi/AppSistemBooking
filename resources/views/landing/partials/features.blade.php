<section id="features" class="bg-white py-24">
    <div class="container-custom">
        {{-- Heading --}}
        <div class="mx-auto max-w-3xl text-center">
            <span
                class="inline-flex rounded-full bg-green-100 px-4 py-2 text-sm font-semibold text-green-700"
            >
                Keunggulan
            </span>

            <h2 class="mt-5 text-4xl font-bold text-slate-900">
                Semua yang Anda Butuhkan untuk Booking Lapangan
            </h2>

            <p
                class="mt-5 text-lg text-slate-600"
            >Kami menghadirkan pengalaman booking lapangan yang lebih cepat, transparan, dan nyaman untuk pelanggan maupun pemilik tempat usaha.</p>
        </div>

        {{-- Main Feature --}}
        <div class="mt-20 grid items-center gap-16 lg:grid-cols-2">
            {{-- Left --}}
            <div>
                <div
                    class="overflow-hidden rounded-3xl border border-slate-200 bg-slate-100 shadow-lg"
                >
                    <img
                        src="https://placehold.co/800x550/F8FAFC/94A3B8?text=Dashboard+Preview"
                        alt="Dashboard"
                        class="w-full"
                    />
                </div>
            </div>

            {{-- Right --}}
            <div>
                <h3 class="text-3xl font-bold text-slate-900">Kenapa Memilih Platform Kami?</h3>

                <p
                    class="mt-5 text-slate-600 leading-8"
                >Platform kami dirancang untuk memberikan pengalaman booking yang sederhana namun tetap lengkap, baik bagi pelanggan maupun pengelola lapangan.</p>

                <div class="mt-8 space-y-5">
                    <div class="flex items-start gap-4">
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-full bg-green-100 text-green-600"
                        >
                            ✓
                        </div>

                        <div>
                            <h4 class="font-semibold">Jadwal Real-Time</h4>

                            <p class="text-slate-600">Ketersediaan lapangan selalu diperbarui secara langsung.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-full bg-green-100 text-green-600"
                        >
                            ✓
                        </div>

                        <div>
                            <h4 class="font-semibold">Booking Online</h4>

                            <p class="text-slate-600">Pesan lapangan kapan saja tanpa harus datang langsung.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-full bg-green-100 text-green-600"
                        >
                            ✓
                        </div>

                        <div>
                            <h4 class="font-semibold">Pembayaran Aman</h4>

                            <p class="text-slate-600">Mendukung berbagai metode pembayaran dengan proses yang aman.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-full bg-green-100 text-green-600"
                        >
                            ✓
                        </div>

                        <div>
                            <h4 class="font-semibold">Riwayat Booking</h4>

                            <p class="text-slate-600">Semua transaksi tersimpan sehingga mudah dilihat kembali.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Feature Grid --}}
        <div class="mt-24 grid gap-8 md:grid-cols-2 xl:grid-cols-3">
            @php
                $features = [
                    ['icon' => '📅', 'title' => 'Jadwal Real-Time', 'desc' => 'Lihat ketersediaan lapangan secara langsung.'],
                    ['icon' => '💳', 'title' => 'Pembayaran Mudah', 'desc' => 'Berbagai metode pembayaran tersedia.'],
                    ['icon' => '📍', 'title' => 'Lokasi Lengkap', 'desc' => 'Cari lapangan berdasarkan lokasi terdekat.'],
                    ['icon' => '🔔', 'title' => 'Notifikasi', 'desc' => 'Informasi booking dikirim secara otomatis.'],
                    ['icon' => '⭐', 'title' => 'Rating & Ulasan', 'desc' => 'Lihat pengalaman pengguna lain sebelum memesan.'],
                    ['icon' => '📱', 'title' => 'Responsive', 'desc' => 'Nyaman digunakan di desktop maupun smartphone.'],
                ];
            @endphp

            @foreach ($features as $feature)
                <div
                    class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm transition duration-300 hover:-translate-y-2 hover:shadow-xl"
                >
                    <div
                        class="flex h-16 w-16 items-center justify-center rounded-2xl bg-green-100 text-3xl"
                    >
                        {{ $feature['icon'] }}
                    </div>

                    <h3 class="mt-6 text-xl font-bold text-slate-900">{{ $feature['title'] }}</h3>

                    <p class="mt-3 leading-7 text-slate-600">{{ $feature['desc'] }}</p>
                </div>

            @endforeach
        </div>
    </div>
</section>
