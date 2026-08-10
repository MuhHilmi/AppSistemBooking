@extends('layouts.dashboard')

@section('title', 'Pengaturan Landing Page')

@section('content')
    <div class="max-w-4xl mx-auto px-4 py-6">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Pengaturan Landing Page</h1>
            <p class="text-gray-500 mt-1">Atur konten yang tampil di halaman depan (landing page) situs Anda.</p>
        </div>

        @if (session('success'))
            <div class="mb-6 bg-green-100 border border-green-300 text-green-700 p-4 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 bg-red-100 border border-red-300 text-red-700 p-4 rounded-lg">
                <p class="font-semibold mb-1">Periksa kembali isian Anda:</p>
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('owner.settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Identitas --}}
            <div class="bg-white rounded-xl shadow p-6">
                <h2 class="font-semibold text-gray-800 mb-4">Identitas Situs</h2>
                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label for="site_name" class="block text-sm text-gray-600 mb-1">
                            Nama Situs / Judul
                        </label>
                        <input type="text" id="site_name" name="site_name" value="{{ old('site_name', $setting->site_name) }}" required class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                    </div>
                    <div>
                        <label for="tagline" class="block text-sm text-gray-600 mb-1">
                            Tagline
                        </label>
                        <input type="text" id="tagline" name="tagline" value="{{ old('tagline', $setting->tagline) }}" placeholder="mis. Sport Reservation" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                    </div>
                </div>
                <div class="mt-4">
                    <label class="block text-sm text-gray-600 mb-2">Logo</label>
                    <div class="flex items-center gap-4">
                        @if ($setting->logo_url)
                            <img src="{{ $setting->logo_url }}" alt="Logo" class="h-16 w-16 rounded-xl object-cover border border-gray-200">
                        @else
                            <div class="h-16 w-16 rounded-xl bg-green-600 text-white flex items-center justify-center font-bold text-2xl">
                                {{ strtoupper(substr($setting->site_name, 0, 1)) }}
                            </div>
                        @endif
                        <input type="file" name="logo" accept="image/png,image/jpeg,image/svg+xml" class="text-sm">
                    </div>
                    <p class="text-xs text-gray-400 mt-1">
                        PNG/JPG/SVG, maksimal 2MB. Kosongkan kalau tidak ingin mengganti.
                    </p>
                </div>
            </div>

            {{-- Hero Section --}}
            <div class="bg-white rounded-xl shadow p-6">
                <h2 class="font-semibold text-gray-800 mb-4">Hero Section (Bagian Atas Landing Page)</h2>
                <div class="space-y-4">
                    <div>
                        <label for="hero_badge_text" class="block text-sm text-gray-600 mb-1">Teks Badge Kecil</label>
                        <input type="text" id="hero_badge_text" name="hero_badge_text" value="{{ old('hero_badge_text', $setting->hero_badge_text) }}" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                    </div>
                    <div>
                        <label for="hero_headline" class="block text-sm text-gray-600 mb-1">Judul Utama</label>
                        <input type="text" id="hero_headline" name="hero_headline" value="{{ old('hero_headline', $setting->hero_headline) }}" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                    </div>
                    <div>
                        <label for="hero_subheadline" class="block text-sm text-gray-600 mb-1">Sinopsis / Penjelasan Singkat</label>
                        <textarea name="hero_subheadline" id="hero_subheadline" rows="3" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">{{ old('hero_subheadline', $setting->hero_subheadline) }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600">Gambar Hero (kanan halaman)</label>
                        <div class="flex items-center gap-4">
                            @if ($setting->hero_image_url)
                                <img src="{{ $setting->hero_image_url }}" alt="Hero" class="h-20 w-32 rounded-lg object-cover border border-gray-200">
                            @endif
                            <input type="file" name="hero_image" accept="image/png,image/jpeg" class="text-sm">
                        </div>
                        <p class="text-sm text-gray-400 mt-1">PNG / JPG, maksimal 4MB.</p>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-2">Statistik (3 angka yang ditampilkan)</label>
                        <div class="grid grid-cols-3 gap-3">
                            <div>
                                <input type="text" name="stat_1_value" value="{{ old('stat_1_value', $setting->stat_1_value) }}" placeholder="300+" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500 text-sm mb-1.5">
                                <input type="text" name="stat_1_label" value="{{ old('stat_1_label', $setting->stat_1_label) }}" placeholder="Lapangan" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500 text-sm">
                            </div>
                            <div>
                                <input type="text" name="stat_2_value" value="{{ old('stat_2_value', $setting->stat_2_value) }}" placeholder="4.500+" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500 text-sm mb-1.5">
                                <input type="text" name="stat_2_label" value="{{ old('stat_2_label', $setting->stat_2_label) }}" placeholder="Booking" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500 text-sm">
                            </div>
                            <div>
                                <input type="text" name="stat_3_value" value="{{ old('stat_3_value', $setting->stat_3_value) }}" placeholder="98%" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500 text-sm mb-1.5">
                                <input type="text" name="stat_3_label" value="{{ old('stat_3_label', $setting->stat_3_label) }}" placeholder="Kepuasan" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500 text-sm">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Kontak & Footer --}}
            <div class="bg-white rounded-xl shadow p-6">
                <h2 class="font-semibold text-gray-800 mb-4">Kontak &amp; Footer</h2>
                <div class="space-y-4">
                    <div>
                        <label for="footer_description" class="block text-sm text-gray-600 mb-1">Deskripsi di Footer</label>
                        <textarea name="footer_description" id="footer_description" rows="3" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">{{ old('footer_description', $setting->footer_description) }}</textarea>
                    </div>
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label for="support_email" class="block text-sm text-gray-600 mb-1">Email Support</label>
                            <input type="email" id="support_email" name="support_email" value="{{ old('support_email', $setting->support_email) }}" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                        </div>
                        <div>
                            <label for="phone" class="block text-sm text-gray-600 mb-1">Nomor Telepon</label>
                            <input type="text" name="phone" value="{{ old('phone', $setting->phone) }}" id="phone" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                        </div>
                    </div>

                    <div>
                        <label for="address" class="block text-sm text-gray-600 mb-1">Alamat</label>
                        <textarea name="address" id="address" rows="2" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">{{ old('address', $setting->address) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm text-gray-600 mb-2">Media Sosial (opsional, kosongkan kalau tidak dipakai)</label>
                        <div class="grid sm:grid-cols-2 gap-3">
                            <input type="url" name="facebook_url" value="{{ old('facebook_url', $setting->facebook_url) }}" placeholder="Link Facebook" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500 text-sm">
                            <input type="url" name="instagram_url" value="{{ old('instagram_url', $setting->instagram_url) }}" placeholder="Link Instagram" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500 text-sm">
                            <input type="url" name="whatsapp_url" value="{{ old('whatsapp_url', $setting->whatsapp_url) }}" placeholder="Link WhatsApp" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500 text-sm">
                            <input type="url" name="youtube_url" value="{{ old('youtube_url', $setting->youtube_url) }}" placeholder="Link YouTube" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500 text-sm">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Struk / Bukti Booking --}}
            <div class="bg-white rounded-xl shadow p-6">
                <h2 class="font-semibold text-gray-800 mb-1">Struk / Bukti Booking</h2>
                <p class="text-sm text-gray-500 mb-4">Teks ini akan tampil di struk yang bisa diunduh customer setelah booking dikonfirmasi.</p>

                <div class="space-y-4">
                    <div>
                        <label for="receipt_header" class="block text-sm text-gray-600 mb-1">Teks Header Struk</label>
                        <textarea id="receipt_header" name="receipt_header" rows="2" placeholder="mis. Terima kasih telah melakukan booking."
                            class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">{{ old('receipt_header', $setting->receipt_header) }}</textarea>
                    </div>
                    <div>
                        <label for="receipt_footer" class="block text-sm text-gray-600 mb-1">Teks Footer Struk</label>
                        <textarea id="receipt_footer" name="receipt_footer" rows="3" placeholder="mis. Syarat &amp; ketentuan, catatan tambahan, dsb."
                            class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">{{ old('receipt_footer', $setting->receipt_footer) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ url('/') }}" target="_blank" rel="noopener noreferrer" class="px-6 py-3 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50">Lihat Landing Page</a>
                <button type="submit" class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold">Simpan Perubahan</button>
            </div>
        </form>
    </div>
@endsection
