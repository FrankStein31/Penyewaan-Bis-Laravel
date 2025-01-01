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
        Route::put('/payment/{id}/verify', [PaymentController::class, 'verify'])->name('payments.verify');
        
        // Request Management
        Route::get('/requests', [RequestController::class, 'index'])->name('requests.index');
        Route::put('/request/{id}/approve', [RequestController::class, 'approve'])->name('requests.approve');
        Route::put('/request/{id}/reject', [RequestController::class, 'reject'])->name('requests.reject');
    });

    // Customer Routes
    Route::prefix('customer')->name('customer.')->middleware('auth')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'customerDashboard'])->name('dashboard');
        
        // Profile Routes
        Route::get('/profile', [UserProfileController::class, 'show'])->name('profile.show');
        Route::get('/profile/edit', [UserProfileController::class, 'edit'])->name('profile.edit');
        Route::post('/profile/update', [UserProfileController::class, 'update'])->name('profile.update');
        
        // Booking
        Route::get('/search', [BusController::class, 'search'])->name('search');
        Route::post('/book/{bus}', [RentalController::class, 'book'])->name('book');
        
        // Rentals & Payments
        Route::get('/rentals', [RentalController::class, 'myRentals'])->name('rentals');
        Route::get('/payments', [PaymentController::class, 'index'])->name('payments');
        Route::post('/payment/{rental}', [PaymentController::class, 'pay'])->name('pay');
        
        // Ratings
        Route::get('/ratings', [RatingController::class, 'index'])->name('ratings');
        Route::post('/rate/{rental}', [RatingController::class, 'store'])->name('rate');
    });

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
});