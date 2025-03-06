<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\BusController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\ConductorController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ArmadaController;
use App\Http\Controllers\MidtransController;

// Landing page (dapat diakses semua orang)
Route::get('/', [HomeController::class, 'index'])->name('home');

// Auth routes (hanya untuk guest/belum login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.perform');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.perform');
    Route::get('/forgot-password', [PasswordResetController::class, 'showForgotForm'])
        ->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink'])
        ->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])
        ->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'resetPassword'])
        ->name('password.update');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

// Protected routes (setelah login)
Route::middleware(['auth'])->group(function () {
    // Redirect after login based on role
    Route::get('/dashboard', function () {
        $role = auth()->user()->role;
        return redirect()->route($role . '.dashboard');
    })->name('dashboard');

    // Owner Routes
    Route::prefix('owner')->name('owner.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'ownerDashboard'])->name('dashboard');
        
        // Statistics Routes
        Route::prefix('statistics')->name('statistics.')->group(function () {
            Route::get('/daily', [StatisticsController::class, 'daily'])->name('daily');
            Route::get('/monthly', [StatisticsController::class, 'monthly'])->name('monthly');
            Route::get('/yearly', [StatisticsController::class, 'yearly'])->name('yearly');
            Route::get('/bus', [StatisticsController::class, 'busUsage'])->name('bus');
            Route::get('/driver', [StatisticsController::class, 'driverHours'])->name('driver');
            Route::get('/fleet', [StatisticsController::class, 'fleetRanking'])->name('fleet');
        });

        Route::resource('users', UserController::class);
    });

    // Admin Routes
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('dashboard');
        
        // Data Management
        Route::resource('drivers', DriverController::class);
        Route::resource('conductors', ConductorController::class);
        Route::resource('buses', BusController::class);
        Route::get('/bus-status', [BusController::class, 'status'])->name('buses.status');
        Route::resource('customers', CustomerController::class);
        
        // Transaction Management
        Route::resource('rentals', RentalController::class);
        Route::resource('payments', PaymentController::class);
        
        // Request Management
        Route::get('/requests', [RequestController::class, 'index'])->name('requests.index');
        Route::put('/request/{id}/approve', [RequestController::class, 'approve'])->name('requests.approve');
        Route::put('/request/{id}/reject', [RequestController::class, 'reject'])->name('requests.reject');

        Route::get('/rentals', [RentalController::class, 'adminIndex'])->name('rentals.index');
        Route::get('/rentals/{rental}', [RentalController::class, 'adminShow'])->name('rentals.show');
        Route::put('/rentals/{rental}/update-status', [RentalController::class, 'updateStatus'])->name('rentals.update-status');
        Route::put('/rentals/{rental}/update-payment', [RentalController::class, 'updatePayment'])->name('rentals.update-payment');

        // Payment Routes
        Route::get('/payments', [PaymentController::class, 'adminIndex'])->name('payments.index');
        Route::post('/payments/{payment}/verify', [PaymentController::class, 'verifyPayment'])->name('payments.verify');
        Route::post('/payments/midtrans-notification', [PaymentController::class, 'verifyPayment'])->name('payments.midtrans.notification');
        
        Route::get('/midtrans', [MidtransController::class, 'index'])->name('midtrans.index');
        Route::get('/midtrans/dashboard', [MidtransController::class, 'dashboard'])->name('midtrans.dashboard');

        Route::resource('armada', ArmadaController::class);

        // Admin Payment Verification Routes
        Route::post('/payments/{payment}/verify-manual', [PaymentController::class, 'verifyManualPayment'])->name('payments.verify-manual');
        Route::post('/payments/{payment}/reject-manual', [PaymentController::class, 'rejectManualPayment'])->name('payments.reject-manual');
    });

    // Customer Routes
    Route::prefix('customer')->name('customer.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'customerDashboard'])->name('dashboard');
        
        // Profile Routes
        Route::get('/profile', [UserProfileController::class, 'show'])->name('profile.show');
        Route::get('/profile/edit', [UserProfileController::class, 'edit'])->name('profile.edit');
        Route::post('/profile/update', [UserProfileController::class, 'update'])->name('profile.update');
        
        // Payment Routes
        Route::get('/payments', [PaymentController::class, 'index'])->name('payments');
        Route::get('/rentals/{rental}/pay', [PaymentController::class, 'showPaymentForm'])->name('pay');
        Route::post('/rentals/{rental}/process-payment', [PaymentController::class, 'pay'])->name('process-payment');
        Route::post('/payments/store', [PaymentController::class, 'store'])->name('payments.store');
        Route::get('/payments/history', [PaymentController::class, 'history'])->name('payments.history');
        
        // Booking
        Route::get('/search', [BusController::class, 'search'])->name('search');
        Route::post('/book/{bus}', [RentalController::class, 'book'])->name('book');
        
        // Rentals & Payments
        Route::get('/rentals', [RentalController::class, 'index'])->name('rentals.index');
        Route::get('/rentals/get-available-crew', [RentalController::class, 'getAvailableCrew'])->name('rentals.get-available-crew');
        Route::post('/rentals', [RentalController::class, 'store'])->name('rentals.store');
        Route::get('/rentals/{rental}', [RentalController::class, 'show'])->name('rentals.show');
        Route::post('/rentals/{rental}/cancel', [RentalController::class, 'cancel'])->name('rentals.cancel');
        
        // Ratings
        Route::get('/ratings', [RatingController::class, 'index'])->name('ratings');
        Route::post('/rate/{rental}', [RatingController::class, 'store'])->name('rate');

        // Routes untuk pembayaran
        Route::post('/rentals/{rental}/pay', [PaymentController::class, 'pay'])->name('pay');

        // Payment Routes
        Route::get('/payments/{rental}/form', [PaymentController::class, 'showPaymentForm'])
            ->name('payments.form');
        Route::post('/payments/{rental}/pay', [PaymentController::class, 'processPayment'])
            ->name('payments.pay');
        Route::get('/payments/history', [PaymentController::class, 'history'])
            ->name('payments.history');

        Route::post('/payments/{rental}/get-snap-token', [PaymentController::class, 'getSnapToken'])->name('payments.get-snap-token');

        Route::get('/payments/success', [PaymentController::class, 'success'])->name('payments.success');
        Route::get('/payments/pending', [PaymentController::class, 'pending'])->name('payments.pending');
        Route::get('/payments/error', [PaymentController::class, 'error'])->name('payments.error');

        // Manual Payment Routes
        Route::post('/payments/{rental}/manual', [PaymentController::class, 'manualPayment'])->name('payments.manual');
        Route::get('/payments/{rental}/upload', [PaymentController::class, 'showUploadForm'])->name('payments.upload');
        Route::post('/payments/{rental}/upload-proof', [PaymentController::class, 'uploadProof'])->name('payments.upload-proof');
    });
    
    Route::post('/payments/{rental}/pay', [PaymentController::class, 'pay'])->name('customer.payments.pay');
    Route::get('/payments/{rental}/form', [PaymentController::class, 'showPaymentForm'])->name('customer.payments.form');

    // Driver Routes (dalam group middleware auth)
    Route::get('/drivers', [DriverController::class, 'index'])->name('drivers.index');
    Route::get('/drivers/create', [DriverController::class, 'create'])->name('drivers.create');
    Route::post('/drivers', [DriverController::class, 'store'])->name('drivers.store');
    Route::get('/drivers/{driver}/edit', [DriverController::class, 'edit'])->name('drivers.edit');
    Route::put('/drivers/{driver}', [DriverController::class, 'update'])->name('drivers.update');
    Route::delete('/drivers/{driver}', [DriverController::class, 'destroy'])->name('drivers.destroy');

    // Routes untuk conductors
    Route::get('/conductors', [ConductorController::class, 'index'])->name('conductors.index');
    Route::get('/conductors/create', [ConductorController::class, 'create'])->name('conductors.create');
    Route::post('/conductors', [ConductorController::class, 'store'])->name('conductors.store');
    Route::get('/conductors/{conductor}/edit', [ConductorController::class, 'edit'])->name('conductors.edit');
    Route::put('/conductors/{conductor}', [ConductorController::class, 'update'])->name('conductors.update');
    Route::delete('/conductors/{conductor}', [ConductorController::class, 'destroy'])->name('conductors.destroy');

    // Routes untuk users management
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    // Routes untuk bus
    Route::get('/buses', [BusController::class, 'index'])->name('buses.index');
    Route::get('/buses/create', [BusController::class, 'create'])->name('buses.create');
    Route::post('/buses', [BusController::class, 'store'])->name('buses.store');
    Route::get('/buses/{bus}/edit', [BusController::class, 'edit'])->name('buses.edit');
    Route::put('/buses/{bus}', [BusController::class, 'update'])->name('buses.update');
    Route::delete('/buses/{bus}', [BusController::class, 'destroy'])->name('buses.destroy');

    // Routes untuk pencarian dan pemesanan bus
    Route::get('/buses/search', [BusController::class, 'search'])->name('buses.search');
    Route::get('/buses/{bus}/book', [BusController::class, 'book'])->name('buses.book');

    // Routes untuk booking
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');

    // Routes untuk rental (tanpa prefix customer)
    Route::get('/rentals', [RentalController::class, 'index'])->name('rentals.index');
    Route::get('/rentals/create', [RentalController::class, 'create'])->name('rentals.create');
    Route::post('/rentals', [RentalController::class, 'store'])->name('rentals.store');
    Route::get('/rentals/{rental}', [RentalController::class, 'show'])->name('rentals.show');
    Route::post('/rentals/{rental}/cancel', [RentalController::class, 'cancel'])->name('rentals.cancel');
    
    // Routes untuk customer dan rental dalam satu prefix
    Route::prefix('customer')->name('customer.')->group(function () {
        // Routes untuk customers
        Route::resource('customers', CustomerController::class);
        
        // Routes untuk rental
        Route::get('/rentals', [RentalController::class, 'index'])->name('rentals.index');
        Route::get('/rentals/get-available-crew', [RentalController::class, 'getAvailableCrew'])->name('rentals.get-available-crew');
        Route::post('/rentals', [RentalController::class, 'store'])->name('rentals.store');
        Route::get('/rentals/{rental}', [RentalController::class, 'show'])->name('rentals.show');
        Route::post('/rentals/{rental}/cancel', [RentalController::class, 'cancel'])->name('rentals.cancel');
    });
    
});

Route::post('payments/start/{rental}', [PaymentController::class, 'startPayment'])->name('payments.start');

