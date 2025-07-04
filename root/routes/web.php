<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VariousApplicationsController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::middleware(['auth'])
    ->prefix('attendances')
    ->name('attendances.')
    ->group(function () {
        Route::controller(AttendanceController::class)
            ->group(function() {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/', 'store')->name('store');
                Route::post('/{attendance}', 'update')->name('update');
            });

        Route::prefix('variousApplications')
            ->name('variousApplications.')
            ->controller(VariousApplicationsController::class)
            ->group(function() {
                Route::get('/', 'index')->name('index');
                Route::get('/list', 'list')->name('list');
            });

        Route::prefix('expenses')
            ->name('expenses.')
            ->controller(ExpenseController::class)
            ->group(function() {
                Route::get('/', 'create')->name('create');
                Route::post('/store', 'store')->name('store');
            });
    });


Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
        'user' => Auth::user(),
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard', ['user' => Auth::user()]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/Admin/admin.php';
require __DIR__ . '/auth.php';
