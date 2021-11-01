<?php
/**
 * Plugin Name:       Golden Egg Admin
 * Description:       Golden egg admin plugin
 * Author:            Creative Slice
 * Version:           0.1.0
 *
 * @package           egg-admin
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

define( 'EGG_ADMIN_VERSION', '0.1.0' );


/**
 * Removes hard-coded admin bar offsets which break layout
 */
add_theme_support( 'admin-bar', array( 'callback' => '__return_false' ) );


/**
 * Modify the admin bar left label
 */
add_action( 'wp_before_admin_bar_render', 'eggplugin_adminbar_titles' );
function eggplugin_adminbar_titles( ) {
	if(is_admin()) { 
		$title = "Home";
	} else {
		$title = "";
		global $wp_admin_bar;
    $wp_admin_bar->add_menu( array(
            'id'    => 'site-name',
            'title' => $title,
        )
    );
	}
    
}


/**
 * Style front end admin bar.
 */
add_action( 'wp_head', 'eggplugin_style_admin_bar' );
function eggplugin_style_admin_bar() {
	if ( is_user_logged_in() ) {
	    echo "<style type='text/css'>
	    @media (min-width: 782px){
			#wpadminbar {
				width: auto;
				min-width: 400px;
				background: rgba(0,0,0,.8);
				border-bottom-right-radius: 8px;
				outline: 1px solid rgba(255,255,255,.5);
			}
		}
		#wpadminbar #wp-admin-bar-site-name>.ab-item {
			border-right: 1px solid rgba(255,255,255,.3);
		}
		#wpadminbar #wp-admin-bar-site-name>.ab-item::before {
			content: '\\f324';
			margin-right: 0;
		}
		</style>";
	}
}


/**
 * Remove howdy in the admin bar
 */
add_filter( 'gettext', 'eggplugin_replace_howdy', 10, 3 );
function eggplugin_replace_howdy( $translated, $text, $domain ) {
	if ( false !== strpos($translated, "Howdy") ) {
		return str_replace("Howdy,", "", $translated);
	}
	return $translated;
}


/**
 * Remove top admin menu items
 */
function eggplugin_customize_admin_bar() {
	global $wp_admin_bar;
	$wp_admin_bar->remove_menu('search');
	$wp_admin_bar->remove_menu('wp-logo');
	$wp_admin_bar->remove_menu('about');
	$wp_admin_bar->remove_menu('wporg');
	$wp_admin_bar->remove_menu('documentation');
	$wp_admin_bar->remove_menu('support-forums');
	$wp_admin_bar->remove_menu('feedback');
	$wp_admin_bar->remove_menu('view-site');
	$wp_admin_bar->remove_menu('new-content');
	$wp_admin_bar->remove_menu('new-link');
	$wp_admin_bar->remove_menu('new-media');
	$wp_admin_bar->remove_menu('new-user');
	$wp_admin_bar->remove_menu('customize');
	$wp_admin_bar->remove_menu('customize-themes');
	$wp_admin_bar->remove_menu('themes');
	$wp_admin_bar->remove_menu('widgets');
	//$wp_admin_bar->remove_node('rank-math'); // Rank Math plugin
}
add_action( 'wp_before_admin_bar_render', 'eggplugin_customize_admin_bar' );



/**
 * Remove Help Tab
 */
function eggplugin_remove_help_tabs() {
	$screen = get_current_screen();
	$screen->remove_help_tabs();
}
add_action('admin_head', 'eggplugin_remove_help_tabs');


/**
 * Disable default dashboard widgets.
 */
function eggplugin_disable_dashboard_widgets() {
	remove_meta_box('dashboard_right_now', 'dashboard', 'core');    	// Right Now Widget
	remove_meta_box('dashboard_incoming_links', 'dashboard', 'core'); 	// Incoming Links Widget
	remove_meta_box('dashboard_plugins', 'dashboard', 'core');			// Plugins Widget
	remove_meta_box('dashboard_quick_press', 'dashboard', 'core');		// Quick Press Widget
	remove_meta_box('dashboard_recent_drafts', 'dashboard', 'core');	// Recent Drafts Widget
	remove_meta_box('dashboard_activity', 'dashboard', 'core');			// Activity Widget
	remove_meta_box('dashboard_primary', 'dashboard', 'core');			// WordPress News Widget
	remove_meta_box('dashboard_site_health', 'dashboard', 'normal');	// Site Health Widget
	// remove_meta_box('rg_forms_dashboard', 'dashboard', 'normal');	// Gravity Forms Widget
	// remove_meta_box('wpe_dify_news_feed', 'dashboard', 'normal');	// WPEngine News Widget
}
add_action( 'admin_menu', 'eggplugin_disable_dashboard_widgets' );


/**
 * Filter the show welcome panel meta data to always be false.
 */
function eggplugin_hide_welcome_panel( $value, int $user_id, string $meta_key ) {
	if ( $meta_key !== 'show_welcome_panel' ) {
		return $value;
	}
	return [0];
}
add_filter( 'get_user_metadata', 'eggplugin_hide_welcome_panel', 10, 3 );



/**
 * Disable the auto generated email sent to the admin after a successful core update:
 */
function eggplugin_bypass_auto_update_email( $send, $type, $core_update, $result ) {
    if ( ! empty( $type ) && $type == 'success' ) {
        return false;
    }
    return true;
}
add_filter( 'auto_core_update_send_email', 'eggplugin_bypass_auto_update_email', 10, 4 );
