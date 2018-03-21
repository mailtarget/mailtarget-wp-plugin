<?php

/*
  Plugin Name: MailTarget Form
  Description: The MailTarget plugin to simplify embedding Mailtarget Form in your post or as widget, also easily to set Mailtarget Forms as popup.
  Version: 1.0.3
  Author: MailTarget Teams
  Author URI: https://mailtarget.co/
  License: GPL V3
 */

define('MAILTARGET_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('MAILTARGET_PLUGIN_URL', plugins_url('', __FILE__));

if (!class_exists('MailtargetApi')) {
	require_once(MAILTARGET_PLUGIN_DIR . '/lib/MailtargetApi.php');
}

class MailtargetFormPlugin {
	private static $instance = null;
	private $plugin_path;
	private $plugin_url;
    private $text_domain = '';
    private $option_group = 'mtg-form-group';
    private $ajax_post = false;

	/**
	 * Creates or returns an instance of this class.
	 */
	public static function get_instance() {
		// If an instance hasn't been created and set to $instance create an instance and set it to $instance.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Initializes the plugin by setting localization, hooks, filters, and administrative functions.
	 */
	private function __construct() {
		$this->plugin_path = plugin_dir_path( __FILE__ );
		$this->plugin_url  = plugin_dir_url( __FILE__ );

		load_plugin_textdomain( $this->text_domain, false, $this->plugin_path . '\lang' );

		add_action( 'wp_enqueue_scripts', array( $this, 'register_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'register_styles' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_styles' ) );

		register_activation_hook( __FILE__, array( $this, 'activation' ) );
		register_deactivation_hook( __FILE__, array( $this, 'deactivation' ) );

        add_action( 'admin_menu', array( $this, 'set_admin_menu' ) );
        add_action( 'admin_init', array( $this, 'register_setting') );
        add_action( 'admin_init', array( $this, 'handling_admin_post') );
        add_action( 'init', array( $this, 'handling_post') );
//        add_action( 'wp_ajax_nopriv_form_submit', array( $this, 'handling_ajax_post') );
	}

	public function get_plugin_url() {
		return $this->plugin_url;
	}

	public function get_plugin_path() {
		return $this->plugin_path;
	}

    /**
     * Place code that runs at plugin activation here.
     */
    public function activation() {
	    global $wpdb;
	    $table_name = $wpdb->base_prefix . "mailtarget_forms";
	    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

	    $charset_collate = ' CHARACTER SET utf8mb4 COLLATE utf8mb4_bin';

	    $sql = "CREATE TABLE IF NOT EXISTS " . $table_name . " (
              id mediumint(9) NOT NULL AUTO_INCREMENT,
              time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
              form_id tinytext NOT NULL,
              name tinytext NOT NULL,
              type tinyint(1) default '1' NOT NULL,
              data text NOT NULL,
              PRIMARY KEY (id)
           ) DEFAULT ".$charset_collate. ";";
	    dbDelta($sql);
	}

    /**
     * Place code that runs at plugin deactivation here.
     */
    public function deactivation() {

	}

    /**
     * Enqueue and register JavaScript files here.
     */
    public function register_scripts() {
        ?>
        <script type="application/javascript" src="<?php echo esc_url(MAILTARGET_PLUGIN_URL . '/assets/js/tingle/tingle.min.js') ?>"></script>
        <script type="application/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script type="text/javascript" >
            $(document).ready(function($) {

                $('input[type=submit').on('click', function (e) {
                    e.preventDefault();
                    var _this = $(this);
                    var target = _this.attr('data-target');
                    var data = $('#form-' + target).serializeArray();
                    var errorTarget = $('.error-' + target);
                    var successTarget = $('.success-' + target);
                    var submitUrl = '<?php echo admin_url('admin-ajax.php') ?>';
                    var submitData = {
                        mailtarget_ajax_post: true
                    }
                    data.forEach(function (item) {
                        submitData[item.name] = item.value
                    })
                    errorTarget.hide();
                    successTarget.hide();
                    _this.attr('disabled', 'disabled');

                    $.post(submitUrl, submitData, function(response) {
                        _this.removeAttr('disabled');
                        if (response.code !== undefined) {
                            switch (response.code) {
                                case 400:
                                    errorTarget.text(response.msg);
                                    errorTarget.show();
                                    break;
                                case 200:
                                    successTarget.text('Form submitted successfully.');
                                    successTarget.show();
                                    $('#form-' + target).hide();
                                    setTimeout(function () {
                                        if (submitData.mailtarget_form_redir !== undefined) {
                                            document.location.href = submitData.mailtarget_form_redir
                                        }
                                    }, 2000)
                                    break;
                            }
                        }
                    }, 'json');
                })
            });
        </script>
        <?php
	}

    /**
     * Enqueue and register CSS files here.
     */
    public function register_styles() {
        ?>
        <link rel="stylesheet"  href="<?php echo esc_url(MAILTARGET_PLUGIN_URL.'/assets/css/style.css') ?>" type="text/css" media="all" />
        <link rel="stylesheet"  href="<?php echo esc_url(MAILTARGET_PLUGIN_URL.'/assets/js/tingle/tingle.min.css') ?>" type="text/css" media="all" />
        <?php
	}

    public function register_admin_styles() {
        ?>
        <link rel="stylesheet"  href="<?php echo esc_url(MAILTARGET_PLUGIN_URL.'/assets/css/mailtarget_admin.css') ?>" type="text/css" media="all" />
        <?php
	}

	function register_setting () {
        register_setting($this->option_group, 'mtg_api_token');
        register_setting($this->option_group, 'mtg_company_id');
        register_setting($this->option_group, 'mtg_popup_enable');
        register_setting($this->option_group, 'mtg_popup_form_id');
        register_setting($this->option_group, 'mtg_popup_form_name');
        register_setting($this->option_group, 'mtg_popup_delay');
        register_setting($this->option_group, 'mtg_popup_title');
        register_setting($this->option_group, 'mtg_popup_description');
        register_setting($this->option_group, 'mtg_popup_submit');
        register_setting($this->option_group, 'mtg_popup_redirect');
    }

    function handling_admin_post () {

        $getAction = isset($_GET['action']) ? sanitize_key($_GET['action']) : null;

        if ($getAction != null) {
            if ($getAction === 'delete') {
                $id = isset($_GET['id']) ? sanitize_text_field($_GET['id']) : null;
                if($id == null) return false;
                global $wpdb;
                $wpdb->delete($wpdb->base_prefix . "mailtarget_forms", array('id' => $id));
                return wp_redirect('admin.php?page=mailtarget-form-plugin--admin-menu');
            }
        }

        $postAction = isset($_POST['mailtarget_form_action']) ?
            sanitize_key($_POST['mailtarget_form_action']) : null;

        if($postAction == null) return false;

        switch ($postAction) {
            case 'setup_setting':
                $apiToken = isset($_POST['mtg_api_token']) ?
                    sanitize_text_field($_POST['mtg_api_token']) : null;
                $popupEnable = isset($_POST['mtg_popup_enable']) && intval($_POST['mtg_popup_enable']) == 1 ? 1 : 0;
                $data = array(
                    'mtg_api_token' => $apiToken,
                    'mtg_popup_enable' => $popupEnable,
                );
	            $api = $this->get_api($data['mtg_api_token']);
	            if (!$api) return false;
                $team = $api->getTeam();
                $redirect = 'admin.php?page=mailtarget-form-plugin--admin-menu-config';
                if (!is_wp_error($team)) {
                    $redirect .= '&success=1';
                    update_option('mtg_company_id', $team['companyId']);
                    update_option('mtg_api_token', $data['mtg_api_token']);
                    update_option('mtg_popup_enable', $data['mtg_popup_enable']);
                }
                wp_redirect($redirect);
                break;
            case 'popup_config':
                $popupFormId = isset($_POST['popup_form_id']) && $_POST['popup_form_id'] != '' ?
                    sanitize_text_field($_POST['popup_form_id']) : null;
                $popupFormName = isset($_POST['popup_form_name']) && $_POST['popup_form_name'] != '' ?
                    sanitize_text_field($_POST['popup_form_name']) : __('Join for Newsletter', 'mailtarget');
                $popupFormDelay = isset($_POST['popup_delay']) && intval($_POST['popup_delay']) > 0 ?
                    intval($_POST['popup_delay']) : 10;
                $popupTitle = isset($_POST['popup_title']) && $_POST['popup_title'] != '' ?
                    sanitize_text_field($_POST['popup_title']) : __('Join form', 'mailtarget');
                $popupDesc = isset($_POST['popup_description']) && $_POST['popup_description'] != '' ?
                    sanitize_textarea_field($_POST['popup_description']) :
                    __('Please send me your newsletter', 'mailtarget');
                $popupRedirect = isset($_POST['popup_redirect']) && $_POST['popup_redirect'] != '' ?
                    esc_url($_POST['popup_redirect']) : null;
                $popupEnable = isset($_POST['mtg_popup_enable']) && intval($_POST['mtg_popup_enable']) == 1 ? 1 : 0;

	            update_option('mtg_popup_form_id', $popupFormId);
	            update_option('mtg_popup_form_name', $popupFormName);
	            update_option('mtg_popup_delay', $popupFormDelay);
	            update_option('mtg_popup_title', $popupTitle);
	            update_option('mtg_popup_description', $popupDesc);
	            update_option('mtg_popup_redirect', $popupRedirect);
                update_option('mtg_popup_enable', $popupEnable);
	            wp_redirect('admin.php?page=mailtarget-form-plugin--admin-menu-popup-main&success=1');
                break;
            case 'create_widget':
	            global $wpdb;
	            $table_name = $wpdb->base_prefix . "mailtarget_forms";
	            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

                $formId = isset($_POST['form_id']) && $_POST['form_id'] != '' ?
                    sanitize_text_field($_POST['form_id']) : null;
                $widgetName = isset($_POST['widget_name']) && $_POST['widget_name'] != '' ?
                    sanitize_text_field($_POST['widget_name']) : __('Newsletter Form', 'mailtarget');
                $widgetTitle = isset($_POST['widget_title']) && $_POST['widget_title'] != '' ?
                    sanitize_text_field($_POST['widget_title']) : __('Newsletter Form', 'mailtarget');
                $widgetDesc = isset($_POST['widget_description']) && $_POST['widget_description'] != '' ?
                    sanitize_textarea_field($_POST['widget_description']) :
                    __('Please send me your newsletter', 'mailtarget');
                $widgetSubmit = isset($_POST['widget_submit_desc']) && $_POST['widget_submit_desc'] != '' ?
                    sanitize_text_field($_POST['widget_submit_desc']) :
                    __('Submit', 'mailtarget');
                $widgetRedir = isset($_POST['widget_redir']) && $_POST['widget_redir'] != '' ?
                    sanitize_text_field($_POST['widget_redir']) : null;

	            $input = array(
                    'time' => current_time('mysql'),
                    'form_id' => $formId,
                    'name' => $widgetName,
                    'type' => 1,
                    'data' => json_encode(array(
                        'widget_title' => $widgetTitle,
                        'widget_description' => $widgetDesc,
                        'widget_submit_desc' => $widgetSubmit,
                        'widget_redir' => $widgetRedir
                    ))
                );
                $wpdb->insert($table_name, $input);
                break;
            case 'edit_widget':
	            global $wpdb;
	            $table_name = $wpdb->base_prefix . "mailtarget_forms";
	            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

                $widgetId = isset($_POST['widget_id']) && $_POST['widget_id'] != '' ?
                    sanitize_text_field($_POST['widget_id']) : null;
                $widgetName = isset($_POST['widget_name']) && $_POST['widget_name'] != '' ?
                    sanitize_text_field($_POST['widget_name']) : __('Newsletter Form', 'mailtarget');
                $widgetTitle = isset($_POST['widget_title']) && $_POST['widget_title'] != '' ?
                    sanitize_text_field($_POST['widget_title']) : __('Newsletter Form', 'mailtarget');
                $widgetDesc = isset($_POST['widget_description']) && $_POST['widget_description'] != '' ?
                    sanitize_textarea_field($_POST['widget_description']) :
                    __('Please send me your newsletter', 'mailtarget');
                $widgetSubmit = isset($_POST['widget_submit_desc']) && $_POST['widget_submit_desc'] != '' ?
                    sanitize_text_field($_POST['widget_submit_desc']) :
                    __('Submit', 'mailtarget');
                $widgetRedir = isset($_POST['widget_redir']) && $_POST['widget_redir'] != '' ?
                    sanitize_text_field($_POST['widget_redir']) : null;

	            $input = array(
                    'time' => current_time('mysql'),
                    'name' => $widgetName,
                    'type' => 1,
                    'data' => json_encode(array(
                        'widget_title' => $widgetTitle,
                        'widget_description' => $widgetDesc,
                        'widget_submit_desc' => $widgetSubmit,
                        'widget_redir' => $widgetRedir,
                    ))
                );
                if ($widgetId != null) $wpdb->update($table_name, $input, array('id' => $widgetId));
                break;
            default:
                break;
        }
    }

    function handling_ajax_post () {
        $this->is_ajax = true;
        return $this->handling_post();
    }

    function handling_post () {
        $action = isset($_POST['mailtarget_form_action']) ? sanitize_key($_POST['mailtarget_form_action']) : null;
        if($action == null) return false;

        switch ($action) {
            case 'submit_form':
                $id = isset($_POST['mailtarget_form_id']) ? sanitize_key($_POST['mailtarget_form_id']) : null;
                $this->ajax_post = isset($_POST['mailtarget_ajax_post']);
	            $api = $this->get_api();
	            if (!$api) return;
	            $form = $api->getFormDetail($id);
                if (is_wp_error($form)) {
                    $this->error_response('Failed to get form data');
                    die();
                }
	            $input = array();
                if (!isset($form['component'])) {
                    $this->error_response('form data not valid');
                    die ();
                }
	            foreach ($form['component'] as $item) {
	                $setting = $item['setting'];
	                $inputVal = isset($_POST['mtin__'.$setting['name']]) ?
                        sanitize_text_field($_POST['mtin__'.$setting['name']]) : null;
                    $input[$setting['name']] = $inputVal;

	                if ($item['type'] == 'inputMultiple'
                        and $setting['showOtherOption']
                        and $inputVal == 'mtiot__'.$setting['name']) {
	                    $inputVal = isset($_POST['mtino__'.$setting['name']]) ?
                            sanitize_text_field($_POST['mtino__'.$setting['name']]) : null;
                        $input[$setting['name']] = $inputVal;
                    }

	                if ($item['type'] == 'inputCheckbox') {
                        $in = isset($_POST['mtin__'.$setting['name']]) ?
                            (array) $_POST['mtin__'.$setting['name']] : array();
                        $in = array_map('sanitize_text_field', $in);
                        $useOther = isset($_POST['mtiot__'.$setting['name']])
                            && sanitize_text_field($_POST['mtiot__'.$setting['name']]) == 'yes' ? true : false;
                        if ($setting['showOtherOption'] and $useOther) {
                            $otherInput = isset($_POST['mtino__'.$setting['name']]) ?
                                sanitize_text_field($_POST['mtino__'.$setting['name']]) : null;
                            if ($otherInput != null) $in[] = $otherInput;
                        }
                        $input[$setting['name']] = join(', ', $in);
                    }
                }
                $submitUrl = $form['url'];
                $res = $api->submit($input, $submitUrl);
                if (is_wp_error($res)) {
                    $this->error_response($this->submit_error_process($res));
                    die();
                }
	            $url = wp_get_referer();
                $formMode = isset($_POST['mailtarget_form_mode']) ?
                    sanitize_text_field($_POST['mailtarget_form_mode']) : null;
	            if ($formMode != null) {
	                $popupUrl =  esc_url(get_option('mtg_popup_redirect'));
	                if ($formMode == 'popup' and $popupUrl != '') {
	                    $url = $popupUrl;
                    }
                }
                if (isset($_POST['mailtarget_form_redir'])) $url = esc_url($_POST['mailtarget_form_redir']);
                if ($this->ajax_post) {
                    echo json_encode(['code' => 200, 'msg' => 'ok']);
                    die();
                }
                else wp_redirect($url);
                break;
            default:
                break;
        }
    }

    function error_response ($msg, $data = []) {
        if ($this->ajax_post) echo json_encode(['code' => 400, 'msg' => $msg, 'data' => $data]);
        else echo $msg;
    }

    function submit_error_process ($err) {
        $msg = 'Failed tu submit form';
        if (isset($err->{0})) $err = $err->{0};
        if (isset($err->errors)) $err = $err->errors;
        if (isset($err['mailtarget-error'])) $err = $err['mailtarget-error'];
        if (isset($err[0])) $err = $err[0];
        if (isset($err['data'])) $err = $err['data'];
        if (isset($err['code'])) {
            switch ($err['code']) {
                case 413:
                    $msg = $err['data'] . ' is required';
            }
        }
        return $msg;
    }

	function set_admin_menu () {
        add_menu_page(
            'MailTarget Form',
            'MailTarget Form',
            'manage_options',
            'mailtarget-form-plugin--admin-menu',
            null,
            MAILTARGET_PLUGIN_URL . '/assets/image/wp-icon-compose.png'
        );
        add_submenu_page(
            'mailtarget-form-plugin--admin-menu',
            'List Form',
            'List Form',
            'manage_options',
            'mailtarget-form-plugin--admin-menu',
            array($this, 'list_widget_view')
        );
        add_submenu_page(
            'mailtarget-form-plugin--admin-menu',
            'New Form',
            'New Form',
            'manage_options',
            'mailtarget-form-plugin--admin-menu-widget-form',
            array($this, 'add_widget_view_form')
        );
        add_submenu_page(
            'mailtarget-form-plugin--admin-menu',
            'Popup Setting',
            'Popup Setting',
            'manage_options',
            'mailtarget-form-plugin--admin-menu-popup-main',
            array($this, 'add_popup_view')
        );
        add_submenu_page(
            'mailtarget-form-plugin--admin-menu',
            'Form Api Setting',
            'Setting',
            'manage_options',
            'mailtarget-form-plugin--admin-menu-config',
            array($this, 'admin_config_view')
        );
        add_submenu_page(
            null,
            'Edit Form',
            'Edit Form',
            'manage_options',
            'mailtarget-form-plugin--admin-menu-widget-edit',
            array($this, 'edit_widget_view')
        );
        add_submenu_page(
            null,
            'New Form',
            'New Form',
            'manage_options',
            'mailtarget-form-plugin--admin-menu-widget-add',
            array($this, 'add_widget_view')
        );
    }

    function list_widget_view () {
        if ( !current_user_can( 'manage_options' ) )  {
            wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
        }
        $valid = $this->is_key_valid();
        if ($valid === true) {
            global $wpdb, $forms;

            if (!current_user_can('edit_posts')) {
                return false;
            }

            $widgets = $wpdb->get_results("SELECT * FROM " . $wpdb->base_prefix . "mailtarget_forms");
            require_once(MAILTARGET_PLUGIN_DIR.'/views/admin/wp_form_list.php');
        }
    }

    function add_widget_view_form () {
        if ( !current_user_can( 'manage_options' ) )  {
            wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
        }
        $valid = $this->is_key_valid();
        if ($valid === true) {
            $api = $this->get_api();
            if (!$api) return null;
            $pg = isset($_GET['pg']) ? intval($_GET['pg']) : 1;
            $forms = $api->getFormList($pg);
            if (is_wp_error($forms)) {
                $error = $forms;
                require_once(MAILTARGET_PLUGIN_DIR.'/views/admin/error.php');
                return false;
            }
            require_once(MAILTARGET_PLUGIN_DIR.'/views/admin/form_list.php');
        }
    }

    function add_widget_view () {
        if ( !current_user_can( 'manage_options' ) )  {
            wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
        }
        $valid = $this->is_key_valid();
        if ($valid === true) {
            $formId = isset($_GET['form_id']) ? sanitize_text_field($_GET['form_id']) : null;
            if ($formId == null) return false;
            $api = $this->get_api();
            if (!$api) return null;
            $form = $api->getFormDetail($formId);
            if (is_wp_error($form)) {
                $error = $form;
                require_once(MAILTARGET_PLUGIN_DIR.'/views/admin/error.php');
                return false;
            }
            require_once(MAILTARGET_PLUGIN_DIR.'/views/admin/wp_form_add.php');
        }
    }

    function edit_widget_view () {
        if ( !current_user_can( 'manage_options' ) )  {
            wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
        }
        $valid = $this->is_key_valid();
        if ($valid === true) {
            global $wpdb;
            $widgetId = isset($_GET['id']) ? sanitize_key($_GET['id']) : null;
            $widget = $wpdb->get_row("SELECT * FROM " . $wpdb->base_prefix . "mailtarget_forms where id = $widgetId");
            if (!isset($widget->form_id)) {
                wp_redirect('admin.php?page=mailtarget-form-plugin--admin-menu');
                return false;
            }
            $api = $this->get_api();
            if (!$api) return null;
            $form = $api->getFormDetail($widget->form_id);
            if (is_wp_error($form)) {
                $error = $form;
                require_once(MAILTARGET_PLUGIN_DIR.'/views/admin/error.php');
                return false;
            }
            require_once(MAILTARGET_PLUGIN_DIR.'/views/admin/wp_form_edit.php');
        }
    }

    function admin_config_view() {
        if ( !current_user_can( 'manage_options' ) )  {
            wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
        }
        $valid = $this->is_key_valid(true);

        if ($valid !== false) {
            require_once(MAILTARGET_PLUGIN_DIR.'/views/admin/setup.php');
        }
    }

    function add_popup_view () {
        if ( !current_user_can( 'manage_options' ) )  {
            wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
        }
        $valid = $this->is_key_valid();

        if ($valid === true) {
            $formId = '';
            $formName = '';
            $getFormId = isset($_GET['form_id']) ? sanitize_text_field($_GET['form_id']) : null;
            if ($getFormId != null) {
                $api = $this->get_api();
                if (!$api) return;
                $form = $api->getFormDetail($getFormId);
                if (!is_wp_error($form)) {
                    $formId = $form['formId'];
                    $formName = $form['name'];
                } else {
                    $error = $form;
                    require_once(MAILTARGET_PLUGIN_DIR.'/views/admin/error.php');
                    return false;
                }
            }
            require_once(MAILTARGET_PLUGIN_DIR.'/views/admin/form_popup.php');
        }
    }

    function is_key_valid ($setup = false) {
        if ($this->get_key() == '' and $setup == false) {
            $error = array('code' => 101);
            require_once(MAILTARGET_PLUGIN_DIR.'/views/admin/error.php');
            return null;
        }
        $api = $this->get_api();
        if (!$api) return null;
	    $valid = $api->ping();
	    if (is_wp_error($valid)) {
	        if ($this->get_code_from_error($valid) == 32 and $setup) return null;
            $error = $valid;
            require_once(MAILTARGET_PLUGIN_DIR.'/views/admin/error.php');
            return false;
	    }
	    $companyId = $this->get_company_id();
	    if ($companyId === '') {
	        $cek = $api->getTeam();
		    if (is_wp_error($cek)) {
			    if ($this->get_code_from_error($valid) == 32 and $setup) {
			        return null;
                }
                $error = $cek;
                require_once(MAILTARGET_PLUGIN_DIR.'/views/admin/error.php');
                return false;
		    }
		    update_option('mtg_company_id', $cek['companyId']);
        }

        return true;
    }

    function get_api ($key = false) {
        if (!$key) $key = $this->get_key();
        if (!$key) return false;
	    $companyId = $this->get_company_id();
        return new MailtargetApi($key, $companyId);
    }

    function get_key () {
        return esc_attr(get_option('mtg_api_token'));
    }

    function get_company_id () {
        return esc_attr(get_option('mtg_company_id'));
    }

    function get_code_from_error ($error) {
        $error = (array) $error;
        if (isset($error['errors'])) $error = $error['errors'];
        if (isset($error['mailtarget-error'])) $error = $error['mailtarget-error'];
        if (isset($error[0])) $error = $error[0];
        if (isset($error['data'])) $error = $error['data'];

        if (isset($error['code'])) return $error['code'];
        return false;
    }
}
require_once(MAILTARGET_PLUGIN_DIR . 'include/mailtarget_shortcode.php');
require_once(MAILTARGET_PLUGIN_DIR . 'include/mailtarget_widget.php');
require_once(MAILTARGET_PLUGIN_DIR . 'include/mailtarget_popup.php');
MailtargetFormPlugin::get_instance();
