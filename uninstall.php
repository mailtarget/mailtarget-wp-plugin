<?php
/**
 * The code in this file runs when a plugin is uninstalled from the WordPress dashboard.
 *
 * @package  Mailtarget_Form
 * @author    {{author_name}} <{{author_email}}>
 * @copyright {{author_copyright}}
 * @license   {{author_license}}
 * @link      {{author_url}}
 */

// if uninstall.php is not called by WordPress, die
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	die;
}

delete_option( 'mtg_company_id' );
delete_option( 'mtg_api_token' );
delete_option( 'mtg_popup_enable' );
delete_option( 'mtg_popup_form_id' );
delete_option( 'mtg_popup_form_name' );
delete_option( 'mtg_popup_delay' );
delete_option( 'mtg_popup_title' );
delete_option( 'mtg_popup_description' );
delete_option( 'mtg_popup_redirect' );

global $wpdb;
$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->base_prefix}mailtarget_forms" );