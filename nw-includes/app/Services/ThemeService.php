<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\ThemeRepositoryInterface;

final readonly class ThemeService
{
    public function __construct(
        private ThemeRepositoryInterface $repository,
        private string $activeThemeSlug,
    ) {}

    /**
     * @return array<int, array{name: string, slug: string, path: string}>
     */
    public function themes(): array
    {
        return $this->repository->all();
    }

    /**
     * @return array{name: string, slug: string, path: string}|null
     */
    public function activeTheme(): ?array
    {
        return $this->repository->find($this->activeThemeSlug);
    }
}
