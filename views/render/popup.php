<?php
$title = esc_attr(get_option('mtg_popup_title'));
$description = get_option('mtg_popup_description');
$redirUlr = get_option('mtg_popup_redirect');
$submitTitle = esc_attr(get_option('mtg_popup_submit'));
if ($submitTitle === '') $submitTitle = 'Submit';
$hash = substr(md5(mt_rand()), 0, 7);
?>
<div>
    <?php if ($title !== '') { ?><h2><?php echo $title ?></h2><?php } ?>
    <?php if ($description !== '') { ?><p><?php echo $description ?></p><?php } ?>
    <div class="mt-c-form">
        <p class="mt-c-form__success success-<?php echo $hash ?>" style="display: none;"></p>
        <form method="post" id="form-<?php echo $hash ?>">
            <?php
            foreach ($form['component'] as $item) {
                switch ($item['type']) {
                    case 'inputText':
                        mtgf_render_text($item);
                        break;
                    case 'inputTextarea':
                        mtgf_render_textarea($item);
                        break;
                    case 'inputMultiple':
                        mtgf_render_multiple($item);
                        break;
                    case 'inputDropdown':
                        mtgf_render_dropdown($item);
                        break;
                    case 'inputCheckbox':
                        mtgf_render_checkbox($item);
                        break;
                    default:
                        break;
                }
            }
            ?>
            <div class="mt-c-form__wrap">
                <div class="mt-c-form__btn-action">
                    <p class="mt-c-form__error error-<?php echo $hash ?>" style="display: none;"></p>
                    <input type="hidden" value="submit_form" name="mailtarget_form_action">
                    <input type="hidden" value="popup" name="mailtarget_form_mode">
                    <input type="hidden" value="<?php echo $redirUlr ?>" name="mailtarget_form_redir">
                    <input type="hidden" value="<?php echo $form['formId'] ?>" name="mailtarget_form_id">
                    <input type="submit" class="mt-o-btn mt-btn-submit" data-target="<?php echo $hash ?>" value="<?php echo $submitTitle ?>">
                </div>
            </div>

        </form>
    </div>
</div>