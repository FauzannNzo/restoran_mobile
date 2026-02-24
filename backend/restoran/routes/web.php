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

/*
|--------------------------------------------------------------------------
| 1. SELF SERVICE (PELANGGAN) - Tanpa Login
|--------------------------------------------------------------------------
*/

Route::controller(OrderController::class)->group(function () {
    // Halaman Menu & Order (Scan QR lari ke sini)
    Route::get('/', 'index')->name('order.index');

    // Proses Checkout (Terima JSON dari JS Cart)
    Route::post('/order', 'store')->name('order.store');

    // Halaman Struk/Sukses
    Route::get('/order/success/{id}', 'success')->name('order.success');
});

/*
|--------------------------------------------------------------------------
| 2. AUTHENTICATION (LOGIN/LOGOUT)
|--------------------------------------------------------------------------
*/
Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'login');
    Route::post('/logout', 'logout')->name('logout');
});

/*
|--------------------------------------------------------------------------
| 3. STAFF AREA (BUTUH LOGIN)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // --- ROLE: ADMIN (Manajemen Data Master) ---
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('index');

        // Resource Route (Otomatis bikin index, create, store, edit, update, destroy)
        Route::resource('kategoris', KategoriController::class);
        Route::resource('menus', MenuController::class);
        Route::resource('mejas', MejaController::class);
        Route::resource('users', UserController::class);
    });

    // --- ROLE: KASIR (Operasional Pembayaran) ---
    Route::middleware(['role:kasir'])->prefix('kasir')->name('kasir.')->group(function () {
        Route::controller(KasirController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/payment/{id}', 'confirmPayment')->name('confirm');
            Route::post('/meja/{id}/clear', 'clearTable')->name('clearTable');
            Route::get('/struk/{id}', 'printStruk')->name('print');
            Route::put('/meja/{id}/update', 'updateMeja')->name('updateMeja');
        });
    });

    // --- ROLE: CHEF (Operasional Dapur) ---
    Route::middleware(['role:chef'])->prefix('dapur')->name('dapur.')->group(function () {
        Route::controller(DapurController::class)->group(function () {
            Route::get('/', 'index')->name('index'); // Lihat Pesanan
            Route::post('/update/{id}', 'updateStatus')->name('update'); // Masak -> Selesai

            // Manajemen Stok Harian
            Route::get('/stok', 'stok')->name('stok');
            Route::post('/stok/{id}', 'updateStok')->name('updateStok');
        });
    });
});
