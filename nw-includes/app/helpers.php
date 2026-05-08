<?php

declare(strict_types=1);

use App\Services\SettingsService;

if (! function_exists('setting')) {
    /**
     * Get a setting value.
     */
    function setting(string $key, mixed $default = null): mixed
    {
        try {
            /** @var SettingsService $service */
            $service = app(SettingsService::class);
            return $service->get($key, $default);
        } catch (\Exception) {
            // Fallback if container is not ready
            return $default;
        }
    }
}

if (! function_exists('nawat_menu')) {
    /**
     * Get a menu tree by location.
     */
    function nawat_menu(string $location): ?\App\Models\Menu
    {
        try {
            /** @var \App\Services\MenuService $service */
            $service = app(\App\Services\MenuService::class);
            return $service->getMenuByLocation($location);
        } catch (\Exception) {
            return null;
        }
    }
}

if (! function_exists('nawat_menu_url')) {
    /**
     * Resolve the URL for a menu item.
     */
    function nawat_menu_url(\App\Models\MenuItem $item): string
    {
        try {
            /** @var \App\Services\MenuService $service */
            $service = app(\App\Services\MenuService::class);
            return $service->resolveUrl($item);
        } catch (\Exception) {
            return '#';
        }
    }
}

