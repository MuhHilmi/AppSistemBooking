@extends('layouts.customer')

@section('title', 'Profil Saya')

@section('content')
<div class="mx-auto max-w-2xl px-4 py-8">
    <h1 class="mb-6 text-2xl font-bold text-gray-900">Profil Saya</h1>
    <div class="space-y-6">
        {{-- Ubah Kata Sandi --}}
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-200 px-6 py-5">
                <h2 class="text-lg font-semibold text-gray-900">Ubah Kata Sandi</h2>
                <p class="mt-1 text-sm text-gray-500">Pastikan Anda menggunakan kata sandi yang kuat dan tidak dipakai di tempat lain.</p>
            </div>
            <form method="POST" action="{{ route('customer.profile.password.update') }}" class="space-y-4 p-6">
                @csrf
                @method('PUT')
                @if ($errors->updatePassword->any())
                    <div class="rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                        {{ $errors->updatePassword->first() }}
                    </div>
                @endif
                @if (session('status') === 'password-updated')
                    <div class="rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-700">
                        Kata sandi berhasil diperbarui.
                    </div>
                @endif
                <div>
                    <label for="current_password" class="mb-1 block text-sm font-medium text-gray-700">Kata Sandi Saat Ini</label>
                    <input type="password" id="current_password" name="current_password" autocomplete="current-password"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label for="password" class="mb-1 block text-sm font-medium text-gray-700">Kata Sandi Baru</label>
                    <input type="password" id="password" name="password" autocomplete="new-password"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label for="password_confirmation" class="mb-1 block text-sm font-medium text-gray-700">Konfirmasi Kata Sandi Baru</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div class="pt-2">
                    <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-indigo-700">
                        Simpan Kata Sandi
                    </button>
                </div>
            </form>
        </div>
        {{-- Hapus Akun --}}
        <div x-data="{ confirmingDeletion: false }" class="overflow-hidden rounded-xl border border-red-200 bg-white shadow-sm">
            <div class="border-b border-red-100 bg-red-50 px-6 py-5">
                <h2 class="text-lg font-semibold text-red-800">Hapus Akun</h2>
                <p class="mt-1 text-sm text-red-700">
                    Setelah akun dihapus, seluruh data dan riwayat booking Anda akan dihapus secara permanen. Tindakan ini tidak dapat dibatalkan.
                </p>
            </div>
            <div class="p-6">
                <button type="button" x-on:click="confirmingDeletion = true"
                    class="inline-flex items-center justify-center rounded-lg bg-red-600 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-red-700">
                    Hapus Akun Saya
                </button>
            </div>
            {{-- Modal konfirmasi --}}
            <div x-show="confirmingDeletion" x-cloak
                class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4">
                <div x-on:click.outside="confirmingDeletion = false"
                    class="w-full max-w-md rounded-xl bg-white p-6 shadow-lg">
                    <h3 class="text-lg font-semibold text-gray-900">Yakin ingin menghapus akun?</h3>
                    <p class="mt-2 text-sm text-gray-600">
                        Masukkan kata sandi Anda untuk mengonfirmasi penghapusan akun secara permanen.
                    </p>
                    <form method="POST" action="{{ route('customer.profile.destroy') }}" class="mt-4">
                        @csrf
                        @method('DELETE')
                        <input type="password" name="password" placeholder="Kata Sandi"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-red-500 focus:ring-red-500">
                        @if ($errors->userDeletion->any())
                            <p class="mt-2 text-sm text-red-600">{{ $errors->userDeletion->first() }}</p>
                        @endif
                        <div class="mt-5 flex justify-end gap-3">
                            <button type="button" x-on:click="confirmingDeletion = false"
                                class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                                Batal
                            </button>
                            <button type="submit"
                                class="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700">
                                Hapus Akun
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
