<?php

use App\Http\Controllers\Admin\AttendanceController as AdminAttendanceController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::middleware(['auth:admin',])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::prefix('user')
            ->name('user.')
            ->controller(UserController::class)
            ->group(function() {
                Route::get('/', 'index')->name('index');
                Route::get('dashboard', 'dashboard')->name('dashboard');
                Route::get('create', 'create')->name('create');
                Route::get('{user}', 'show')->name('show');
                Route::post('/', 'store')->name('store');
                Route::post('{attendance}', 'update')->name('update');
            });
        Route::prefix('attendances')
            ->name('attendances.')
            ->controller(AdminAttendanceController::class)
            ->group(function() {
                Route::get('/', 'index')->name('index');
                Route::get('{attendances}', 'show')->name('show');
                Route::get('create', 'create')->name('create');
                Route::post('/', 'store')->name('store');
                Route::post('{attendance}', 'update')->name('update');
            });
    });

Route::get('/admin', function () {
    return Inertia::render('Admin/Welcome', [
        'canLogin' => Route::has('admin.login'),
        'canRegister' => Route::has('admin.register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
        'user' => Auth::user(),
    ]);
});

Route::middleware('auth:admin')
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
