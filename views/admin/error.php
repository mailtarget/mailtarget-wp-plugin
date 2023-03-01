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

$err     = (array) $error;
$message = '';
$code    = '';
$data    = '';

if ( isset( $err['errors'] ) ) {
	$err = $err['errors'];
}
if ( isset( $err['mailtarget-error'] ) ) {
	$err = $err['mailtarget-error'];
}
if ( isset( $err[0] ) ) {
	$err = $err[0];
}
if ( isset( $err['data'] ) ) {
	$err = $err['data'];
}

if ( isset( $err['code'] ) ) {
	$code = $err['code'];
}
if ( isset( $err['message'] ) ) {
	$message = $err['message'];
}
if ( isset( $err['data'] ) ) {
	$data = $err['data'];
}


?>
<div class="mtg-form-plugin">
	<div class="mtg-banner">
		<img src="<?php echo esc_url( MAILTARGET_PLUGIN_URL . '/assets/image/logo.png' ); ?>" />
	</div>

	<div class="wrap">
		<h1 class="">Error - MTARGET Form</h1>
		<?php
		switch ( $code ) {
			case 101:
				?>
				<div class="update-nag">Apikey not set, please update your apikey at
				<a href="admin.php?page=mailtarget-form-plugin--admin-menu-config">mtarget form setting</a></div>
				<?php
				break;
			case 'tokenException':
				?>
				<div class="update-nag">Apikey invalid or expired, please update your apikey at
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
