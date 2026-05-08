@extends('admin::layouts.admin')

@section('title', 'Menus')

@section('header')
    <h1 class="page-title">Menus <span lang="ar" dir="rtl">القوائم</span></h1>
@endsection

@section('content')
    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 24px;">
        <!-- Create Menu Panel -->
        <div class="panel">
            <div class="panel-header">
                <h2>Create Menu</h2>
            </div>
            <div class="panel-body">
                <form action="{{ route('admin.menus.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="name">Menu Name <span lang="ar" dir="rtl">اسم القائمة</span></label>
                        <input type="text" id="name" name="name" class="form-control" required placeholder="e.g., Main Menu">
                    </div>
                    <button type="submit" class="btn btn-primary" style="width: 100%;">Create Menu</button>
                </form>
            </div>
        </div>

        <!-- List Menus Panel -->
        <div class="panel">
            <div class="panel-header">
                <h2>Existing Menus</h2>
            </div>
            <div class="panel-body" style="padding: 0;">
                @if($menus->isEmpty())
                    <div style="padding: 24px; text-align: center; color: #6b7280;">
                        No menus found. Create one to get started.
                    </div>
                @else
                    <table class="table" style="margin: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Location</th>
                                <th style="text-align: end;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($menus as $menu)
                                <tr>
                                    <td><strong>{{ $menu->name }}</strong></td>
                                    <td>
                                        @if($menu->location)
                                            <span style="background: #e5e7eb; padding: 2px 8px; border-radius: 4px; font-size: 12px;">{{ $menu->location }}</span>
                                        @else
                                            <span style="color: #9ca3af; font-size: 12px;">Not assigned</span>
                                        @endif
                                    </td>
                                    <td style="text-align: end;">
                                        <a href="{{ route('admin.menus.show', $menu) }}" class="btn btn-secondary" style="padding: 4px 12px; font-size: 13px;">Edit Items</a>
                                        <form action="{{ route('admin.menus.destroy', $menu) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Are you sure you want to delete this menu?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-secondary" style="padding: 4px 12px; font-size: 13px; color: #ef4444; border-color: #fca5a5;">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
@endsection
