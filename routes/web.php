<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ReservationController;

Route::get('/', fn() => redirect()->route('facilities.index'));

// Daftar sendiri khusus USER (langsung PENDING), + login & logout
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Setelah login diarahkan sesuai role-nya
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'active'])->name('dashboard');

// Siapa aja boleh lihat daftar & detail fasilitas
Route::get('/facilities', [FacilityController::class, 'index'])->name('facilities.index');
Route::get('/facilities/{facility}', [FacilityController::class, 'show'])->middleware('auth')->name('facilities.show');

// Cuma admin yang boleh tambah / edit / nonaktifkan fasilitas
Route::resource('facilities', FacilityController::class)
    ->except(['index', 'show'])
    ->middleware(['auth', 'active', 'role:ADMIN']);

// Cuma admin: kelola akun & verifikasi pendaftar baru
Route::middleware(['auth', 'active', 'role:ADMIN'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::patch('/users/{user}/verify', [UserController::class, 'verify'])->name('users.verify');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
});

// Reservasi khusus USER
Route::middleware(['auth', 'active', 'role:USER'])->group(function () {
    Route::get('/facilities/{facility}/reservations/create', [ReservationController::class, 'create'])
        ->name('reservations.create');

    Route::post('/facilities/{facility}/reservations', [ReservationController::class, 'store'])
        ->name('reservations.store');

    Route::get('/reservations', [ReservationController::class, 'index'])
        ->name('reservations.index');

    Route::patch('/reservations/{reservation}/cancel', [ReservationController::class, 'cancel'])
        ->name('reservations.cancel');
});

// Pengelolaan reservasi oleh STAFF dan ADMIN
Route::middleware(['auth', 'active', 'role:STAFF,ADMIN'])
    ->prefix('staff')
    ->name('staff.')
    ->group(function () {
        Route::get('/reservations', [ReservationController::class, 'staffIndex'])
            ->name('reservations.index');

        Route::patch('/reservations/{reservation}/approve', [ReservationController::class, 'approve'])
            ->name('reservations.approve');

        Route::patch('/reservations/{reservation}/reject', [ReservationController::class, 'reject'])
            ->name('reservations.reject');

        Route::patch('/reservations/{reservation}/cancel', [ReservationController::class, 'staffCancel'])
            ->name('reservations.cancel');
    });
