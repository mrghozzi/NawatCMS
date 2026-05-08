@extends('admin::layouts.admin')

@section('title', __('Plugins') . ' - ' . config('app.name'))

@section('header')
    <h1 class="page-title">{{ __('Plugins') }} <span lang="ar" dir="rtl">الإضافات</span></h1>
@endsection

@section('content')
    <div class="panel">
        <div class="panel-header">
            <h2>Installed Plugins</h2>
        </div>
        <div class="panel-body" style="padding: 0;">
            @if(empty($plugins))
                <div style="padding: 40px; text-align: center; color: #6b7280;">
                    No plugins found in <code>nw-content/plugins/</code>.
                </div>
            @else
                <table class="table" style="margin: 0; width: 100%;">
                    <thead>
                        <tr>
                            <th style="width: 250px;">Plugin</th>
                            <th>Description</th>
                            <th style="width: 150px; text-align: end;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($plugins as $slug => $plugin)
                            <tr style="background: {{ $plugin['is_active'] ? '#f0fdf4' : '#fff' }};">
                                <td>
                                    <strong>{{ $plugin['name'] ?? $slug }}</strong>
                                    <div style="font-size: 12px; color: #6b7280; margin-top: 4px;">
                                        Version {{ $plugin['version'] ?? '1.0' }} | By {{ $plugin['author'] ?? 'Unknown' }}
                                    </div>
                                </td>
                                <td style="color: #4b5563;">
                                    {{ $plugin['description'] ?? 'No description available.' }}
                                </td>
                                <td style="text-align: end;">
                                    <form action="{{ route('admin.plugins.toggle', $slug) }}" method="POST" style="margin: 0;">
                                        @csrf
                                        @if($plugin['is_active'])
                                            <input type="hidden" name="action" value="deactivate">
                                            <button type="submit" class="btn btn-secondary" style="padding: 4px 12px; font-size: 13px; color: #b91c1c; border-color: #fca5a5;">Deactivate</button>
                                        @else
                                            <input type="hidden" name="action" value="activate">
                                            <button type="submit" class="btn btn-secondary" style="padding: 4px 12px; font-size: 13px; color: #15803d; border-color: #86efac;">Activate</button>
                                        @endif
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
@endsection
