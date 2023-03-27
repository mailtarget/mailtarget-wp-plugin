<?php
/**
 * Widget
 *
 * @category   Widget
 * @package    Mailtarget Form
 * @NeedTODO support CKEditor tag, adjust esc_html to support CKEditor tag //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
 */

$mailtarget_title        = '';
$mailtarget_description  = '';
$mailtarget_submit_title = '';
$mailtarget_redir_ulr    = '';
$mailtarget_hash         = substr( md5( wp_rand() ), 0, 7 );

$data = $widget['data'];  //phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
if ( isset( $data['widget_title'] ) ) {
	$mailtarget_title = $data['widget_title'];
}
if ( isset( $data['widget_description'] ) ) {
	$mailtarget_description = $data['widget_description'];
}
if ( isset( $data['widget_submit_desc'] ) ) {
	$mailtarget_submit_title = $data['widget_submit_desc'];
}
if ( isset( $data['widget_redir'] ) ) {
	$mailtarget_redir_ulr = $data['widget_redir'];
}

if ( '' === $mailtarget_submit_title ) {
	$mailtarget_submit_title = 'Submit';
}
?>
<div>
	<?php
	if ( '' !== $mailtarget_title ) {
		?>
		<h2><?php echo esc_html( $mailtarget_title ); ?></h2><?php } ?>
	<?php
	if ( '' !== $mailtarget_description ) {
		?>
		<p><?php echo esc_html( $mailtarget_description ); ?></p><?php } ?>
	<div class="mt-c-form">
		<p class="mt-c-form__success success-<?php echo esc_html( $mailtarget_hash ); ?>" style="display: none;"></p>
		<form  method="post" id="form-<?php echo esc_html( $mailtarget_hash ); ?>" enctype="multipart/form-data">
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
					<p class="mt-c-form__error error-<?php echo esc_html( $mailtarget_hash ); ?>" style="display: none;"></p>
					<input type="hidden" value="submit_form" name="mailtarget_form_action">
					<input type="hidden" value="<?php echo wp_create_nonce( 'wpnonce_action' );//phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>" name="_wpnonce"/>
					<input type="hidden" value="<?php echo esc_html( $mailtarget_redir_ulr ); ?>" name="mailtarget_form_redir">
					<input type="hidden" value="<?php echo esc_html( $form['formId'] ); ?>" name="mailtarget_form_id">
					<input type="submit" class="mt-o-btn mt-btn-submit" data-target="<?php echo esc_html( $mailtarget_hash ); ?>" value="<?php echo esc_html( $mailtarget_submit_title ); ?>">
				</div>
			</div>

		</form>
	</div>
</div>
