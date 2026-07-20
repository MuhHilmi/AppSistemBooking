<x-guest-layout>
<div x-data="{ isLogin: true }" class="auth-background min-h-screen flex items-center">
    <div class="auth-container w-full">
        <div class="auth-card soft-shadow overflow-hidden">
            <div class="relative min-h-[720px] overflow-hidden">
                {{-- IMAGE PANEL --}}
                <section id="imagePanel" class="hidden lg:block absolute top-0 bottom-0 w-1/2 transition-all duration-700 ease-in-out" :class="isLogin ? 'left-0' : 'left-1/2'">
                    {{-- nanti di Part 3 akan dibuat animasi --}}
                    <div class="absolute inset-5 rounded-[30px] overflow-hidden">
                        <img src="https://picsum.photos/900/1200" alt="Booking Lapangan" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-br from-emerald-900/60 via-emerald-800/20 to-black/40">
                        </div>
                        <div class="absolute inset-0 flex flex-col justify-between p-10">
                            <div>
                                <span class="uppercase tracking-[4px] text-xs text-white">
                                    Sistem Booking Lapangan
                                </span>
                            </div>
                            <div>
                                <h1 class="text-white text-6xl font-bold leading-tight">
                                    Booking
                                    <br>
                                    Lapangan
                                    <br>
                                    Jadi Lebih Mudah
                                </h1>
                                <p class="text-emerald-100 mt-6 max-w-sm">
                                    Pesan lapangan olahraga kapan saja, dimana saja dengan proses yang cepat dan mudah.
                                </p>
                            </div>
                        </div>
                    </div>
                </section>
                {{-- FORM PANEL --}}
                <div class="flex w-full">
                    {{-- Login Form --}}
                    <section id="formPanel" class="absolute inset-y-0 left-0 w-full lg:w-1/2 flex items-center justify-center p-10 lg:p-16 transition-all duration-700 z-20" :class="isLogin ? 'translate-x-0 opacity-100 lg:translate-x-full' : '-translate-x-full opacity-0 pointer-events-none lg:-translate-x-full lg:opacity-0'">
                        <div class="w-full max-w-md">
                            {{-- Logo --}}
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-emerald-600 flex items-center justify-center text-white font-bold">
                                    SB
                                </div>
                                <div>
                                    <h2 class="font-semibold">
                                        Sistem Booking
                                    </h2>
                                    <small class="text-gray-500">
                                        Lapangan
                                    </small>
                                </div>
                            </div>
                            <div class="mt-14">
                                <h1 class="section-title">
                                    Welcome Back
                                </h1>
                                <p class="section-subtitle">
                                    Silakan login menggunakan akun Anda.
                                </p>
                            </div>
                            <form action="{{ route('customer.login') }}" method="POST" class="space-y-5 mt-10">
                                @csrf
                                <div>
                                    <label class="block mb-2 text-sm font-medium">
                                        Nomor HandPhone
                                    </label>
                                    <input type="text" name="phone" value="{{ old('phone') }}" class="input" placeholder="08xxxxxxxxxx">
                                    @error('phone')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block mb-2 text-sm font-medium">
                                        Password
                                    </label>
                                    <input type="password" name="password" class="input" placeholder="Masukkan password">
                                    @error('password')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <label class="flex items-center gap-2">
                                        <input type="checkbox" name="remember">
                                        Ingat Saya
                                    </label>
                                    <a href="#" class="text-emerald-600 hover:text-emerald-700">
                                        Lupa Password?
                                    </a>
                                </div>
                                <button class="btn-primary">
                                    Login
                                </button>
                            </form>
                            <div class="mt-10 text-center">
                                <span class="text-gray-500">
                                    Belum punya akun?
                                </span>
                                <button type="button" @click="isLogin = false" class="font-semibold text-emerald-600 ml-1">
                                    Daftar
                                </button>
                            </div>
                        </div>
                    </section>
                    {{-- Register Form --}}
                    <section class="absolute inset-y-0 left-0 w-full lg:w-1/2 flex items-center justify-center p-10 lg:p-16 transition-all duration-700 z-10" :class="isLogin ? 'translate-x-full opacity-0 pointer-events-none lg:translate-x-[200%]' : 'translate-x-0 opacity-100'">
                        <div class="w-full max-w-md">
                            <div class="mb-10">
                                <h1 class="section-title">
                                    Buat Akun
                                </h1>
                                <p class="section-subtitle">
                                    Daftarkan akun baru untuk mulai melakukan booking.
                                </p>
                            </div>
                            <form action="{{ route('customer.register') }}" method="POST" class="space-y-5">
                                @csrf
                                <input type="text" class="input" name="name" placeholder="Nama Lengkap">
                                @error('name')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                                <input type="text" class="input" name="phone" placeholder="Nomor HP">
                                @error('phone')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                                <input type="password" class="input" name="password" placeholder="Password">
                                @error('password')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                                <input type="password" class="input" name="password_confirmation" placeholder="Konfirmasi Password">
                                <button class="btn-primary">
                                    Daftar
                                </button>
                            </form>
                            <div class="mt-8 text-center">
                                <span class="text-gray-500">
                                    Sudah punya akun?
                                </span>
                                <button type="button" class="ml-1 font-semibold text-emerald-600" @click="isLogin = true">
                                    Masuk
                                </button>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>
</x-guest-layout>