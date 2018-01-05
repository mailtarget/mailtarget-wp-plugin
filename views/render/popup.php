<?php
$title = esc_attr(get_option('mtg_popup_title'));
$description = get_option('mtg_popup_description');
$submitTitle = esc_attr(get_option('mtg_popup_submit'));
if ($submitTitle === '') $submitTitle = 'Submit';
?>
<div>
    <?php if ($title !== '') { ?><h2><?php echo $title ?></h2><?php } ?>
    <?php if ($description !== '') { ?><p><?php echo $description ?></p><?php } ?>
    <div class="mt-c-form">
        <form method="post">
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
                    <input type="hidden" value="submit_form" name="mailtarget_form_action">
                    <input type="hidden" value="popup" name="mailtarget_form_mode">
                    <input type="hidden" value="<?php echo $form['formId'] ?>" name="mailtarget_form_id">
                    <input type="submit" class="mt-o-btn mt-btn-submit" value="<?php echo $submitTitle ?>">
                </div>
            </div>

        </form>
    </div>
</div>