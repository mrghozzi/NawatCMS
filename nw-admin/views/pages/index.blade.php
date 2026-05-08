@extends('admin::layouts.admin')

@section('title', 'Pages')
@section('page_title', 'Pages')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <h2 style="margin: 0; font-size: 20px;">All Pages <span lang="ar" dir="rtl" style="font-size: 14px; color: var(--muted); margin-left: 8px;">جميع الصفحات</span></h2>
        <a href="{{ route('admin.pages.create') }}" class="primary-action" style="text-decoration: none; display: inline-block;">
            Add New <span lang="ar" dir="rtl">إضافة جديد</span>
        </a>
    </div>

    @if(session('success'))
        <div style="background: rgba(31, 138, 91, 0.1); color: var(--success); border: 1px solid rgba(31, 138, 91, 0.2); padding: 12px 16px; border-radius: 8px; margin-bottom: 24px;">
            {{ session('success') }}
        </div>
    @endif

    <div class="panel-header" style="border-bottom: none; border-radius: 12px 12px 0 0;">
        <span>{{ $pages->total() }} items</span>
    </div>
    
    <div style="background: var(--surface); border: 1px solid var(--border); border-top: none; border-radius: 0 0 12px 12px; overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead style="background: var(--bg); border-bottom: 1px solid var(--border);">
                <tr>
                    <th style="padding: 12px 24px; font-weight: 600; font-size: 13px; color: var(--muted);">Title</th>
                    <th style="padding: 12px 24px; font-weight: 600; font-size: 13px; color: var(--muted);">Author</th>
                    <th style="padding: 12px 24px; font-weight: 600; font-size: 13px; color: var(--muted);">Status</th>
                    <th style="padding: 12px 24px; font-weight: 600; font-size: 13px; color: var(--muted);">Date</th>
                    <th style="padding: 12px 24px; font-weight: 600; font-size: 13px; color: var(--muted); text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pages as $page)
                    <tr style="border-bottom: 1px solid var(--border);">
                        <td style="padding: 16px 24px; font-weight: 500;">
                            {{ $page->title }}
                            <div style="font-size: 12px; color: var(--muted); margin-top: 4px; font-family: 'JetBrains Mono', monospace;">{{ $page->slug }}</div>
                        </td>
                        <td style="padding: 16px 24px; color: var(--muted); font-size: 14px;">{{ $page->author->name }}</td>
                        <td style="padding: 16px 24px;">
                            @if($page->status === 'published')
                                <span style="background: rgba(31, 138, 91, 0.1); color: var(--success); padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;">Published</span>
                            @elseif($post->status === 'draft')
                                <span style="background: rgba(107, 114, 128, 0.1); color: var(--muted); padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;">Draft</span>
                            @else
                                <span style="background: rgba(229, 62, 62, 0.1); color: #e53e3e; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;">Trash</span>
                            @endif
                        </td>
                        <td style="padding: 16px 24px; color: var(--muted); font-size: 14px;">
                            {{ $page->created_at->format('M d, Y') }}
                        </td>
                        <td style="padding: 16px 24px; text-align: right;">
                            <a href="{{ route('admin.pages.edit', $page) }}" style="color: var(--accent); text-decoration: none; font-size: 14px; margin-right: 12px; font-weight: 500;">Edit</a>
                            
                            <form action="{{ route('admin.pages.destroy', $page) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Are you sure you want to delete this page?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background: none; border: none; color: #e53e3e; cursor: pointer; font-size: 14px; font-weight: 500; font-family: inherit;">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="padding: 32px 24px; text-align: center; color: var(--muted);">
                            No pages found. <a href="{{ route('admin.pages.create') }}" style="color: var(--accent);">Create your first page</a>.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 24px;">
        {{ $pages->links() }}
    </div>
@endsection
