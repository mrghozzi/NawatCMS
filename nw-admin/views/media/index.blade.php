@extends('admin::layouts.admin')

@section('title', 'Media Manager')

@section('header')
    <h1 class="page-title">Media Manager <span lang="ar" dir="rtl">إدارة الوسائط</span></h1>
@endsection

@section('content')
    <!-- Upload Panel -->
    <div class="panel" style="margin-bottom: 24px;">
        <div class="panel-header">
            <h2>Upload New Media</h2>
        </div>
        <div class="panel-body">
            <form action="{{ route('admin.media.store') }}" method="POST" enctype="multipart/form-data" style="display: flex; gap: 16px; align-items: flex-end;">
                @csrf
                <div class="form-group" style="flex: 2; margin-bottom: 0;">
                    <label for="file">Select File (Max 5MB)</label>
                    <input type="file" id="file" name="file" class="form-control" required>
                    @error('file')
                        <div style="color: #ef4444; font-size: 13px; margin-top: 4px;">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group" style="flex: 3; margin-bottom: 0;">
                    <label for="alt_text">Alternative Text (Optional)</label>
                    <input type="text" id="alt_text" name="alt_text" class="form-control" placeholder="Describe the image for accessibility">
                </div>
                <div style="margin-bottom: 0;">
                    <button type="submit" class="btn btn-primary" style="height: 42px; padding: 0 24px;">Upload</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Gallery Panel -->
    <div class="panel">
        <div class="panel-header">
            <h2>Media Gallery</h2>
        </div>
        <div class="panel-body">
            @if($media->isEmpty())
                <div style="padding: 40px; text-align: center; color: #6b7280; border: 2px dashed #e5e7eb; border-radius: 8px;">
                    No media files found. Upload some files above.
                </div>
            @else
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 16px;">
                    @foreach($media as $item)
                        <div style="border: 1px solid #e5e7eb; border-radius: 8px; overflow: hidden; background: #fff; display: flex; flex-direction: column;">
                            <div style="height: 150px; background: #f3f4f6; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                                @if(str_starts_with($item->mime_type, 'image/'))
                                    <img src="{{ $item->url }}" alt="{{ $item->alt_text }}" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                @else
                                    <div style="font-size: 48px; color: #9ca3af;">
                                        📄
                                    </div>
                                @endif
                            </div>
                            <div style="padding: 12px; flex: 1; display: flex; flex-direction: column; justify-content: space-between;">
                                <div style="margin-bottom: 8px;">
                                    <div style="font-size: 13px; font-weight: 500; color: #111827; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $item->filename }}">
                                        {{ $item->filename }}
                                    </div>
                                    <div style="font-size: 11px; color: #6b7280; margin-top: 4px;">
                                        {{ number_format($item->size / 1024, 2) }} KB &bull; {{ strtoupper(explode('/', $item->mime_type)[1] ?? 'File') }}
                                    </div>
                                </div>
                                <div style="display: flex; gap: 8px;">
                                    <input type="text" value="{{ $item->url }}" readonly class="form-control" style="font-size: 11px; padding: 4px 8px; height: 28px;" onclick="this.select();" title="Copy URL">
                                    <form action="{{ route('admin.media.destroy', $item) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this file permanently?');" style="margin: 0;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-secondary" style="padding: 4px 8px; height: 28px; color: #ef4444; border-color: #fca5a5; font-size: 12px;">Del</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection
