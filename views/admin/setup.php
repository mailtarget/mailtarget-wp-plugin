<?php
/**
 * Setup
 *
 * @package  Mailtarget_Form
 * @author    {{author_name}} <{{author_email}}>
 */

$popup_enable = esc_attr( get_option( 'mtg_popup_enable' ) ) === '1'; //phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
?>

<?php
$condition = isset(  //phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
	$_GET['success'] //phpcs:ignore WordPress.Security.NonceVerification.Recommended
);
if ( $condition ) {
	?>
	<div class="update-nag">MTARGET Form configuration updated successfully !</div>
	<?php
}
?>

<div class="mtg-form-plugin">
	<div class="mtg-banner">
		<img src="<?php echo esc_url( MAILTARGET_PLUGIN_URL . '/assets/image/logo.svg' ); ?>" />
	</div>

	<div class="wrap">
		<h1 class="wp-heading-inline">Setup - MTARGET Form</h1>
		<div class="mtg-form-wrapper">
			<p>Use this plugin to show your MTARGET Form in your WordPress Application as embed to your post, as widget or as popup form. You may choose MTARGET Form directly from your WordPress Application.</p>
			<p>But you must set API token via form below, you can find the API token at integration page of MTARGET Application.</p>
			<form method="post" action="admin.php?page=mailtarget-form-plugin--admin-menu">
				<?php settings_fields( $this->option_group ); ?>
				<?php do_settings_sections( $this->option_group ); ?>
				<table class="form-table">
					<tr class="user-rich-editing-wrap">
						<th>API Token</th>
						<td><textarea class="regular-text" name="mtg_api_token"
							><?php echo esc_attr( get_option( 'mtg_api_token' ) ); ?></textarea></td>
					</tr>
					<tr class="user-rich-editing-wrap">
						<th>Popup Status</th>
						<td>
							<select name="mtg_popup_enable">
								<option value="1" 
								<?php
								if ( $popup_enable ) {
									echo esc_attr( 'selected' );}
								?>
								>Enable</option>
								<option value="0" 
								<?php
								if ( ! $popup_enable ) {
									echo esc_attr( 'selected' );}
								?>
								>Disable</option>
							</select>
						</td>
					</tr>

					<tr>
						<td></td>
						<td>
							<input type="hidden" value="setup_setting" name="mailtarget_form_action">
							<input type="hidden" value="<?php echo wp_create_nonce( 'wpnonce_action' ); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>" name="_wpnonce"/>
							<?php submit_button(); ?></td>
					</tr>
				</table>
			</form>
		</div>
	</div>
</div>
