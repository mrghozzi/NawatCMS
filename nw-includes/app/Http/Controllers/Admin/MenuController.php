<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Post;
use App\Services\MenuService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MenuController extends Controller
{
    public function index(): View
    {
        $menus = Menu::all();
        return view('admin::menus.index', compact('menus'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        Menu::create($validated);

        return redirect()->route('admin.menus.index')->with('success', 'Menu created successfully.');
    }

    public function show(Menu $menu): View
    {
        $posts = Post::where('status', 'published')->get();
        // Load items with their children for the builder view
        $menu->load('parentItems.children.post', 'parentItems.post');

        return view('admin::menus.builder', compact('menu', 'posts'));
    }

    public function update(Request $request, Menu $menu, MenuService $service): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
        ]);

        // If location is changing, we should nullify this location on other menus to keep it unique
        if (!empty($validated['location']) && $validated['location'] !== $menu->location) {
            Menu::where('location', $validated['location'])->update(['location' => null]);
            $service->clearCache($validated['location']);
        }

        if ($menu->location && empty($validated['location'])) {
             $service->clearCache($menu->location);
        }

        $menu->update($validated);

        return redirect()->back()->with('success', 'Menu updated successfully.');
    }

    public function destroy(Menu $menu, MenuService $service): RedirectResponse
    {
        if ($menu->location) {
            $service->clearCache($menu->location);
        }
        $menu->delete();

        return redirect()->route('admin.menus.index')->with('success', 'Menu deleted successfully.');
    }

    public function storeItem(Request $request, Menu $menu, MenuService $service): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:custom,page,post'],
            'url' => ['nullable', 'string', 'max:500'],
            'reference_id' => ['nullable', 'integer', 'exists:posts,id'],
            'parent_id' => ['nullable', 'integer', 'exists:menu_items,id'],
        ]);

        $menu->items()->create($validated);

        if ($menu->location) {
            $service->clearCache($menu->location);
        }

        return redirect()->back()->with('success', 'Menu item added.');
    }

    public function destroyItem(Menu $menu, MenuItem $item, MenuService $service): RedirectResponse
    {
        if ($item->menu_id !== $menu->id) {
            abort(403);
        }

        $item->delete();

        if ($menu->location) {
            $service->clearCache($menu->location);
        }

        return redirect()->back()->with('success', 'Menu item deleted.');
    }
}
