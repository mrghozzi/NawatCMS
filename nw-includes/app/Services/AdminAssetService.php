<?php

declare(strict_types=1);

namespace App\Services;

final readonly class AdminAssetService
{
    private const BASE_PATH = 'nw-admin/assets';

    public function __construct(private string $publicRoot) {}

    public function path(string $path = ''): string
    {
        $relativePath = trim(self::BASE_PATH.'/'.ltrim($path, '/\\'), '/\\');

        return rtrim($this->publicRoot, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.str_replace(
            ['/', '\\'],
            DIRECTORY_SEPARATOR,
            $relativePath,
        );
    }

    public function url(string $path): string
    {
        return asset(self::BASE_PATH.'/'.ltrim($path, '/\\'));
    }
}
