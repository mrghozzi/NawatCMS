@props(['name', 'value' => ''])

<div class="tinymce-wrapper" style="border-radius: 8px; overflow: hidden; border: 1px solid var(--border);">
    <textarea id="tinymce-editor-{{ $name }}" name="{{ $name }}">{{ $value }}</textarea>
</div>

<!-- Load TinyMCE from CDN -->
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

<script>
    (function() {
        function initTinyMCE() {
            if (typeof tinymce === 'undefined') {
                document.querySelector('.tinymce-wrapper').innerHTML = '<div style="color: red; padding: 20px;">TinyMCE failed to load from CDN. Please check your internet connection.</div>';
                return;
            }

            const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
            const isRtl = document.documentElement.dir === 'rtl';

            tinymce.init({
                selector: '#tinymce-editor-{{ $name }}',
                height: 500,
                menubar: false,
                directionality: isRtl ? 'rtl' : 'ltr',
                skin: isDark ? 'oxide-dark' : 'oxide',
                content_css: isDark ? 'dark' : 'default',
                plugins: [
                    'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                    'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                    'insertdatetime', 'media', 'table', 'help', 'wordcount'
                ],
                toolbar: 'undo redo | blocks | ' +
                    'bold italic backcolor | alignleft aligncenter ' +
                    'alignright alignjustify | bullist numlist outdent indent | ' +
                    'removeformat | help',
                content_style: 'body { font-family: Inter, Helvetica, Arial, sans-serif; font-size:16px }',
                setup: function (editor) {
                    editor.on('change', function () {
                        editor.save(); // Updates the underlying textarea
                    });
                }
            });
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initTinyMCE);
        } else {
            initTinyMCE();
        }
    })();
</script>
