<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\AuthController;

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

// Route Auth bawaan Breeze/Jetstream akan ada di bawah sini
require __DIR__.'/auth.php';