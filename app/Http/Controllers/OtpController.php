<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OtpVerification;
use App\Models\Customer;
use App\Services\OtpService;

class OtpController extends Controller
{
    public function form()
    {
        return view(
            'customer.auth.verify'
        );
    }

    public function verify(Request $request)
    {
        $otp = OtpVerification::where(
            'otp',
            $request->otp
        )->where('is_used', false)->latest()->first();

        if (!$otp)
        {
            return back()
                ->withErrors([
                    'otp' => 'OTP tidak valid'
                ]);
        }

        if (now()->gt($otp->expired_at))
        {
            return back()
                ->withErrors([
                    'otp' => 'OTP sudah kadaluarsa'
                ]);
        }
        $otp->customer->update([
            'is_verified' => true
        ]);
        $otp->update([
            'is_used' => true
        ]);
        return redirect('/customer/login')
            ->with('success', 'Verifikasi berhasil! Silakan login.');
    }

    public function resend(Request $request)
    {
        $request->validate([
            'phone' => 'required|exists:customers'
        ]);

        $customer = Customer::where('phone', $request->phone)->first();
        
        $latestOtp = OtpVerification::where(
            'customer_id',
            $customer->id
        )->latest()->first();

        if ($latestOtp && now()->lt($latestOtp->expired_at)) {
            return back()->withErrors([
                'phone' => 'OTP masih berlaku. Tunggu beberapa menit sebelum meminta ulang.'
            ]);
        }

        $otp = random_int(100000, 999999);
        
        OtpVerification::create([
            'customer_id' => $customer->id,
            'otp' => $otp,
            'expired_at' => now()->addMinutes(5)
        ]);

        OtpService::send($customer->phone, $otp);

        return back()->with('success', 'OTP telah dikirim ulang ke nomor Anda.');
    }
}
