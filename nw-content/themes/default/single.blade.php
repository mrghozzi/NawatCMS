<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $post->title }} - {{ config('app.name', 'Nawat CMS') }}</title>
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
            padding: 64px 48px;
            max-width: 800px;
            margin: 0 auto;
            width: 100%;
            box-sizing: border-box;
        }
        article {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 48px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }
        h1 {
            font-size: 40px;
            margin-top: 0;
            margin-bottom: 16px;
            font-weight: 800;
            line-height: 1.2;
        }
        .meta {
            color: #6b7280;
            font-size: 14px;
            margin-bottom: 32px;
            padding-bottom: 24px;
            border-bottom: 1px solid #e5e7eb;
        }
        .content {
            font-size: 18px;
            line-height: 1.7;
            color: #374151;
        }
        .content p {
            margin-bottom: 24px;
        }
        footer {
            background-color: #ffffff;
            border-top: 1px solid #e5e7eb;
            padding: 32px 48px;
            text-align: center;
            color: #6b7280;
            font-size: 14px;
            margin-top: auto;
        }
        .back-link {
            display: inline-block;
            margin-bottom: 24px;
            color: #087f8c;
            text-decoration: none;
            font-weight: 500;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <header>
        <a href="/" class="logo">{{ config('app.name', 'Nawat CMS') }}</a>
        <nav>
            <a href="/" style="color: #4b5563; text-decoration: none; font-weight: 500;">Home</a>
        </nav>
    </header>

    <main>
        <a href="/" class="back-link">&larr; Back to Home</a>
        <article>
            <h1>{{ $post->title }}</h1>
            <div class="meta">
                Published by {{ $post->author->name }} on {{ $post->published_at?->format('F j, Y') ?? $post->created_at->format('F j, Y') }}
                @if($post->type === 'page')
                    &bull; Page
                @endif
            </div>
            <div class="content">
                {!! nl2br(e($post->content)) !!}
            </div>
        </article>
    </main>

    <footer>
        &copy; {{ date('Y') }} {{ config('app.name', 'Nawat CMS') }}. Powered by Nawat CMS.
    </footer>
</body>
</html>
