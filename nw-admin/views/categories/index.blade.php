@extends('admin::layouts.admin')

@section('title', __('Categories'))
@section('page_title', __('Categories'))

@section('content')
<div style="display: grid; grid-template-columns: 1fr 2fr; gap: 24px;">
    <!-- Add New Category Form -->
    <div style="background: var(--surface); border: 1px solid var(--border); border-radius: 12px; padding: 24px; height: fit-content;">
        <h3 style="margin-top: 0; margin-bottom: 20px; font-size: 16px;">{{ __('Add New Category') }}</h3>
        
        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf
            <div style="margin-bottom: 16px;">
                <label for="name" style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 14px;">{{ __('Name') }}</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required style="width: 100%; padding: 10px 12px; border: 1px solid var(--border); border-radius: 6px; background: var(--bg); color: var(--text);">
                @error('name')<span style="color: var(--warning); font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>@enderror
            </div>

            <div style="margin-bottom: 16px;">
                <label for="slug" style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 14px;">{{ __('Slug') }}</label>
                <input type="text" id="slug" name="slug" value="{{ old('slug') }}" style="width: 100%; padding: 10px 12px; border: 1px solid var(--border); border-radius: 6px; background: var(--bg); color: var(--text);">
                <span style="font-size: 12px; color: var(--muted); display: block; margin-top: 4px;">Leave empty to auto-generate from name.</span>
                @error('slug')<span style="color: var(--warning); font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>@enderror
            </div>

            <div style="margin-bottom: 16px;">
                <label for="parent_id" style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 14px;">{{ __('Parent Category') }}</label>
                <select id="parent_id" name="parent_id" style="width: 100%; padding: 10px 12px; border: 1px solid var(--border); border-radius: 6px; background: var(--bg); color: var(--text);">
                    <option value="">None</option>
                    @foreach($parentCategories as $parent)
                        <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                    @endforeach
                </select>
                @error('parent_id')<span style="color: var(--warning); font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>@enderror
            </div>

            <div style="margin-bottom: 24px;">
                <label for="description" style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 14px;">{{ __('Description') }}</label>
                <textarea id="description" name="description" rows="4" style="width: 100%; padding: 10px 12px; border: 1px solid var(--border); border-radius: 6px; background: var(--bg); color: var(--text);">{{ old('description') }}</textarea>
                @error('description')<span style="color: var(--warning); font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span>@enderror
            </div>

            <button type="submit" class="primary-action" style="width: 100%;">{{ __('Add New Category') }}</button>
        </form>
    </div>

    <!-- Categories List -->
    <div style="background: var(--surface); border: 1px solid var(--border); border-radius: 12px; overflow: hidden; height: fit-content;">
        <table style="width: 100%; border-collapse: collapse; text-align: start;">
            <thead style="background: var(--bg); border-bottom: 1px solid var(--border);">
                <tr>
                    <th style="padding: 12px 24px; font-weight: 600; font-size: 13px; color: var(--muted);">{{ __('Name') }}</th>
                    <th style="padding: 12px 24px; font-weight: 600; font-size: 13px; color: var(--muted);">{{ __('Description') }}</th>
                    <th style="padding: 12px 24px; font-weight: 600; font-size: 13px; color: var(--muted);">{{ __('Slug') }}</th>
                    <th style="padding: 12px 24px; font-weight: 600; font-size: 13px; color: var(--muted); text-align: end;">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                    <tr style="border-bottom: 1px solid var(--border);">
                        <td style="padding: 16px 24px; font-weight: 500;">
                            @if($category->parent_id)
                                <span style="color: var(--muted);">— </span>
                            @endif
                            {{ $category->name }}
                        </td>
                        <td style="padding: 16px 24px; color: var(--muted); font-size: 13px;">{{ $category->description ?? '—' }}</td>
                        <td style="padding: 16px 24px; color: var(--muted); font-size: 13px;">{{ $category->slug }}</td>
                        <td style="padding: 16px 24px; text-align: end;">
                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background: none; border: none; color: var(--warning); cursor: pointer; font-size: 13px; font-weight: 500;">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="padding: 32px 24px; text-align: center; color: var(--muted);">No categories found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
