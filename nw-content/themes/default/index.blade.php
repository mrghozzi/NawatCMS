<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Nawat CMS') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9fafb;
            color: #111827;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        header {
            background-color: #ffffff;
            border-bottom: 1px solid #e5e7eb;
            padding: 24px 48px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .logo {
            font-size: 24px;
            font-weight: 700;
            color: #087f8c; /* SuperDesign Accent */
            text-decoration: none;
        }
        main {
            flex: 1;
            padding: 48px;
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
            box-sizing: border-box;
        }
        .hero {
            text-align: center;
            padding: 64px 0;
        }
        h1 {
            font-size: 48px;
            margin-bottom: 16px;
            font-weight: 800;
        }
        p.subtitle {
            font-size: 20px;
            color: #6b7280;
            max-width: 600px;
            margin: 0 auto 32px;
            line-height: 1.6;
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 24px;
            margin-top: 48px;
        }
        .card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }
        .card h3 {
            margin-top: 0;
            font-size: 20px;
        }
        .card p {
            color: #6b7280;
            line-height: 1.5;
        }
        footer {
            background-color: #ffffff;
            border-top: 1px solid #e5e7eb;
            padding: 32px 48px;
            text-align: center;
            color: #6b7280;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <header>
        <a href="/" class="logo">{{ config('app.name', 'Nawat CMS') }}</a>
        <nav>
            <a href="#" style="color: #4b5563; text-decoration: none; font-weight: 500;">Home</a>
        </nav>
    </header>

    <main>
        <div class="hero">
            <h1>{{ setting('site_name', config('app.name', 'Nawat CMS')) }}</h1>
            <p class="subtitle">{{ setting('site_description', 'Welcome to your new website powered by Nawat CMS.') }}</p>
        </div>

        <div class="grid">
            @forelse($posts as $post)
                <div class="card">
                    <h3><a href="{{ route('single', $post->slug) }}" style="color: inherit; text-decoration: none;">{{ $post->title }}</a></h3>
                    <div style="font-size: 13px; color: #9ca3af; margin-bottom: 12px;">{{ $post->published_at?->format('M d, Y') ?? $post->created_at->format('M d, Y') }}</div>
                    <p>{{ Str::limit(strip_tags($post->content), 120) }}</p>
                    <a href="{{ route('single', $post->slug) }}" style="color: #087f8c; text-decoration: none; font-weight: 500; font-size: 14px; display: inline-block; margin-top: 12px;">Read more &rarr;</a>
                </div>
            @empty
                <div class="card" style="grid-column: 1 / -1; text-align: center; padding: 48px;">
                    <h3>No posts yet.</h3>
                    <p>When you publish posts, they will appear here.</p>
                </div>
            @endforelse
        </div>

        @if(isset($posts) && method_exists($posts, 'links'))
            <div style="margin-top: 48px; text-align: center;">
                {{ $posts->links() }}
            </div>
        @endif
    </main>

    <footer>
        &copy; {{ date('Y') }} {{ setting('site_name', config('app.name', 'Nawat CMS')) }}. Powered by Nawat CMS.
    </footer>
</body>
</html>
