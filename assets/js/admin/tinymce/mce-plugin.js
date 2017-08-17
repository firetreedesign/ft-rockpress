(function (tinymce) {

    tinymce.PluginManager.add('rockpress_shortcode', function (editor) {

		editor.addCommand('RockPress_Shortcode', function () {
            if (window.RockPressShortcodeForm) {
                window.RockPressShortcodeForm.open(editor.id);
            }
        });

    });

})(window.tinymce);
