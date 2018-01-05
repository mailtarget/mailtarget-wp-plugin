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

if ($code == 32) $errSlug = 'expired-token';
if ($code == 410 and $data = 'form') $errSlug = 'form-not-found';

?>
<div class="wrap mtg-form-plugin">
    <?php include MAILTARGET_PLUGIN_DIR . '/views/admin/style.php' ?>
    <div class="mtg-banner">
        <img src="<?php echo MAILTARGET_PLUGIN_URL ?>/assets/image/logo.png" />
    </div>
    <h1 class="">Error - Mailtarget Form</h1>
    <?php switch ($errSlug) {
        case 'expired-token':
            ?><div class="update-nag">Apikey invalid or expired, please update your apikey at
            <a href="admin.php?page=mailtarget-form-plugin--admin-menu-config">mailtarget form setting</a></div><?php
            break;
        case 'form-not-found':
            ?><div class="update-nag">Form data not found, possible form not published yet</div><?php
            break;
        default:
            ?><div class="update-nag">Service from server currently unavailable right now</div><?php
            break;
    }?>
<!--    <textarea style="width: 100%; height: 400px;">--><?php //print_r($error) ?><!--</textarea>-->
</div>