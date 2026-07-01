@extends ('layouts.guest')

@section ('content')
    <div class="min-h-screen flex items-center justify-center bg-gray-50">
        <div class="w-full max-w-md bg-white rounded-2xl shadow-lg p-8">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-indigo-600">Verifikasi OTP</h1>
                <p class="text-gray-500 mt-2">Masukkan kode OTP yang telah dikirim</p>
            </div>

            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                    @foreach ($errors->all() as $error)
                        <p class="text-red-600 text-sm">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                    <p class="text-green-600 text-sm">{{ session('success') }}</p>
                </div>
            @endif

            <form method="POST" action="{{ route('customer.verify.otp') }}" class="space-y-4">
                @csrf
                @if (session('phone'))
                    <input type="hidden" name="phone" value="{{ session('phone') }}" />
                @endif
                <div>
                    <label class="block text-sm mb-1"> Kode OTP </label>
                    <input
                        type="text"
                        name="otp"
                        class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500"
                        placeholder="Masukkan 6 digit OTP"
                        maxlength="6"
                        required
                    />
                </div>
                <button
                    type="submit"
                    class="w-full bg-indigo-600 text-white py-3 rounded-lg hover:bg-indigo-700"
                >
                    Verifikasi
                </button>
            </form>

            <div class="mt-5 text-center">
                <p class="text-sm text-gray-600">
                    Belum menerima OTP?
                    <a
                        href="{{ route('customer.resend-otp') }}"
                        class="text-indigo-600 font-semibold"
                    >
                        Kirim Ulang
                    </a>
                </p>
            </div>
        </div>
    </div>
@endsection
