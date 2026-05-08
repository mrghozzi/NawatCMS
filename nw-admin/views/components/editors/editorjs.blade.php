@props(['name', 'value' => ''])

<div style="border: 1px solid var(--border); border-radius: 8px; background: var(--bg); color: var(--text);">
    <div id="editorjs" style="padding: 16px; min-height: 400px;"></div>
</div>

<input type="hidden" name="{{ $name }}" id="nawat-editorjs-hidden-input">

<!-- Editor.js Styles to fit SuperDesign -->
<style>
    .ce-block__content, .ce-toolbar__content {
        max-width: 100% !important;
    }
    .codex-editor__redactor {
        padding-bottom: 50px !important;
    }
    .ce-toolbar__actions {
        right: 100%;
        margin-right: 5px; /* RTL adjustments needed later if in Arabic */
    }
    /* Simple RTL support for Editor.js Toolbar if html[dir="rtl"] */
    html[dir="rtl"] .ce-toolbar__actions {
        right: auto;
        left: 100%;
        margin-right: 0;
        margin-left: 5px;
    }
</style>

<!-- Load Editor.js Core and Tools -->
<script src="https://cdn.jsdelivr.net/npm/@editorjs/editorjs@latest"></script>
<script src="https://cdn.jsdelivr.net/npm/@editorjs/header@latest"></script>
<script src="https://cdn.jsdelivr.net/npm/@editorjs/list@latest"></script>
<script src="https://cdn.jsdelivr.net/npm/@editorjs/quote@latest"></script>
<script src="https://cdn.jsdelivr.net/npm/@editorjs/delimiter@latest"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        
        let existingData = {};
        try {
            // Check if the value is valid JSON, otherwise it might be empty or old HTML
            const rawValue = `{!! addslashes($value) !!}`;
            if (rawValue && rawValue.trim().startsWith('{')) {
                existingData = JSON.parse(rawValue);
            }
        } catch (e) {
            console.error("Failed to parse existing Editor.js content", e);
        }

        const editor = new EditorJS({
            holder: 'editorjs',
            data: existingData,
            placeholder: '{{ __("Let\'s write an awesome story!") }}',
            tools: {
                header: {
                    class: Header,
                    inlineToolbar: true,
                    config: {
                        placeholder: '{{ __("Enter a header") }}',
                        levels: [2, 3, 4],
                        defaultLevel: 2
                    }
                },
                list: {
                    class: List,
                    inlineToolbar: true,
                },
                quote: {
                    class: Quote,
                    inlineToolbar: true,
                    config: {
                        quotePlaceholder: '{{ __("Enter a quote") }}',
                        captionPlaceholder: '{{ __("Quote\'s author") }}',
                    },
                },
                delimiter: Delimiter,
            },
            i18n: {
                direction: document.documentElement.dir || 'ltr',
            }
        });

        // Intercept form submission
        const hiddenInput = document.getElementById('nawat-editorjs-hidden-input');
        const form = hiddenInput.closest('form');
        
        if (form) {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                editor.save().then((outputData) => {
                    hiddenInput.value = JSON.stringify(outputData);
                    form.submit();
                }).catch((error) => {
                    console.error('Saving failed: ', error);
                });
            });
        }
    });
</script>
