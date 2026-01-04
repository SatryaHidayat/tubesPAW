<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Admin\MenuController as AdminMenuController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\PromoController as AdminPromoController;
use App\Http\Controllers\AdminPaymentController;

Route::post('/checkout', [App\Http\Controllers\OrderController::class, 'store'])->name('user.checkout');
Route::get('/', function () {
    return redirect('/login');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/menus', [OrderController::class, 'index'])->name('user.menus');

    Route::post('/checkout', [OrderController::class, 'store'])->name('user.checkout');
    Route::get('/history', [OrderController::class, 'history'])->name('order.history');
});

Route::get('/pembayaran/{id}', [OrderController::class, 'pembayaran'])
    ->name('order.pembayaran');

Route::post('/pembayaran/{id}', [OrderController::class, 'prosesPembayaran'])
    ->name('order.prosesPembayaran');


Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');
        Route::resource('menus', AdminMenuController::class);
        Route::resource('promos', AdminPromoController::class);
        Route::resource('orders', AdminOrderController::class)->only(['index', 'update']);
    });

Route::middleware(['auth', 'is_admin'])->group(function () {
    Route::get('/admin/pembayaran', [AdminPaymentController::class, 'index'])
        ->name('admin.pembayaran.index');
});

Route::get('/pembayaran/{id}', [OrderController::class, 'pembayaran'])
    ->name('order.pembayaran');

Route::post('/pembayaran/{id}', [OrderController::class, 'prosesPembayaran'])
    ->name('order.prosesPembayaran');
