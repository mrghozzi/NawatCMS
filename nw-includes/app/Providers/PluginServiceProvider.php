<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Services\PluginService;
use Illuminate\Support\Str;

class PluginServiceProvider extends ServiceProvider
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
        try {
            /** @var PluginService $pluginService */
            $pluginService = app(PluginService::class);
            $plugins = $pluginService->discoverPlugins();
            $activeSlugs = $pluginService->getActivePlugins();

            $basePath = dirname(base_path()) . '/nw-content/plugins';

            foreach ($plugins as $slug => $plugin) {
                if (!in_array($slug, $activeSlugs)) {
                    continue;
                }

                // 1. Dynamic Autoloader for the plugin's namespace
                // We assume the namespace is "Plugins\StudlySlug\" pointing to "nw-content/plugins/slug/src/"
                $studlySlug = Str::studly($slug);
                $namespacePrefix = "Plugins\\{$studlySlug}\\";
                $srcDirectory = "{$basePath}/{$slug}/src/";

                spl_autoload_register(function ($class) use ($namespacePrefix, $srcDirectory) {
                    if (str_starts_with($class, $namespacePrefix)) {
                        $relativeClass = substr($class, strlen($namespacePrefix));
                        $file = $srcDirectory . str_replace('\\', '/', $relativeClass) . '.php';
                        if (file_exists($file)) {
                            require_once $file;
                        }
                    }
                });

                // 2. Register the Plugin's main Service Provider
                if (isset($plugin['provider']) && class_exists($plugin['provider'])) {
                    $this->app->register($plugin['provider']);
                }
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('PluginServiceProvider Error: ' . $e->getMessage());
        }
    }
}
