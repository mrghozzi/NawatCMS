@extends('admin::install.layout', ['currentStepIndex' => 4])

@section('title', 'Installation Successful - Nawat CMS')

@section('content')
    <div class="panel-header">
        <span>Installation Complete</span>
        <span class="mono-label">Success</span>
    </div>

    <div style="padding: 40px; text-align: center;">
        <div style="width: 64px; height: 64px; background: rgba(31, 138, 91, 0.1); color: var(--success); border-radius: 999px; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 24px;">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
        </div>
        
        <h2 style="margin: 0 0 12px; font-size: 24px;">Congratulations! <span lang="ar" dir="rtl">تهانينا!</span></h2>
        <p style="color: var(--muted); margin: 0 0 32px;">
            Nawat CMS has been successfully installed. You can now log in to your dashboard.
            <br>
            <span lang="ar" dir="rtl">تم تثبيت نظام نواة بنجاح. يمكنك الآن تسجيل الدخول إلى لوحة التحكم.</span>
        </p>

        <div class="form-actions" style="max-width: 300px; margin: 0 auto;">
            <a href="/" class="primary-action" style="width: 100%;">
                Go to Homepage
                <span lang="ar" dir="rtl">الذهاب للرئيسية</span>
            </a>
        </div>
    </div>
@endsection

@section('sidebar_note')
    <p lang="ar" dir="rtl" class="arabic-note">
        تم قفل المثبّت بنجاح. لبدء عملية تثبيت جديدة، يجب حذف ملف <code>.install-lock</code> من مجلد <code>storage</code>.
    </p>
@endsection
