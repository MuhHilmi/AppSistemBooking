<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\OtpVerification;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function registerForm()
    {
        return view('customer.auth.login-register', ['showLogin' => false]);
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|unique:customers,phone',
            'email' => 'required|email|unique:customers,email',
            'password' => 'required|string|min:8|confirmed',
        ]);
        $result = DB::transaction(function () use ($validated) {
            $customer = Customer::create([
                'name' => $validated['name'],
                'phone' => $validated['phone'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);
            $otp = random_int(100000, 999999);
            OtpVerification::create([
                'customers_id' => $customer->id,
                'otp' => $otp,
            'expired_at' => now()->addMinutes(5)
            ]);

            return [$customer, $otp];
        });
        [$customer, $otp] = $result;
        $otpSent = OtpService::send($customer->phone, $otp);
        if (! $otpSent) {
            $customer->delete();

            return back()->withInput()->withErrors(['phone' => 'OTP gagal dikirim, silahkan coba lagi']);
        }

        session(['phone' => $customer->phone]);
        return redirect()->route('customer.verify');
    }

    public function loginForm()
    {
        return view('customer.auth.login-register', ['showLogin' => true]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'phone' => 'required',
            'password' => 'required',
        ]);

        $credentials = [
            'phone' => $request->phone,
            'password' => $request->password
        ];
        $customer = Customer::where(
            'phone',
            $request->phone
        )->first();

        if (!$customer) {
            return back()->withErrors(['phone' => 'Nomor HP belum terdaftar']);
        }

        if (!$customer->is_verified) {
            return back()->withErrors(['phone' => 'Nomor belum diverifikasi']);
        }
        if (Auth::guard('customer')->attempt($credentials)) {
            $request
                ->session()
                ->regenerate();
            return redirect('/customer/dashboard');
        }
        return back()->withErrors(['phone' => 'Login gagal']);
    }

    public function logout(Request $request)
    {
        Auth::guard('customer')
            ->logout();
        // $request // Di uncomment ketika production
        //     ->session()
        //     ->invalidate();
        $request
            ->session()
            ->regenerateToken();
        return redirect(
            '/customer/login'
        );
    }
}
