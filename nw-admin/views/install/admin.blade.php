@extends('admin::install.layout', ['currentStepIndex' => 3])

@section('title', 'Administrator Account - Nawat CMS')

@section('content')
    <div class="panel-header">
        <span>Administrator account</span>
        <span class="mono-label">Super Admin</span>
    </div>

    <form action="{{ route('install.admin.store') }}" method="POST" class="install-form">
        @csrf
        
        <div class="form-group">
            <label for="name">Full Name <span lang="ar" dir="rtl">الاسم الكامل</span></label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required>
            @error('name') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="email">Email Address <span lang="ar" dir="rtl">البريد الإلكتروني</span></label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required>
            @error('email') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="password">Password <span lang="ar" dir="rtl">كلمة المرور</span></label>
            <input type="password" id="password" name="password" required>
            @error('password') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="password_confirmation">Confirm Password <span lang="ar" dir="rtl">تأكيد كلمة المرور</span></label>
            <input type="password" id="password_confirmation" name="password_confirmation" required>
        </div>

        <div class="form-actions">
            <button type="submit" class="primary-action">
                Complete Installation
                <span lang="ar" dir="rtl">إتمام التثبيت</span>
            </button>
        </div>
    </form>
@endsection

@section('sidebar_note')
    <p lang="ar" dir="rtl" class="arabic-note">
        هذا الحساب سيكون له كامل الصلاحيات لإدارة الموقع. يرجى حفظ بيانات الدخول جيداً.
    </p>
@endsection
