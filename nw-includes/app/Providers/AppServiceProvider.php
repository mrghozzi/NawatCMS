<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\NawatPathService;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(NawatPathService::class, function (Application $app): NawatPathService {
            return new NawatPathService(
                rootPath: dirname($app->basePath()),
                enginePath: $app->basePath(),
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
