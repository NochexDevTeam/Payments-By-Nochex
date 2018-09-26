<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// display activation notice
function wpeppsubNCX_admin_notices() {
	if (!get_option('wpeppsubNCX_notice_shown')) {
		echo "<div class='updated notice is-dismissible'><p>Payments by Nochex: <a href='admin.php?page=wpeppsubNCX_settings'>Click here to view the plugin settings</a>.</p></div>";
		update_option("wpeppsubNCX_notice_shown", "true");
	}
}
add_action('admin_notices', 'wpeppsubNCX_admin_notices');

// add menu
function wpeppsubNCX_plugin_menu() {
	global $plugin_dir_url;
	
	add_menu_page("Subscription", "Nochex", "manage_options", "wpeppsubNCX_menu", "wpeppsubNCX_plugin_orders",'dashicons-tag','28.5');
	
	add_submenu_page("wpeppsubNCX_menu", "Payments", "Payments", "manage_options", "wpeppsubNCX_menu", "wpeppsubNCX_plugin_orders");
	
	add_submenu_page("wpeppsubNCX_menu", "Buttons", "Buttons", "manage_options", "wpeppsubNCX_buttons", "wpeppsubNCX_plugin_buttons");
	
	add_submenu_page("wpeppsubNCX_menu", "Settings", "Settings", "manage_options", "wpeppsubNCX_settings", "wpeppsubNCX_plugin_options");
}
add_action("admin_menu", "wpeppsubNCX_plugin_menu");

// plugins menu links
function wpeppsubNCX_action_links($links) {
	global $support_link, $edit_link, $settings_link;
	
	$links[] = '<a href="https://support.nochex.com/index.php" target="_blank">Nochex Support</a>';
	$links[] = '<a href="admin.php?page=wpeppsubNCX_settings">Settings</a>';
	
	return $links;
}
add_filter( 'plugin_action_links_' . $plugin_basename, 'wpeppsubNCX_action_links' );

// logging
function wpeppsubNCX_log($input) {
	
	// check if logging in enabled
	$options = get_option('wpeppsubNCX_settingsoptions');
		foreach ($options as $k => $v ) {
			$value[$k] =  wp_kses($v, array(
					'a' => array(
					'href' => array(),
					'title' => array()
				),
				'br' => array(),
				'em' => array(),
				'strong' => array(),
			));
		}


	if ($value['log'] == "1") {
		
		// read current post 
		$post_data = get_post($value['logging_id']);
		$data = $post_data->post_content;
		$date = date('m/d/Y H:i:s', current_time('timestamp', 0));
		$data = $date.": ".$input."\n".$data;
		
		// setup new post data
		$my_post = array(
			'ID'           => $value['logging_id'],
			'post_title'   => 'wpeppsubNCX_logs',
			'post_content' => $data,
		);
		
		// update
		wp_update_post( $my_post );
		
	}

}

// clear logs
function wpeppsubNCX_clear_log() {
	
	// check if logging in enabled
	$options = get_option('wpeppsubNCX_settingsoptions');
		foreach ($options as $k => $v ) {
			$value[$k] =  wp_kses($v, array(
					'a' => array(
					'href' => array(),
					'title' => array()
				),
				'br' => array(),
				'em' => array(),
				'strong' => array(),
			));
		}
		
		$data = "";
		
		// setup new post data
		$my_post = array(
			'ID'           => $value['logging_id'],
			'post_title'   => 'wpeppsubNCX_logs',
			'post_content' => $data,
		);
		
		// update
		wp_update_post( $my_post );

}

// get php arg separator
function wpeppsubNCX_get_php_arg_separator_outputa() {
	return ini_get( 'arg_separator.output' );
}

// hide admin bar for subscribers
function wpeppsubNCX_hide_admin_bar() {
	if (current_user_can('read') && !current_user_can('upload_files')) {
		$options = get_option('wpeppsubNCX_settingsoptions');
			foreach ($options as $k => $v ) {
				$value[$k] =  wp_kses($v, array(
						'a' => array(
						'href' => array(),
						'title' => array()
					),
					'br' => array(),
					'em' => array(),
					'strong' => array(),
				));
			}
		if ($value['hideadmin'] == "1") {
			show_admin_bar( false );
		}
	}
}
add_action('init', 'wpeppsubNCX_hide_admin_bar');