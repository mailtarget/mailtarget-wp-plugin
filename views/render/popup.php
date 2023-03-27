<?php
/**
 * Popup
 *
 * @category   Popup
 * @package    Mailtarget Form
 * @NeedTODO support CKEditor tag, adjust esc_html to support CKEditor tag //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
 */

$mt_title     = esc_attr( get_option( 'mtg_popup_title' ) ); //phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
$description  = get_option( 'mtg_popup_description' ); //phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
$redir_ulr    = get_option( 'mtg_popup_redirect' ); //phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
$submit_title = esc_attr( get_option( 'mtg_popup_submit' ) ); //phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
if ( '' === $submit_title ) {
	$submit_title = 'Submit'; //phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
}
$hash = substr( md5( wp_rand() ), 0, 7 ); //phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
?>
<div>
	<?php
	if ( '' !== $mt_title ) {
		?>
		<h2><?php echo esc_html( $mt_title ); ?></h2><?php } ?>
	<?php
	if ( '' !== $description ) {
		?>
		<p><?php echo esc_html( $description ); ?></p><?php } ?>
	<div class="mt-c-form">
		<p class="mt-c-form__success success-<?php echo esc_html( $hash ); ?>" style="display: none;"></p>
		<form method="post" id="form-<?php echo esc_html( $hash ); ?>" enctype="multipart/form-data">
			<?php
			foreach ( $form['component'] as $item ) { //phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
				switch ( $item['type'] ) {
					case 'inputText':
						mailtarget_render_text( $item );
						break;
					case 'inputTextarea':
						mailtarget_render_textarea( $item );
						break;
					case 'inputMultiple':
						mailtarget_render_multiple( $item );
						break;
					case 'inputDropdown':
						mailtarget_render_dropdown( $item );
						break;
					case 'inputCheckbox':
						mailtarget_render_checkbox( $item );
						break;
					case 'uploadFile':
						mailtarget_render_upload( $item );
						break;
					case 'inputPhone':
						mailtarget_render_phone( $item );
						break;
					default:
						break;
				}
			}
			?>
			<div class="mt-c-form__wrap">
				<div class="mt-c-form__btn-action">
					<p class="mt-c-form__error error-<?php echo esc_html( $hash ); ?>" style="display: none;"></p>
					<input type="hidden" value="submit_form" name="mailtarget_form_action">
					<input type="hidden" value="<?php echo wp_create_nonce( 'wpnonce_action' );//phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>" name="_wpnonce"/> 
					<input type="hidden" value="popup" name="mailtarget_form_mode">
					<input type="hidden" value="<?php echo esc_html( $redir_ulr ); ?>" name="mailtarget_form_redir">
					<input type="hidden" value="<?php echo esc_html( $form['formId'] ); ?>" name="mailtarget_form_id">
					<input type="submit" class="mt-o-btn mt-btn-submit" data-target="<?php echo esc_html( $hash ); ?>" value="<?php echo esc_html( $submit_title ); ?>">
				</div>
			</div>

		</form>
	</div>
</div>
