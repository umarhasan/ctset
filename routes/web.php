<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Assessor\AssessorDashboardController;
use App\Http\Controllers\Trainee\TraineeDashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;

Route::get('/', function () {
    return view('welcome');
});

    Route::middleware(['auth'])->group(function () {
          Route::resource('roles', RoleController::class);
            Route::resource('permissions', PermissionController::class);
            Route::resource('users', UserController::class);
        
        Route::prefix('profile')->group(function () {
            Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
            Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
            Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
        });

    
        Route::prefix('admin')->name('admin.')->group(function () {
            Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        });

        Route::prefix('assessor')->name('assessor.')->group(function () {
            Route::get('/dashboard', [AssessorDashboardController::class, 'index'])->name('dashboard');
        });

        Route::prefix('trainee')->name('trainee.')->group(function () {
            Route::get('/dashboard', [TraineeDashboardController::class, 'index'])->name('dashboard');
        });
    });

require __DIR__.'/auth.php';
