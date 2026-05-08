<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

final class SettingsService
{
    private const CACHE_KEY = 'nawat.settings';

    /**
     * Load all settings from the database and cache them.
     * @return array<string, string|null>
     */
    public function all(): array
    {
        return Cache::rememberForever(self::CACHE_KEY, function () {
            try {
                return Setting::pluck('value', 'key')->toArray();
            } catch (\Exception) {
                // In case the table doesn't exist yet (e.g., during initial setup or tests)
                return [];
            }
        });
    }

    /**
     * Get a specific setting value.
     */
    public function get(string $key, mixed $default = null): mixed
    {
        $settings = $this->all();

        return array_key_exists($key, $settings) ? $settings[$key] : $default;
    }

    /**
     * Set multiple settings.
     * @param array<string, string|null> $settings
     */
    public function setMany(array $settings): void
    {
        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        $this->clearCache();
    }

    /**
     * Set a single setting.
     */
    public function set(string $key, ?string $value): void
    {
        $this->setMany([$key => $value]);
    }

    public function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}
