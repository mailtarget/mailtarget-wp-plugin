<?php //phpcs:ignore WordPress.Files.FileName.NotHyphenatedLowercase
/**
 * Mailtarget Form
 *
 * Mailtarget Form.
 *
 * @category   Mailtarget Form
 * @package    Mailtarget Form
 * @subpackage Mailtarget Form
 */

?>
<?php
/**
 * Define widget id.
 *
 * @param string $widget_id is widget id to get.
 **/
function mailtarget_load_form( $widget_id ) {
	global $wpdb;
	$widget_id = sanitize_key( $widget_id );

	$table_name = "{$wpdb->base_prefix}mailtarget_forms";
	$widget     = $wpdb->get_row(
		$wpdb->prepare(
			"SELECT * FROM `$table_name` WHERE `id` = %s", //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$widget_id
		)
	); // WPCS: db call ok. // WPCS: cache ok.
	if ( ! isset( $widget->form_id ) ) {
		echo 'Widget not exist';
		return;
	}
	$widget->data = json_decode( $widget->data, true );
	$widget       = (array) $widget;
	require_once MAILTARGET_PLUGIN_DIR . '/lib/MailtargetApi.php';
	$key        = get_option( 'mtg_api_token' );
	$company_id = get_option( 'mtg_company_id' );
	$api        = new MailtargetApi( $key, $company_id );
	$form       = $api->get_form_detail( $widget['form_id'] );
	if ( is_wp_error( $form ) ) {
		echo( 'Failed to get form data' );
		return;
	}

	if ( ! isset( $form['formId'] ) ) {
		echo( 'Form data not valid' );
		return;
	}
	$country = array();
	$city    = array();
	foreach ( $form['component'] as $key => $item ) {
		if ( ! isset( $item['setting'] ) ) {
			continue;
		}
		$setting = $item['setting'];
		switch ( $setting['name'] ) {
			case 'country':
				if ( count( $country ) < 1 ) {
					$country = $api->get_country();
				}
				$form['component'][ $key ]['setting']['options'] = $country;
				break;
			case 'city':
				if ( count( $city ) < 1 ) {
					$city = $api->get_city();
				}
				$form['component'][ $key ]['setting']['options'] = $city;
				break;
			case 'gender':
				$form['component'][ $key ]['setting']['options'] = array( 'male', 'female', 'other' );
				break;
			default:
				break;
		}
	}

	include MAILTARGET_PLUGIN_DIR . '/views/render/widget.php';
}

/**
 * Define mailtarget_load_popup.
 *
 * @param string $form_id is form id to get.
 **/
function mailtarget_load_popup( $form_id ) {
	$form_id = sanitize_key( $form_id );
	require_once MAILTARGET_PLUGIN_DIR . '/lib/MailtargetApi.php';
	$key        = get_option( 'mtg_api_token' );
	$company_id = get_option( 'mtg_company_id' );
	$api        = new MailtargetApi( $key, $company_id );
	$form       = $api->get_form_detail( $form_id );
	if ( is_wp_error( $form ) ) {
		echo( 'Failed to get form data' );
		return;
	}

	if ( ! isset( $form['formId'] ) ) {
		echo( 'Form data not valid' );
		return;
	}
	$country = array();
	$city    = array();
	foreach ( $form['component'] as $key => $item ) {
		if ( ! isset( $item['setting'] ) ) {
			continue;
		}
		$setting = $item['setting'];
		switch ( $setting['name'] ) {
			case 'country':
				if ( count( $country ) < 1 ) {
					$country = $api->get_country();
				}
				$form['component'][ $key ]['setting']['options'] = $country;
				break;
			case 'city':
				if ( count( $city ) < 1 ) {
					$city = $api->get_city();
				}
				$form['component'][ $key ]['setting']['options'] = $city;
				break;
			case 'gender':
				$form['component'][ $key ]['setting']['options'] = array( 'male', 'female', 'other' );
				break;
			default:
				break;
		}
	}

	include MAILTARGET_PLUGIN_DIR . '/views/render/popup.php';
}

/**
 * Define mailtarget_render_text.
 *
 * @param string $row is row to get.
 **/
function mailtarget_render_text( $row ) {
	if ( ! isset( $row['setting'] ) ) {
		return;
	}
	include MAILTARGET_PLUGIN_DIR . '/views/render/input_text.php';
}

/**
 * Define mailtarget_render_textarea.
 *
 * @param string $row is row to get.
 **/
function mailtarget_render_textarea( $row ) {
	if ( ! isset( $row['setting'] ) ) {
		return;
	}
	include MAILTARGET_PLUGIN_DIR . '/views/render/input_textarea.php';
}

/**
 * Define mailtarget_render_dropdown.
 *
 * @param string $row is row to get.
 **/
function mailtarget_render_dropdown( $row ) {
	if ( ! isset( $row['setting'] ) ) {
		return;
	}
	include MAILTARGET_PLUGIN_DIR . '/views/render/input_dropdown.php';
}

/**
 * Define mailtarget_render_multiple.
 *
 * @param string $row is row to get.
 **/
function mailtarget_render_multiple( $row ) {
	if ( ! isset( $row['setting'] ) ) {
		return;
	}
	include MAILTARGET_PLUGIN_DIR . '/views/render/input_multiple.php';
}

/**
 * Define mailtarget_render_checkbox.
 *
 * @param string $row is row to get.
 **/
function mailtarget_render_checkbox( $row ) {
	if ( ! isset( $row['setting'] ) ) {
		return;
	}
	include MAILTARGET_PLUGIN_DIR . '/views/render/input_checkbox.php';
}

/**
 * Define mailtarget_render_upload.
 *
 * @param string $row is row to get.
 **/
function mailtarget_render_upload( $row ) {
	if ( ! isset( $row['setting'] ) ) {
		return;
	}
	include MAILTARGET_PLUGIN_DIR . '/views/render/input_upload.php';
}

/**
 * Define mailtarget_render_phone.
 *
 * @param string $row is row to get.
 **/
function mailtarget_render_phone( $row ) {
	if ( ! isset( $row['setting'] ) ) {
		return;
	}
	include MAILTARGET_PLUGIN_DIR . '/views/render/input_phone.php';
}
