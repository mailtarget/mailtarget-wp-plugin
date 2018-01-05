<?php
$title = $widget['data']['widget_title'];
$description = $widget['data']['widget_description'];
$submitTitle = $widget['data']['widget_submit_desc'];
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
                        render_text($item);
                        break;
                    case 'inputTextarea':
                        render_textarea($item);
                        break;
                    case 'inputMultiple':
                        render_multiple($item);
                        break;
                    case 'inputDropdown':
                        render_dropdown($item);
                        break;
                    case 'inputCheckbox':
                        render_checkbox($item);
                        break;
                    default:
                        break;
                }
            }
            ?>
            <div class="mt-c-form__wrap">
                <div class="mt-c-form__btn-action">
                    <input type="hidden" value="submit_form" name="mailtarget_form_action">
                    <input type="hidden" value="<?php echo $form['formId'] ?>" name="mailtarget_form_id">
                    <input type="submit" class="mt-o-btn mt-btn-submit" value="<?php echo $submitTitle ?>">
                </div>
            </div>

        </form>
    </div>
</div>