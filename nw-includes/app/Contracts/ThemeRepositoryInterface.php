<?php

declare(strict_types=1);

namespace App\Contracts;

interface ThemeRepositoryInterface
{
    /**
     * @return array<int, array{name: string, slug: string, path: string}>
     */
    public function all(): array;

    /**
     * @return array{name: string, slug: string, path: string}|null
     */
    public function find(string $slug): ?array;
}
