<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AiController;

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CarController as AdminCarController;
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
Route::middleware(['auth'])->group(function () {
    Route::resource('cars', CarController::class)->except(['index']);
});

Route::get('/ai', [AiController::class, 'index'])->name('ai.index');
Route::post('/ai/chat', [AiController::class, 'chat'])->name('ai.chat');

// --- Admin Routes ---
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


    // Admin Car Moderation
    Route::get('/cars', [AdminCarController::class, 'index'])->name('cars.index');
    Route::get('/cars/{car}', [AdminCarController::class, 'show'])->name('cars.show');
    Route::patch('/cars/{car}/status', [AdminCarController::class, 'updateStatus'])->name('cars.update-status');
    Route::post('/cars/{car}/ai-check', [AdminCarController::class, 'runAiCheck'])->name('cars.ai-check');
    Route::delete('/cars/{car}', [AdminCarController::class, 'destroy'])->name('cars.destroy');
});
