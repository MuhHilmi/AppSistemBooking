<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard-test', function () {
    return view('dashboard-test');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware([
    'auth',
    'role:admin',
])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
});

Route::middleware([
    'auth',
    'role:owner',
])->group(function () {
    Route::get('/owner/dashboard', function () {
        return view('owner.dashboard');
    })->name('owner.dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware([
    'auth',
    'role:admin',
])->group(function () {
    Route::get('/admin-test', function () {
        return 'Super Admin';
    });
});

Route::middleware([
    'auth',
    'role:owner',
])->group(function () {
    Route::get('/owner-test', function () {
        return 'Owner';
    });
});

Route::get('/admin', function () {
    return view('admin');
})->middleware(['auth', 'role:admin']);

require __DIR__.'/auth.php';
