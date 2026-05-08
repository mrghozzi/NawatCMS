<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Services\EnvService;
use App\Services\ThemeService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;

final class ThemeController extends Controller
{
    public function __construct(
        private ThemeService $themeService,
        private EnvService $envService
    ) {}

    public function index(): View
    {
        $themes = $this->themeService->themes();
        $activeTheme = $this->themeService->activeTheme();

        return view('admin::themes.index', [
            'themes' => $themes,
            'activeTheme' => $activeTheme,
        ]);
    }

    public function activate(string $slug): RedirectResponse
    {
        // Verify the theme exists
        $themes = collect($this->themeService->themes());
        $themeExists = $themes->contains('slug', $slug);

        if (! $themeExists) {
            return redirect()->back()->withErrors(['error' => 'Theme not found.']);
        }

        // Update the .env file
        $this->envService->update([
            'NAWAT_ACTIVE_THEME' => $slug,
        ]);

        return redirect()->route('admin.themes.index')->with('success', 'Theme activated successfully.');
    }
}
