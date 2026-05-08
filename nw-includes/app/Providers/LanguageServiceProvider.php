<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class LanguageServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // 1. Add the custom JSON path for language files
        $languagePath = dirname(base_path()) . '/nw-content/languages';
        \Illuminate\Support\Facades\Lang::addJsonPath($languagePath);

        // 2. Set the application locale based on the database setting
        // The setting() helper handles try-catch internally if DB is missing
        $locale = setting('site_language', config('app.locale'));
        app()->setLocale($locale);
        app()->setFallbackLocale('en');
    }
}
