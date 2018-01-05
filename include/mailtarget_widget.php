<?php

class MailTarget_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'mailtarget_widget',
            __('Mailtarget sign up form', 'mailtarget'),
            array('description' => __(
                'Mailtarget sign up form Widget', 'mailtarget'
            ))
        );
    }

    public function widget ($args, $instance) {
        error_log(json_encode($args));
        error_log(json_encode($instance));
        if (!isset($instance['mailtarget_form_id'])) {
            echo 'id not recognize';
            return false;
        }
        $widgetId = sanitize_key($instance['mailtarget_form_id']);

        require_once MAILTARGET_PLUGIN_DIR . '/include/mailtarget_form.php';
//        ob_start();
        load_mailtarget_form($widgetId);
//        return ob_get_clean();
    }

    public function form ($instance) {
        global $wpdb;
        $widgets = $wpdb->get_results("select * from " . $wpdb->base_prefix .
            "mailtarget_forms order by time desc limit 500");
        $id = 0;
        if (isset($instance['mailtarget_form_id'])) $id = $instance['mailtarget_form_id'];
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('mailtarget_form_id') ?>">
                <?php echo __('Select form:', 'mailtarget') ?>
            </label>
            <select class="widefat"
                    id="<?php echo $this->get_field_id('mailtarget_form_id') ?>"
                    name="<?php echo $this->get_field_name('mailtarget_form_id') ?>"
            >
                <?php
                foreach ($widgets as $item) {
                    ?><option value="<?php echo $item->id ?>"
                    <?php echo $item->id == $id ? 'selected="selected"' : ''  ?>
                    ><?php echo $item->name ?></option><?php
                }
                ?>
            </select>
        </p>
        <?php
    }
}

function register_mailtarget_widget () {
    register_widget('MailTarget_Widget');
}

add_action('widgets_init', 'register_mailtarget_widget');