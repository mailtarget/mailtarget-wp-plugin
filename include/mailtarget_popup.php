<?php

class MailTarget_Popup {

    public static function init () {
        $formId = esc_attr(get_option('mtg_popup_form_id'));
        $delay = esc_attr(get_option('mtg_popup_delay')) * 1000;
        if ($formId == '') return false;
        require_once MAILTARGET_PLUGIN_DIR . '/include/mailtarget_form.php';

        ?>
        <div class="mtg-popup-modal" style="display: none">
            <div class="modal"><?php echo load_mailtarget_popup($formId) ?></div>
        </div>
        <script>
            var delay = <?php echo $delay ?>;
            var modal = new tingle.modal({
                footer: false,
                stickyFooter: false,
                closeMethods: ['overlay', 'button', 'escape'],
                closeLabel: "Close",
                cssClass: ['custom-class-1', 'custom-class-2'],
                onOpen: function() {
                    console.log('modal open');
                },
                onClose: function() {
                    console.log('modal closed');
                },
                beforeClose: function() {
                    return true; // close the modal
                }
            });
            modal.setContent(document.querySelector('.mtg-popup-modal').innerHTML);
            setTimeout(function () {
                modal.open()
            }, delay)
        </script>
        <?php
    }
}

add_action('wp_footer', array('MailTarget_Popup', 'init'));