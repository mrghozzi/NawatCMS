<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\ThemeRepositoryInterface;
use JsonException;

final readonly class FilesystemThemeRepository implements ThemeRepositoryInterface
{
    public function __construct(private string $themesPath) {}

    public function all(): array
    {
        if (! is_dir($this->themesPath)) {
            return [];
        }

        $themes = [];

        foreach ((scandir($this->themesPath) ?: []) as $entry) {
            if ($entry === '.' || $entry === '..' || str_starts_with($entry, '.')) {
                continue;
            }

            $path = $this->themesPath.DIRECTORY_SEPARATOR.$entry;

            if (! is_dir($path)) {
                continue;
            }

            $manifest = $this->readManifest($path);

            $themes[] = [
                'name' => $manifest['name'] ?? $this->formatThemeName($entry),
                'slug' => $entry,
                'path' => $path,
            ];
        }

        return $themes;
    }

    public function find(string $slug): ?array
    {
        foreach ($this->all() as $theme) {
            if ($theme['slug'] === $slug) {
                return $theme;
            }
        }

        return null;
    }

    /**
     * @return array{name?: string}
     */
    private function readManifest(string $themePath): array
    {
        $manifestPath = $themePath.DIRECTORY_SEPARATOR.'theme.json';

        if (! is_file($manifestPath)) {
            return [];
        }

        try {
            $manifest = json_decode((string) file_get_contents($manifestPath), true, flags: JSON_THROW_ON_ERROR);
        } catch (JsonException) {
            return [];
        }

        return is_array($manifest) ? $manifest : [];
    }

    private function formatThemeName(string $slug): string
    {
        return ucwords(str_replace(['-', '_'], ' ', $slug));
    }
}
