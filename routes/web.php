<?php

use App\Http\Controllers\Customer\AuthController;
use App\Http\Controllers\Customer\ProfileController as CustomerProfileController;
// use App\Http\Controllers\Customer\MembershipController as CustomerMembershipController;
use App\Http\Controllers\Customer\ReceiptController as CustomerReceiptController;
use App\Http\Controllers\Customer\ReviewController as CustomerReviewController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VenueController;
use App\Http\Controllers\FieldController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\OperatingScheduleController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\Owner\BookingController as OwnerBookingController;
use App\Http\Controllers\Owner\CustomerController as OwnerCustomerController;
use App\Http\Controllers\Owner\ReviewController as OwnerReviewController;
use App\Http\Controllers\Owner\RevenueController as OwnerRevenueController;
use App\Http\Controllers\Owner\SettingController as OwnerSettingController;
use App\Http\Controllers\Owner\StaffController as OwnerStaffController;
use App\Http\Controllers\BookingPaymentController;
use App\Http\Controllers\MidtransWebhookController;
use Illuminate\Support\Facades\Route;

// Route Guest
Route::get('/', [LandingController::class, 'index'])
    ->name('landing');

Route::get('/lapangan', [LandingController::class, 'allFields'])
    ->name('fields.index');

Route::get('/laravel', function () {
    return view('welcome');
});

// Webhook Midtrans - dipanggil server-to-server oleh Midtrans, bukan browser customer.
Route::post('/midtrans/callback', [MidtransWebhookController::class, 'handle'])->name('midtrans.callback');

// Route Customer
Route::prefix('customer')
    ->name('customer.')
    ->group(function() {
    Route::get('/register', [AuthController::class, 'registerForm'])->name('register.form');

    Route::post('/register', [AuthController::class, 'register'])->name('register');

    Route::get('/verify', [OtpController::class, 'form'])->name('verify');

    Route::post('/verify', [OtpController::class, 'verify'])->name('verify.otp');

    Route::get('/resend-otp', [OtpController::class, 'resend'])->name('resend-otp');

    Route::get('/login', [AuthController::class, 'loginForm'])->name('login.form');

    Route::post('/login', [AuthController::class, 'login'])->name('login');

    Route::middleware(['customer'])->group(function(){
        Route::get('/dashboard', [BookingController::class, 'dashboardView']) -> name('dashboard');
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
        Route::get('/bookings/history', [BookingController::class, 'historyCustomer'])->name('bookings.history');
        Route::get('/bookings/{field}/create', [BookingController::class, 'create'])->name('bookings.create');
        Route::post('/bookings/{field}', [BookingController::class, 'store'])->name('bookings.store');
        Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
        Route::get('/bookings/{field}/slots', [BookingController::class, 'availableSlots'])->name('bookings.slots');
        Route::patch('/bookings/{booking}/cancel', [BookingController::class, 'cancelCustomer'])->name('bookings.cancel');
        Route::get('/bookings/{booking}/payment', [BookingPaymentController::class, 'show'])->name('bookings.payment');
        Route::post('/bookings/{booking}/payment', [BookingPaymentController::class, 'store'])->name('bookings.payment.store');
        Route::get('/bookings/{booking}/payment/pending', [BookingPaymentController::class, 'pending'])->name('bookings.payment.pending');
        Route::get('/bookings/{booking}/payment/check-status', [BookingPaymentController::class, 'checkStatus'])->name('bookings.payment.check-status');
        Route::get('/bookings/{booking}/receipt', [CustomerReceiptController::class, 'show'])->name('bookings.receipt');
        // Route::get('/bookings/{booking}/receipt/download', [CustomerReceiptController::class, 'download'])->name('bookings.receipt.download');
        Route::get('/fields/{field}/review', [CustomerReviewController::class, 'edit'])->name('reviews.edit');
        Route::post('/fields/{field}/review', [CustomerReviewController::class, 'store'])->name('reviews.store');

        // Route::get('/membership', [CustomerMembershipController::class, 'index'])->name('membership.index');
        // Route::post('/membership/redeem/{benefit}', [CustomerMembershipController::class, 'redeem'])->name('membership.redeem');

        Route::get('/profile', [CustomerProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile/password', [CustomerProfileController::class, 'updatePassword'])->name('profile.password.update');
        Route::delete('/profile', [CustomerProfileController::class, 'destroy'])->name('profile.destroy');
    });
});

// Route verifikasi auth
// Route::get('/dashboard', function () {
//     return view('dashboard-test');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route Admin
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {
    Route::get('/dashboard', function () { return view('admin.dashboard'); })->name('dashboard');

    Route::get('/test', function () { return 'Super Admin'; })->name('test');

    Route::get('/', function () { return view('admin'); })->name('index');
});

// Route Owner & Penjaga
Route::prefix('owner')
    ->name('owner.')
    ->middleware(['auth', 'force.password.change'])
    ->group(function () {

    // Bisa diakses owner maupun penjaga (scoped ke venue masing-masing)
    Route::middleware(['role:owner,penjaga'])->group(function () {
        Route::get('/dashboard', [BookingController::class, 'dashboardOwnerView'])->name('dashboard');

        Route::patch('/bookings/{booking}/cancel', [BookingController::class, 'cancelOwner'])->name('bookings.cancel');

        Route::get('/bookings', [OwnerBookingController::class, 'index'])->name('bookings.index');
        Route::get('/bookings/create', [OwnerBookingController::class, 'create'])->name('bookings.create');
        Route::post('/bookings', [OwnerBookingController::class, 'store'])->name('bookings.store');
        Route::get('/bookings/customers/search', [OwnerBookingController::class, 'searchCustomers'])->name('bookings.customers.search');
        Route::get('/fields/{field}/slots', [BookingController::class, 'availableSlots'])->name('bookings.slots');
        Route::patch('/bookings/{booking}/confirm-cash', [OwnerBookingController::class, 'confirmCashPayment'])->name('bookings.confirm-cash');
        Route::post('/bookings/{booking}/confirm-transfer', [OwnerBookingController::class, 'confirmTransferPayment'])->name('bookings.confirm-transfer');

        Route::get('/revenue', [OwnerRevenueController::class, 'index'])->name('revenue.index');
        Route::get('/revenue/export', [OwnerRevenueController::class, 'export'])->name('revenue.export');
    });

    // Khusus owner: venue, staf/penjaga, dan hal-hal di luar operasional harian
    Route::middleware(['role:owner'])->group(function () {
        Route::get('/test', function () {
            return 'Owner';
        })->name('test');

        Route::get('/fields/{field}/operating-schedules', [OperatingScheduleController::class, 'edit'])->name('operating-schedules.edit');

        Route::put('/fields/{field}/operating-schedules', [OperatingScheduleController::class, 'update'])->name('operating-schedules.update');

    Route::get('/customers', [OwnerCustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/{customer}', [OwnerCustomerController::class, 'show'])->name('customers.show');

        Route::get('/reviews', [OwnerReviewController::class, 'index'])->name('reviews.index');
        Route::post('/reviews/{review}/approve', [OwnerReviewController::class, 'approve'])->name('reviews.approve');
        Route::post('/reviews/{review}/reject', [OwnerReviewController::class, 'reject'])->name('reviews.reject');

        Route::get('/settings', [OwnerSettingController::class, 'edit'])->name('settings.edit');
        Route::put('/settings', [OwnerSettingController::class, 'update'])->name('settings.update');

        Route::get('/staff', [OwnerStaffController::class, 'index'])->name('staff.index');
        Route::get('/staff/create', [OwnerStaffController::class, 'create'])->name('staff.create');
        Route::post('/staff', [OwnerStaffController::class, 'store'])->name('staff.store');
        Route::get('/staff/{staff}/edit', [OwnerStaffController::class, 'edit'])->name('staff.edit');
        Route::put('/staff/{staff}', [OwnerStaffController::class, 'update'])->name('staff.update');
        Route::patch('/staff/{staff}/toggle-active', [OwnerStaffController::class, 'toggleActive'])->name('staff.toggle-active');
        Route::delete('/staff/{staff}', [OwnerStaffController::class, 'destroy'])->name('staff.destroy');

        Route::resource('venues', VenueController::class);

        Route::resource('fields', FieldController::class);

        Route::resource('operating-schedules', OperatingScheduleController::class);
    });
});

// Route Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
