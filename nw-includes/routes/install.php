<?php

declare(strict_types=1);

use App\Http\Controllers\Install\WelcomeController;
use App\Http\Controllers\Install\EnvironmentController;
use App\Http\Controllers\Install\DatabaseController;
use App\Http\Controllers\Install\AdminController;
use Illuminate\Support\Facades\Route;

Route::get('/install', WelcomeController::class)->name('install.index');

Route::get('/install/environment', [EnvironmentController::class, 'index'])->name('install.environment');
Route::post('/install/environment', [EnvironmentController::class, 'store'])->name('install.environment.store');

Route::get('/install/database', [DatabaseController::class, 'index'])->name('install.database');
Route::post('/install/database', [DatabaseController::class, 'store'])->name('install.database.store');

Route::get('/install/admin', [AdminController::class, 'index'])->name('install.admin');
Route::post('/install/admin', [AdminController::class, 'store'])->name('install.admin.store');

Route::get('/install/success', [AdminController::class, 'success'])->name('install.success');
