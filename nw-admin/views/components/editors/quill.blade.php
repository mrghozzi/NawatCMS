@props(['name', 'value' => ''])

<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

<div class="nawat-editor-tabs" style="display: flex; gap: 4px; margin-bottom: -1px; position: relative; z-index: 1;">
    <button type="button" class="nawat-editor-tab active" data-mode="visual" onclick="switchQuillMode('{{ $name }}', 'visual')" style="padding: 10px 20px; background: var(--bg); border: 1px solid var(--border); border-bottom: 1px solid var(--bg); border-radius: 8px 8px 0 0; cursor: pointer; font-size: 14px; font-weight: 500; color: var(--text);">{{ __('Visual') }} <span lang="ar" dir="rtl">مرئي</span></button>
    <button type="button" class="nawat-editor-tab" data-mode="code" onclick="switchQuillMode('{{ $name }}', 'code')" style="padding: 10px 20px; background: var(--surface); border: 1px solid var(--border); border-radius: 8px 8px 0 0; cursor: pointer; font-size: 14px; color: var(--muted);">{{ __('Code') }} <span lang="ar" dir="rtl">كود</span></button>
</div>

<div class="quill-wrapper" style="border: 1px solid var(--border); border-radius: 0 8px 8px 8px; background: var(--bg); position: relative;">
    <div id="quill-container-{{ $name }}">
        <div id="quill-toolbar-{{ $name }}">
            <span class="ql-formats">
                <select class="ql-header">
                    <option value="2"></option>
                    <option value="3"></option>
                    <option value="4"></option>
                    <option selected></option>
                </select>
            </span>
            <span class="ql-formats">
                <button class="ql-bold"></button>
                <button class="ql-italic"></button>
                <button class="ql-underline"></button>
            </span>
            <span class="ql-formats">
                <button class="ql-list" value="ordered"></button>
                <button class="ql-list" value="bullet"></button>
            </span>
            <span class="ql-formats">
                <button class="ql-link"></button>
                <button class="ql-image"></button>
                <button class="ql-video"></button>
            </span>
            <span class="ql-formats">
                <select class="ql-align"></select>
                <button class="ql-direction" value="rtl"></button>
            </span>
            <span class="ql-formats">
                <button class="ql-clean"></button>
            </span>
        </div>
        <div id="quill-editor-{{ $name }}" style="min-height: 400px; font-size: 16px; font-family: inherit; color: var(--text);">
            {!! $value !!}
        </div>
    </div>
    
    <textarea name="{{ $name }}" id="quill-textarea-{{ $name }}" style="display: none; width: 100%; min-height: 442px; padding: 20px; font-size: 14px; font-family: 'JetBrains Mono', monospace; line-height: 1.6; border: none; background: var(--bg); color: var(--text); resize: vertical; outline: none; box-sizing: border-box; border-radius: 0 0 8px 8px;" dir="ltr">{!! $value !!}</textarea>
</div>

<script>
    (function() {
        function initQuill() {
            var quill = new Quill('#quill-editor-{{ $name }}', {
                theme: 'snow',
                modules: {
                    toolbar: {
                        container: '#quill-toolbar-{{ $name }}',
                        handlers: {
                            image: function() {
                                var input = document.createElement('input');
                                input.setAttribute('type', 'file');
                                input.setAttribute('accept', 'image/*');
                                input.click();

                                input.onchange = function() {
                                    var file = input.files[0];
                                    if (file) {
                                        var formData = new FormData();
                                        formData.append('file', file);
                                        formData.append('_token', '{{ csrf_token() }}');

                                        var range = quill.getSelection(true);

                                        fetch('{{ route('admin.media.store') }}', {
                                            method: 'POST',
                                            headers: {
                                                'Accept': 'application/json'
                                            },
                                            body: formData
                                        })
                                        .then(response => response.json())
                                        .then(data => {
                                            if (data.success && data.url) {
                                                quill.insertEmbed(range.index, 'image', data.url);
                                                quill.setSelection(range.index + 1);
                                            } else {
                                                alert('Upload failed.');
                                            }
                                        })
                                        .catch(error => {
                                            console.error('Error:', error);
                                            alert('Upload failed. Check console.');
                                        });
                                    }
                                };
                            }
                        }
                    }
                }
            });

            // Expose globally for tab switching
            window.nawatQuillInstances = window.nawatQuillInstances || {};
            window.nawatQuillInstances['{{ $name }}'] = quill;

            // Set RTL if needed
            if (document.documentElement.dir === 'rtl') {
                quill.format('direction', 'rtl');
                quill.format('align', 'right');
            }

            var textarea = document.getElementById('quill-textarea-{{ $name }}');
            var form = textarea.closest('form');
            
            if (form) {
                form.addEventListener('submit', function() {
                    // Only update textarea if we are in visual mode
                    if (document.getElementById('quill-container-{{ $name }}').style.display !== 'none') {
                        textarea.value = quill.root.innerHTML;
                    }
                });
            }
        }

        window.switchQuillMode = function(name, mode) {
            const quillContainer = document.getElementById('quill-container-' + name);
            const textarea = document.getElementById('quill-textarea-' + name);
            const quillInstance = window.nawatQuillInstances[name];
            const wrapper = quillContainer.closest('.quill-wrapper');
            const tabs = wrapper.previousElementSibling.querySelectorAll('.nawat-editor-tab');

            tabs.forEach(tab => {
                if (tab.getAttribute('data-mode') === mode) {
                    tab.classList.add('active');
                    tab.style.background = 'var(--bg)';
                    tab.style.color = 'var(--text)';
                    tab.style.fontWeight = '500';
                    tab.style.borderBottom = '1px solid var(--bg)';
                } else {
                    tab.classList.remove('active');
                    tab.style.background = 'var(--surface)';
                    tab.style.color = 'var(--muted)';
                    tab.style.fontWeight = 'normal';
                    tab.style.borderBottom = '1px solid var(--border)';
                }
            });

            if (mode === 'code') {
                textarea.value = quillInstance.root.innerHTML;
                quillContainer.style.display = 'none';
                textarea.style.display = 'block';
            } else {
                quillInstance.clipboard.dangerouslyPasteHTML(textarea.value);
                textarea.style.display = 'none';
                quillContainer.style.display = 'block';
            }
        };

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initQuill);
        } else {
            initQuill();
        }
    })();
</script>
<style>
    /* SuperDesign adaptations */
    .ql-toolbar.ql-snow {
        border: none !important;
        border-bottom: 1px solid var(--border) !important;
        background: var(--surface);
        border-top-left-radius: 8px;
        border-top-right-radius: 8px;
        font-family: inherit;
    }
    .ql-container.ql-snow {
        border: none !important;
        font-family: inherit;
    }
    .ql-editor {
        padding: 20px;
    }
    html[dir="rtl"] .ql-editor {
        text-align: right;
    }
    /* Toolbar colors */
    .ql-snow .ql-stroke {
        stroke: var(--text);
    }
    .ql-snow .ql-fill {
        fill: var(--text);
    }
    .ql-snow .ql-picker {
        color: var(--text);
    }
    .ql-snow.ql-toolbar button:hover, .ql-snow .ql-toolbar button:hover, .ql-snow.ql-toolbar button:focus, .ql-snow .ql-toolbar button:focus, .ql-snow.ql-toolbar button.ql-active, .ql-snow .ql-toolbar button.ql-active, .ql-snow.ql-toolbar .ql-picker-label:hover, .ql-snow .ql-toolbar .ql-picker-label:hover, .ql-snow.ql-toolbar .ql-picker-label.ql-active, .ql-snow .ql-toolbar .ql-picker-label.ql-active, .ql-snow.ql-toolbar .ql-picker-item:hover, .ql-snow .ql-toolbar .ql-picker-item:hover, .ql-snow.ql-toolbar .ql-picker-item.ql-selected, .ql-snow .ql-toolbar .ql-picker-item.ql-selected {
        color: var(--accent);
    }
    .ql-snow.ql-toolbar button:hover .ql-fill, .ql-snow .ql-toolbar button:hover .ql-fill, .ql-snow.ql-toolbar button:focus .ql-fill, .ql-snow .ql-toolbar button:focus .ql-fill, .ql-snow.ql-toolbar button.ql-active .ql-fill, .ql-snow .ql-toolbar button.ql-active .ql-fill, .ql-snow.ql-toolbar .ql-picker-label:hover .ql-fill, .ql-snow .ql-toolbar .ql-picker-label:hover .ql-fill, .ql-snow.ql-toolbar .ql-picker-label.ql-active .ql-fill, .ql-snow .ql-toolbar .ql-picker-label.ql-active .ql-fill, .ql-snow.ql-toolbar .ql-picker-item:hover .ql-fill, .ql-snow .ql-toolbar .ql-picker-item:hover .ql-fill, .ql-snow.ql-toolbar .ql-picker-item.ql-selected .ql-fill, .ql-snow .ql-toolbar .ql-picker-item.ql-selected .ql-fill, .ql-snow.ql-toolbar button:hover .ql-stroke.ql-fill, .ql-snow .ql-toolbar button:hover .ql-stroke.ql-fill, .ql-snow.ql-toolbar button:focus .ql-stroke.ql-fill, .ql-snow .ql-toolbar button:focus .ql-stroke.ql-fill, .ql-snow.ql-toolbar button.ql-active .ql-stroke.ql-fill, .ql-snow .ql-toolbar button.ql-active .ql-stroke.ql-fill, .ql-snow.ql-toolbar .ql-picker-label:hover .ql-stroke.ql-fill, .ql-snow .ql-toolbar .ql-picker-label:hover .ql-stroke.ql-fill, .ql-snow.ql-toolbar .ql-picker-label.ql-active .ql-stroke.ql-fill, .ql-snow .ql-toolbar .ql-picker-label.ql-active .ql-stroke.ql-fill, .ql-snow.ql-toolbar .ql-picker-item:hover .ql-stroke.ql-fill, .ql-snow .ql-toolbar .ql-picker-item:hover .ql-stroke.ql-fill, .ql-snow.ql-toolbar .ql-picker-item.ql-selected .ql-stroke.ql-fill, .ql-snow .ql-toolbar .ql-picker-item.ql-selected .ql-stroke.ql-fill {
        fill: var(--accent);
    }
    .ql-snow.ql-toolbar button:hover .ql-stroke, .ql-snow .ql-toolbar button:hover .ql-stroke, .ql-snow.ql-toolbar button:focus .ql-stroke, .ql-snow .ql-toolbar button:focus .ql-stroke, .ql-snow.ql-toolbar button.ql-active .ql-stroke, .ql-snow .ql-toolbar button.ql-active .ql-stroke, .ql-snow.ql-toolbar .ql-picker-label:hover .ql-stroke, .ql-snow .ql-toolbar .ql-picker-label:hover .ql-stroke, .ql-snow.ql-toolbar .ql-picker-label.ql-active .ql-stroke, .ql-snow .ql-toolbar .ql-picker-label.ql-active .ql-stroke, .ql-snow.ql-toolbar .ql-picker-item:hover .ql-stroke, .ql-snow .ql-toolbar .ql-picker-item:hover .ql-stroke, .ql-snow.ql-toolbar .ql-picker-item.ql-selected .ql-stroke, .ql-snow .ql-toolbar .ql-picker-item.ql-selected .ql-stroke, .ql-snow.ql-toolbar button:hover .ql-stroke-miter, .ql-snow .ql-toolbar button:hover .ql-stroke-miter, .ql-snow.ql-toolbar button:focus .ql-stroke-miter, .ql-snow .ql-toolbar button:focus .ql-stroke-miter, .ql-snow.ql-toolbar button.ql-active .ql-stroke-miter, .ql-snow .ql-toolbar button.ql-active .ql-stroke-miter, .ql-snow.ql-toolbar .ql-picker-label:hover .ql-stroke-miter, .ql-snow .ql-toolbar .ql-picker-label:hover .ql-stroke-miter, .ql-snow.ql-toolbar .ql-picker-label.ql-active .ql-stroke-miter, .ql-snow .ql-toolbar .ql-picker-label.ql-active .ql-stroke-miter, .ql-snow.ql-toolbar .ql-picker-item:hover .ql-stroke-miter, .ql-snow .ql-toolbar .ql-picker-item:hover .ql-stroke-miter, .ql-snow.ql-toolbar .ql-picker-item.ql-selected .ql-stroke-miter, .ql-snow .ql-toolbar .ql-picker-item.ql-selected .ql-stroke-miter {
        stroke: var(--accent);
    }
</style>
