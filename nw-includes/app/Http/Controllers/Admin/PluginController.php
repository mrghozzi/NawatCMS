<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\PluginService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PluginController extends Controller
{
    public function index(PluginService $pluginService): View
    {
        $plugins = $pluginService->discoverPlugins();
        return view('admin::plugins.index', compact('plugins'));
    }

    public function toggle(Request $request, string $slug, PluginService $pluginService): RedirectResponse
    {
        $action = $request->input('action'); // 'activate' or 'deactivate'

        if ($action === 'activate') {
            $pluginService->activate($slug);
            return redirect()->back()->with('success', 'Plugin activated successfully.');
        } elseif ($action === 'deactivate') {
            $pluginService->deactivate($slug);
            return redirect()->back()->with('success', 'Plugin deactivated successfully.');
        }

        return redirect()->back()->with('error', 'Invalid action.');
    }
}
