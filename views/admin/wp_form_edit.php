<?php
$data = json_decode($widget->data);
$widget_title = '';
$widget_description = '';
$widget_submit_desc = '';
$widget_redir = '';

if (isset($data->widget_title)) $widget_title = $data->widget_title;
if (isset($data->widget_description)) $widget_description = $data->widget_description;
if (isset($data->widget_submit_desc)) $widget_submit_desc = $data->widget_submit_desc;
if (isset($data->widget_redir)) $widget_redir = $data->widget_redir;
?>
<div class="mtg-form-plugin">
    <div class="mtg-banner">
        <img src="<?php echo esc_url(MAILTARGET_PLUGIN_URL.'/assets/image/logo.png') ?>" />
    </div>
    <div class="wrap">
        <h1 class="wp-heading-inline">Edit Form - Mailtarget Form</h1>
        <div class="mtg-form-wrapper">
            <form method="post" action="admin.php?page=mailtarget-form-plugin--admin-menu">
                <?php settings_fields( $this->option_group ); ?>
                <?php do_settings_sections( $this->option_group ); ?>
                <table class="form-table">
                    <tr class="user-rich-editing-wrap">
                        <th>Form</th>
                        <td>
                            <strong><?php echo esc_attr($form['name']) ?></strong>
                        </td>
                    </tr>
                    <tr class="user-rich-editing-wrap">
                        <th>Name</th>
                        <td>
                            <input type="text" class="regular-text" name="widget_name" value="<?php echo esc_attr($widget->name) ?>">
                        </td>
                    </tr>
                    <tr class="user-rich-editing-wrap">
                        <th>Title</th>
                        <td>
                            <input type="text" class="regular-text" name="widget_title" value="<?php echo esc_attr($widget_title) ?>">
                        </td>
                    </tr>
                    <tr class="user-rich-editing-wrap">
                        <th>Description</th>
                        <td>
                            <textarea class="regular-text" name="widget_description"><?php echo esc_attr($widget_description) ?></textarea>
                        </td>
                    </tr>
                    <tr class="user-rich-editing-wrap">
                        <th>Submit Title</th>
                        <td>
                            <input type="text" class="regular-text" name="widget_submit_desc" value="<?php echo esc_attr($widget_submit_desc) ?>">
                        </td>
                    </tr>
                    <tr class="user-rich-editing-wrap">
                        <th>Redirect Url</th>
                        <td>
                            <input type="text" class="regular-text" name="widget_redir" value="<?php echo esc_attr($widget_redir) ?>">
                        </td>
                    </tr>

                    <tr>
                        <td></td>
                        <td>
                            <input type="hidden" value="edit_widget" name="mailtarget_form_action">
                            <input type="hidden" value="<?php echo esc_attr($widget->id) ?>" name="widget_id">
                            <?php submit_button('Edit Widget'); ?></td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</div>