<?php
$popupEnable = esc_attr(get_option('mtg_popup_enable')) == '1';
?>

<?php if (isset($_GET['success'])) {
    ?><div class="update-nag">Mailtarget Form configuration updated successfully !</div><?php
} ?>

<div class="wrap mtg-form-plugin">
    <?php include MAILTARGET_PLUGIN_DIR . '/views/admin/style.php' ?>
    <div class="mtg-banner">
        <img src="<?php echo MAILTARGET_PLUGIN_URL ?>/assets/image/logo.png" />
    </div>
    <h1 class="wp-heading-inline">Setup - Mailtarget Form</h1>
	<br>
	<p>Use this plugin to show your MailTarget Form in your Wordpress Application as embed to your post, as widget or as popup form. You may choose MailTarget Form directly from your Wordpress Application.</p>
    <p>But you must set API token via form below, you can find the API token at integration page of MailTarget Application.</p>
	<form method="post" action="admin.php?page=mailtarget-form-plugin--admin-menu">
		<?php settings_fields( $this->option_group ); ?>
		<?php do_settings_sections( $this->option_group ); ?>
		<table class="form-table">
			<tr class="user-rich-editing-wrap">
				<th>API Token</th>
				<td><textarea class="regular-text" name="mtg_api_token"
					><?php echo esc_attr(get_option('mtg_api_token')); ?></textarea></td>
			</tr>
			<tr class="user-rich-editing-wrap">
				<th>Popup Status</th>
                <td>
                    <select name="mtg_popup_enable">
                        <option value="1" <?php if ($popupEnable) echo 'selected' ?>>Enable</option>
                        <option value="0" <?php if (!$popupEnable) echo 'selected' ?>>Disable</option>
                    </select>
                </td>
			</tr>

			<tr>
				<td></td>
				<td>
                    <input type="hidden" value="setup_setting" name="mailtarget_form_action">
                    <?php submit_button(); ?></td>
			</tr>
		</table>
	</form>
</div>