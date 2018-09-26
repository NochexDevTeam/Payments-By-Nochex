<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/*
Plugin Name: Payments by Nochex 
Description: A simple and easy way to accept payments.
Author: Nochex 
License: GPL2
Version: 1
*/
 

//// variables
// plugin function 	  			= wpeppsubNCXNCX
// shortcode 		  			= wpeppsubNCXNCX
// subscription post type	  	= wpplugin_subscr
// expired post type	  		= wpplugin_subscr_eot
// cancelled post type	  		= wpplugin_subscr_cancel - not used
// button post type	  			= wpplugin_sub_button
// order post type	  			= wpplugin_sub_order
// log post type	  			= wpplugin__sub_log
$plugin_basename = plugin_basename(__FILE__);
 
function activation_wpeppsubNCXNCX() {
	
	// get admin email
	$admin_email = get_option( 'admin_email' );
	
	$current_user = wp_get_current_user();
	
	// make new post for logging
	if( !get_option("wpeppsubNCXNCX_settingsoptions") ) {
		$my_post = array(
			 'post_title'    		=> 'wpplugin__sub_logs'
			,'post_status'   		=> 'publish'
			,'post_author'   		=> $current_user->ID
			,'post_type'     		=> 'wpplugin__sub_log'
		);
		$log_id = wp_insert_post( $my_post );
	} else {
		$log_id = "";
	}
	
	// initial options
	$wpeppsubNCXNCX_settingsoptions = array(
		 'currency'    			=> '25'
		,'language'   			=> '3'
		,'liveaccount'   		=> ''
		,'sandboxaccount'    	=> ''
		,'mode'    				=> '2'
		,'show_currency'    	=> '0'
		,'opens'    			=> '2'
		,'size'    				=> '2'
		,'no_note'    			=> '1'
		,'subscriber'    		=> '1'
		,'content'    			=> '1'
		,'hideadmin'    		=> '1'
		,'guest_text'    		=> 'Subscribers please login to see content.'
		,'cancelled_text'    	=> 'Your subscription has expired or been cancelled.'
		,'no_shipping'    		=> '1'
		,'cancel'    			=> ''
		,'return'    			=> ''
		,'note'    				=> '1'
		,'upload_image'    		=> ''
		,'log'		    		=> '2'
		,'logs'		    		=> ''
		,'logging_id'			=> $log_id
		,'uninstall'			=> '2'
	);
	
	// save options
	add_option("wpeppsubNCXNCX_settingsoptions", $wpeppsubNCXNCX_settingsoptions);
	
}

function deactivation_wpeppsubNCXNCX() {

	delete_option("wpeppsubNCXNCX_notice_shown");
	
}

function uninstall_wpeppsubNCXNCX() {

	// remove all plugin data if option is enabled
	$options = get_option('wpeppsubNCXNCX_settingsoptions');
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
	
	if ($value['uninstall'] == "1") {
		
		// logs
		$args = array('numberposts' => 5,'post_type' =>'wpeppsubNCXNCX_log');
		$posts = get_posts( $args );
		if (is_array($posts)) {
			foreach ($posts as $post) {
			   wp_delete_post( $post->ID, true);
			}
		}
		
		// buttons
		$args = array('numberposts' => 1000,'post_type' =>'wpplugin_sub_button');
		$posts = get_posts( $args );
		if (is_array($posts)) {
			foreach ($posts as $post) {
			   wp_delete_post( $post->ID, true);
			}
		}
		
		// orders
		$args = array('numberposts' => 1000,'post_type' =>'wpplugin_sub_order');
		$posts = get_posts( $args );
		if (is_array($posts)) {
			foreach ($posts as $post) {
			   wp_delete_post( $post->ID, true);
			}
		}
		
		// subscribers
		$args = array('numberposts' => 1000,'post_type' =>'wpplugin_subscr');
		$posts = get_posts( $args );
		if (is_array($posts)) {
			foreach ($posts as $post) {
			   wp_delete_post( $post->ID, true);
			}
		}
		
		// expired subscriptions
		$args = array('numberposts' => 1000,'post_type' =>'wpplugin_subscr_eot');
		$posts = get_posts( $args );
		if (is_array($posts)) {
			foreach ($posts as $post) {
			   wp_delete_post( $post->ID, true);
			}
		}
		
		delete_option("wpeppsubNCXNCX_notice_shown");
		delete_option("wpeppsubNCXNCX_settingsoptions");
		
	}
	
}

// register hooks
register_activation_hook(__FILE__,'activation_wpeppsubNCXNCX');
register_deactivation_hook(__FILE__, 'deactivation_wpeppsubNCXNCX');
register_uninstall_hook(__FILE__,'uninstall_wpeppsubNCXNCX');
	

//// plugin includes
include_once ('includes/private_functions.php');
include_once ('includes/private_button_inserter.php');
include_once ('includes/private_orders.php');
include_once ('includes/private_buttons.php');
include_once ('includes/private_settings.php');
include_once ('includes/public_shortcode.php');
//include_once ('includes/private_filters.php');
include_once ('includes/public_ipn.php');

?>