<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Models\Post;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

final class PageController extends Controller
{
    public function index(): View
    {
        $pages = Post::where('type', 'page')->latest()->paginate(20);
        return view('admin::pages.index', compact('pages'));
    }

    public function create(): View
    {
        return view('admin::pages.form', ['page' => new Post()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['nullable', 'string'],
            'status' => ['required', 'in:published,draft,trash'],
            'editor_type' => ['nullable', 'string', 'max:50'],
        ]);

        $page = new Post($validated);
        $page->author_id = Auth::id();
        $page->type = 'page';
        
        // Generate slug
        $slug = Str::slug($validated['title']);
        $count = Post::where('slug', 'LIKE', "{$slug}%")->count();
        $page->slug = $count ? "{$slug}-{$count}" : $slug;

        if ($validated['status'] === 'published') {
            $page->published_at = now();
        }

        $page->save();

        return redirect()->route('admin.pages.index')->with('success', 'Page created successfully.');
    }

    public function edit(Post $page): View
    {
        return view('admin::pages.form', compact('page'));
    }

    public function update(Request $request, Post $page): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['nullable', 'string'],
            'status' => ['required', 'in:published,draft,trash'],
            'editor_type' => ['nullable', 'string', 'max:50'],
        ]);

        // Re-generate slug if title changed
        if ($page->title !== $validated['title']) {
            $slug = Str::slug($validated['title']);
            $count = Post::where('slug', 'LIKE', "{$slug}%")->where('id', '!=', $page->id)->count();
            $page->slug = $count ? "{$slug}-{$count}" : $slug;
        }

        if ($page->status !== 'published' && $validated['status'] === 'published') {
            $page->published_at = now();
        }

        $page->update($validated);

        return redirect()->route('admin.pages.index')->with('success', 'Page updated successfully.');
    }

    public function destroy(Post $page): RedirectResponse
    {
        $page->delete();
        return redirect()->route('admin.pages.index')->with('success', 'Page deleted successfully.');
    }
}
