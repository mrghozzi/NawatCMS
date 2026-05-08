<?php

namespace Plugins\HelloWorld\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class PluginServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Register any plugin bindings here
    }

    public function boot(): void
    {
        \Illuminate\Support\Facades\Log::info('Hello World Plugin is Booted successfully!');
    }
}
