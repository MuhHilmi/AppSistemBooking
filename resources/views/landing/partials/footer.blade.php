<footer id="contact" class="bg-slate-900 text-slate-300">
    <div class="container-custom py-20">
        <div class="grid gap-12 lg:grid-cols-5">
            {{-- Brand --}}
            <div class="lg:col-span-2">
                <a href="/" class="flex items-center gap-3">
                    @if ($siteSettings->logo_url)
                        <img src="{{ $siteSettings->logo_url }}" alt="{{ $siteSettings->site_name }}" class="h-12 w-12 rounded-xl object-cover">
                    @else
                        <div
                            class="flex h-12 w-12 items-center justify-center rounded-xl bg-green-600 text-xl font-bold text-white"
                        >
                            {{ strtoupper(substr($siteSettings->site_name, 0, 1)) }}
                        </div>
                    @endif

                    <div>
                        <h2 class="text-xl font-bold text-white">{{ $siteSettings->site_name }}</h2>

                        <p class="text-sm text-slate-400">{{ $siteSettings->tagline }}</p>
                    </div>
                </a>

                <p class="mt-6 max-w-md leading-8 text-slate-400">{{ $siteSettings->footer_description }}</p>

                {{-- Social Media --}}
                <div class="mt-8 flex gap-4">
                    @if ($siteSettings->facebook_url)
                        <a
                            href="{{ $siteSettings->facebook_url }}" target="_blank" rel="noopener"
                            class="flex h-11 w-11 items-center justify-center rounded-xl bg-slate-800 transition hover:bg-green-600"
                        >
                            <img src="{{ asset('img/icon/png/facebook.png') }}" alt="Facebook Icon">
                        </a>
                    @endif

                    @if ($siteSettings->instagram_url)
                        <a
                            href="{{ $siteSettings->instagram_url }}" target="_blank" rel="noopener"
                            class="flex h-11 w-11 items-center justify-center rounded-xl bg-slate-800 transition hover:bg-green-600"
                        >
                            <img src="{{ asset('img/icon/png/instagram.png') }}" alt="Instagram Icon">
                        </a>
                    @endif

                    @if ($siteSettings->whatsapp_url)
                        <a
                            href="{{ $siteSettings->whatsapp_url }}" target="_blank" rel="noopener"
                            class="flex h-11 w-11 items-center justify-center rounded-xl bg-slate-800 transition hover:bg-green-600"
                        >
                            <img src="{{ asset('img/icon/png/whatsapp.png') }}" alt="WhatsApp Icon">
                        </a>
                    @endif

                    @if ($siteSettings->youtube_url)
                        <a
                            href="{{ $siteSettings->youtube_url }}" target="_blank" rel="noopener"
                            class="flex h-11 w-11 items-center justify-center rounded-xl bg-slate-800 transition hover:bg-green-600"
                        >
                            <img src="{{ asset('img/icon/png/youtube.png') }}" alt="YouTube Icon">
                        </a>
                    @endif
                </div>
            </div>

            {{-- Navigasi --}}
            <div>
                <h3 class="text-lg font-semibold text-white">Navigasi</h3>

                <ul class="mt-6 space-y-4">
                    <li><a href="#hero" class="hover:text-green-400">Beranda</a></li>
                    <li><a href="#fields" class="hover:text-green-400">Lapangan</a></li>
                    <li><a href="#features" class="hover:text-green-400">Keunggulan</a></li>
                    <li><a href="#testimonial" class="hover:text-green-400">Testimoni</a></li>
                </ul>
            </div>

            {{-- Layanan --}}
            <div>
                <h3 class="text-lg font-semibold text-white">Layanan</h3>

                <ul class="mt-6 space-y-4">
                    <li><a href="{{ route('customer.bookings.index') }}" class="hover:text-green-400">Booking Lapangan</a></li>
                    <li><a href="{{ route('customer.bookings.index') }}" class="hover:text-green-400">Jadwal Operasional</a></li>
                    <li><a href="#" class="hover:text-green-400">Tempat Usaha</a></li>
                    <li><a href="#" class="hover:text-green-400">Rating & Review</a></li>
                </ul>
            </div>

            {{-- Bantuan & Kontak --}}
            <div>
                <h3 class="text-lg font-semibold text-white">Bantuan</h3>

                <ul class="mt-6 space-y-4">
                    <li>FAQ</li>
                    <li>Privacy Policy</li>
                    <li>Terms & Conditions</li>
                    <li>Support Center</li>
                </ul>

                <div class="mt-8">
                    <h4 class="font-semibold text-white">Hubungi Kami</h4>

                    <div class="mt-4 space-y-3 text-sm">
                        @if ($siteSettings->support_email)
                            <p class="flex gap-2">
                                <img src="{{ asset('img/icon/png/email.png') }}" alt="Email Icon" class="h-6 w-6">
                                {{ $siteSettings->support_email }}
                            </p>
                        @endif

                        @if ($siteSettings->phone)
                            <p class="flex gap-2">
                                <img src="{{ asset('img/icon/png/smartphone.png') }}" alt="Phone Icon" class="h-6 w-6">
                                {{ $siteSettings->phone }}
                            </p>
                        @endif

                        @if ($siteSettings->address)
                            <p class="flex gap-2">
                                <img src="{{ asset('img/icon/png/placeholder.png') }}" alt="Point Icon" class="h-6 w-6">
                                {{ $siteSettings->address }}
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Divider --}}
        <div class="my-12 border-t border-slate-800"></div>

        {{-- Bottom --}}
        <div class="flex flex-col items-center justify-between gap-6 text-sm md:flex-row">
            <p class="text-slate-500">© {{ date('Y') }} {{ $siteSettings->site_name }}. Seluruh hak cipta dilindungi.</p>

            <div class="flex flex-wrap gap-6">
                <a href="#" class="hover:text-green-400"> Privacy Policy </a>

                <a href="#" class="hover:text-green-400"> Terms & Conditions </a>

                <a href="#" class="hover:text-green-400"> Cookie Policy </a>
            </div>
        </div>
    </div>
</footer>
