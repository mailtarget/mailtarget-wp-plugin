<?php
$error = (array) $error;
$errSlug = '';
$message = '';
$code = '';
$data = '';

if (isset($error['errors'])) $error = $error['errors'];
if (isset($error['mailtarget-error'])) $error = $error['mailtarget-error'];
if (isset($error[0])) $error = $error[0];
if (isset($error['data'])) $error = $error['data'];

if (isset($error['code'])) $code = $error['code'];
if (isset($error['message'])) $message = $error['message'];
if (isset($error['data'])) $data = $error['data'];


?>
<div class="mtg-form-plugin">
    <div class="mtg-banner">
        <img src="<?php echo esc_url(MAILTARGET_PLUGIN_URL.'/assets/image/logo.png') ?>" />
    </div>

    <div class="wrap">
        <h1 class="">Error - MTARGET Form</h1>
        <?php switch ($code) {
            case 101:
                ?><div class="update-nag">Apikey not set, please update your apikey at
                <a href="admin.php?page=mailtarget-form-plugin--admin-menu-config">mtarget form setting</a></div><?php
                break;
            case 'tokenException':
                ?><div class="update-nag">Apikey invalid or expired, please update your apikey at
                <a href="admin.php?page=mailtarget-form-plugin--admin-menu-config">mtarget form setting</a></div><?php
                break;
            case 'entityNotFoundException':
                ?><div class="update-nag">Form data not found, possible form not published yet</div><?php
                break;
            case 'cap-domain-regist':
                ?><div class="update-nag">Form you are selecting is enabling captcha, for now this plugin not supporting captcha.</div><?php
                break;
            default:
                ?><div class="update-nag">Service from server currently unavailable right now</div><?php
                break;
        }?>
    </div>
</div>