@extends('admin::layouts.admin')

@section('title', $post->exists ? 'Edit Post' : 'Add New Post')
@section('page_title', $post->exists ? 'Edit Post' : 'Add New Post')

@section('content')
    <form action="{{ $post->exists ? route('admin.posts.update', $post) : route('admin.posts.store') }}" method="POST">
        @csrf
        @if($post->exists)
            @method('PUT')
        @endif

        <div style="display: flex; gap: 32px; align-items: flex-start;">
            
            <!-- Main Content Area -->
            <div style="flex: 1;">
                <div style="background: var(--surface); border: 1px solid var(--border); border-radius: 12px; padding: 24px; margin-bottom: 24px;">
                    <div class="form-group" style="margin-bottom: 24px;">
                        <label for="title" style="display: block; margin-bottom: 8px; font-weight: 500;">Title <span lang="ar" dir="rtl">العنوان</span></label>
                        <input type="text" id="title" name="title" value="{{ old('title', $post->title) }}" required style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 8px; font-size: 16px; font-family: inherit; box-sizing: border-box;">
                        @error('title') <span style="color: #e53e3e; font-size: 13px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="content" style="display: block; margin-bottom: 8px; font-weight: 500;">Content <span lang="ar" dir="rtl">المحتوى</span></label>
                        <textarea id="content" name="content" rows="15" style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 8px; font-size: 14px; font-family: inherit; box-sizing: border-box; resize: vertical; line-height: 1.6;">{{ old('content', $post->content) }}</textarea>
                        @error('content') <span style="color: #e53e3e; font-size: 13px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div style="width: 300px; flex-shrink: 0;">
                <div style="background: var(--surface); border: 1px solid var(--border); border-radius: 12px; padding: 24px;">
                    <h3 style="margin: 0 0 16px; font-size: 16px;">Publish <span lang="ar" dir="rtl">نشر</span></h3>
                    
                    <div class="form-group" style="margin-bottom: 24px;">
                        <label for="status" style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 14px;">Status</label>
                        <select id="status" name="status" style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: 6px; font-size: 14px; font-family: inherit; box-sizing: border-box; background: white;">
                            <option value="draft" {{ old('status', $post->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ old('status', $post->status) === 'published' ? 'selected' : '' }}>Published</option>
                        </select>
                        @error('status') <span style="color: #e53e3e; font-size: 13px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
                    </div>

                    <div style="padding-top: 16px; border-top: 1px solid var(--border); display: flex; justify-content: flex-end; gap: 12px;">
                        <a href="{{ route('admin.posts.index') }}" style="padding: 10px 16px; border: 1px solid var(--border); border-radius: 8px; color: var(--text); text-decoration: none; font-size: 14px; font-weight: 500;">Cancel</a>
                        <button type="submit" style="padding: 10px 16px; background: var(--primary); color: white; border: none; border-radius: 8px; font-size: 14px; font-weight: 500; cursor: pointer;">
                            {{ $post->exists ? 'Update Post' : 'Publish Post' }}
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </form>
@endsection
