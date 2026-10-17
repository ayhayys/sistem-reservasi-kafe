<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OwnerController;

// Public Routes (Bisa diakses Guest)
Route::get('/', [CustomerController::class, 'index'])->name('home');
Route::get('/menu', [CustomerController::class, 'menu'])->name('menu');

// Route Registrasi
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Protected Routes (Harus Login & Role Customer)
Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/reservasi', [CustomerController::class, 'reservation'])->name('reservation');
    Route::post('/reservasi', [CustomerController::class, 'storeReservation'])->name('reservation.store');
    
    Route::get('/denah-meja', [CustomerController::class, 'tableLayout'])->name('table.layout');
    
    Route::post('/checkout', [CustomerController::class, 'checkout'])->name('checkout');
    
    Route::get('/riwayat', [CustomerController::class, 'history'])->name('history');
});

// Route untuk Pemilik (Owner)
Route::middleware(['auth', 'role:owner'])->prefix('owner')->name('owner.')->group(function () {
    Route::get('/', function () { return redirect()->route('owner.menu.index'); })->name('dashboard');

    // Manajemen Menu
    Route::get('/menu', [\App\Http\Controllers\OwnerController::class, 'menuIndex'])->name('menu.index');
    Route::post('/menu', [\App\Http\Controllers\OwnerController::class, 'menuStore'])->name('menu.store');
    Route::put('/menu/{id}', [\App\Http\Controllers\OwnerController::class, 'menuUpdate'])->name('menu.update');
    Route::delete('/menu/{id}', [\App\Http\Controllers\OwnerController::class, 'menuDestroy'])->name('menu.destroy');
    
    // Manajemen Kategori
    Route::post('/category', [\App\Http\Controllers\OwnerController::class, 'categoryStore'])->name('category.store');
    Route::put('/category/{id}', [\App\Http\Controllers\OwnerController::class, 'categoryUpdate'])->name('category.update');
    Route::delete('/category/{id}', [\App\Http\Controllers\OwnerController::class, 'categoryDestroy'])->name('category.destroy');

    // Laporan (Tetap dipertahankan)
    Route::get('/laporan-pemesanan', [\App\Http\Controllers\OwnerController::class, 'reportOrders'])->name('reports.orders');
    Route::get('/laporan-reservasi', [\App\Http\Controllers\OwnerController::class, 'reportReservations'])->name('reports.reservations');
});

// Route untuk Kasir (Cashier)
Route::middleware(['auth', 'role:cashier'])->prefix('cashier')->name('cashier.')->group(function () {
    // Redirect default kasir ke halaman pesanan
    Route::get('/', function () {
        return redirect()->route('cashier.orders');
    })->name('dashboard');

    // Pesanan Masuk
    Route::get('/pesanan', [\App\Http\Controllers\CashierController::class, 'orders'])->name('orders');
    Route::post('/pesanan/{id}/pay', [\App\Http\Controllers\CashierController::class, 'confirmPayment'])->name('orders.pay');
    
    // Daftar Reservasi
    Route::get('/reservasi', [\App\Http\Controllers\CashierController::class, 'reservations'])->name('reservations');
});

// Route Auth bawaan Breeze/Jetstream akan ada di bawah sini
require __DIR__.'/auth.php';