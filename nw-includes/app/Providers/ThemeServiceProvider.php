<?php

declare(strict_types=1);

namespace App\Providers;

use App\Contracts\ThemeRepositoryInterface;
use App\Repositories\FilesystemThemeRepository;
use App\Services\NawatPathService;
use App\Services\ThemeService;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

final class ThemeServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(ThemeRepositoryInterface::class, function (Application $app): ThemeRepositoryInterface {
            $paths = $app->make(NawatPathService::class);

            return new FilesystemThemeRepository($paths->themes());
        });

        $this->app->singleton(ThemeService::class, function (Application $app): ThemeService {
            return new ThemeService(
                repository: $app->make(ThemeRepositoryInterface::class),
                activeThemeSlug: (string) config('nawat.active_theme', 'default'),
            );
        });
    }
}
