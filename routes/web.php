<?php

use App\Http\Controllers\Admin\AnalyticsController;
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

// =========================================================
// KELOLA FASILITAS (ADMIN) - Wajib Di atas Rute Publik {facility}
// =========================================================
Route::middleware(['auth', 'active', 'role:ADMIN'])->group(function () {
    Route::get('/facilities/create', [FacilityController::class, 'create'])->name('facilities.create');
    Route::post('/facilities', [FacilityController::class, 'store'])->name('facilities.store');
    Route::get('/facilities/{facility}/edit', [FacilityController::class, 'edit'])->name('facilities.edit');
    Route::put('/facilities/{facility}', [FacilityController::class, 'update'])->name('facilities.update');
    Route::delete('/facilities/{facility}', [FacilityController::class, 'destroy'])->name('facilities.destroy');
});

// =========================================================
// FASILITAS PUBLIK (UMUM)
// =========================================================
Route::get('/facilities', [FacilityController::class, 'index'])->name('facilities.index');
Route::get('/facilities/{facility}', [FacilityController::class, 'show'])->name('facilities.show');

// =========================================================
// ADMIN ROUTES (KELOLA USER & REKAP OKUPANSI / ANALYTICS)
// =========================================================
Route::middleware(['auth', 'active', 'role:ADMIN'])->prefix('admin')->name('admin.')->group(function () {
    // Kelola User
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::patch('/users/{user}/verify', [UserController::class, 'verify'])->name('users.verify');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    // Halaman Tersendiri Rekap Okupansi & Frekuensi Kerusakan (Khusus Admin)
    Route::get('/analytics', 'App\\Http\\Controllers\\Admin\\AnalyticsController@index')->name('analytics.index');
    Route::get('/analytics/export-csv', 'App\\Http\\Controllers\\Admin\\AnalyticsController@exportCsv')->name('analytics.exportCsv');
});

// =========================================================
// USER ROUTES (RESERVASI & LAPORAN USER)
// =========================================================
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

// =========================================================
// STAFF WORKSPACE (PERSETUJUAN RESERVASI & PENANGANAN LAPORAN)
// =========================================================
Route::middleware(['auth', 'active', 'role:STAFF'])->prefix('staff')->name('staff.')->group(function () {
    // Management Laporan Staff (Export CSV wajib ditaruh sebelum {report})
    Route::get('/reports/export-csv', [ReportController::class, 'exportCsv'])->name('reports.exportCsv');
    Route::get('/reports', [ReportController::class, 'staffIndex'])->name('reports.index');
    Route::get('/reports/{report}', [ReportController::class, 'staffShow'])->name('reports.show');
    Route::patch('/reports/{report}', [ReportController::class, 'update'])->name('reports.update');
    Route::patch('/reports/{report}/status', [ReportController::class, 'updateStatus'])->name('reports.updateStatus');

    // Persetujuan Reservasi Staff
    Route::get('/reservations', [ReservationApprovalController::class, 'index'])->name('reservations.index');
    Route::patch('/reservations/{id}/approve', [ReservationApprovalController::class, 'approve'])->name('reservations.approve');
    Route::patch('/reservations/{id}/reject', [ReservationApprovalController::class, 'reject'])->name('reservations.reject');
    Route::patch('/reservations/{id}/cancel', [ReservationApprovalController::class, 'staffCancel'])->name('reservations.cancel');
});
