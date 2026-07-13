<?php

use App\Http\Controllers\Customer\AuthController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VenueController;
use App\Http\Controllers\FieldController;
use App\Http\Controllers\OperatingScheduleController;
use App\Http\Controllers\BookingController;
use Illuminate\Support\Facades\Route;

// Route Guest
Route::get('/', function () {
    return view('landing.index');
});

Route::get('/laravel', function () {
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
        Route::get('/dashboard', [BookingController::class, 'dashboardView']) -> name('bookings.dashboardView');
        Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
        Route::get('/bookings/{field}/create', [BookingController::class, 'create'])->name('bookings.create');
        Route::post('/bookings/{field}', [BookingController::class, 'store'])->name('bookings.store');
        Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
        Route::get('/bookings/{field}/slots', [BookingController::class, 'availableSlots'])->name('bookings.slots');
        Route::patch('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
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
    Route::get('/dashboard', [BookingController::class, 'dashboardOwnerView']) -> name('dashboardOwnerView');

    Route::get('/test', function () {
        return 'Owner';
    })->name('test');

    Route::get('/fields/{field}/operating-schedules',
        [OperatingScheduleController::class, 'edit']
    )->name('operating-schedules.edit');

    Route::put('/fields/{field}/operating-schedules',
        [OperatingScheduleController::class, 'update']
    )->name('operating-schedules.update');
});

// Route Venues Management, Fields, and Operating-Schedules using role Owner
Route::middleware(['auth', 'role:owner'])
    ->group(function () {
    Route::resource('venues', VenueController::class);
    Route::resource('fields', FieldController::class);
    Route::resource('operating-schedules', OperatingScheduleController::class);
});

// Route Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
