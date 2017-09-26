<div class="wrap">
	<h1>Mailtarget Form Setting</h1>
	<p>Please set your setting in this form</p>
	<form method="post" action="options.php">
		<?php settings_fields( $this->option_group ); ?>
		<?php do_settings_sections( $this->option_group ); ?>
		<table class="form-table">
			<tr class="user-rich-editing-wrap">
				<th>API Token</th>
				<td><textarea class="regular-text" name="mtg_api_token"
					><?php echo esc_attr( get_option( 'mtg_api_token' ) ); ?></textarea></td>
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