<?php //phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * MailTarget_Shortcode.php Doc Comment
 *
 * MailTarget_Shortcode is file that Converts shortcode into html.
 *
 * @category   MailTarget_Shortcode
 * @package    Mailtarget Form
 */

/**
 * MailTarget_Popup
 */
class MailTarget_Shortcode {


	/**
	 * WordPress' init() hook
	 */
	public static function init() {

		add_shortcode(
			'mailtarget_form',
			array(
				'MailTarget_Shortcode',
				'mailtarget_generate_shortcode',
			)
		);

		add_action(
			'wp_ajax_nopriv_mailtarget_tinymce_window',
			array( 'MailTarget_Shortcode', 'mailtarget_tinymce_window' )
		);
		add_action(
			'wp_ajax_mailtarget_tinymce_window',
			array( 'MailTarget_Shortcode', 'mailtarget_tinymce_window' )
		);

		if ( get_user_option( 'rich_editing' ) ) {
			add_filter(
				'mce_buttons',
				array(
					'MailTarget_Shortcode',
					'mailtarget_register_button',
				)
			);
			add_filter(
				'mce_external_plugins',
				array(
					'MailTarget_Shortcode',
					'mailtarget_add_tinymce_plugin',
				)
			);
		}
	}

	/**
	 * Add tinymce button to toolbar
	 *
	 * @param mixed $buttons is buttons id to select.
	 */
	public static function mailtarget_register_button( $buttons ) {
		array_push( $buttons, 'mailtarget_shortcode' );
		return $buttons;
	}

	/**
	 * Register tinymce plugin
	 *
	 * @param mixed $plugin_array is buttons id to select.
	 */
	public static function mailtarget_add_tinymce_plugin( $plugin_array ) {
		$plugin_array['mailtarget_shortcode'] = MAILTARGET_PLUGIN_URL . '/assets/js/mailtarget_shortcode.js';
		return $plugin_array;
	}

	/**
	 *
	 * Converts shortcode into html
	 *
	 * @param string $attributes is attributes.
	 */
	public static function mailtarget_generate_shortcode( $attributes ) {
		require_once MAILTARGET_PLUGIN_DIR . '/include/mailtarget_form.php';
		$form_attributes = shortcode_atts( array( 'form_id' => '1' ), $attributes );

		ob_start();
		mailtarget_load_form( $form_attributes['form_id'] );
		return ob_get_clean();
	}
}

add_action( 'init', array( 'MailTarget_Shortcode', 'init' ) );
