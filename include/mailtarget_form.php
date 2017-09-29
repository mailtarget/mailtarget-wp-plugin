<?php

function load_mailtarget_form ($widgetId) {
	global $wpdb;
	$widgetId = sanitize_key($widgetId);
	$sql = "SELECT * FROM " . $wpdb->base_prefix . "mailtarget_forms where id = $widgetId";
	$widget = $wpdb->get_row($sql);
	if (!isset($widget->form_id)) {
		echo 'Widget not exist';
		return;
	}
	$widget->data = json_decode($widget->data, true);
	$widget = (array)$widget;
	require_once MAILTARGET_PLUGIN_DIR.'/lib/MailtargetApi.php';
	$key = get_option('mtg_api_token');
	$companyId = get_option('mtg_company_id');
	$api = new MailtargetApi($key, $companyId);
	$form = $api->getFormDetail($widget['form_id']);
	if (is_wp_error($form)) {
		echo('Failed to get form data');
		return;
	}

	if (!isset($form['formId'])) {
		echo('Form data not valid');
		return;
	}

	include MAILTARGET_PLUGIN_DIR.'/views/render/widget.php';

}

function render_text($row) {
	if (!isset($row['setting'])) return;
	include MAILTARGET_PLUGIN_DIR.'/views/render/input_text.php';
}

function render_textarea($row) {
	if (!isset($row['setting'])) return;
	include MAILTARGET_PLUGIN_DIR.'/views/render/input_textarea.php';
}

function render_dropdown($row) {
	if (!isset($row['setting'])) return;
	include MAILTARGET_PLUGIN_DIR.'/views/render/input_dropdown.php';
}

function render_multiple($row) {
	if (!isset($row['setting'])) return;
	include MAILTARGET_PLUGIN_DIR.'/views/render/input_multiple.php';
}

function render_checkbox($row) {
	if (!isset($row['setting'])) return;
	include MAILTARGET_PLUGIN_DIR.'/views/render/input_checkbox.php';
}