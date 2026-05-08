<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
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
        $categories = Category::with('children')->whereNull('parent_id')->get();
        return view('admin::posts.form', ['post' => new Post(), 'categories' => $categories]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['nullable', 'string'],
            'editor_type' => ['nullable', 'string'],
            'status' => ['required', 'in:published,draft,trash'],
            'categories' => ['nullable', 'array'],
            'categories.*' => ['exists:categories,id'],
            'tags' => ['nullable', 'string'],
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

        if (isset($validated['categories'])) {
            $post->categories()->sync($validated['categories']);
        }

        if (isset($validated['tags'])) {
            $tagNames = array_map('trim', explode(',', $validated['tags']));
            $tagIds = [];
            foreach ($tagNames as $tagName) {
                if (!empty($tagName)) {
                    $tagSlug = Str::slug($tagName) ?: str_replace(' ', '-', $tagName);
                    $tag = Tag::firstOrCreate(['slug' => $tagSlug], ['name' => $tagName]);
                    $tagIds[] = $tag->id;
                }
            }
            $post->tags()->sync($tagIds);
        }

        return redirect()->route('admin.posts.index')->with('success', 'Post created successfully.');
    }

    public function edit(Post $post): View
    {
        $categories = Category::with('children')->whereNull('parent_id')->get();
        return view('admin::posts.form', compact('post', 'categories'));
    }

    public function update(Request $request, Post $post): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['nullable', 'string'],
            'editor_type' => ['nullable', 'string'],
            'status' => ['required', 'in:published,draft,trash'],
            'categories' => ['nullable', 'array'],
            'categories.*' => ['exists:categories,id'],
            'tags' => ['nullable', 'string'],
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

        if (isset($validated['categories'])) {
            $post->categories()->sync($validated['categories']);
        } else {
            $post->categories()->detach();
        }

        if (isset($validated['tags'])) {
            $tagNames = array_map('trim', explode(',', $validated['tags']));
            $tagIds = [];
            foreach ($tagNames as $tagName) {
                if (!empty($tagName)) {
                    $tagSlug = Str::slug($tagName) ?: str_replace(' ', '-', $tagName);
                    $tag = Tag::firstOrCreate(['slug' => $tagSlug], ['name' => $tagName]);
                    $tagIds[] = $tag->id;
                }
            }
            $post->tags()->sync($tagIds);
        } else {
            $post->tags()->detach();
        }

        return redirect()->route('admin.posts.index')->with('success', 'Post updated successfully.');
    }

    public function destroy(Post $post): RedirectResponse
    {
        $post->delete();
        return redirect()->route('admin.posts.index')->with('success', 'Post deleted successfully.');
    }
}
