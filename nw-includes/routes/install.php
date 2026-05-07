<?php

declare(strict_types=1);

use App\Http\Controllers\Install\WelcomeController;
use Illuminate\Support\Facades\Route;

Route::get('/install', WelcomeController::class)->name('install.index');
