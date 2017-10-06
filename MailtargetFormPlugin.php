<?php

/*
  Plugin Name: Mailtarget Form Plugin
  Plugin URI: https://mailtarget.co
  Description: A plugin to enable mailtarget form in your wp
  Version: 1.0.0
  Author: Timen Chad
  Author URI: http://www.timen.net
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


//        add_action( 'admin_enqueue_scripts', array( $this, 'register_scripts' ) );
//		add_action( 'admin_enqueue_scripts', array( $this, 'register_styles' ) );

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
        ?><?php
	}

    /**
     * Enqueue and register CSS files here.
     */
    public function register_styles() {
        ?><link rel="stylesheet"  href="<?php echo MAILTARGET_PLUGIN_URL ?>/assets/css/style.css" type="text/css" media="all" /><?php
	}

	function register_setting () {
        register_setting($this->option_group, 'mtg_api_token');
        register_setting($this->option_group, 'mtg_company_id');
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
	            if (!$api) return;
                $team = $api->getTeam();
	            update_option('mtg_api_token', $key);
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
                    ))
                );
	            if ($_POST['widget_name'] != '') {
		            $wpdb->update($table_name, $input, array('id' => $_POST['widget_id']));
                }
                break;
            default:
                break;
        }

        error_log('handling admin setting '.$action);
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
	            $input = array();
	            foreach ($form['component'] as $item) {
	                $setting = $item['setting'];
	                $input[$setting['name']] = $_POST['mtin__'.$setting['name']];
                }
                $res = $api->submit($input, $form['url']);
	            $res = json_encode($res);
                if ($res === 'true') wp_redirect(wp_get_referer());
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
            'All Form',
            'manage_options',
            'mailtarget-form-plugin--admin-menu',
            array($this, 'list_widget_view')
        );
        add_submenu_page(
            'mailtarget-form-plugin--admin-menu',
            'New Form',
            'Add New',
            'manage_options',
            'mailtarget-form-plugin--admin-menu-widget-add',
            array($this, 'add_widget_view')
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
            'Edit Widget',
            'Edit Widget',
            'manage_options',
            'mailtarget-form-plugin--admin-menu-widget-edit',
            array($this, 'edit_widget_view')
        );
    }

    function list_widget_view () {
        if ( !current_user_can( 'manage_options' ) )  {
            wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
        }
        $valid = $this->is_key_valid();
        if ($valid === false) {
            ?><p>Problem connecting to mailtarget server e</p><?php
        } else {
            global $wpdb, $forms;

            if (!current_user_can('edit_posts')) {
                return false;
            }

            $widgets = $wpdb->get_results("SELECT * FROM " . $wpdb->base_prefix . "mailtarget_forms");
            require_once(MAILTARGET_PLUGIN_DIR.'/views/widget_list.php');
        }
    }

    function add_widget_view () {
        if ( !current_user_can( 'manage_options' ) )  {
            wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
        }
        $valid = $this->is_key_valid();
        if ($valid === false) {
            ?><p>Problem connecting to mailtarget server e</p><?php
        } else {
            $api = $this->get_api();
            if (!$api) return null;
            $form = $api->getFormList();
            if (is_wp_error($valid)) {
                return false;
            }
            require_once(MAILTARGET_PLUGIN_DIR.'/views/widget_add.php');
        }
    }

    function edit_widget_view () {
        if ( !current_user_can( 'manage_options' ) )  {
            wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
        }
        $valid = $this->is_key_valid();
        if ($valid === false) {
            ?><p>Problem connecting to mailtarget server e</p><?php
        } else {
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
            if (is_wp_error($valid)) {
                return false;
            }
            require_once(MAILTARGET_PLUGIN_DIR.'/views/widget_edit.php');
        }
    }

    function admin_config_view() {
        if ( !current_user_can( 'manage_options' ) )  {
            wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
        }
        $valid = $this->is_key_valid();

        if ($valid === false) {
            ?><p>Problem connecting to mailtarget server e</p><?php
        } else {
            require_once(MAILTARGET_PLUGIN_DIR.'/views/setup.php');
        }
    }

    function is_key_valid () {
        $api = $this->get_api();
        if (!$api) return null;
	    $valid = $api->ping();
	    if (is_wp_error($valid)) {
	        if ($this->get_code_from_error($valid) == 32) return null;
	        return false;
	    }
	    $companyId = $this->get_company_id();
	    if ($companyId === '') {
	        $cek = $api->getTeam();
		    if (is_wp_error($cek)) {
			    if ($this->get_code_from_error($valid) == 32) {
			        return null;
                }
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

    function get_code_from_error ($err) {
        if (!isset($err->errors['mailtarget-error-get'][0]['code'])) return false;
        return $err->errors['mailtarget-error-get'][0]['code'];
    }
}
require_once(MAILTARGET_PLUGIN_DIR . 'include/mailtarget_shortcode.php');
MailtargetFormPlugin::get_instance();
