<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
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
        $request->validate([
            'name' => 'required',
            'phone' => 'required|unique:customers',
            'password' => 'required|min:8',
        ]);
        Customer::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'password' => Hash::make(
                $request->password
            ),
        ]);
        return redirect(
            '/customer/login'
        );
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
