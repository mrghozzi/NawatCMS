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
                        @include('admin::components.editor', [
                            'name' => 'content', 
                            'value' => old('content', $post->content), 
                            'type' => old('editor_type', $post->editor_type ?? 'quill')
                        ])
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
                        <button type="submit" style="padding: 10px 16px; background: var(--accent); color: white; border: none; border-radius: 8px; font-size: 14px; font-weight: 500; cursor: pointer;">
                            {{ $post->exists ? 'Update Post' : 'Publish Post' }}
                        </button>
                    </div>
                </div>

                <!-- Categories Panel -->
                <div style="background: var(--surface); border: 1px solid var(--border); border-radius: 12px; padding: 24px; margin-top: 24px;">
                    <h3 style="margin: 0 0 16px; font-size: 16px;">{{ __('Categories') }}</h3>
                    <div style="max-height: 200px; overflow-y: auto; padding: 12px; border: 1px solid var(--border); border-radius: 6px; background: var(--bg);">
                        @php
                            $postCategories = $post->exists ? $post->categories->pluck('id')->toArray() : [];
                        @endphp
                        @foreach($categories as $category)
                            <div style="margin-bottom: 8px;">
                                <label style="display: flex; align-items: center; gap: 8px; font-size: 14px; cursor: pointer;">
                                    <input type="checkbox" name="categories[]" value="{{ $category->id }}" {{ in_array($category->id, old('categories', $postCategories)) ? 'checked' : '' }}>
                                    {{ $category->name }}
                                </label>
                                @if($category->children->count())
                                    <div style="margin-inline-start: 24px; margin-top: 8px;">
                                        @foreach($category->children as $child)
                                            <label style="display: flex; align-items: center; gap: 8px; font-size: 14px; cursor: pointer; margin-bottom: 8px;">
                                                <input type="checkbox" name="categories[]" value="{{ $child->id }}" {{ in_array($child->id, old('categories', $postCategories)) ? 'checked' : '' }}>
                                                {{ $child->name }}
                                            </label>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Tags Panel -->
                <div style="background: var(--surface); border: 1px solid var(--border); border-radius: 12px; padding: 24px; margin-top: 24px;">
                    <h3 style="margin: 0 0 16px; font-size: 16px;">{{ __('Tags') }}</h3>
                    <div class="form-group">
                        <label for="tags" style="display: block; margin-bottom: 8px; font-size: 13px; color: var(--muted);">Separate tags with commas</label>
                        @php
                            $postTags = $post->exists ? $post->tags->pluck('name')->implode(', ') : '';
                        @endphp
                        <input type="text" id="tags" name="tags" value="{{ old('tags', $postTags) }}" style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: 6px; font-size: 14px; font-family: inherit; box-sizing: border-box; background: var(--bg); color: var(--text);">
                    </div>
                </div>
            </div>

        </div>
    </form>
@endsection
