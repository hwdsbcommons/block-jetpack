<?php
/**
 * Plugin Name: UMW Multisite JetPack Control
 * Description: Allows code-based control over which JetPack modules are available to site admins https://bitbucket.org/umwedu/umw-multisite-jetpack-control/
 * Version: 1.2
 * Author: cgrymala
 * Network: true
 * License: GPL2
 */

if ( ! class_exists( 'UMW_Multisite_JetPack_Control' ) ) {
	class UMW_Multisite_JetPack_Control {
		function __construct() {
			add_filter( 'jetpack_get_default_modules', array( $this, 'get_default_modules' ) );
			add_filter( 'jetpack_get_available_modules', array( $this, 'get_available_modules' ) );
			add_action( 'init', array( $this, 'activate_forced_modules' ) );
			// Make sure the "Activate" nag for the Site Management module doesn't appear
			add_filter( 'can_display_jetpack_manage_notice', array( $this, '__return_false' ) );
		}

		/**
		 * Retrieve the list of blocked modules
		 * @return array the array of blocked modules
		 */
		function get_blocked_modules() {
			return apply_filters( 'umw-blocked-jetpack-modules', array(
				'contact-form', /* No need when we have Gravity Forms installed */
				'custom-content-types', /* Currently useless */
				'gravatar-hovercards', /* Causes some JS conflicts throughout the site */
				'infinite-scroll', /* None of our theme configurations support this */
				'sso', /* Would conflict with our sign-on system */
				'manage', /* Would be ridiculous with the number of sites running off of one install */
				'minileven', /* We don't want a third-party mobile theme */
				'monitor', /* We have other systems in-place to keep an eye on site uptime */
				'notes', /* We don't want to receive notifications about every little thing that happens throughout the install */
				'photon', /* Until we have a better chance to investigate how this effects us, we need it turned off */
				'site-icon', /* We have a single favicon that needs to be used */
				'verification-tools', /* We don't want them to verify that someone other than us owns the site */
				'videopress', /* We don't have a VideoPress subscription, so need to confuse users */
				'vaultpress', /* We don't have a VaultPress subscription, so no need to confuse users */
				'stats', /* Make sure we're collecting stats on all sites */
				'comments', /* Seeing errors in the logs, so let's turn this off  */
				'enhanced-distribution', /* What does this do? */
				'likes', /* extraneous  */
				'subscriptions', /* extraneous  */
				'sitemaps', /* extraneous  */
			) );
		}

		/**
		 * Retrieve a list of modules that should be forcibly activated on all sites
		 * @return array the array of forced modules
		 */
		function get_forced_modules() {
			return apply_filters( 'umw-forced-jetpack-modules', array(
				'tiled-gallery', /* Very useful, doesn't hurt to have it active */
				'carousel', /* Very useful, doesn't hurt to have it active */
				'shortcodes', /* Very useful, doesn't hurt to have it active */
				'widget-visibility', /* Very useful, doesn't hurt to have it active */
				'custom-css', /* Very useful, doesn't hurt to have it active */
				'widgets', /* Very useful, doesn't hurt to have it active */
			) );
		}

		/**
		 * Make sure that none of our blocked modules are activated by default
		 * @param array $modules an auto-indexed array of the module slugs
		 * @return array the modified array of default modules
		 */
		function get_default_modules( $modules=array() ) {
			if ( empty( $modules ) || ! is_array( $modules ) )
				return $modules;

			$modules = array_unique( array_merge( $modules, $this->get_forced_modules() ) );

			$good_modules = array();
			$bad_modules = $this->get_blocked_modules();

			foreach ( $modules as $m ) {
				if ( ! in_array( $m, $bad_modules ) ) {
					$good_modules[] = $m;
				}
			}

			return $good_modules;
		}

		/**
		 * Make sure that none of our blocked modules are available for activation
		 * @param array $modules an associative array using the module slugs as keys
		 * @return array the modified array of available modules
		 */
		function get_available_modules( $modules=array() ) {
			if ( empty( $modules ) || ! is_array( $modules ) )
				return $modules;

			$good_modules = array();
			$bad_modules = $this->get_blocked_modules();
			foreach( $modules as $k=>$v ) {
				if ( ! in_array( $k, $bad_modules ) ) {
					$good_modules[$k] = $v;
				}
			}

			return $good_modules;
		}

		/**
		 * Forcibly deactivate modules that are blocked
		 */
		function deactivate_blocked_modules() {
			$bad_modules = $this->get_blocked_modules();
			if ( ! class_exists( 'Jetpack_Options' ) )
				return false;

			$active = Jetpack_Options::get_option( 'active_modules', array() );
			$good_modules = array_diff( $active, $bad_modules );
			/*error_log( '[JetPack Force Deactivate Debug]: The difference between the active & bad modules arrays looks like: ' . "\n" . print_r( $good_modules, true ) );*/
			Jetpack_Options::update_option( 'active_modules', $good_modules );
		}

		/**
		 * Forcibly activate modules that we need/want active everywhere
		 * This method also handles forcibly deactivating blocked modules, so
		 * 		we don't need to run that separate method unless our list of
		 * 		forced modules is empty.
		 */
		function activate_forced_modules() {
			$forced_modules = $this->get_forced_modules();
			if ( empty( $forced_modules ) )
				return $this->deactivate_blocked_modules();

			$bad_modules = $this->get_blocked_modules();
			if ( ! class_exists( 'Jetpack_Options' ) )
				return false;

			$active = Jetpack_Options::get_option( 'active_modules', array() );
			/* Add our forced modules to the list of good modules */
			$good_modules = array_unique( array_merge( $active, $forced_modules ) );
			/* If a moddule is both forced & blocked for some reason, default to blocking it */
			$good_modules = array_diff( $good_modules, $bad_modules );
			Jetpack_Options::update_option( 'active_modules', $good_modules );
		}

		/**
		 * Return boolean false
		 * Currently, this function is only used in one hook, but I'm separating it out,
		 * 		expecting that we'll need to use it more in the future
		 * Doing this, rather than create_func() or encapsulation makes it compatible with
		 * 		more versions of PHP
		 * @return bool false
		 */
		function __return_false() {
			return false;
		}
	}

	function inst_umw_multisite_jetpack_control_obj() {
		global $umw_multisite_jetpack_control_obj;
		$umw_multisite_jetpack_control_obj = new UMW_Multisite_JetPack_Control;
	}

	inst_umw_multisite_jetpack_control_obj();
}
