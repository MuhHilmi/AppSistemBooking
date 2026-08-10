<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\OtpVerification;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OtpController extends Controller
{
    public function form()
    {
        return view(
            'customer.auth.verify'
        );
    }

    protected const MAX_OTP_ATTEMPTS = 5;

    public function verify(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $phone = session('phone');

        if (! $phone) {
            return back()->withErrors([
                'otp' => 'Informasi nomor tidak ditemukan. Silakan minta ulang OTP.',
            ]);
        }

        $customer = Customer::where('phone', $phone)->first();

        if (! $customer) {
            return back()->withErrors([
                'otp' => 'Nomor tidak ditemukan.',
            ]);
        }

        if ($customer->is_verified) {
            return redirect()->route('customer.login.form')->with('info', 'Akun Anda sudah terverifikasi, silahkan login.');
        }

        $otpRecord = OtpVerification::where('customers_id', $customer->id)
            ->where('is_used', false)
            ->latest()
            ->first();

        if (! $otpRecord) {
            return back()->withErrors(['otp' => 'OTP tidak valid/ditemukan. Silahkan minta ulang OTP.']);
        }

        if (now()->gt($otpRecord->expired_at)) {
            return back()->withErrors(['otp' => 'OTP sudah kadaluarsa. Silahkan minta ulang OTP.']);
        }

        if ($otpRecord->attempts >= self::MAX_OTP_ATTEMPTS) {
            $otpRecord->update(['is_used' => true]);

            return back()->withErrors(['otp' => 'Terlalu banyak percobaan gagal. Silahkan minta ulang OTP.']);
        }

        if ($otpRecord->otp !== $request->otp) {
            $otpRecord->increment('attempts');

            return back()->withErrors(['otp' => 'OTP tidak valid.']);
        }

        DB::transaction(function () use ($customer, $otpRecord) {
            $customer->update(['is_verified' => true]);
            $otpRecord->update(['is_used' => true]);
        });

        session()->forget('phone');

        return redirect()->route('customer.login.form')->with('success', 'Verifikasi berhasil! Silakan login.');
    }

    public function resend(Request $request)
    {
        $phone = session('phone');

        if (!$phone) {
            return redirect()->route('customer.login.form')->withErrors(['phone' => 'Informasi nomor tidak ditemukan. Silahkan daftar/login kembali']);
        }

        $customer = Customer::where('phone', $phone)->first();

        if (!$customer) {
            return back()->withErrors(['phone' => 'Nomor tidak ditemukan.']);
        }

        $latestOtp = OtpVerification::where('customers_id', $customer->id)
            ->where('is_used', false)
            ->latest()
            ->first();

        if ($latestOtp && now()->lt($latestOtp->expired_at)) {
            return back()->withErrors(['otp' => 'OTP masih berlaku. Tunggu beberapa menit sebelum meminta ulang']);
        }

        OtpVerification::where('customers_id', $customer->id)
            ->where('is_used', false)
            ->update([
                'is_used' => true,
            ]);

        $otp = random_int(100000, 999999);

        $otpRecord = OtpVerification::create([
            'customers_id' => $customer->id,
            'otp' => $otp,
            'expired_at' => now()->addMinutes(5),
            'attempts' => 0
        ]);

        $otpSent = OtpService::send($customer->phone, $otp);

        if (!$otpSent) {
            $otpRecord->delete();

            Log::warning("Failed to send OTP via Fonnte", [
                'customers_id' => $customer->id,
                'phone' => $customer->phone
            ]);

            return back()->withErrors([
                'otp' => 'Gagal mengirim OTP. Silahkan coba lagi nanti.'
            ]);
        }
        Log::info('OTP resent successfully', [
            'customer_id' => $customer->id,
            'phone' => $customer->phone,
        ]);

        session(['phone' => $customer->phone]);

        return back()->with(
            'success',
            'OTP telah dikirim ulang ke nomor Anda.'
        );
    }
}
