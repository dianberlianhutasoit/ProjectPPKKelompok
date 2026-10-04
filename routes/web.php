<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\Staff\ReservationApprovalController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('facilities.index'));

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'active'])->name('dashboard');

Route::get('/facilities', [FacilityController::class, 'index'])
    ->name('facilities.index');

Route::resource('facilities', FacilityController::class)
    ->except(['index', 'show'])
    ->middleware(['auth', 'active', 'role:ADMIN']);

// Guests see availability without requester details.
// Inactive facilities return 404 for non-admins.
Route::get('/facilities/{facility}', [FacilityController::class, 'show'])
    ->name('facilities.show');

Route::middleware(['auth', 'active', 'role:ADMIN'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::patch('/users/{user}/verify', [UserController::class, 'verify'])->name('users.verify');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
});

// Signed-in users manage their own reservations and reports.
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

Route::middleware(['auth', 'active', 'role:USER'])->group(function () {
    Route::get('/reports', [ReportController::class, 'index'])
        ->name('reports.index');

    Route::get('/reports/create', [ReportController::class, 'create'])
        ->name('reports.create');

    Route::post('/reports', [ReportController::class, 'store'])
        ->name('reports.store');

    Route::get('/reports/{report}', [ReportController::class, 'show'])
        ->name('reports.show');
});

Route::middleware(['auth', 'active', 'role:STAFF'])
    ->prefix('staff')
    ->name('staff.')
    ->group(function () {
        Route::get('/reports', [ReportController::class, 'staffIndex'])->name('reports.index');
        Route::get('/reports/{report}', [ReportController::class, 'staffShow'])->name('reports.show');
        Route::patch('/reports/{report}', [ReportController::class, 'update'])->name('reports.update');

        Route::get('/reservations', [ReservationApprovalController::class, 'index'])
            ->name('reservations.index');

        Route::patch('/reservations/{id}/approve', [ReservationApprovalController::class, 'approve'])
            ->name('reservations.approve');

        Route::patch('/reservations/{id}/reject', [ReservationApprovalController::class, 'reject'])
            ->name('reservations.reject');

        Route::patch('/reservations/{id}/cancel', [ReservationApprovalController::class, 'staffCancel'])
            ->name('reservations.cancel');
    });

    // Laporan untuk Pengguna Terautentikasi
Route::middleware(['auth', 'active'])->group(function () {
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/create', [ReportController::class, 'create'])->name('reports.create');
        Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');
    });
    
    // Pengelolaan Laporan khusus Staff & Admin
Route::middleware(['auth', 'active', 'role:STAFF,ADMIN'])->prefix('staff')->name('staff.')->group(function () {
        Route::patch('/reports/{report}/status', [ReportController::class, 'updateStatus'])->name('reports.updateStatus');
        Route::get('/reports/export-csv', [ReportController::class, 'exportCsv'])->name('reports.exportCsv');
    });