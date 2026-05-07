@extends('admin::install.layout', ['currentStepIndex' => 2])

@section('title', 'Database Connection - Nawat CMS')

@section('content')
    <div class="panel-header">
        <span>Database connection</span>
        <span class="mono-label">DB_CONNECTION</span>
    </div>

    @if($errors->has('error'))
        <div class="alert alert-danger" style="margin: 24px 24px 0; padding: 12px; background: rgba(229, 62, 62, 0.1); color: #e53e3e; border-radius: 8px; border: 1px solid rgba(229, 62, 62, 0.2); font-size: 14px;">
            {{ $errors->first('error') }}
        </div>
    @endif

    <form action="{{ route('install.database.store') }}" method="POST" class="install-form" id="db-form">
        @csrf
        
        <div class="form-group">
            <label for="driver">Database Driver <span lang="ar" dir="rtl">نوع قاعدة البيانات</span></label>
            <select id="driver" name="driver">
                <option value="mysql" {{ old('driver', $driver) === 'mysql' ? 'selected' : '' }}>MySQL / MariaDB</option>
                <option value="sqlite" {{ old('driver', $driver) === 'sqlite' ? 'selected' : '' }}>SQLite</option>
                <option value="pgsql" {{ old('driver', $driver) === 'pgsql' ? 'selected' : '' }}>PostgreSQL</option>
            </select>
            @error('driver') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div id="mysql-fields">
            <div class="form-group">
                <label for="host">Host <span lang="ar" dir="rtl">المضيف</span></label>
                <input type="text" id="host" name="host" value="{{ old('host', $host) }}">
                @error('host') <span class="error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="port">Port <span lang="ar" dir="rtl">المنفذ</span></label>
                <input type="text" id="port" name="port" value="{{ old('port', $port) }}">
                @error('port') <span class="error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="username">Username <span lang="ar" dir="rtl">اسم المستخدم</span></label>
                <input type="text" id="username" name="username" value="{{ old('username', $username) }}">
                @error('username') <span class="error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="password">Password <span lang="ar" dir="rtl">كلمة المرور</span></label>
                <input type="password" id="password" name="password" value="{{ old('password', $password) }}">
                @error('password') <span class="error">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="database">
                <span id="db-name-label">Database Name</span>
                <span lang="ar" dir="rtl">اسم قاعدة البيانات</span>
            </label>
            <input type="text" id="database" name="database" value="{{ old('database', $database) }}" required>
            <p id="sqlite-hint" style="font-size: 12px; color: var(--muted); margin-top: 4px; display: none;">
                For SQLite, enter the filename (e.g., <code>database.sqlite</code>). It will be created in <code>nw-includes/database/</code>.
            </p>
            @error('database') <span class="error">{{ $message }}</span> @enderror
        </div>

        <div class="form-actions">
            <button type="submit" class="primary-action">
                Test and Continue
                <span lang="ar" dir="rtl">اختبار ومتابعة</span>
            </button>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const driverSelect = document.getElementById('driver');
            const mysqlFields = document.getElementById('mysql-fields');
            const dbNameLabel = document.getElementById('db-name-label');
            const sqliteHint = document.getElementById('sqlite-hint');

            function toggleFields() {
                const driver = driverSelect.value;
                if (driver === 'sqlite') {
                    mysqlFields.style.display = 'none';
                    dbNameLabel.textContent = 'Database File Path';
                    sqliteHint.style.display = 'block';
                } else {
                    mysqlFields.style.display = 'grid';
                    mysqlFields.style.gap = '20px';
                    dbNameLabel.textContent = 'Database Name';
                    sqliteHint.style.display = 'none';
                }
            }

            driverSelect.addEventListener('change', toggleFields);
            toggleFields();
        });
    </script>
@endsection

@section('sidebar_note')
    <p lang="ar" dir="rtl" class="arabic-note">
        سيتم اختبار الاتصال فور الضغط على الزر. إذا كان الاتصال صحيحاً، سيقوم النظام بإنشاء الجداول اللازمة.
    </p>
@endsection
