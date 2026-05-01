<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth; // Fixes "Undefined type Auth"
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\AuthController;
// Import the Admin Namespace Controllers
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Middleware\AdminMiddleware;

// --- Language Switcher ---
Route::get('lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'ru', 'tk'])) {
        session(['locale' => $locale]);
    }
    return back();
})->name('lang');

// --- Custom Auth Routes ---
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// --- Public Routes ---
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');

// --- Car Routes (Protected) ---
// We apply 'auth' middleware here instead of inside the Controller
Route::middleware(['auth'])->group(function () {
    Route::resource('cars', CarController::class)->except(['index']);
});

// --- Admin Routes ---
// Fixes "Undefined type AdminController" by using specific controllers
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard (Main Admin Page)
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Users
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

    // Banners
    Route::get('/banners', [BannerController::class, 'index'])->name('banners.index');
    Route::post('/banners', [BannerController::class, 'store'])->name('banners.store');
    Route::delete('/banners/{id}', [BannerController::class, 'destroy'])->name('banners.destroy');

    // Brand Routes
    Route::get('/brands', [BrandController::class, 'index'])->name('brands.index');
    Route::post('/brands', [BrandController::class, 'store'])->name('brands.store');
    Route::delete('/brands/{id}', [BrandController::class, 'destroy'])->name('brands.destroy');
});
