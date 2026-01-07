<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Admin\MenuController as AdminMenuController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\PromoController as AdminPromoController;
use App\Http\Controllers\AdminPaymentController;

// 1. Redirection Awal
Route::get('/', function () {
    return redirect('/login');
});

Auth::routes();

// 2. User Routes (Halaman Pembeli)
Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/menus', [OrderController::class, 'index'])->name('user.menus');

    // Route ini yang akan menangani input pesanan dari halaman menu
    Route::post('/checkout', [OrderController::class, 'store'])->name('user.checkout');

    Route::get('/history', [OrderController::class, 'history'])->name('order.history');

    // Route Pembayaran
    Route::get('/pembayaran/{id}', [OrderController::class, 'pembayaran'])->name('order.pembayaran');
    Route::post('/pembayaran/{id}', [OrderController::class, 'prosesPembayaran'])->name('order.prosesPembayaran');

    // --- TAMBAHAN BARU: Route untuk input promo di halaman pembayaran ---
    Route::post('/pembayaran/{id}/promo', [OrderController::class, 'applyPromoPembayaran'])->name('order.applyPromo');
});

// 3. Admin Routes (Manajemen Menu & Promo)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::resource('menus', AdminMenuController::class);
    Route::resource('promos', AdminPromoController::class); // Untuk mengelola data promo di tabel promos
    Route::resource('orders', AdminOrderController::class)->only(['index', 'update']);

    // Verifikasi Pembayaran oleh Admin
    Route::get('/pembayaran', [AdminPaymentController::class, 'index'])->name('pembayaran.index');
});
