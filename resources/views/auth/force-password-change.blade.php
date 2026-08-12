<x-guest-layout>
    @section('title', 'Ganti Password - '. config('app.name'))

    <div class="min-h-screen bg-gradient-to-br from-green-300 via-sky-200 to-green-400 flex flex-col justify-center items-center px-4">
        <h1 class="mb-2 text-3xl font-bold text-center">
            Ganti Password
        </h1>
        <p class="mb-6 text-center text-gray-700 max-w-sm">
            Ini adalah login pertama Anda. Demi keamanan, silakan buat password baru sebelum melanjutkan.
        </p>
        <div class="bg-white p-8 rounded-lg shadow-xl w-full max-w-sm">
            <form method="POST" action="{{ route('password.force-change.update') }}">
                @csrf

                <div>
                    <x-input-label for="password" value="Password Baru" />
                    <x-text-input
                        id="password"
                        class="block mt-1 w-full"
                        type="password"
                        name="password"
                        required
                        autofocus
                        autocomplete="new-password"
                    />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="mt-4">
                    <x-input-label for="password_confirmation" value="Konfirmasi Password Baru" />
                    <x-text-input
                        id="password_confirmation"
                        class="block mt-1 w-full"
                        type="password"
                        name="password_confirmation"
                        required
                        autocomplete="new-password"
                    />
                </div>

                <div class="flex items-center justify-end mt-8">
                    <x-primary-button> Simpan &amp; Lanjutkan </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
