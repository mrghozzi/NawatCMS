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
