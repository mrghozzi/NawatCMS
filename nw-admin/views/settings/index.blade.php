@extends('admin::layouts.admin')

@section('title', 'Settings - ' . config('app.name'))

@section('header')
    <h1 class="page-title">
        Settings <span lang="ar" dir="rtl">الإعدادات</span>
    </h1>
@endsection

@section('content')
    <div class="panel">
        <div class="panel-header">
            <h2>General Settings</h2>
        </div>
        <div class="panel-body">
            <form action="{{ route('admin.settings.update') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="site_name">{{ __('Site Name') }} <span lang="ar" dir="rtl">اسم الموقع</span></label>
                    <input type="text" id="site_name" name="site_name" class="form-control" value="{{ old('site_name', $settings['site_name'] ?? config('app.name')) }}" required>
                    @error('site_name')
                        <div style="color: #ef4444; font-size: 13px; margin-top: 4px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="site_description">{{ __('Site Description') }} <span lang="ar" dir="rtl">وصف الموقع</span></label>
                    <textarea id="site_description" name="site_description" class="form-control" rows="3">{{ old('site_description', $settings['site_description'] ?? '') }}</textarea>
                    <div style="font-size: 13px; color: #6b7280; margin-top: 4px;">A short tagline or description of what the site is about.</div>
                    @error('site_description')
                        <div style="color: #ef4444; font-size: 13px; margin-top: 4px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="admin_email">{{ __('Admin Email') }} <span lang="ar" dir="rtl">بريد الإدارة</span></label>
                    <input type="email" id="admin_email" name="admin_email" class="form-control" value="{{ old('admin_email', $settings['admin_email'] ?? auth()->user()->email) }}" required>
                    <div style="font-size: 13px; color: #6b7280; margin-top: 4px;">This address is used for admin purposes.</div>
                    @error('admin_email')
                        <div style="color: #ef4444; font-size: 13px; margin-top: 4px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="site_language">{{ __('Site Language') }} <span lang="ar" dir="rtl">لغة الموقع</span></label>
                    <select id="site_language" name="site_language" class="form-control">
                        @foreach($languages as $lang)
                            <option value="{{ $lang }}" {{ old('site_language', $settings['site_language'] ?? config('app.locale')) === $lang ? 'selected' : '' }}>
                                {{ strtoupper($lang) }}
                            </option>
                        @endforeach
                    </select>
                    @error('site_language')
                        <div style="color: #ef4444; font-size: 13px; margin-top: 4px;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-actions" style="margin-top: 24px;">
                    <button type="submit" class="btn btn-primary">{{ __('Save Settings') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection
