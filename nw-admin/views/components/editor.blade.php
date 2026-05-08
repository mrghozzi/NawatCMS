@props(['name', 'value' => '', 'type' => 'quill'])

@php
    // Map legacy or empty values to the default editor
    $activeEditor = $type ?: 'quill';
    if ($activeEditor === 'editorjs' || $activeEditor === 'tinymce') {
        $activeEditor = 'quill'; // Fallback to current default for old posts
    }
@endphp

<div class="nawat-editor-wrapper">
    @if(view()->exists("admin::components.editors.{$activeEditor}"))
        @include("admin::components.editors.{$activeEditor}", [
            'name' => $name,
            'value' => $value
        ])
    @else
        <!-- Fallback to standard textarea if the registered editor view is missing -->
        <div style="padding: 12px; background: #fff3cd; color: #856404; border: 1px solid #ffeeba; border-radius: 6px; margin-bottom: 16px; font-size: 13px;">
            Warning: The requested editor type "{{ $activeEditor }}" was not found. Falling back to default textarea.
        </div>
        <textarea name="{{ $name }}" rows="15" style="width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 8px; font-size: 14px; font-family: inherit; box-sizing: border-box; resize: vertical; line-height: 1.6; background: var(--bg); color: var(--text);">{{ $value }}</textarea>
    @endif
    
    <input type="hidden" name="editor_type" value="{{ $activeEditor }}">
</div>
