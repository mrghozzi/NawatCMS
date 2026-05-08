<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Support\Facades\Cache;

final class MenuService
{
    /**
     * Get a menu by its location, fully eager loaded.
     */
    public function getMenuByLocation(string $location): ?Menu
    {
        $cacheKey = "nawat.menu.{$location}";

        return Cache::rememberForever($cacheKey, function () use ($location) {
            try {
                return Menu::where('location', $location)
                    ->with(['parentItems.children', 'parentItems.post', 'parentItems.children.post'])
                    ->first();
            } catch (\Exception) {
                return null;
            }
        });
    }

    /**
     * Clear cache for a specific menu location.
     */
    public function clearCache(string $location): void
    {
        Cache::forget("nawat.menu.{$location}");
    }

    /**
     * Resolve the actual URL for a menu item based on its type.
     */
    public function resolveUrl(MenuItem $item): string
    {
        if ($item->type === 'custom') {
            return $item->url ?? '#';
        }

        if ($item->post) {
            return route('single', $item->post->slug);
        }

        return '#';
    }
}
