<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Nawat CMS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ $adminAssets->url('css/install.css') }}">
    <style>
        .login-box {
            width: min(400px, calc(100% - 32px));
            margin: 10vh auto;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px;
            box-shadow: var(--shadow);
            padding: 32px;
        }
        .login-logo {
            text-align: center;
            margin-bottom: 32px;
        }
        .login-logo h1 {
            font-size: 32px;
            margin: 0;
        }
        .login-logo p {
            color: var(--muted);
            margin: 8px 0 0;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <main class="login-box">
        <div class="login-logo">
            <h1>Nawat CMS</h1>
            <p>Admin Workspace <span lang="ar" dir="rtl">لوحة الإدارة</span></p>
        </div>

        <form action="{{ route('admin.login.store') }}" method="POST" class="install-form" style="padding: 0;">
            @csrf
            
            <div class="form-group">
                <label for="email">Email Address <span lang="ar" dir="rtl">البريد الإلكتروني</span></label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
                @error('email') <span class="error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="password">Password <span lang="ar" dir="rtl">كلمة المرور</span></label>
                <input type="password" id="password" name="password" required>
                @error('password') <span class="error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group" style="display: flex; align-items: center; gap: 8px; flex-direction: row;">
                <input type="checkbox" id="remember" name="remember" style="min-height: auto; width: 16px; height: 16px;">
                <label for="remember" style="font-weight: 400; margin: 0;">Remember me <span lang="ar" dir="rtl">تذكرني</span></label>
            </div>

            <div class="form-actions" style="margin-top: 24px;">
                <button type="submit" class="primary-action">
                    Sign In
                    <span lang="ar" dir="rtl">تسجيل الدخول</span>
                </button>
            </div>
        </form>
    </main>
</body>
</html>
