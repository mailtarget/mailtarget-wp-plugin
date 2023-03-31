<?php //phpcs:ignore WordPress.Files.FileName.InvalidClassFileName
/**
 * Plugin Name: MTARGET Form
 * Description: The MTARGET plugin to simplify embedding MTARGET Form in your post or as widget, also easily to set MTARGET Forms as popup.
 * Version: 2.1.5
 * Author: MTARGET Teams
 * Author URI: https://mtarget.co/
 * License: GPL V3
 *
 * @package  MailtargetForm
 * @author    {{author_name}} <{{author_email}}>
 * @copyright {{author_copyright}}
 * @license   {{author_license}}
 * @link      {{author_url}}
 */

define( 'MAILTARGET_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'MAILTARGET_PLUGIN_URL', plugins_url( '', __FILE__ ) );

if ( ! class_exists( 'MailtargetApi' ) ) {
	require_once MAILTARGET_PLUGIN_DIR . '/lib/MailtargetApi.php';
}

/**
 * MyClass Class Doc Comment
 *
 * @category Class
 * @package  MyClass
 * @author    A N Other
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     http://www.hashbangcode.com/
 */
class MailtargetFormPlugin {
	/**
	 * Instance
	 *
	 * @var null
	 */
	private static $instance = null;
	/**
	 * Plugin path
	 *
	 * @var string
	 */
	private $plugin_path;
	/**
	 * Plugin Url
	 *
	 * @var string
	 */
	private $plugin_url;
	/**
	 * Text domain
	 *
	 * @var string
	 */
	private $text_domain = '';
	/**
	 * Option Group
	 *
	 * @var string
	 */
	private $option_group = 'mtg-form-group';
	/**
	 * Ajax Post
	 *
	 * @var string
	 */
	private $ajax_post = false;

	/**
	 * Creates or returns an instance of this class.
	 */
	public static function get_instance() {
		// If an instance hasn't been created and set to $instance create an instance and set it to $instance.
		if ( null === self::$instance ) {
			self::$instance = new self();
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
		add_action( 'admin_init', array( $this, 'register_setting' ) );
		add_action( 'admin_init', array( $this, 'handling_admin_post' ) );
		add_action( 'init', array( $this, 'handling_post' ) );
	}

	/**
	 * Get plugin url
	 */
	public function get_plugin_url() {
		return $this->plugin_url;
	}

	/**
	 * Get Plugin Path
	 */
	public function get_plugin_path() {
		return $this->plugin_path;
	}

	/**
	 * Place code that runs at plugin activation here.
	 */
	public function activation() {
		global $wpdb;
		$table_name = $wpdb->base_prefix . 'mailtarget_forms';
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		$charset_collate = ' CHARACTER SET utf8mb4 COLLATE utf8mb4_bin';

		$sql = 'CREATE TABLE IF NOT EXISTS ' . $table_name . " (
              id mediumint(9) NOT NULL AUTO_INCREMENT,
              time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
              form_id tinytext NOT NULL,
              name tinytext NOT NULL,
              type tinyint(1) default '1' NOT NULL,
              data text NOT NULL,
              PRIMARY KEY (id)
           ) DEFAULT " . $charset_collate . ';';
		dbDelta( $sql );
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
		wp_register_script( 'mailtarget-jquery', esc_url( '/wp-includes/js/jquery/jquery.min.js' ), array( 'jquery' ), '3.6.1' ); //phpcs:ignore WordPress.WP.EnqueuedResourceParameters.NotInFooter
		wp_register_script( 'mailtarget-action', esc_url( MAILTARGET_PLUGIN_URL . '/assets/js/main.js' ), array( 'mailtarget-jquery' ), '1.1.2', true );
		wp_register_script( 'mailtarget-tingle', esc_url( MAILTARGET_PLUGIN_URL . '/assets/js/tingle/tingle.min.js' ), array( 'mailtarget-jquery' ), '1.1.2' ); //phpcs:ignore WordPress.WP.EnqueuedResourceParameters.NotInFooter

		wp_enqueue_script( 'mailtarget-jquery' );
		wp_enqueue_script( 'mailtarget-action' );
		wp_enqueue_script( 'mailtarget-tingle' );
		wp_localize_script( 'mailtarget-action', 'WPURLS', array( 'siteurl' => get_option( 'siteurl' ) ) );
	}

	/**
	 * Enqueue and register CSS files here.
	 */
	public function register_styles() {
		wp_register_style( 'mailtarget_style', esc_url( MAILTARGET_PLUGIN_URL . '/assets/css/style.css' ), '2.2.1' ); //phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
		wp_register_style( 'mailtarget_tingle_style', esc_url( MAILTARGET_PLUGIN_URL . '/assets/js/tingle/tingle.min.css' ), '2.2.1' ); //phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
		wp_enqueue_style( 'mailtarget_style' );
		wp_enqueue_style( 'mailtarget_tingle_style' );
	}

	/**
	 * Register Admin Styles
	 */
	public function register_admin_styles() {
		?>
	<link rel="stylesheet" href="<?php echo esc_url( MAILTARGET_PLUGIN_URL . '/assets/css/mailtarget_admin.css' ); ?>" type="text/css" media="all" /><?php //phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedStylesheet ?>
		<?php
	}

	/**
	 * Register Setting
	 */
	public function register_setting() {
		register_setting( $this->option_group, 'mtg_api_token' );
		register_setting( $this->option_group, 'mtg_company_id' );
		register_setting( $this->option_group, 'mtg_popup_enable' );
		register_setting( $this->option_group, 'mtg_popup_form_id' );
		register_setting( $this->option_group, 'mtg_popup_form_name' );
		register_setting( $this->option_group, 'mtg_popup_delay' );
		register_setting( $this->option_group, 'mtg_popup_title' );
		register_setting( $this->option_group, 'mtg_popup_description' );
		register_setting( $this->option_group, 'mtg_popup_submit' );
		register_setting( $this->option_group, 'mtg_popup_redirect' );
	}
	/**
	 * Handling Admin Post
	 */
	public function handling_admin_post() {
		$verify = isset( $_GET['_wpnonce'] ) ? wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'delete_action' ) : false;
		if ( $verify ) {
			$get_action = isset( $_GET['action'] ) ? sanitize_key( $_GET['action'] ) : null;
			if ( null !== $get_action ) {
				if ( 'delete' === $get_action ) {
					$id = isset( $_GET['id'] ) ? sanitize_text_field( wp_unslash( $_GET['id'] ) ) : null;
					if ( null === $id ) {
						return false;
					}
					global $wpdb;
					$wpdb->delete( $wpdb->base_prefix . 'mailtarget_forms', array( 'id' => $id ) );// WPCS: db call ok. // WPCS: cache ok.
					return wp_safe_redirect( 'admin.php?page=mailtarget-form-plugin--admin-menu' );
				}
			}
		}

		$verify = isset( $_POST['_wpnonce'] ) ? wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ) ), 'wpnonce_action' ) : false;
		if ( $verify ) {
			$post_action = isset( $_POST['mailtarget_form_action'] ) ? sanitize_key( $_POST['mailtarget_form_action'] ) : null;
			if ( null === $post_action ) {
				return false;
			}
			switch ( $post_action ) {
				case 'setup_setting':
					$api_token    = isset( $_POST['mtg_api_token'] ) ? sanitize_text_field( wp_unslash( $_POST['mtg_api_token'] ) ) : null;
					$popup_enable = isset( $_POST['mtg_popup_enable'] ) && intval( $_POST['mtg_popup_enable'] ) === 1 ? 1 : 0;
					$data         = array(
						'mtg_api_token'    => $api_token,
						'mtg_popup_enable' => $popup_enable,
					);
					$api          = $this->get_api( $data['mtg_api_token'] );
					if ( ! $api ) {
						return false;
					}
					$team     = $api->get_team();
					$redirect = 'admin.php?page=mailtarget-form-plugin--admin-menu-config';
					if ( ! is_wp_error( $team ) ) {
						$redirect .= '&success=1';
						update_option( 'mtg_company_id', $team['companyId'] );
						update_option( 'mtg_api_token', $data['mtg_api_token'] );
						update_option( 'mtg_popup_enable', $data['mtg_popup_enable'] );
					}
					wp_safe_redirect( $redirect );
					break;
				case 'popup_config':
					$popup_form_id    = isset( $_POST['popup_form_id'] ) && '' !== $_POST['popup_form_id'] ? sanitize_text_field( wp_unslash( $_POST['popup_form_id'] ) ) : null;
					$popup_form_name  = isset( $_POST['popup_form_name'] ) && '' !== $_POST['popup_form_name'] ? sanitize_text_field( wp_unslash( $_POST['popup_form_name'] ) ) : __( 'Join for Newsletter', 'mailtarget' );
					$popup_form_delay = isset( $_POST['popup_delay'] ) && intval( $_POST['popup_delay'] ) > 0 ? intval( $_POST['popup_delay'] ) : 10;
					$popup_title      = isset( $_POST['popup_title'] ) && '' !== $_POST['popup_title'] ? sanitize_text_field( wp_unslash( $_POST['popup_title'] ) ) : __( 'Join form', 'mailtarget' );
					$popup_desc       = isset( $_POST['popup_description'] ) && '' !== $_POST['popup_description'] ? sanitize_textarea_field( wp_unslash( $_POST['popup_description'] ) ) : __( 'Please send me your newsletter', 'mailtarget' );
					$popup_redirect   = isset( $_POST['popup_redirect'] ) && '' !== $_POST['popup_redirect'] ? esc_url_raw( wp_unslash( $_POST['popup_redirect'] ) ) : null;
					$popup_enable     = isset( $_POST['mtg_popup_enable'] ) && intval( $_POST['mtg_popup_enable'] ) === 1 ? 1 : 0;

					update_option( 'mtg_popup_form_id', $popup_form_id );
					update_option( 'mtg_popup_form_name', $popup_form_name );
					update_option( 'mtg_popup_delay', $popup_form_delay );
					update_option( 'mtg_popup_title', $popup_title );
					update_option( 'mtg_popup_description', $popup_desc );
					update_option( 'mtg_popup_redirect', $popup_redirect );
					update_option( 'mtg_popup_enable', $popup_enable );
					wp_safe_redirect( 'admin.php?page=mailtarget-form-plugin--admin-menu-popup-main&success=1' );
					break;
				case 'create_widget':
					global $wpdb;
					$table_name = $wpdb->base_prefix . 'mailtarget_forms';
					require_once ABSPATH . 'wp-admin/includes/upgrade.php';

					$form_id       = isset( $_POST['form_id'] ) && '' !== $_POST['form_id'] ? sanitize_text_field( wp_unslash( $_POST['form_id'] ) ) : null;
					$widget_name   = isset( $_POST['widget_name'] ) && '' !== $_POST['widget_name'] ? sanitize_text_field( wp_unslash( $_POST['widget_name'] ) ) : __( 'Newsletter Form', 'mailtarget' );
					$widget_title  = isset( $_POST['widget_title'] ) && '' !== $_POST['widget_title'] ? sanitize_text_field( wp_unslash( $_POST['widget_title'] ) ) : __( 'Newsletter Form', 'mailtarget' );
					$widget_desc   = isset( $_POST['widget_description'] ) && '' !== $_POST['widget_description'] ? sanitize_textarea_field( wp_unslash( $_POST['widget_description'] ) ) : __( 'Please send me your newsletter', 'mailtarget' );
					$widget_submit = isset( $_POST['widget_submit_desc'] ) && '' !== $_POST['widget_submit_desc'] ? sanitize_text_field( wp_unslash( $_POST['widget_submit_desc'] ) ) : __( 'Submit', 'mailtarget' );
					$widget_redir  = isset( $_POST['widget_redir'] ) && '' !== $_POST['widget_redir'] ? sanitize_text_field( wp_unslash( $_POST['widget_redir'] ) ) : null;

					$input = array(
						'time'    => current_time( 'mysql' ),
						'form_id' => $form_id,
						'name'    => $widget_name,
						'type'    => 1,
						'data'    => wp_json_encode(
							array(
								'widget_title'       => $widget_title,
								'widget_description' => $widget_desc,
								'widget_submit_desc' => $widget_submit,
								'widget_redir'       => $widget_redir,
							)
						),
					);
					$wpdb->insert( $table_name, $input );// WPCS: db call ok.
					break;
				case 'edit_widget':
					global $wpdb;
					$table_name = $wpdb->base_prefix . 'mailtarget_forms';
					require_once ABSPATH . 'wp-admin/includes/upgrade.php';

					$widget_id     = isset( $_POST['widget_id'] ) && '' !== $_POST['widget_id'] ? sanitize_text_field( wp_unslash( $_POST['widget_id'] ) ) : null;
					$widget_name   = isset( $_POST['widget_name'] ) && '' !== $_POST['widget_name'] ? sanitize_text_field( wp_unslash( $_POST['widget_name'] ) ) : __( 'Newsletter Form', 'mailtarget' );
					$widget_title  = isset( $_POST['widget_title'] ) && '' !== $_POST['widget_title'] ? sanitize_text_field( wp_unslash( $_POST['widget_title'] ) ) : __( 'Newsletter Form', 'mailtarget' );
					$widget_desc   = isset( $_POST['widget_description'] ) && '' !== $_POST['widget_description'] ? sanitize_textarea_field( wp_unslash( $_POST['widget_description'] ) ) : __( 'Please send me your newsletter', 'mailtarget' );
					$widget_submit = isset( $_POST['widget_submit_desc'] ) && '' !== $_POST['widget_submit_desc'] ? sanitize_text_field( wp_unslash( $_POST['widget_submit_desc'] ) ) : __( 'Submit', 'mailtarget' );
					$widget_redir  = isset( $_POST['widget_redir'] ) && '' !== $_POST['widget_redir'] ? sanitize_text_field( wp_unslash( $_POST['widget_redir'] ) ) : null;

					$input = array(
						'time' => current_time( 'mysql' ),
						'name' => $widget_name,
						'type' => 1,
						'data' => wp_json_encode(
							array(
								'widget_title'       => $widget_title,
								'widget_description' => $widget_desc,
								'widget_submit_desc' => $widget_submit,
								'widget_redir'       => $widget_redir,
							)
						),
					);
					if ( null !== $widget_id ) {
						$wpdb->update( $table_name, $input, array( 'id' => $widget_id ) );// WPCS: db call ok. // WPCS: cache ok.
					}
					break;
				default:
					break;
			}
		}
	}

	/**
	 * Handling Ajax Post
	 */
	public function handling_ajax_post() {
		$this->is_ajax = true;
		return $this->handling_post();
	}

	/**
	 * Handling Post
	 */
	public function handling_post() {
		$action = isset( $_POST['mailtarget_form_action'] ) ? sanitize_key( $_POST['mailtarget_form_action'] ) : null;
		if ( null === $action ) {
			return false;
		}

		$verify = isset( $_POST['_wpnonce'] ) ? wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ) ), 'wpnonce_action' ) : false;
		if ( $verify ) {
			switch ( $action ) {
				case 'submit_form':
					$id              = isset( $_POST['mailtarget_form_id'] ) ? sanitize_key( $_POST['mailtarget_form_id'] ) : null;
					$this->ajax_post = isset( $_POST['mailtarget_ajax_post'] );
					$api             = $this->get_api();
					if ( ! $api ) {
						return;
					}
					$form = $api->get_form_detail( $id );
					if ( is_wp_error( $form ) ) {
						$this->error_response( 'Failed to get form data' );
						die();
					}
					$input = array();
					if ( ! isset( $form['component'] ) ) {
						$this->error_response( 'form data not valid' );
						die();
					}
					foreach ( $form['component'] as $item ) {
						$setting                   = $item['setting'];
						$input_val                 = isset( $_POST[ 'mtin__' . $setting['name'] ] ) ?
						sanitize_text_field( wp_unslash( $_POST[ 'mtin__' . $setting['name'] ] ) ) : null;
						$input[ $setting['name'] ] = $input_val;

						if ( 'inputMultiple' === $item['type']
						&& $setting['showOtherOption']
						&& 'mtiot__' . $setting['name'] === $input_val
						) {
							$input_val                 = isset( $_POST[ 'mtino__' . $setting['name'] ] ) ?
							sanitize_text_field( wp_unslash( $_POST[ 'mtino__' . $setting['name'] ] ) ) : null;
							$input[ $setting['name'] ] = $input_val;
						}

						if ( 'inputCheckbox' === $item['type'] ) {
							$in        = isset( $_POST[ 'mtin__' . $setting['name'] ] ) ?
							(array) sanitize_key( $_POST[ 'mtin__' . $setting['name'] ] ) : array();
							$in        = array_map( 'sanitize_text_field', $in );
							$use_other = isset( $_POST[ 'mtiot__' . $setting['name'] ] )
							&& sanitize_text_field( wp_unslash( $_POST[ 'mtiot__' . $setting['name'] ] ) ) === 'yes' ? true : false;
							if ( $setting['showOtherOption'] && $use_other ) {
								$other_input = isset( $_POST[ 'mtino__' . $setting['name'] ] ) ?
								sanitize_text_field( wp_unslash( $_POST[ 'mtino__' . $setting['name'] ] ) ) : null;
								if ( null !== $other_input ) {
									$in[] = $other_input;
								}
							}
							$input[ $setting['name'] ] = join( ', ', $in );
						}

						if ( 'inputPhone' === $item['type'] ) {
							$input_val                 = isset( $_POST[ 'mtin__' . $setting['name'] ] ) ?
							sanitize_text_field( wp_unslash( $_POST[ 'mtin__' . $setting['name'] ] ) ) : null;
							$input[ $setting['name'] ] = $input_val;
						}
					}
					$submit_url = $form['url'];
					$res        = $api->submit( $input, $submit_url );
					if ( is_wp_error( $res ) ) {
						$this->error_response( $this->submit_error_process( $res ) );
						die();
					}
					$url       = wp_get_referer();
					$form_mode = isset( $_POST['mailtarget_form_mode'] ) ?
					sanitize_text_field( wp_unslash( $_POST['mailtarget_form_mode'] ) ) : null;
					if ( null !== $form_mode ) {
						$popup_url = esc_url( get_option( 'mtg_popup_redirect' ) );
						if ( 'popup' === $form_mode && '' !== $popup_url ) {
							$url = $popup_url;
						}
					}
					if ( isset( $_POST['mailtarget_form_redir'] ) && sanitize_key( $_POST['mailtarget_form_redir'] ) ) {
						$url = esc_url_raw( wp_unslash( $_POST['mailtarget_form_redir'] ) );
					}
					if ( $this->ajax_post ) {
						echo wp_json_encode(
							array(
								'code' => 200,
								'msg'  => 'ok',
							)
						);
						die();
					} else {
						wp_safe_redirect( $url );
					}
					break;
				default:
					break;
			}
		} else {
			wp_die( esc_html_e( 'Security check', 'mailtarget' ) );
		}
	}

	/**
	 * Error Response
	 *
	 * @param string $msg message.
	 * @param array  $data data.
	 */
	public function error_response( $msg, $data = array() ) {
		if ( $this->ajax_post ) {
			echo wp_json_encode(
				array(
					'code' => 400,
					'msg'  => $msg,
					'data' => $data,
				)
			);
		} else {
			echo esc_attr( $msg );
		}
	}

	/**
	 * Submit Error Process
	 *
	 * @param array $err error.
	 */
	public function submit_error_process( $err ) {
		$msg = 'Failed to submit form';
		if ( isset( $err->{0} ) ) {
			$err = $err->{0};
		}
		if ( isset( $err->errors ) ) {
			$err = $err->errors;
		}
		if ( isset( $err['mailtarget-error'] ) ) {
			$err = $err['mailtarget-error'];
		}
		if ( isset( $err[0] ) ) {
			$err = $err[0];
		}
		if ( isset( $err['data'] ) ) {
			$err = $err['data'];
		}
		if ( isset( $err['code'] ) ) {
			$msg = $err['message'];
		}
		return $msg;
	}

	/**
	 * Set Admin Menu
	 */
	public function set_admin_menu() {
		add_menu_page(
			'MTARGET Form',
			'MTARGET Form',
			'manage_options',
			'mailtarget-form-plugin--admin-menu',
			null,
			MAILTARGET_PLUGIN_URL . '/assets/image/mt-icon-20x20.png'
		);
		add_submenu_page(
			'mailtarget-form-plugin--admin-menu',
			'List Form',
			'List Form',
			'manage_options',
			'mailtarget-form-plugin--admin-menu',
			array( $this, 'list_widget_view' )
		);
		add_submenu_page(
			'mailtarget-form-plugin--admin-menu',
			'New Form',
			'New Form',
			'manage_options',
			'mailtarget-form-plugin--admin-menu-widget-form',
			array( $this, 'add_widget_view_form' )
		);
		add_submenu_page(
			'mailtarget-form-plugin--admin-menu',
			'Popup Setting',
			'Popup Setting',
			'manage_options',
			'mailtarget-form-plugin--admin-menu-popup-main',
			array( $this, 'add_popup_view' )
		);
		add_submenu_page(
			'mailtarget-form-plugin--admin-menu',
			'Form Api Setting',
			'Setting',
			'manage_options',
			'mailtarget-form-plugin--admin-menu-config',
			array( $this, 'admin_config_view' )
		);
		add_submenu_page(
			null,
			'Edit Form',
			'Edit Form',
			'manage_options',
			'mailtarget-form-plugin--admin-menu-widget-edit',
			array( $this, 'edit_widget_view' )
		);
		add_submenu_page(
			null,
			'New Form',
			'New Form',
			'manage_options',
			'mailtarget-form-plugin--admin-menu-widget-add',
			array( $this, 'add_widget_view' )
		);
	}
	/**
	 * List Widget View
	 */
	public function list_widget_view() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html_e( 'You do not have sufficient permissions to access this page.', 'mailtarget' ) );
		}
		$valid = $this->is_key_valid();
		if ( true === $valid ) {
			global $wpdb, $forms;

			if ( ! current_user_can( 'edit_posts' ) ) {
				return false;
			}

			$widgets = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->base_prefix . 'mailtarget_forms' );// WPCS: db call ok. // WPCS: cache ok.
			require_once MAILTARGET_PLUGIN_DIR . '/views/admin/wp-form-list.php';
		}
	}

	/**
	 * Add Widget View Form
	 */
	public function add_widget_view_form() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html_e( 'You do not have sufficient permissions to access this page.', 'mailtarget' ) );
		}

		$verify = isset( $_GET['_wpnonce'] ) ? wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'admin-menu-widget-form-action' ) : false;
		$valid  = $this->is_key_valid();
		if ( true === $valid ) {
			$api = $this->get_api();
			if ( ! $api ) {
				return null;
			}
			$pg    = isset( $_GET['pg'] ) ? intval( $_GET['pg'] ) : 1;
			$forms = $api->get_form_list( $pg );
			if ( is_wp_error( $forms ) ) {
				$error = $forms;
				require_once MAILTARGET_PLUGIN_DIR . '/views/admin/error.php';
				return false;
			}
			require_once MAILTARGET_PLUGIN_DIR . '/views/admin/form-list.php';
		}
	}

	/**
	 * Add Widget View
	 */
	public function add_widget_view() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html_e( 'You do not have sufficient permissions to access this page.', 'mailtarget' ) );
		}

		$verify = isset( $_GET['_wpnonce'] ) ? wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'admin-menu-widget-form-action' ) : false;
		$valid  = $this->is_key_valid();
		if ( true === $valid ) {
			$form_id = isset( $_GET['form_id'] ) ? sanitize_text_field( wp_unslash( $_GET['form_id'] ) ) : null;
			if ( null === $form_id ) {
				return false;
			}
			$api = $this->get_api();
			if ( ! $api ) {
				return null;
			}
			$form = $api->get_form_detail( $form_id );
			if ( is_wp_error( $form ) ) {
				$error = $form;
				require_once MAILTARGET_PLUGIN_DIR . '/views/admin/error.php';
				return false;
			}
			require_once MAILTARGET_PLUGIN_DIR . '/views/admin/wp_form_add.php';
		}
	}

	/**
	 * Edit Widget View
	 */
	public function edit_widget_view() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html_e( 'You do not have sufficient permissions to access this page.', 'mailtarget' ) );
		}

		$verify = isset( $_REQUEST['_wpnonce'] ) ? wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['_wpnonce'] ) ), 'edit_action' ) : false;
		if ( $verify ) {
			$valid = $this->is_key_valid();
			if ( true === $valid ) {
				global $wpdb;
				$widget_id = isset( $_GET['id'] ) ? sanitize_key( $_GET['id'] ) : null;
				$widget    = $wpdb->get_row(
					'SELECT * FROM ' . $wpdb->base_prefix .
					"mailtarget_forms where id = $widget_id"  //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
				); // WPCS: db call ok. // WPCS: cache ok.
				if ( ! isset( $widget->form_id ) ) {
					wp_safe_redirect( 'admin.php?page=mailtarget-form-plugin--admin-menu' );
					return false;
				}
				$api = $this->get_api();
				if ( ! $api ) {
					return null;
				}
				$form = $api->get_form_detail( $widget->form_id );
				if ( is_wp_error( $form ) ) {
					$error = $form;
					require_once MAILTARGET_PLUGIN_DIR . '/views/admin/error.php';
					return false;
				}
				require_once MAILTARGET_PLUGIN_DIR . '/views/admin/wp_form_edit.php';
			}
		} else {
			wp_die( esc_html_e( 'Security check', 'mailtarget' ) );
		}
	}

	/**
	 * Admin Config View
	 */
	public function admin_config_view() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html_e( 'You do not have sufficient permissions to access this page.', 'mailtarget' ) );
		}
		$valid = $this->is_key_valid( true );

		if ( false !== $valid ) {
			require_once MAILTARGET_PLUGIN_DIR . '/views/admin/setup.php';
		}
	}

	/**
	 * Add Popup View
	 */
	public function add_popup_view() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html_e( 'You do not have sufficient permissions to access this page.', 'mailtarget' ) );
		}
		$valid = $this->is_key_valid();
		if ( true === $valid ) {
			$form_id   = '';
			$form_name = '';

			$verify      = isset( $_REQUEST['_wpnonce'] ) ? wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['_wpnonce'] ) ), 'edit_action' ) : false;
			$get_form_id = isset( $_GET['form_id'] ) ? sanitize_text_field( wp_unslash( $_GET['form_id'] ) ) : null;
			if ( null !== $get_form_id ) {
				$api = $this->get_api();
				if ( ! $api ) {
					return;
				}
				$form = $api->get_form_detail( $get_form_id );
				if ( ! is_wp_error( $form ) ) {
					$form_id   = $form['formId'];
					$form_name = $form['name'];
				} else {
					$error = $form;
					require_once MAILTARGET_PLUGIN_DIR . '/views/admin/error.php';
					return false;
				}
			}
			require_once MAILTARGET_PLUGIN_DIR . '/views/admin/form_popup.php';
		}
	}

	/**
	 * Is Key Valid
	 *
	 * @param boolean $setup setup.
	 */
	public function is_key_valid( $setup = false ) {
		if ( $this->get_key() === '' && false === $setup ) {
			$error = array( 'code' => 101 );
			require_once MAILTARGET_PLUGIN_DIR . '/views/admin/error.php';
			return null;
		}
		$api = $this->get_api();
		if ( ! $api ) {
			return null;
		}
		$valid = $api->ping();
		if ( is_wp_error( $valid ) ) {
			if ( $this->get_code_from_error( $valid ) === 400 ) {
				return null;
			}
			if ( $this->get_code_from_error( $valid ) === 401 ) {
				return null;
			}
			if ( $this->get_code_from_error( $valid ) === 32 && $setup ) {
				return null;
			}
			$error = $valid;
			require_once MAILTARGET_PLUGIN_DIR . '/views/admin/error.php';
			return false;
		}
		$company_id = $this->get_company_id();
		if ( '' === $company_id ) {
			$cek = $api->get_team();
			if ( is_wp_error( $cek ) ) {
				if ( $this->get_code_from_error( $valid ) === 32 && $setup ) {
					return null;
				}
				$error = $cek;
				require_once MAILTARGET_PLUGIN_DIR . '/views/admin/error.php';
				return false;
			}
			update_option( 'mtg_company_id', $cek['companyId'] );
		}

		return true;
	}

	/**
	 * Get Api
	 *
	 * @param boolean $key key.
	 */
	public function get_api( $key = false ) {
		if ( ! $key ) {
			$key = $this->get_key();
		}
		if ( ! $key ) {
			return false;
		}
		$company_id = $this->get_company_id();
		return new MailtargetApi( $key, $company_id );
	}

	/**
	 * Get key
	 */
	public function get_key() {
		return esc_attr( get_option( 'mtg_api_token' ) );
	}

	/**
	 * Get company id
	 */
	public function get_company_id() {
		return esc_attr( get_option( 'mtg_company_id' ) );
	}

	/**
	 * Get code from error
	 *
	 * @param array $error error.
	 */
	public function get_code_from_error( $error ) {
		$error = (array) $error;
		if ( isset( $error['errors'] ) ) {
			$error = $error['errors'];
		}
		if ( isset( $error['mailtarget-error'] ) ) {
			$error = $error['mailtarget-error'];
		}
		if ( isset( $error[0] ) ) {
			return $error[0]['code'];
		}
		return false;
	}
}
require_once MAILTARGET_PLUGIN_DIR . 'include/mailtarget_shortcode.php';
require_once MAILTARGET_PLUGIN_DIR . 'include/mailtarget_widget.php';
require_once MAILTARGET_PLUGIN_DIR . 'include/mailtarget_popup.php';
MailtargetFormPlugin::get_instance();
