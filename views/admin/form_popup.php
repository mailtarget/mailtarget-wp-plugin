<?php
if ($formId == '') {
    $formId = esc_attr(get_option('mtg_popup_form_id'));
    $formName = esc_attr(get_option('mtg_popup_form_name'));
}
?>
<div class="wrap mtg-form-plugin">
    <?php include MAILTARGET_PLUGIN_DIR . '/views/admin/style.php' ?>
    <div class="mtg-banner">
        <img src="<?php echo MAILTARGET_PLUGIN_URL ?>/assets/image/logo.png" />
    </div>
    <h1 class="wp-heading-inline">Setup New Form - Mailtarget Form</h1>
    <?php if ($valid === null) { ?>
        <br>
        <div class="update-nag">Token not correctly set / empty, please update
            <a href="admin.php?page=mailtarget-form-plugin--admin-menu-config">config</a></div><?php
    }?>

    <?php if ($valid) {
        if ($formId != '') {
            ?>
            <form method="post" action="admin.php?page=mailtarget-form-plugin--admin-menu">
                <?php settings_fields($this->option_group); ?>
                <?php do_settings_sections($this->option_group); ?>
                <table class="form-table">
                    <tr class="user-rich-editing-wrap">
                        <th>MailTarget Form Name</th>
                        <td>
                            <strong><?php echo $formName ?></strong>
                            <input type="hidden" name="popup_form_id" value="<?php echo $formId ?>">
                            <input type="hidden" name="popup_form_name" value="<?php echo $formName ?>">
                        </td>
                    </tr>
                    <tr class="user-rich-editing-wrap">
                        <th>Width</th>
                        <td>
                            <input type="number" class="regular-text" name="popup_width" value="<?php echo esc_attr(get_option('mtg_popup_width')); ?>">
                        </td>
                    </tr>
                    <tr class="user-rich-editing-wrap">
                        <th>Height</th>
                        <td>
                            <input type="number" class="regular-text" name="popup_height" value="<?php echo esc_attr(get_option('mtg_popup_height')); ?>">
                        </td>
                    </tr>
                    <tr class="user-rich-editing-wrap">
                        <th>Delay</th>
                        <td>
                            <input type="number" class="regular-text" name="popup_delay" value="<?php echo esc_attr(get_option('mtg_popup_delay')); ?>">
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
        }
    } ?>

</div>