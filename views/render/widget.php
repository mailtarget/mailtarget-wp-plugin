<?php
/**
 * Widget
 *
 * @category   Widget
 * @package    Mailtarget Form
 */

$mt_title     = '';//phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
$description  = '';
$submit_title = '';
$redir_ulr    = '';
$hash         = substr( md5( wp_rand() ), 0, 7 );

$data = $widget['data'];
if ( isset( $data['widget_title'] ) ) {
	$mt_title = $data['widget_title'];
}
if ( isset( $data['widget_description'] ) ) {
	$description = $data['widget_description'];
}
if ( isset( $data['widget_submit_desc'] ) ) {
	$submit_title = $data['widget_submit_desc'];
}
if ( isset( $data['widget_redir'] ) ) {
	$redir_ulr = $data['widget_redir'];
}

if ( '' === $submit_title ) {
	$submit_title = 'Submit';
}
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
			foreach ( $form['component'] as $item ) {
				switch ( $item['type'] ) {
					case 'inputText':
						mtgf_render_text( $item );
						break;
					case 'inputTextarea':
						mtgf_render_textarea( $item );
						break;
					case 'inputMultiple':
						mtgf_render_multiple( $item );
						break;
					case 'inputDropdown':
						mtgf_render_dropdown( $item );
						break;
					case 'inputCheckbox':
						mtgf_render_checkbox( $item );
						break;
					case 'uploadFile':
						mtgf_render_upload( $item );
						break;
					case 'inputPhone':
						mtgf_render_phone( $item );
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
					<input type="hidden" value="<?php echo esc_html( $redir_ulr ); ?>" name="mailtarget_form_redir">
					<input type="hidden" value="<?php echo esc_html( $form['formId'] ); ?>" name="mailtarget_form_id">
					<input type="submit" class="mt-o-btn mt-btn-submit" data-target="<?php echo esc_html( $hash ); ?>" value="<?php echo esc_html( $submit_title ); ?>">
				</div>
			</div>

		</form>
	</div>
</div>
