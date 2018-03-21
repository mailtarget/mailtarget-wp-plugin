(function() {
    tinymce.create('tinymce.plugins.mailtarget_shortcode', {
        init : function(ed, url) {
            ed.addCommand('mailtarget_shortcode_popup', function() {
                    ed.windowManager.open({
                            file: ajaxurl + '?action=mailtarget_tinymce_window',
                            width : 400 + ed.getLang('example.delta_width', 0),
                            height : 400 + ed.getLang('example.delta_height', 0),
                            inline : 1
                        }, {
                            plugin_url : url
                        }
                    );
                }
            );
            ed.addButton('mailtarget_shortcode', {
                    title : 'Add a MailTarget sign-up form',
                    image : url+'/../image/wp-icon.png',
                    cmd :  'mailtarget_shortcode_popup'
                }
            );
        },
        createControl : function(n, ml) {
            return null;
        }
    });
    tinymce.PluginManager.add('mailtarget_shortcode', tinymce.plugins.mailtarget_shortcode);
})();
