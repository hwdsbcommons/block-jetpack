<?php
/*
Plugin Name: Jetpack Killer
Description: Kill Modules in Jetpack
*/

// First, hide the Remote Site Management banner
add_filter( 'can_display_jetpack_manage_notice', '__return_false' );

// Hide the Jetpack Sub_Menu

//function my_plugin_menu_remover() {
//remove_submenu_page( 'jetpack', 'jetpack' );
//}
add_action( 'admin_menu', 'my_plugin_menu_remover', 999 );

function blacklist_jetpack_modules( $modules ){
$jb_mods_to_disable = array(
// 'shortcodes',
// 'widget-visibility',
'contact-form',
// 'shortlinks',
// 'infinite-scroll',
'wpcc',
// 'tiled-gallery',
'json-api',
// 'publicize',
'vaultpress',
// 'custom-css',
// 'post-by-email',
// 'widgets',
'comments',
// 'minileven',
// 'latex',
'gravatar-hovercards',
// 'enhanced-distribution',
'notes',
// 'subscriptions',
// 'stats',
// 'after-the-deadline',
// 'carousel',
// 'photon',
// 'sharedaddy',
'omnisearch',
// 'mobile-push',
// 'likes',
'videopress',
// 'gplus-authorship',
'sso',
'monitor',
// 'markdown',
// 'verification-tools',
// 'related-posts',
'custom-content-types',
);
foreach ( $jb_mods_to_disable as $mod ) {
if ( isset( $modules[$mod] ) ) {
unset( $modules[$mod] );
}
}
return $modules;
}
add_filter( 'jetpack_get_available_modules', 'blacklist_jetpack_modules' ); 