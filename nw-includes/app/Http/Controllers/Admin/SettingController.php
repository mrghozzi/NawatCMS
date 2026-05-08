<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SettingsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function index(SettingsService $settings): View
    {
        return view('admin::settings.index', [
            'settings' => $settings->all(),
        ]);
    }

    public function update(Request $request, SettingsService $settings): RedirectResponse
    {
        $validated = $request->validate([
            'site_name' => ['required', 'string', 'max:255'],
            'site_description' => ['nullable', 'string', 'max:500'],
            'admin_email' => ['required', 'email', 'max:255'],
        ]);

        $settings->setMany($validated);

        return redirect()->route('admin.settings.index')->with('success', 'Settings updated successfully.');
    }
}
