<?php
$data = json_decode($widget->data);
$widget_title = '';
$widget_description = '';
$widget_submit_desc = '';

if (isset($data->widget_title)) $widget_title = $data->widget_title;
if (isset($data->widget_description)) $widget_description = $data->widget_description;
if (isset($data->widget_submit_desc)) $widget_submit_desc = $data->widget_submit_desc;
?>
<div class="wrap">
    <?php include MAILTARGET_PLUGIN_DIR . '/views/admin/style.php' ?>
    <div class="mtg-banner">
        <img src="<?php echo MAILTARGET_PLUGIN_URL ?>/assets/image/logo.png" />
    </div>
    <h1 class="wp-heading-inline">Edit Form - Mailtarget Form</h1>
    <form method="post" action="options-general.php?page=mailtarget-form-plugin--admin-menu">
        <?php settings_fields( $this->option_group ); ?>
        <?php do_settings_sections( $this->option_group ); ?>
        <table class="form-table">
            <tr class="user-rich-editing-wrap">
                <th>Form</th>
                <td>
                    <strong><?php echo $form['name'] ?></strong>
                </td>
            </tr>
            <tr class="user-rich-editing-wrap">
                <th>Name</th>
                <td>
                    <input type="text" class="regular-text" name="widget_name" value="<?php echo $widget->name ?>">
                </td>
            </tr>
            <tr class="user-rich-editing-wrap">
                <th>Title</th>
                <td>
                    <input type="text" class="regular-text" name="widget_title" value="<?php echo $widget_title ?>">
                </td>
            </tr>
            <tr class="user-rich-editing-wrap">
                <th>Description</th>
                <td>
                    <textarea class="regular-text" name="widget_description"><?php echo $widget_description ?></textarea>
                </td>
            </tr>
            <tr class="user-rich-editing-wrap">
                <th>Submit Title</th>
                <td>
                    <input type="text" class="regular-text" name="widget_submit_desc" value="<?php echo $widget_submit_desc ?>">
                </td>
            </tr>

            <tr>
                <td></td>
                <td>
                    <input type="hidden" value="edit_widget" name="mailtarget_form_action">
                    <input type="hidden" value="<?php echo $widget->id ?>" name="widget_id">
                    <?php submit_button('Edit Widget'); ?></td>
            </tr>
        </table>
    </form>
</div>