/**
 * Admin media helpers — Laravel File Manager + Metronic CKEditor Classic.
 * Loaded once from layouts/admin (via admin.partials.media-assets).
 */
(function (window, $) {
    'use strict';

    var AdminMedia = window.AdminMedia || {};
    window.AdminMedia = AdminMedia;

    AdminMedia.lfmPrefix = AdminMedia.lfmPrefix || (window.location.origin + '/admin/file_manager');
    AdminMedia.ckUploadUrl = AdminMedia.ckUploadUrl || null;
    AdminMedia.blankImage = AdminMedia.blankImage || '';
    AdminMedia._editors = AdminMedia._editors || [];

    function syncPreview(previewId, url) {
        var $img = $('#' + previewId);
        var wrapId = $img.data('wrap');
        var $wrap = wrapId ? $('#' + wrapId) : $();

        if (url) {
            $img.attr('src', url);
            if ($wrap.length) {
                $wrap.css('background-image', "url('" + url + "')");
                $wrap.closest('.image-input').removeClass('image-input-empty');
            }
        } else {
            $img.attr('src', '');
            if ($wrap.length) {
                $wrap.css('background-image', AdminMedia.blankImage ? "url('" + AdminMedia.blankImage + "')" : 'none');
                $wrap.closest('.image-input').addClass('image-input-empty');
            }
        }
    }

    AdminMedia.initFileManagers = function () {
        if (!$.fn.filemanager) {
            console.warn('AdminMedia: lfm.js is not loaded');
            return;
        }

        var prefix = AdminMedia.lfmPrefix;

        $('[data-lfm-open], [id$="_lfm"], #lfm').each(function () {
            var $el = $(this);
            if ($el.data('lfm-bound')) {
                return;
            }
            $el.data('lfm-bound', 1);
            $el.filemanager('image', { prefix: prefix });
        });

        $(document).on('change', '.admin-lfm-preview-img', function () {
            syncPreview(this.id, $(this).attr('src'));
        });

        $(document).on('click', '.admin-image-picker-clear', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var inputId = $(this).data('input');
            var previewId = $(this).data('preview');
            if (inputId) {
                $('#' + inputId).val('').trigger('change');
            }
            if (previewId) {
                syncPreview(previewId, '');
            }
        });

        var previousSetUrl = window.SetUrl;
        window.SetUrl = function (url, file_path) {
            var inputId = localStorage.getItem('target_input');
            var previewId = localStorage.getItem('target_preview');
            if (inputId) {
                $('#' + inputId).val(file_path).trigger('change');
            }
            if (previewId) {
                $('#' + previewId).attr('src', url).trigger('change');
                syncPreview(previewId, url);
            }
            if (typeof previousSetUrl === 'function' && previousSetUrl !== window.SetUrl) {
                try { previousSetUrl(url, file_path); } catch (err) { /* ignore */ }
            }
        };
    };

    AdminMedia.editorConfig = function (lang) {
        lang = lang || 'ar';
        var isRtl = (lang === 'ar' || lang === 'fa' || lang === 'he' || lang === 'ur');

        // ملاحظة: توليفة العناصر هون مقيّدة بما هو موجود فعلاً داخل bundle الـCKEditor
        // المخصص لميترونيك (ckeditor-classic.bundle.js). عناصر غير مبنية بالحزمة
        // (مثل alignment / underline / fontSize / fontColor) بترمي خطأ عند create()
        // فيسقط الكود للتهيئة الاحتياطية المصغّرة (انظر createEditor أدناه) وتختفي
        // كل الأزرار الإضافية بصمت — كانت هاي بالضبط سبب عدم ظهور مزايا المحرر.
        var config = {
            language: lang,
            toolbar: {
                items: [
                    'heading', '|',
                    'bold', 'italic', 'strikethrough', 'highlight', '|',
                    'bulletedList', 'numberedList', 'todoList', '|',
                    'outdent', 'indent', '|',
                    'link', 'uploadImage', 'insertTable', 'blockQuote', 'horizontalLine', 'code', 'codeBlock', '|',
                    'undo', 'redo'
                ],
                shouldNotGroupWhenFull: true
            },
            image: {
                toolbar: [
                    'imageStyle:inline', 'imageStyle:block', 'imageStyle:side', '|',
                    'toggleImageCaption', 'imageTextAlternative'
                ]
            },
            table: {
                contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells']
            }
        };

        if (AdminMedia.ckUploadUrl) {
            config.ckfinder = { uploadUrl: AdminMedia.ckUploadUrl };
        }

        config._adminDir = isRtl ? 'rtl' : 'ltr';
        config._adminLang = lang;
        return config;
    };

    AdminMedia.applyDirection = function (editor, dir, lang) {
        dir = dir || 'rtl';
        lang = lang || 'ar';

        editor.editing.view.change(function (writer) {
            var root = editor.editing.view.document.getRoot();
            writer.setAttribute('dir', dir, root);
            writer.setAttribute('lang', lang, root);
        });

        var editable = editor.ui.view.editable.element;
        if (editable) {
            editable.setAttribute('dir', dir);
            editable.setAttribute('lang', lang);
            editable.style.direction = dir;
            editable.style.textAlign = dir === 'rtl' ? 'right' : 'left';
        }

        AdminMedia._injectDirectionButtons(editor);
    };

    AdminMedia._injectDirectionButtons = function (editor) {
        var toolbarEl = editor.ui.view.toolbar && editor.ui.view.toolbar.element;
        if (!toolbarEl || toolbarEl.querySelector('.admin-dir-btns')) {
            return;
        }

        var group = document.createElement('div');
        group.className = 'admin-dir-btns';
        group.setAttribute('role', 'group');
        group.style.cssText = 'display:inline-flex;gap:4px;margin-inline-start:8px;align-items:center;';

        function makeDirBtn(text, dir) {
            var btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'btn btn-sm btn-light-primary';
            btn.textContent = text;
            btn.title = dir === 'rtl' ? 'اتجاه النص RTL' : 'Text direction LTR';
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                AdminMedia.setSelectionDirection(editor, dir);
            });
            return btn;
        }

        group.appendChild(makeDirBtn('RTL', 'rtl'));
        group.appendChild(makeDirBtn('LTR', 'ltr'));
        toolbarEl.appendChild(group);
    };

    AdminMedia.setSelectionDirection = function (editor, dir) {
        var editable = editor.ui.view.editable.element;
        if (!editable) {
            return;
        }

        var sel = window.getSelection();
        var target = null;

        if (sel && sel.rangeCount && editable.contains(sel.anchorNode)) {
            var node = sel.anchorNode;
            if (node.nodeType === 3) {
                node = node.parentNode;
            }
            while (node && node !== editable) {
                var tag = (node.nodeName || '').toUpperCase();
                if (['P', 'H1', 'H2', 'H3', 'H4', 'H5', 'H6', 'LI', 'BLOCKQUOTE', 'DIV', 'TD', 'TH'].indexOf(tag) !== -1) {
                    target = node;
                    break;
                }
                node = node.parentNode;
            }
        }

        if (!target) {
            target = editable;
        }

        target.setAttribute('dir', dir);
        target.style.direction = dir;
        target.style.textAlign = dir === 'rtl' ? 'right' : 'left';

        // Keep model in sync so HTML output retains dir
        try {
            editor.model.change(function () {
                editor.updateSourceElement();
            });
        } catch (err) { /* ignore */ }

        // Prefer alignment command when available for better persistence
        try {
            if (editor.commands.get('alignment')) {
                editor.execute('alignment', { value: dir === 'rtl' ? 'right' : 'left' });
            }
        } catch (err2) { /* ignore */ }
    };

    AdminMedia.createEditor = function (target, lang) {
        if (typeof ClassicEditor === 'undefined') {
            console.error('AdminMedia: ClassicEditor is not loaded');
            return Promise.reject(new Error('ClassicEditor missing'));
        }

        var el = typeof target === 'string' ? document.querySelector(target) : target;
        if (!el) {
            return Promise.resolve(null);
        }

        if (el.getAttribute('data-ck-ready') === '1') {
            return Promise.resolve(null);
        }
        el.setAttribute('data-ck-ready', '1');

        var config = AdminMedia.editorConfig(lang || el.getAttribute('data-lang') || 'ar');

        return ClassicEditor.create(el, config).then(function (editor) {
            AdminMedia.applyDirection(editor, config._adminDir, config._adminLang);
            AdminMedia._editors.push(editor);

            var form = el.closest('form');
            if (form && !form.getAttribute('data-ck-sync')) {
                form.setAttribute('data-ck-sync', '1');
                form.addEventListener('submit', function () {
                    AdminMedia._editors.forEach(function (ed) {
                        try { ed.updateSourceElement(); } catch (e) { /* ignore */ }
                    });
                });
            }

            return editor;
        }).catch(function (err) {
            el.removeAttribute('data-ck-ready');
            // Retry with minimal config if toolbar items are unsupported by this build
            return ClassicEditor.create(el, {
                language: config._adminLang,
                ckfinder: AdminMedia.ckUploadUrl ? { uploadUrl: AdminMedia.ckUploadUrl } : undefined
            }).then(function (editor) {
                el.setAttribute('data-ck-ready', '1');
                AdminMedia.applyDirection(editor, config._adminDir, config._adminLang);
                AdminMedia._editors.push(editor);
                return editor;
            }).catch(function (err2) {
                console.error(err2);
                throw err2;
            });
        });
    };

    AdminMedia.initEditors = function () {
        var nodes = document.querySelectorAll('textarea.ckeditor, textarea[data-ckeditor]');
        nodes.forEach(function (node) {
            var lang = node.getAttribute('data-lang') || 'ar';
            if (/^en[_-]/i.test(node.id) || /_en$/i.test(node.id) || node.id.indexOf('en_') === 0) {
                lang = 'en';
            } else if (/^ar[_-]/i.test(node.id) || /_ar$/i.test(node.id) || node.id.indexOf('ar_') === 0) {
                lang = 'ar';
            }
            AdminMedia.createEditor(node, lang);
        });
    };

    AdminMedia.init = function () {
        AdminMedia.initFileManagers();
        AdminMedia.initEditors();
    };

    $(function () {
        AdminMedia.init();
    });
})(window, window.jQuery);
