<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontController;

Route::get('/', [FrontController::class, 'index'])->name('home');

// Catch-all route for posts and pages
// It should be registered last to avoid conflicts with other static routes (like /install, /admin)
Route::get('/{slug}', [FrontController::class, 'show'])
    ->where('slug', '^(?!admin|install).*$')
    ->name('single');
