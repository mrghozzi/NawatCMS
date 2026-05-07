@extends('admin::install.layout', ['currentStepIndex' => 0])

@section('title', 'Server Checks - Nawat CMS')

@section('header_action')
    <a class="primary-action" href="{{ route('install.environment') }}">
        Start setup
        <span lang="ar" dir="rtl">بدء الإعداد</span>
    </a>
@endsection

@section('content')
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
@endsection

@section('sidebar_note')
    <p lang="ar" dir="rtl" class="arabic-note">
        هذه الصفحة هي بوابة التثبيت الأولى. الخطوات التالية ستضيف إعداد البيئة وقاعدة البيانات.
    </p>
@endsection
