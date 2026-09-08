<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\UserController;

Route::get('/', function () {
    return view('welcome');
});

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
Route::resource('facilities', FacilityController::class)->only(['index', 'show']);

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
});

use App\Http\Controllers\ReservationController;

Route::middleware(['auth'])->group(function () {
    // Route Pengguna
    Route::patch('/reservasi/{id}/batal-user', [ReservationController::class, 'cancelByUser'])->name('reservations.cancelByUser');
    Route::post('/reservasi', [ReservationController::class, 'store'])->name('reservations.store');
    // Route Petugas
    Route::middleware(['role:petugas'])->group(function () {
        Route::patch('/petugas/reservasi/{id}/setujui', [ReservationController::class, 'approveByPetugas'])->name('reservations.approve');
        Route::patch('/petugas/reservasi/{id}/tolak', [ReservationController::class, 'rejectByPetugas'])->name('reservations.reject');
        Route::patch('/petugas/reservasi/{id}/batal', [ReservationController::class, 'cancelByPetugas'])->name('reservations.cancelByPetugas');
    });
});