<?php

declare(strict_types=1);

namespace App\Http\Controllers\Install;

use App\Models\User;
use App\Services\EnvService;
use App\Services\NawatPathService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;

final class AdminController extends Controller
{
    public function __construct(
        private EnvService $envService,
        private NawatPathService $pathService
    ) {}

    public function index(): View|RedirectResponse
    {
        if (file_exists($this->pathService->engine('storage/.install-lock'))) {
            return redirect('/');
        }

        return view('admin::install.admin');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Create the admin user
        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'email_verified_at' => now(),
        ]);

        // Lock the installer
        file_put_contents($this->pathService->engine('storage/.install-lock'), date('Y-m-d H:i:s'));

        // Reset session driver to database if preferred, or keep as file
        // For now let's keep it as file as set in the previous step, 
        // or we could set it to database now that tables exist.
        $this->envService->update([
            'SESSION_DRIVER' => 'database',
        ]);

        return redirect()->route('install.success');
    }

    public function success(): View
    {
        return view('admin::install.success');
    }
}
