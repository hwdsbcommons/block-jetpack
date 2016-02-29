<?php
/*
Plugin Name: Jetpack - Toggle Development Mode
Plugin URI: http://commons.hwdsb.on.ca
Description: Toggle Jetpack's development mode in order to use WordPress.com JP features or not.
Author: r-a-y
Version: 1.0
Author URI: https://commons.hwdsb.on.ca
License: GPLv2 or later
*/

/**
 * Should we toggle JP's development mode?
 */
function hwdsb_jp_toggle_development() {
	if ( false === defined( 'JETPACK__VERSION' ) ) {
		return;
	}

	$use_jp = (int) get_option( 'hwdsb_use_jetpack' );

	// If we're not enabling WP.com for Jetpack, use JP's development mode
	if ( empty( $use_jp ) ) {
		add_filter( 'jetpack_development_mode', '__return_true' );
	}

	// Add admin hook
	add_action( 'admin_menu', 'hwdsb_jp_admin_init', 999 );
}
add_action( 'plugins_loaded', 'hwdsb_jp_toggle_development' );

/**
 * Admin loader.
 */
function hwdsb_jp_admin_init() {
	require_once dirname( __FILE__ ) . '/jetpack-toggle-development-admin.php';
	HWDSB_JP_Admin::init();
}