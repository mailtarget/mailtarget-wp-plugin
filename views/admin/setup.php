<div class="wrap mtg-form-plugin">
    <?php include MAILTARGET_PLUGIN_DIR . '/views/admin/style.php' ?>
    <div class="mtg-banner">
        <img src="<?php echo MAILTARGET_PLUGIN_URL ?>/assets/image/logo.png" />
    </div>
    <h1 class="wp-heading-inline">Setup - Mailtarget Form</h1>
	<br>
	<p>Please set your setting in this form</p>
	<form method="post" action="admin.php?page=mailtarget-form-plugin--admin-menu">
		<?php settings_fields( $this->option_group ); ?>
		<?php do_settings_sections( $this->option_group ); ?>
		<table class="form-table">
			<tr class="user-rich-editing-wrap">
				<th>API Token</th>
				<td><textarea class="regular-text" name="mtg_api_token"
					><?php echo esc_attr(get_option('mtg_api_token')); ?></textarea></td>
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