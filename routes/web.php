<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\Staff\ReservationApprovalController;
use Illuminate\Support\Facades\Route;

// Redirect Halaman Utama
Route::get('/', fn () => redirect()->route('facilities.index'));

// Guest Routes (Belum Login)
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Auth Routes (Umum)
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'active'])->name('dashboard');

// Facility Public Routes
Route::get('/facilities', [FacilityController::class, 'index'])->name('facilities.index');
Route::resource('facilities', FacilityController::class)
    ->except(['index', 'show'])
    ->middleware(['auth', 'active', 'role:ADMIN']);
Route::get('/facilities/{facility}', [FacilityController::class, 'show'])->name('facilities.show');

// Admin Routes
Route::middleware(['auth', 'active', 'role:ADMIN'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::patch('/users/{user}/verify', [UserController::class, 'verify'])->name('users.verify');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
});

// User Routes (Reservasi & Laporan Disatukan dalam 1 Group)
Route::middleware(['auth', 'active', 'role:USER'])->group(function () {
    // Management Reservasi User
    Route::get('/facilities/{facility}/reservations/create', [ReservationController::class, 'create'])->name('reservations.create');
    Route::post('/facilities/{facility}/reservations', [ReservationController::class, 'store'])->name('reservations.store');
    Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations.index');
    Route::patch('/reservations/{reservation}/cancel', [ReservationController::class, 'cancel'])->name('reservations.cancel');

    // Management Laporan User
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/create', [ReportController::class, 'create'])->name('reports.create');
    Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');
    Route::get('/reports/{report}', [ReportController::class, 'show'])->name('reports.show');
});

// Staff Routes (Persetujuan Reservasi & Penanganan Laporan)
Route::middleware(['auth', 'active', 'role:STAFF'])
    ->prefix('staff')
    ->name('staff.')
    ->group(function () {
        // Laporan Staff
        Route::get('/reports', [ReportController::class, 'staffIndex'])->name('reports.index');
        Route::get('/reports/{report}', [ReportController::class, 'staffShow'])->name('reports.show');
        Route::patch('/reports/{report}', [ReportController::class, 'update'])->name('reports.update');

        // Reservasi Staff (Approval & Emergency Cancellation)
        Route::get('/reservations', [ReservationApprovalController::class, 'index'])->name('reservations.index');
        Route::patch('/reservations/{id}/approve', [ReservationApprovalController::class, 'approve'])->name('reservations.approve');
        Route::patch('/reservations/{id}/reject', [ReservationApprovalController::class, 'reject'])->name('reservations.reject');
        Route::patch('/reservations/{id}/cancel', [ReservationApprovalController::class, 'staffCancel'])->name('reservations.cancel');
    });