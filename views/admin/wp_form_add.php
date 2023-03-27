<?php //phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * WP Form Add
 *
 * @category   WP Form Add
 * @package    Mailtarget Form
 */

?>
<div class="mtg-form-plugin">
	<div class="mtg-banner">
		<img src="<?php echo esc_url( MAILTARGET_PLUGIN_URL . '/assets/image/logo.svg' ); ?>" />
	</div>
	<div class="wrap">
		<h1 class="wp-heading-inline">Setup New Form - MTARGET Form</h1>
		<div class="mtg-form-wrapper">
			<form method="post" action="admin.php?page=mailtarget-form-plugin--admin-menu">
				<?php settings_fields( $this->option_group ); ?>
				<?php do_settings_sections( $this->option_group ); ?>
				<table class="form-table">
					<tr class="user-rich-editing-wrap">
						<th>MTARGET Form Name</th>
						<td><strong><?php echo esc_attr( $form['name'] ); ?></strong>
							<input type="hidden" name="form_id" value="<?php echo esc_attr( $form['formId'] ); ?>">
						</td>
					</tr>
					<tr class="user-rich-editing-wrap">
						<th>Name</th>
						<td>
							<input type="text" class="regular-text" name="widget_name">
						</td>
					</tr>
					<tr class="user-rich-editing-wrap">
						<th>Title</th>
						<td>
							<input type="text" class="regular-text" name="widget_title">
						</td>
					</tr>
					<tr class="user-rich-editing-wrap">
						<th>Description</th>
						<td>
							<textarea class="regular-text" name="widget_description"></textarea>
						</td>
					</tr>
					<tr class="user-rich-editing-wrap">
						<th>Submit Title</th>
						<td>
							<input type="text" class="regular-text" name="widget_submit_desc">
						</td>
					</tr>

					<tr class="user-rich-editing-wrap">
						<th>Redirect Url</th>
						<td>
							<input type="text" class="regular-text" name="widget_redir">
						</td>
					</tr>
					<tr>
						<td></td>
						<td>
							<input type="hidden" value="<?php echo wp_create_nonce( 'wpnonce_action' ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>" name="_wpnonce"/>
							<input type="hidden" value="create_widget" name="mailtarget_form_action">
							<?php submit_button( 'Create Form' ); ?></td>
					</tr>
				</table>
			</form>
		</div>
	</div>
</div>
