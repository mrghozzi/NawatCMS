@extends('admin::layouts.admin')

@section('title', 'Themes')
@section('page_title', 'Themes')

@section('content')
    <style>
        .themes-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 24px;
        }

        .theme-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .theme-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow);
        }

        .theme-screenshot {
            width: 100%;
            height: 200px;
            background-color: #f3f4f6;
            background-size: cover;
            background-position: top center;
            border-bottom: 1px solid var(--border);
        }

        .theme-screenshot.no-image {
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--muted);
            font-size: 14px;
        }

        .theme-info {
            padding: 20px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .theme-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 8px;
        }

        .theme-name {
            font-size: 18px;
            font-weight: 600;
            margin: 0;
            color: var(--text);
        }

        .theme-badge {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            padding: 4px 8px;
            border-radius: 4px;
            letter-spacing: 0.05em;
        }

        .theme-badge.active {
            background: rgba(8, 127, 140, 0.1);
            color: var(--accent);
            border: 1px solid rgba(8, 127, 140, 0.2);
        }

        .theme-slug {
            font-family: 'JetBrains Mono', monospace;
            font-size: 12px;
            color: var(--muted);
            margin-bottom: 16px;
        }

        .theme-actions {
            margin-top: auto;
            padding-top: 16px;
            display: flex;
            gap: 12px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px 16px;
            font-size: 14px;
            font-weight: 500;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            border: 1px solid transparent;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-hover);
        }

        .btn-outline {
            background: transparent;
            border-color: var(--border);
            color: var(--text);
        }

        .btn-outline:hover {
            background: var(--bg);
            border-color: #d1d5db;
        }

        .btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
    </style>

    @if(session('success'))
        <div style="background: rgba(31, 138, 91, 0.1); color: var(--success); border: 1px solid rgba(31, 138, 91, 0.2); padding: 12px 16px; border-radius: 8px; margin-bottom: 24px;">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div style="background: rgba(229, 62, 62, 0.1); color: #e53e3e; border: 1px solid rgba(229, 62, 62, 0.2); padding: 12px 16px; border-radius: 8px; margin-bottom: 24px;">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="themes-grid">
        @foreach($themes as $theme)
            @php
                $isActive = $activeTheme && $activeTheme['slug'] === $theme['slug'];
                
                // Construct screenshot URL
                // In a real scenario, this would use an asset helper mapping to the theme folder
                // Since themes are in nw-content, we'd need a route or symlink.
                // For now, we'll try to guess if it exists locally to show a placeholder.
                $hasScreenshot = file_exists($theme['path'] . '/screenshot.png');
                $screenshotUrl = $hasScreenshot ? asset('nw-content/themes/' . $theme['slug'] . '/screenshot.png') : null;
            @endphp

            <div class="theme-card {{ $isActive ? 'active-theme' : '' }}">
                @if($hasScreenshot)
                    <div class="theme-screenshot" style="background-image: url('{{ $screenshotUrl }}');"></div>
                @else
                    <div class="theme-screenshot no-image">
                        No Preview Available
                    </div>
                @endif
                
                <div class="theme-info">
                    <div class="theme-header">
                        <h3 class="theme-name">{{ $theme['name'] }}</h3>
                        @if($isActive)
                            <span class="theme-badge active">Active</span>
                        @endif
                    </div>
                    
                    <div class="theme-slug">{{ $theme['slug'] }}</div>

                    <div class="theme-actions">
                        @if($isActive)
                            <button class="btn btn-primary" disabled>Active Theme</button>
                        @else
                            <form action="{{ route('admin.themes.activate', $theme['slug']) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline">Activate</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
