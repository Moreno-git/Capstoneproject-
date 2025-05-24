<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\CalendarController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\DonationManagementController;
use App\Http\Controllers\Admin\CampaignController as AdminCampaignController;
use App\Http\Controllers\UrgentFundsController;

// Public Routes
Route::get('/', [AdminAuthController::class, 'showLoginForm']);


// Public Donation Routes
Route::resource('donations', DonationController::class);

// Admin Authentication Routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Guest routes
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
    });

    // Authenticated routes
    Route::middleware('auth:admin')->group(function () {
        Route::get('/dashboard', [AdminAuthController::class, 'dashboard'])->name('dashboard');
        Route::get('/profile', [AdminAuthController::class, 'profile'])->name('profile');
        Route::put('/profile', [AdminAuthController::class, 'updateProfile'])->name('profile.update');
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
        
        // Admin Management Routes
        Route::get('/', [AdminController::class, 'index'])->name('index');
        Route::get('/create', [AdminController::class, 'create'])->name('create');
        Route::post('/', [AdminController::class, 'store'])->name('store');
        Route::get('/{admin}/edit', [AdminController::class, 'edit'])->name('edit');
        Route::put('/{admin}', [AdminController::class, 'update'])->name('update');
        Route::delete('/{admin}', [AdminController::class, 'destroy'])->name('destroy');

        // Campaign Management Routes
        Route::prefix('campaigns')->name('campaigns.')->group(function () {
            Route::get('/', [AdminCampaignController::class, 'dashboard'])->name('dashboard');
            Route::get('/manage', [AdminCampaignController::class, 'index'])->name('manage');
            Route::get('/create', [AdminCampaignController::class, 'create'])->name('create');
            Route::post('/', [AdminCampaignController::class, 'store'])->name('store');
            Route::get('/{campaign}', [AdminCampaignController::class, 'show'])->name('show');
            Route::get('/{campaign}/edit', [AdminCampaignController::class, 'edit'])->name('edit');
            Route::put('/{campaign}', [AdminCampaignController::class, 'update'])->name('update');
            Route::delete('/{campaign}', [AdminCampaignController::class, 'destroy'])->name('destroy');
        });

        // Donation Management Routes
        Route::prefix('donations')->name('donations.')->group(function () {
            Route::get('/', [DonationManagementController::class, 'index'])->name('index');
            Route::get('/all', [DonationManagementController::class, 'all'])->name('all');
            Route::get('/dropoffs', [DonationManagementController::class, 'dropoffs'])->name('dropoffs');
            Route::get('/{donation}', [DonationManagementController::class, 'show'])->name('show');
            Route::put('/{donation}/status', [DonationManagementController::class, 'updateStatus'])->name('update-status');
            Route::post('/{donation}/dropoff-status', [DonationManagementController::class, 'updateDropoffStatus'])
                ->name('update-dropoff-status');
        });
        
        // Calendar routes
        Route::prefix('calendar')->name('calendar.')->group(function () {
            Route::get('/', [CalendarController::class, 'index'])->name('index');
            Route::post('/', [CalendarController::class, 'store'])->name('store');
            Route::put('/{campaign}', [CalendarController::class, 'update'])->name('update');
            Route::delete('/{campaign}', [CalendarController::class, 'destroy'])->name('destroy');
        });

        // Category routes
        Route::prefix('categories')->name('categories.')->group(function () {
            Route::get('/', [CategoryController::class, 'index'])->name('index');
            Route::get('/create', [CategoryController::class, 'create'])->name('create');
            Route::post('/', [CategoryController::class, 'store'])->name('store');
            Route::get('/{category}/edit', [CategoryController::class, 'edit'])->name('edit');
            Route::put('/{category}', [CategoryController::class, 'update'])->name('update');
            Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('destroy');
        });

        // Reports routes
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', [AdminReportController::class, 'index'])->name('index');
            Route::get('/export', [AdminReportController::class, 'export'])->name('export');
        });

        // Urgent Funds routes
        Route::prefix('urgent-funds')->name('urgent-funds.')->group(function () {
            Route::get('/', [UrgentFundsController::class, 'index'])->name('index');
            Route::get('/create', [UrgentFundsController::class, 'create'])->name('create');
            Route::post('/', [UrgentFundsController::class, 'store'])->name('store');
            Route::get('/{campaign}/edit', [UrgentFundsController::class, 'edit'])->name('edit');
            Route::put('/{campaign}', [UrgentFundsController::class, 'update'])->name('update');
            Route::delete('/{campaign}', [UrgentFundsController::class, 'destroy'])->name('destroy');
        });
    });
});





