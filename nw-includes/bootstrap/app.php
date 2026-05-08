<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

require_once __DIR__ . '/../app/helpers.php';

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: [
            __DIR__.'/../routes/install.php',
            __DIR__.'/../routes/admin.php',
            __DIR__.'/../routes/web.php',
        ],
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectGuestsTo(fn () => route('admin.login'));
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

$app->usePublicPath(dirname($app->basePath()));

return $app;
