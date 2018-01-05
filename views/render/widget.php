<div>
	<h2><?php echo $widget['data']['widget_title']; ?></h2>
    <p><?php echo $widget['data']['widget_description']; ?></p>
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
                    <input type="submit" class="mt-o-btn mt-btn-submit" value="<?php echo $widget['data']['widget_submit_desc']; ?>">
                </div>
            </div>

        </form>
    </div>
</div>