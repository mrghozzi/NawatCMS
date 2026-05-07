@extends('admin::layouts.admin')

@section('title', 'Dashboard')
@section('page_title', 'Overview')

@section('content')
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Total Posts <span lang="ar" dir="rtl">المقالات</span></div>
            <div class="stat-value">{{ $stats['posts'] }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Total Pages <span lang="ar" dir="rtl">الصفحات</span></div>
            <div class="stat-value">{{ $stats['pages'] }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Total Users <span lang="ar" dir="rtl">المستخدمين</span></div>
            <div class="stat-value">{{ $stats['users'] }}</div>
        </div>
    </div>

    <section class="main-panel">
        <div class="panel-header">
            <span>Welcome to Nawat CMS</span>
            <span class="mono-label">v1.0.0-alpha</span>
        </div>
        <div style="padding: 24px;">
            <h3 style="margin: 0 0 16px;">Getting Started <span lang="ar" dir="rtl">البداية</span></h3>
            <p style="color: var(--muted); line-height: 1.6; max-width: 600px;">
                You have successfully installed Nawat CMS. This is your administrative dashboard where you can manage your content, themes, and plugins.
                <br><br>
                <span lang="ar" dir="rtl">لقد قمت بتثبيت نظام نواة بنجاح. هذه هي لوحة التحكم الخاصة بك حيث يمكنك إدارة المحتوى والقوالب والإضافات.</span>
            </p>
        </div>
    </section>
@endsection
