<?php
/**
 * Doc Comment for views/admin/error.php
 *
 * @package  Mailtarget_Form
 * @author    {{author_name}} <{{author_email}}>
 * @copyright {{author_copyright}}
 * @license   {{author_license}}
 * @link      {{author_url}}
 */

$mailtarget_page    = isset( $_GET['page'] ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : ''; //phpcs:ignore WordPress.Security.NonceVerification.Recommended
$mailtarget_title   = '';
$mailtarget_err     = (array) $error;
$mailtarget_message = '';
$mailtarget_code    = '';
$mailtarget_data    = '';

if ( isset( $mailtarget_err['errors'] ) ) {
	$mailtarget_err = $mailtarget_err['errors']; //phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
}
if ( isset( $mailtarget_err['mailtarget-error'] ) ) {
	$mailtarget_err = $mailtarget_err['mailtarget-error'];
}
if ( isset( $mailtarget_err[0] ) ) {
	$mailtarget_err = $mailtarget_err[0];
}
if ( isset( $mailtarget_err['data'] ) ) {
	$mailtarget_err = $mailtarget_err['data'];
}

if ( isset( $mailtarget_err['code'] ) ) {
	$mailtarget_code = $mailtarget_err['code'];
}
if ( isset( $mailtarget_err['message'] ) ) {
	$mailtarget_message = $mailtarget_err['message'];
}
if ( isset( $mailtarget_err['data'] ) ) {
	$mailtarget_data = $mailtarget_err['data'];
}

switch ( $mailtarget_page ) {
	case 'mailtarget-form-plugin--admin-menu':
		$mailtarget_title = 'List';
		break;
	case 'mailtarget-form-plugin--admin-menu-widget-form':
		$mailtarget_title = 'Select Form';
		break;
	case 'mailtarget-form-plugin--admin-menu-popup-main':
		$mailtarget_title = 'Pop-up Setting';
		break;
	default:
		$mailtarget_title = 'Error';
		break;
}


?>
<div class="mtg-form-plugin">
	<div class="mtg-banner">
		<img src="<?php echo esc_url( MAILTARGET_PLUGIN_URL . '/assets/image/logo.svg' ); ?>" />
	</div>

	<div class="wrap">
		<h1 class="wp-heading-inline"><?php echo esc_html( $mailtarget_title ); ?> - MTARGET Form</h1>
		<?php
		switch ( $mailtarget_code ) {
			case 101:
				?>
				<div class="update-nag">API token not set, please update your API token at
				<a href="admin.php?page=mailtarget-form-plugin--admin-menu-config">mtarget form setting</a></div>
				<?php
				break;
			case 'tokenException':
				?>
				<div class="update-nag">API token invalid or expired, please update your API token at
				<a href="admin.php?page=mailtarget-form-plugin--admin-menu-config">mtarget form setting</a></div>
				<?php
				break;
			case 'entityNotFoundException':
				?>
				<div class="update-nag">Form data not found, possible form not published yet</div>
				<?php
				break;
			case 'cap-domain-regist':
				?>
				<div class="update-nag">Form you are selecting is enabling captcha, for now this plugin not supporting captcha.</div>
				<?php
				break;
			default:
				?>
				<div class="update-nag">Service from server currently unavailable right now</div>
				<?php
				break;
		}
		?>
	</div>
</div>
