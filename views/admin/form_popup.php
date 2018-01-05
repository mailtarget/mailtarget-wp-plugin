<?php
if ($formId == '') {
    $formId = esc_attr(get_option('mtg_popup_form_id'));
    $formName = esc_attr(get_option('mtg_popup_form_name'));
}
$popupEnable = esc_attr(get_option('mtg_popup_enable')) == '1';
?>

<?php if (isset($_GET['success'])) {
    ?><div class="update-nag">Mailtarget Form Popup configuration updated successfully !</div><?php
} ?>

<div class="mtg-form-plugin">
    <div class="mtg-banner">
        <img src="<?php echo esc_url(MAILTARGET_PLUGIN_URL.'/assets/image/logo.png') ?>" />
    </div>

    <div class="wrap">
        <h1 class="wp-heading-inline">Setup New Form - Mailtarget Form</h1>
        <div class="mtg-form-wrapper">
            <?php if ($formId != '') { ?>
                <form method="post" action="admin.php?page=mailtarget-form-plugin--admin-menu">
                    <?php settings_fields($this->option_group); ?>
                    <?php do_settings_sections($this->option_group); ?>
                    <table class="form-table">
                        <tr class="user-rich-editing-wrap">
                            <th>MailTarget Form Name</th>
                            <td>
                                <strong><?php echo esc_attr($formName) ?></strong>
                                or <a class="page-title-action" href="admin.php?page=mailtarget-form-plugin--admin-menu-widget-form&for=popup">change form</a>
                                <input type="hidden" name="popup_form_id" value="<?php echo esc_attr($formId) ?>">
                                <input type="hidden" name="popup_form_name" value="<?php echo esc_attr($formName) ?>">
                            </td>
                        </tr>
                        <tr class="user-rich-editing-wrap">
                            <th>Title</th>
                            <td>
                                <input type="text" class="regular-text" name="popup_title" value="<?php echo esc_attr(get_option('mtg_popup_title')); ?>">
                            </td>
                        </tr>
                        <tr class="user-rich-editing-wrap">
                            <th>Description</th>
                            <td>
                                <textarea class="regular-text" name="popup_description"><?php echo esc_attr(get_option('mtg_popup_description')); ?></textarea>
                                <p>* you may fill with plain text or html</p>
                            </td>
                        </tr>
                        <tr class="user-rich-editing-wrap">
                            <th>Delay</th>
                            <td>
                                <input type="number" class="regular-text" name="popup_delay" value="<?php echo esc_attr(get_option('mtg_popup_delay')); ?>">
                            </td>
                        </tr>
                        <tr class="user-rich-editing-wrap">
                            <th>Redirect Url</th>
                            <td>
                                <input type="text" class="regular-text" name="popup_redirect" value="<?php echo esc_attr(get_option('mtg_popup_redirect')); ?>">
                                <p>* please fill with a valid url</p>
                            </td>
                        </tr>

                        <tr class="user-rich-editing-wrap">
                            <th>Popup Status</th>
                            <td>
                                <select name="mtg_popup_enable">
                                    <option value="1" <?php if ($popupEnable) echo esc_attr('selected') ?>>Enable</option>
                                    <option value="0" <?php if (!$popupEnable) echo esc_attr('selected') ?>>Disable</option>
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <td></td>
                            <td>
                                <input type="hidden" value="popup_config" name="mailtarget_form_action">
                                <?php submit_button('Setup Popup'); ?></td>
                        </tr>
                    </table>
                </form>
                <?php
            } else {
                ?>
                <br>
                <br>
                <br>
                <a class="page-title-action" href="admin.php?page=mailtarget-form-plugin--admin-menu-widget-form&for=popup">select form</a>
                <?php

            } ?>
        </div>
    </div>
</div>