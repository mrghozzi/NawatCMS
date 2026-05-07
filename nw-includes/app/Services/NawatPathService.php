<?php

declare(strict_types=1);

namespace App\Services;

final readonly class NawatPathService
{
    public function __construct(
        private string $rootPath,
        private string $enginePath,
    ) {}

    public function root(string $path = ''): string
    {
        return $this->join($this->rootPath, $path);
    }

    public function engine(string $path = ''): string
    {
        return $this->join($this->enginePath, $path);
    }

    public function admin(string $path = ''): string
    {
        return $this->root('nw-admin'.($path !== '' ? DIRECTORY_SEPARATOR.$path : ''));
    }

    public function content(string $path = ''): string
    {
        return $this->root('nw-content'.($path !== '' ? DIRECTORY_SEPARATOR.$path : ''));
    }

    public function themes(string $path = ''): string
    {
        return $this->content('themes'.($path !== '' ? DIRECTORY_SEPARATOR.$path : ''));
    }

    public function plugins(string $path = ''): string
    {
        return $this->content('plugins'.($path !== '' ? DIRECTORY_SEPARATOR.$path : ''));
    }

    public function uploads(string $path = ''): string
    {
        return $this->content('uploads'.($path !== '' ? DIRECTORY_SEPARATOR.$path : ''));
    }

    public function languages(string $path = ''): string
    {
        return $this->content('languages'.($path !== '' ? DIRECTORY_SEPARATOR.$path : ''));
    }

    private function join(string $basePath, string $path): string
    {
        if ($path === '') {
            return rtrim($basePath, '/\\');
        }

        return rtrim($basePath, '/\\').DIRECTORY_SEPARATOR.ltrim($path, '/\\');
    }
}
