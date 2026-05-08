<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Contracts\View\View;

final class FrontController extends Controller
{
    public function index(): View
    {
        // Fetch the latest published posts for the homepage
        $posts = Post::where('type', 'post')
            ->where('status', 'published')
            ->latest('published_at')
            ->paginate(10);

        return view('theme::index', compact('posts'));
    }

    public function show(string $slug): View
    {
        // Find the post or page by slug
        $post = Post::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        // Depending on the type, render a different view if it exists,
        // otherwise fallback to a generic single view.
        if ($post->type === 'page' && view()->exists('theme::page')) {
            return view('theme::page', compact('post'));
        }

        if (view()->exists('theme::single')) {
            return view('theme::single', compact('post'));
        }

        // Ultimate fallback
        return view('theme::index', ['posts' => collect([$post])]);
    }
}
