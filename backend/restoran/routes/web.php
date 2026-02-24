<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\DapurController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\MejaController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\KategoriController;


Route::controller(OrderController::class)->group(function () {
    Route::get('/', 'index')->name('order.index');
    Route::post('/order', 'store')->name('order.store');
    Route::get('/order/success/{id}', 'success')->name('order.success');
});

Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'login');
    Route::post('/logout', 'logout')->name('logout');
});

Route::middleware(['auth'])->group(function () {

    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('index');

        Route::resource('kategoris', KategoriController::class);
        Route::resource('menus', MenuController::class);
        Route::resource('mejas', MejaController::class);
        Route::resource('users', UserController::class);
    });

    Route::middleware(['role:kasir'])->prefix('kasir')->name('kasir.')->group(function () {
        Route::controller(KasirController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/payment/{id}', 'confirmPayment')->name('confirm');
            Route::post('/meja/{id}/clear', 'clearTable')->name('clearTable');
            Route::get('/struk/{id}', 'printStruk')->name('print');
            Route::put('/meja/{id}/update', 'updateMeja')->name('updateMeja');
        });
    });

    Route::middleware(['role:chef'])->prefix('dapur')->name('dapur.')->group(function () {
        Route::controller(DapurController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/update/{id}', 'updateStatus')->name('update');

            Route::get('/stok', 'stok')->name('stok');
            Route::post('/stok/{id}', 'updateStok')->name('updateStok');
        });
    });
});
