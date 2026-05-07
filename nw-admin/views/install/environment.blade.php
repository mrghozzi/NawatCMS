@extends('admin::install.layout', ['currentStepIndex' => 1])

@section('title', 'Environment Setup - Nawat CMS')

@section('content')
    <div class="panel-header">
        <span>Environment setup</span>
        <span class="mono-label">.env</span>
    </div>

    <form action="{{ route('install.environment.store') }}" method="POST" class="install-form">
        @csrf
        
        <div class="form-group">
            <label for="app_name">Site Name <span lang="ar" dir="rtl">اسم الموقع</span></label>
            <input type="text" id="app_name" name="app_name" value="{{ old('app_name', $appName) }}" required>
            @error('app_name') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="app_url">Site URL <span lang="ar" dir="rtl">رابط الموقع</span></label>
            <input type="url" id="app_url" name="app_url" value="{{ old('app_url', $appUrl) }}" required>
            @error('app_url') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="app_env">Environment <span lang="ar" dir="rtl">البيئة</span></label>
            <select id="app_env" name="app_env">
                <option value="local" {{ old('app_env', $appEnv) === 'local' ? 'selected' : '' }}>Local (Development)</option>
                <option value="production" {{ old('app_env', $appEnv) === 'production' ? 'selected' : '' }}>Production (Live)</option>
            </select>
            @error('app_env') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div class="form-actions">
            <button type="submit" class="primary-action">
                Save and Continue
                <span lang="ar" dir="rtl">حفظ ومتابعة</span>
            </button>
        </div>
    </form>
@endsection

@section('sidebar_note')
    <p lang="ar" dir="rtl" class="arabic-note">
        سيتم حفظ هذه الإعدادات في ملف <code>.env</code>. يمكنك تغييرها لاحقاً يدوياً إذا لزم الأمر.
    </p>
@endsection
