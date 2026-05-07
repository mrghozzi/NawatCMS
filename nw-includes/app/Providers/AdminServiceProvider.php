<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\AdminAssetService;
use App\Services\NawatPathService;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

final class AdminServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(AdminAssetService::class, function (Application $app): AdminAssetService {
            $paths = $app->make(NawatPathService::class);

            return new AdminAssetService($paths->root());
        });
    }

    public function boot(): void
    {
        $paths = $this->app->make(NawatPathService::class);

        $this->loadViewsFrom($paths->admin('views'), 'admin');

        View::share('adminAssets', $this->app->make(AdminAssetService::class));
    }
}
