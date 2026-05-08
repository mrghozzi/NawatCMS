@extends('admin::layouts.admin')

@section('title', 'Menu Builder: ' . $menu->name)

@section('header')
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h1 class="page-title">
            <a href="{{ route('admin.menus.index') }}" style="color: #6b7280; text-decoration: none; margin-right: 8px;">&larr;</a>
            Edit Menu: {{ $menu->name }}
        </h1>
    </div>
@endsection

@section('content')
    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 24px;">
        
        <!-- Add Items Column -->
        <div style="display: flex; flex-direction: column; gap: 24px;">
            
            <!-- Menu Settings -->
            <div class="panel">
                <div class="panel-header">
                    <h2>Menu Settings</h2>
                </div>
                <div class="panel-body">
                    <form action="{{ route('admin.menus.update', $menu) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="name">Menu Name</label>
                            <input type="text" id="name" name="name" class="form-control" value="{{ $menu->name }}" required>
                        </div>
                        <div class="form-group">
                            <label for="location">Display Location</label>
                            <select id="location" name="location" class="form-control">
                                <option value="">-- Do not display --</option>
                                <option value="primary" {{ $menu->location === 'primary' ? 'selected' : '' }}>Primary Menu (Header)</option>
                                <option value="footer" {{ $menu->location === 'footer' ? 'selected' : '' }}>Footer Menu</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary" style="width: 100%;">Save Settings</button>
                    </form>
                </div>
            </div>

            <!-- Add Custom Link -->
            <div class="panel">
                <div class="panel-header">
                    <h2>Add Custom Link</h2>
                </div>
                <div class="panel-body">
                    <form action="{{ route('admin.menus.items.store', $menu) }}" method="POST">
                        @csrf
                        <input type="hidden" name="type" value="custom">
                        <div class="form-group">
                            <label for="url">URL</label>
                            <input type="url" id="url" name="url" class="form-control" placeholder="https://" required>
                        </div>
                        <div class="form-group">
                            <label for="title">Link Text</label>
                            <input type="text" id="title" name="title" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-secondary" style="width: 100%;">Add to Menu</button>
                    </form>
                </div>
            </div>

            <!-- Add Content Link -->
            <div class="panel">
                <div class="panel-header">
                    <h2>Add Content Link</h2>
                </div>
                <div class="panel-body">
                    <form action="{{ route('admin.menus.items.store', $menu) }}" method="POST">
                        @csrf
                        <input type="hidden" name="type" value="post">
                        <div class="form-group">
                            <label for="reference_id">Select Page/Post</label>
                            <select id="reference_id" name="reference_id" class="form-control" required>
                                <option value="">-- Select Content --</option>
                                @foreach($posts as $post)
                                    <option value="{{ $post->id }}">{{ $post->title }} ({{ ucfirst($post->type) }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="title_content">Link Text</label>
                            <input type="text" id="title_content" name="title" class="form-control" placeholder="Leave blank to use content title">
                        </div>
                        <button type="submit" class="btn btn-secondary" style="width: 100%;">Add to Menu</button>
                    </form>
                    <script>
                        document.getElementById('reference_id').addEventListener('change', function() {
                            const titleInput = document.getElementById('title_content');
                            if (!titleInput.value && this.options[this.selectedIndex].text !== '-- Select Content --') {
                                // Extract just the title, remove the (Type) part
                                titleInput.value = this.options[this.selectedIndex].text.replace(/\s\([^)]+\)$/, '');
                            }
                        });
                    </script>
                </div>
            </div>
        </div>

        <!-- Menu Structure Column -->
        <div class="panel">
            <div class="panel-header">
                <h2>Menu Structure</h2>
            </div>
            <div class="panel-body">
                @if($menu->parentItems->isEmpty())
                    <div style="padding: 40px 20px; text-align: center; color: #6b7280; border: 2px dashed #e5e7eb; border-radius: 8px;">
                        Add menu items from the left column to build your menu.
                    </div>
                @else
                    <div class="menu-structure" style="display: flex; flex-direction: column; gap: 8px;">
                        @foreach($menu->parentItems as $item)
                            <div style="border: 1px solid #e5e7eb; border-radius: 6px; background: #fff;">
                                <div style="padding: 12px 16px; display: flex; justify-content: space-between; align-items: center; background: #f9fafb; border-radius: 6px;">
                                    <div>
                                        <strong>{{ $item->title }}</strong>
                                        <span style="font-size: 12px; color: #6b7280; margin-left: 8px;">
                                            {{ $item->type === 'custom' ? 'Custom Link' : ucfirst($item->type) }}
                                        </span>
                                    </div>
                                    <div>
                                        <form action="{{ route('admin.menus.items.destroy', [$menu, $item]) }}" method="POST" onsubmit="return confirm('Delete this item?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" style="background: none; border: none; color: #ef4444; cursor: pointer; font-size: 13px;">Remove</button>
                                        </form>
                                    </div>
                                </div>
                                
                                <!-- Children (Sub-menu items) -->
                                @if($item->children->isNotEmpty())
                                    <div style="padding: 8px 16px 12px 32px; display: flex; flex-direction: column; gap: 8px; border-top: 1px solid #e5e7eb;">
                                        @foreach($item->children as $child)
                                            <div style="border: 1px solid #e5e7eb; border-radius: 6px; padding: 10px 16px; display: flex; justify-content: space-between; align-items: center;">
                                                <div>
                                                    <strong>{{ $child->title }}</strong>
                                                    <span style="font-size: 12px; color: #6b7280; margin-left: 8px;">
                                                        {{ $child->type === 'custom' ? 'Custom Link' : ucfirst($child->type) }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <form action="{{ route('admin.menus.items.destroy', [$menu, $child]) }}" method="POST" onsubmit="return confirm('Delete this item?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" style="background: none; border: none; color: #ef4444; cursor: pointer; font-size: 13px;">Remove</button>
                                                    </form>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                <!-- Add child form (simplified for now) -->
                                <div style="padding: 8px 16px; border-top: 1px solid #e5e7eb; background: #fff;">
                                    <form action="{{ route('admin.menus.items.store', $menu) }}" method="POST" style="display: flex; gap: 8px; align-items: center;">
                                        @csrf
                                        <input type="hidden" name="type" value="custom">
                                        <input type="hidden" name="parent_id" value="{{ $item->id }}">
                                        <input type="text" name="title" placeholder="Sub-item name" class="form-control" style="padding: 4px 8px; font-size: 13px;" required>
                                        <input type="url" name="url" placeholder="URL" class="form-control" style="padding: 4px 8px; font-size: 13px;" required>
                                        <button type="submit" class="btn btn-secondary" style="padding: 4px 8px; font-size: 13px;">+ Sub</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
