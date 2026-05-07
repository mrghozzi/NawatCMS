<?php

declare(strict_types=1);

namespace App\Http\Controllers\Install;

use App\Services\EnvService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

final class EnvironmentController extends Controller
{
    public function __construct(
        private EnvService $envService
    ) {}

    public function index(): View
    {
        return view('admin::install.environment', [
            'appName' => $this->envService->get('APP_NAME', 'Nawat CMS'),
            'appUrl' => $this->envService->get('APP_URL', request()->getSchemeAndHttpHost()),
            'appEnv' => $this->envService->get('APP_ENV', 'local'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'app_name' => ['required', 'string', 'max:255'],
            'app_url' => ['required', 'url', 'max:255'],
            'app_env' => ['required', 'in:local,production'],
        ]);

        $this->envService->update([
            'APP_NAME' => $validated['app_name'],
            'APP_URL' => $validated['app_url'],
            'APP_ENV' => $validated['app_env'],
            'APP_DEBUG' => $validated['app_env'] === 'local' ? 'true' : 'false',
        ]);

        return redirect()->route('install.database');
    }
}
