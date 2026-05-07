<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nawat CMS Installer</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ $adminAssets->url('css/install.css') }}">
</head>
<body>
    <main class="install-shell" aria-labelledby="install-title">
        <section class="install-header">
            <div>
                <p class="eyebrow">Phase 1 Foundation</p>
                <h1 id="install-title">Nawat CMS</h1>
                <p class="lead">
                    A modular Laravel engine with WordPress-style content folders.
                    <span lang="ar" dir="rtl">نواة لإدارة المحتوى ببنية مرنة ومناسبة للاستضافة المشتركة.</span>
                </p>
            </div>
            <a class="primary-action" href="#server-checks">
                Start setup
                <span lang="ar" dir="rtl">بدء الإعداد</span>
            </a>
        </section>

        <section class="workspace-grid" aria-label="Installer workspace">
            <aside class="sidebar-panel" aria-label="Installation steps">
                <div class="panel-header">
                    <span class="status-dot"></span>
                    <span>Installer</span>
                </div>
                <ol class="step-list">
                    @foreach ($steps as $index => $step)
                        <li class="{{ $index === 0 ? 'is-active' : '' }}">
                            <span>{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}</span>
                            {{ $step }}
                        </li>
                    @endforeach
                </ol>
            </aside>

            <section class="main-panel" id="server-checks">
                <div class="panel-header">
                    <span>Server checks</span>
                    <span class="mono-label">/install</span>
                </div>

                <div class="check-table" role="table" aria-label="Server readiness">
                    <div class="check-row check-head" role="row">
                        <span role="columnheader">Check</span>
                        <span role="columnheader">Value</span>
                        <span role="columnheader">Status</span>
                    </div>
                    @foreach ($checks as $check)
                        <div class="check-row" role="row">
                            <span role="cell">{{ $check['label'] }}</span>
                            <code role="cell">{{ $check['value'] }}</code>
                            <span role="cell" class="badge {{ $check['passed'] ? 'is-ok' : 'is-warning' }}">
                                {{ $check['passed'] ? 'Ready' : 'Review' }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </section>

            <aside class="summary-panel" aria-label="Project structure">
                <div class="panel-header">
                    <span>Structure</span>
                    <span class="mono-label">nw-*</span>
                </div>
                <dl class="path-list">
                    <div>
                        <dt>Admin</dt>
                        <dd>/nw-admin</dd>
                    </div>
                    <div>
                        <dt>Content</dt>
                        <dd>/nw-content</dd>
                    </div>
                    <div>
                        <dt>Engine</dt>
                        <dd>/nw-includes</dd>
                    </div>
                </dl>
                <p lang="ar" dir="rtl" class="arabic-note">
                    هذه الصفحة هي بوابة التثبيت الأولى. الخطوات التالية ستضيف إعداد البيئة وقاعدة البيانات.
                </p>
            </aside>
        </section>
    </main>
</body>
</html>
