<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\OtpVerification;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function registerForm()
    {
        return view('customer.auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'phone' => 'required|unique:customers',
            'password' => 'required|min:8',
        ]);
        
        $customer = Customer::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'password' => Hash::make(
                $request->password
            ),
        ]);
        
        $otp = random_int(
            100000,
            999999
        );
        
        OtpVerification::create([
            'customers_id' => $customer->id,
            'otp' => $otp,
            'expired_at' => now()->addMinutes(5)
        ]);
        
        $otpSent = OtpService::send(
            $customer->phone,
            $otp
        );
        
        return redirect(
            '/customer/verify'
        )->with('phone', $customer->phone);
    }

    public function loginForm()
    {
        return view('customer.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = [
            'phone' => $request->phone,
            'password' => $request->password
        ];
        $customer = Customer::where(
            'phone',
            $request->phone
        )->first();
        if (
            !$customer->is_verified
        ) {
            return back()
                ->withErrors([
                    'phone' =>
                    'Nomor belum diverifikasi'
                ]);
        }
        if (
            Auth::guard('customer')
            ->attempt($credentials)
        ) {
            $request
                ->session()
                ->regenerate();
            return redirect(
                '/customer/dashboard'
            );
        }
        return back()->withErrors([
            'phone' => 'Login gagal'
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('customer')
            ->logout();
        $request
            ->session()
            ->invalidate();
        $request
            ->session()
            ->regenerateToken();
        return redirect(
            '/customer/login'
        );
    }
}
