<?php

use App\Http\Controllers\Customer\AuthController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VenueController;
use Illuminate\Support\Facades\Route;

// Route Guest
Route::get('/', function () {
    return view('welcome');
});

// Route Customer
Route::prefix('customer')
    ->name('customer.')
    ->group(function() {
    Route::get('/register', [AuthController::class, 'registerForm'])
        ->name('register.form');

    Route::post('/register', [AuthController::class, 'register'])
        ->name('register');

    Route::get('/verify', [OtpController::class, 'form'])
        ->name('verify');

    Route::post('/verify', [OtpController::class, 'verify'])
        ->name('verify.otp');

    Route::get('/resend-otp', [OtpController::class, 'resend'])
        ->name('resend-otp');

    Route::get('/login', [AuthController::class, 'loginForm'])
        ->name('login.form');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('login');

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');

    Route::middleware(['customer'])->group(function(){
        Route::get('/dashboard',function () {
            return view('customer.dashboard');
        })->name('dashboard');
    });
});

// Route verifikasi auth
Route::get('/dashboard', function () {
    return view('dashboard-test');
})->middleware(['auth', 'verified'])->name('dashboard');

// Route Admin
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::get('/test', function () {
        return 'Super Admin';
    })->name('test');

    Route::get('/', function () {
        return view('admin');
    })->name('index');
});

// Route Owner
Route::prefix('owner')
    ->name('owner.')
    ->middleware(['auth','role:owner',])
    ->group(function () {
    Route::get('/owner/dashboard', function () {
        return view('owner.dashboard');
    })->name('owner.dashboard');

    Route::get('/test', function () {
        return 'Owner';
    })->name('test');
});

// Route Venues Management using role Owner
Route::middleware(['auth', 'role:owner'])->group(function () {
    Route::resource('venues', VenueController::class);
});

// Route Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
