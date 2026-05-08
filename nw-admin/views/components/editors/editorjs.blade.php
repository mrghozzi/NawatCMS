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
    (function() {
        function initEditor() {
            let existingData = {};
            try {
                const rawValue = {!! json_encode($value) !!};
                if (typeof rawValue === 'string' && rawValue.trim().startsWith('{')) {
                    existingData = JSON.parse(rawValue);
                }
            } catch (e) {
                console.error("Failed to parse existing Editor.js content", e);
            }

            try {
                const editor = new EditorJS({
                    holder: 'editorjs',
                    data: existingData,
                    placeholder: '{{ __("Let\'s write an awesome story!") }}',
                    tools: {
                        header: {
                            class: typeof Header !== 'undefined' ? Header : undefined,
                            inlineToolbar: true,
                        },
                        list: {
                            class: typeof List !== 'undefined' ? List : (typeof NestedList !== 'undefined' ? NestedList : undefined),
                            inlineToolbar: true,
                        },
                        quote: {
                            class: typeof Quote !== 'undefined' ? Quote : undefined,
                            inlineToolbar: true,
                        },
                        delimiter: typeof Delimiter !== 'undefined' ? Delimiter : undefined,
                    },
                    i18n: {
                        direction: document.documentElement.dir || 'ltr',
                    }
                });

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
            } catch (e) {
                document.getElementById('editorjs').innerHTML = '<div style="color: red; padding: 20px;"><strong>Editor Error:</strong> ' + e.message + '<br>Check if CDN scripts are loaded correctly or if there is a tool naming mismatch.</div>';
                console.error("Editor.js Init Error: ", e);
            }
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initEditor);
        } else {
            initEditor();
        }
    })();
</script>
