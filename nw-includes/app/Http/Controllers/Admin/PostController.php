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

final class PostController extends Controller
{
    public function index(): View
    {
        $posts = Post::where('type', 'post')->latest()->paginate(20);
        return view('admin::posts.index', compact('posts'));
    }

    public function create(): View
    {
        return view('admin::posts.form', ['post' => new Post()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['nullable', 'string'],
            'status' => ['required', 'in:published,draft,trash'],
        ]);

        $post = new Post($validated);
        $post->author_id = Auth::id();
        $post->type = 'post';
        
        // Generate slug
        $slug = Str::slug($validated['title']);
        $count = Post::where('slug', 'LIKE', "{$slug}%")->count();
        $post->slug = $count ? "{$slug}-{$count}" : $slug;

        if ($validated['status'] === 'published') {
            $post->published_at = now();
        }

        $post->save();

        return redirect()->route('admin.posts.index')->with('success', 'Post created successfully.');
    }

    public function edit(Post $post): View
    {
        return view('admin::posts.form', compact('post'));
    }

    public function update(Request $request, Post $post): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['nullable', 'string'],
            'status' => ['required', 'in:published,draft,trash'],
        ]);

        // Re-generate slug if title changed (optional, but good for SEO if strictly controlled)
        if ($post->title !== $validated['title']) {
            $slug = Str::slug($validated['title']);
            $count = Post::where('slug', 'LIKE', "{$slug}%")->where('id', '!=', $post->id)->count();
            $post->slug = $count ? "{$slug}-{$count}" : $slug;
        }

        if ($post->status !== 'published' && $validated['status'] === 'published') {
            $post->published_at = now();
        }

        $post->update($validated);

        return redirect()->route('admin.posts.index')->with('success', 'Post updated successfully.');
    }

    public function destroy(Post $post): RedirectResponse
    {
        $post->delete();
        return redirect()->route('admin.posts.index')->with('success', 'Post deleted successfully.');
    }
}
