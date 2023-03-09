<?php
/**
 * Mailtarget Form
 *
 * Mailtarget Form.
 *
 * @category   Components
 * @package    Mailtarget Form
 * @subpackage Mailtarget Form
 */

?>
<?php
function mailtarget_load_form( $widget_id ) {
	global $wpdb;
	$widget_id = sanitize_key( $widget_id );
	$sql       = 'SELECT * FROM ' . $wpdb->base_prefix . "mailtarget_forms where id = '$widget_id'";
	$widget    = $wpdb->get_row( $sql );
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
	$form       = $api->getFormDetail( $widget['form_id'] );
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
					$country = $api->getCountry();
				}
				$form['component'][ $key ]['setting']['options'] = $country;
				break;
			case 'city':
				if ( count( $city ) < 1 ) {
					$city = $api->getCity();
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

function mailtarget_load_popup( $form_id ) {
	$form_id = sanitize_key( $form_id );
	require_once MAILTARGET_PLUGIN_DIR . '/lib/MailtargetApi.php';
	$key        = get_option( 'mtg_api_token' );
	$company_id = get_option( 'mtg_company_id' );
	$api        = new MailtargetApi( $key, $company_id );
	$form       = $api->getFormDetail( $form_id );
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
					$country = $api->getCountry();
				}
				$form['component'][ $key ]['setting']['options'] = $country;
				break;
			case 'city':
				if ( count( $city ) < 1 ) {
					$city = $api->getCity();
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

function mtgf_render_text( $row ) {
	if ( ! isset( $row['setting'] ) ) {
		return;
	}
	include MAILTARGET_PLUGIN_DIR . '/views/render/input_text.php';
}

function mtgf_render_textarea( $row ) {
	if ( ! isset( $row['setting'] ) ) {
		return;
	}
	include MAILTARGET_PLUGIN_DIR . '/views/render/input_textarea.php';
}

function mtgf_render_dropdown( $row ) {
	if ( ! isset( $row['setting'] ) ) {
		return;
	}
	include MAILTARGET_PLUGIN_DIR . '/views/render/input_dropdown.php';
}

function mtgf_render_multiple( $row ) {
	if ( ! isset( $row['setting'] ) ) {
		return;
	}
	include MAILTARGET_PLUGIN_DIR . '/views/render/input_multiple.php';
}

function mtgf_render_checkbox( $row ) {
	if ( ! isset( $row['setting'] ) ) {
		return;
	}
	include MAILTARGET_PLUGIN_DIR . '/views/render/input_checkbox.php';
}

function mtgf_render_upload( $row ) {
	if ( ! isset( $row['setting'] ) ) {
		return;
	}
	include MAILTARGET_PLUGIN_DIR . '/views/render/input_upload.php';
}

function mtgf_render_phone( $row ) {
	if ( ! isset( $row['setting'] ) ) {
		return;
	}
	include MAILTARGET_PLUGIN_DIR . '/views/render/input_phone.php';
}
