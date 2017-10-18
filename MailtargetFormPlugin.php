<?php

/*
  Plugin Name: MailTarget Forms
  Description: The MailTarget plugin to simplify embedding Mailtarget Forms in your post or as widget, also easily to set Mailtarget Forms as popup.
  Version: 1.0.0
  Author: MailTarget Teams
  Author URI: https://mailtarget.co/
  License: GPL V3
 */

define('MAILTARGET_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('MAILTARGET_PLUGIN_URL', plugins_url('', __FILE__));

if (!class_exists('MailtargetApi')) {
	$path = plugin_dir_path(__FILE__);
	require_once($path . 'lib/MailtargetApi.php');
}

class MailtargetFormPlugin {
	private static $instance = null;
	private $plugin_path;
	private $plugin_url;
    private $text_domain = '';
    private $option_group = 'mtg-form-group';

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

		register_activation_hook( __FILE__, array( $this, 'activation' ) );
		register_deactivation_hook( __FILE__, array( $this, 'deactivation' ) );

        add_action( 'admin_menu', array( $this, 'set_admin_menu' ) );
        add_action( 'admin_init', array( $this, 'register_setting') );
        add_action( 'admin_init', array( $this, 'handling_admin_post') );
        add_action( 'init', array( $this, 'handling_post') );

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

	    $charset_collate = ' CHARACTER SET utf8 COLLATE utf8_bin';

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
        <script type="application/javascript" src="<?php echo MAILTARGET_PLUGIN_URL ?>/assets/js/tingle/tingle.min.js"></script>
        <?php
	}

    /**
     * Enqueue and register CSS files here.
     */
    public function register_styles() {
        ?>
        <link rel="stylesheet"  href="<?php echo MAILTARGET_PLUGIN_URL ?>/assets/css/style.css" type="text/css" media="all" />
        <link rel="stylesheet"  href="<?php echo MAILTARGET_PLUGIN_URL ?>/assets/js/tingle/tingle.min.css" type="text/css" media="all" />
        <?php
	}

	function register_setting () {
        register_setting($this->option_group, 'mtg_api_token');
        register_setting($this->option_group, 'mtg_company_id');
        register_setting($this->option_group, 'mtg_popup_form_id');
        register_setting($this->option_group, 'mtg_popup_form_name');
        register_setting($this->option_group, 'mtg_popup_delay');
        register_setting($this->option_group, 'mtg_popup_title');
        register_setting($this->option_group, 'mtg_popup_description');
        register_setting($this->option_group, 'mtg_popup_submit');
        register_setting($this->option_group, 'mtg_popup_redirect');
    }

    function handling_admin_post () {

        if (isset($_GET['action'])) {
            $action = $_GET['action'];
            if ($action === 'delete') {
                if(!isset($_GET['id'])) return false;
                $id = $_GET['id'];
                global $wpdb;
                $wpdb->delete($wpdb->base_prefix . "mailtarget_forms", array('id' => $id));
                return wp_redirect('admin.php?page=mailtarget-form-plugin--admin-menu');
            }
        }

        if(!isset($_POST['mailtarget_form_action'])) return false;
        $action = $_POST['mailtarget_form_action'];

        switch ($action) {
            case 'setup_setting':
                $key = $_POST['mtg_api_token'];
	            $api = $this->get_api($key);
	            if (!$api) return false;
                $team = $api->getTeam();
	            update_option('mtg_api_token', $key);
                break;
            case 'popup_config':
                $data = array(
                    'mtg_popup_form_id' => $_POST['popup_form_id'],
                    'mtg_popup_form_name' => $_POST['popup_form_name'],
                    'mtg_popup_delay' => $_POST['popup_delay'],
                    'mtg_popup_title' => $_POST['popup_title'],
                    'mtg_popup_description' => $_POST['popup_description'],
                    'mtg_popup_redirect' => $_POST['popup_redirect'],
                );
	            update_option('mtg_popup_form_id', $data['mtg_popup_form_id']);
	            update_option('mtg_popup_form_name', $data['mtg_popup_form_name']);
	            update_option('mtg_popup_delay', $data['mtg_popup_delay']);
	            update_option('mtg_popup_title', $data['mtg_popup_title']);
	            update_option('mtg_popup_description', $data['mtg_popup_description']);
	            update_option('mtg_popup_redirect', $data['mtg_popup_redirect']);
	            wp_redirect('admin.php?page=mailtarget-form-plugin--admin-menu-popup-main');
                break;
            case 'create_widget':
	            global $wpdb;
	            $table_name = $wpdb->base_prefix . "mailtarget_forms";
	            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	            $input = array(
                    'time' => current_time('mysql'),
                    'form_id' => $_POST['form_id'],
                    'name' => $_POST['widget_name'],
                    'type' => 1,
                    'data' => json_encode(array(
                        'widget_title' => $_POST['widget_title'],
                        'widget_description' => $_POST['widget_description'],
                        'widget_submit_desc' => $_POST['widget_submit_desc'],
                        'widget_redir' => $_POST['widget_redir'],
                    ))
                );
	            if ($_POST['widget_name'] != '') {
		            $wpdb->insert($table_name, $input);
                }
                break;
            case 'edit_widget':
	            global $wpdb;
	            $table_name = $wpdb->base_prefix . "mailtarget_forms";
	            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	            $input = array(
                    'time' => current_time('mysql'),
                    'name' => $_POST['widget_name'],
                    'type' => 1,
                    'data' => json_encode(array(
                        'widget_title' => $_POST['widget_title'],
                        'widget_description' => $_POST['widget_description'],
                        'widget_submit_desc' => $_POST['widget_submit_desc'],
                        'widget_redir' => $_POST['widget_redir'],
                    ))
                );
	            if ($_POST['widget_name'] != '') {
		            $wpdb->update($table_name, $input, array('id' => $_POST['widget_id']));
                }
                break;
            default:
                break;
        }
    }

    function handling_post () {
        if(!isset($_POST['mailtarget_form_action'])) return false;
        $action = $_POST['mailtarget_form_action'];

        switch ($action) {
            case 'submit_form':
                $id = $_POST['mailtarget_form_id'];
	            $api = $this->get_api();
	            if (!$api) return;
	            $form = $api->getFormDetail($id);
                if (is_wp_error($form)) {
                    die('failed to get form data');
                    break;
                }
	            $input = array();
                if (!isset($form['component'])) die ('form data not valid');
	            foreach ($form['component'] as $item) {
	                $setting = $item['setting'];
	                $input[$setting['name']] = $_POST['mtin__'.$setting['name']];
                }
                $res = $api->submit($input, $form['url']);
                if (is_wp_error($form)) {
                    die('failed to submit form');
                    break;
                }
	            $url = wp_get_referer();
	            if (isset($_POST['mailtarget_form_mode'])) {
	                $popupUrl =  esc_attr(get_option('mtg_popup_redirect'));
	                if ($_POST['mailtarget_form_mode'] == 'popup' and $popupUrl != '') {
	                    $url = $popupUrl;
                    }
                }
                if (isset($_POST['mailtarget_form_redir'])) $url = $_POST['mailtarget_form_redir'];
                wp_redirect($url);
                break;
            default:
                break;
        }
    }

	function set_admin_menu () {
        add_menu_page(
            'Mailtarget Form',
            'Mailtarget Form',
            'manage_options',
            'mailtarget-form-plugin--admin-menu',
            null
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
            'Popup Config',
            'Popup Config',
            'manage_options',
            'mailtarget-form-plugin--admin-menu-popup-main',
            array($this, 'add_popup_view')
        );
        add_submenu_page(
            'mailtarget-form-plugin--admin-menu',
            'Configure Form Api',
            'Configure',
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
            $pg = isset($_GET['pg']) ? $_GET['pg'] : 1;
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
            if (!isset($_GET['form_id'])) return false;
            $formId = $_GET['form_id'];
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
            $widgetId = sanitize_key($_GET['id']);
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
            if (isset($_GET['form_id'])) {
                $getFormId = $_GET['form_id'];
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
