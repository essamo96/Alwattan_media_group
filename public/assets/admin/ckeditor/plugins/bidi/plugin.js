/**
 * Minimal "bidi" plugin: adds LTR / RTL text-direction toggle buttons to the
 * toolbar. This build of CKEditor ships without the official bidi plugin,
 * so this reimplements just the direction toggle on the current block.
 */
(function () {
    function toggleDir(editor, dir) {
        var range = editor.getSelection() && editor.getSelection().getRanges()[0];
        if (!range) {
            return;
        }
        var block = range.startContainer.getAscendant(function (node) {
            return node.type === CKEDITOR.NODE_ELEMENT && CKEDITOR.dtd.$block[node.getName()];
        }, true) || editor.editable();

        if (block.getAttribute('dir') === dir) {
            block.removeAttribute('dir');
        } else {
            block.setAttribute('dir', dir);
            block.removeStyle('direction');
        }
        editor.fire('change');
    }

    CKEDITOR.plugins.add('bidi', {
        icons: 'bidiltr,bidirtl',
        init: function (editor) {
            editor.addCommand('bidiltr', {
                exec: function (editor) {
                    toggleDir(editor, 'ltr');
                }
            });
            editor.addCommand('bidirtl', {
                exec: function (editor) {
                    toggleDir(editor, 'rtl');
                }
            });

            editor.ui.addButton('BidiLtr', {
                label: 'Text direction left to right',
                command: 'bidiltr',
                toolbar: 'bidi'
            });
            editor.ui.addButton('BidiRtl', {
                label: 'Text direction right to left',
                command: 'bidirtl',
                toolbar: 'bidi'
            });
        }
    });
})();
