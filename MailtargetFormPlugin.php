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
	}

	public function process_form ($atts) {
		$a = shortcode_atts( array(
			'form-id' => ''
		), $atts );

		$formId = $a["form-id"];
		$json = wp_remote_get('https://api.mailtarget.co/form/public/'.$formId);
        print_r($json);
		return 'ok';
//		return $json;
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

	}

	function register_setting () {
        register_setting($this->option_group, 'mtg_api_token');
        register_setting($this->option_group, 'mtg_company_id');
    }

    function handling_admin_post () {
        if(!isset($_POST['mailtarget_form_action'])) return false;
        $action = $_POST['mailtarget_form_action'];
        $api = $this->get_api();

        switch ($action) {
            case 'setup_setting':
                $key = $_POST['mtg_api_token'];
                $team = $api->getTeam();
                error_log(json_encode($team));
                break;
            case 'create_widget':
	            global $wpdb;
	            $table_name = $wpdb->base_prefix . "mailtarget_forms";
	            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	            $input = array(
                    'time' => current_time('mysql'),
                    'form_id' => $_POST['form_id'],
                    'type' => 1,
                    'data' => json_encode(array(
                        'widget_title' => $_POST['widget_title'],
                        'widget_description' => $_POST['widget_description'],
                        'widget_submit_desc' => $_POST['widget_submit_desc'],
                    ))
                );
	            if ($_POST['widget_title'] != '') {
		            $wpdb->insert($table_name, $input);
                }
                break;
            default:
                break;
        }

        error_log('handling setting '.$action);
    }

	function set_admin_menu () {
        add_options_page(
            'MailTarget Config',
            'MailTarget Config',
            'manage_options',
            'mailtarget-form-plugin--admin-menu',
            array($this, 'admin_menu_view') );
    }

    function admin_menu_view() {
	    $path = plugin_dir_path(__FILE__);
        if ( !current_user_can( 'manage_options' ) )  {
            wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
        }
        $valid = $this->is_key_valid();
        if ($valid) {
	        $api = $this->get_api();
	        if (!$api) return;
	        $form = $api->getFormList();
	        require_once($path.'/views/widget_form.php');
        } else if ($valid === null) {
	        require_once($path.'/views/setup.php');
        } else {
            ?><p>Problem connecting to mailtarget server</p><?php
        }
    }

    function is_key_valid () {
        $api = $this->get_api();
        if (!$api) return false;
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
//	        error_log(json_encode($cek['companyId']));
        }

        return true;
    }

    function get_api () {
        $key = $this->get_key();
        $companyId = $this->get_company_id();
        if (!$key) return false;
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

add_shortcode( 'mailtarget_form', array( 'MailtargetFormPlugin', 'process_form' ) );
MailtargetFormPlugin::get_instance();
