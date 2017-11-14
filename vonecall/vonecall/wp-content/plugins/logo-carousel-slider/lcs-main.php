<?php
/*
Plugin Name: Logo Carousel Slider
Plugin URI:  http://adlplugins.com/plugin/logo-carousel-slider
Description: This plugin allows you to easily create logo carousel slider to display logos of clients, partners, sponsors, affiliates etc. in a beautiful carousel slider.
Version:     1.5
Author:      ADL Plugins
Author URI:  http://adlplugins.com
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Domain Path: /languages/
Text Domain: logo-carousel-slider
*/

/**
 * Protect direct access
 */
if( ! defined( 'LCS_HACK_MSG' ) ) define( 'LCS_HACK_MSG', __( 'Sorry! This is not your place!', 'logo-carousel-slider' ) );
if ( ! defined( 'ABSPATH' ) ) die( LCS_HACK_MSG );

/**
 * Defining constants
 */
if( ! defined( 'LCS_PLUGIN_DIR' ) ) define( 'LCS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
if( ! defined( 'LCS_PLUGIN_URI' ) ) define( 'LCS_PLUGIN_URI', plugins_url( '', __FILE__ ) );

require_once LCS_PLUGIN_DIR . 'includes/lcs-metabox-overrider.php';
require_once LCS_PLUGIN_DIR . 'includes/lcs-settings.php';
require_once LCS_PLUGIN_DIR . 'includes/lcs-metabox.php';
require_once LCS_PLUGIN_DIR . 'includes/lcs-img-resizer.php';
require_once LCS_PLUGIN_DIR . 'includes/lcs-shortcodes.php';

/**
 * Registers scripts and stylesheets
 */
function lcs_frontend_scripts_and_styles() {
	wp_register_style( 'lcs-owl-carousel-style', LCS_PLUGIN_URI . '/css/owl.carousel.css' );
	wp_register_style( 'lcs-owl-theme-style', LCS_PLUGIN_URI . '/css/owl.theme.css' );
	wp_register_style( 'lcs-owl-transitions', LCS_PLUGIN_URI . '/css/owl.transitions.css' );
	wp_register_style( 'lcs-custom-style', LCS_PLUGIN_URI . '/css/lcs-styles.css' );
	wp_register_style( 'lcs-tooltipster-style', LCS_PLUGIN_URI . '/css/tooltipster.css' );
	wp_register_script( 'lcs-owl-carousel-js', LCS_PLUGIN_URI . '/js/owl.carousel.min.js', array('jquery'),'1.3.1', true );
	wp_register_script( 'lcs-tooltipster-js', LCS_PLUGIN_URI . '/js/jquery.tooltipster.min.js', array('jquery'),'3.3.0', true );
}
add_action( 'wp_enqueue_scripts', 'lcs_frontend_scripts_and_styles' );

function lcs_admin_scripts_and_styles() {
	global $typenow;	
	if ( ($typenow == 'logocarousel') ) {
		wp_enqueue_style( 'lcs_custom_wp_admin_css', LCS_PLUGIN_URI . '/css/lcs-admin-styles.css' );
		wp_enqueue_script( 'lcs_custom_wp_admin_js', LCS_PLUGIN_URI . '/js/lcs-admin-script.js', array('jquery'), '1.3.3', true );
	}	
}
add_action( 'admin_enqueue_scripts', 'lcs_admin_scripts_and_styles' );

/**
 * Enables shortcode for Widget
 */
add_filter('widget_text', 'do_shortcode');

/**
 * Pro Version link
 */
function lcs_pro_version_link( $links ) {
   $links[] = '<a href="http://adlplugins.com/plugin/logo-carousel-slider-pro" target="_blank">Pro Version</a>';
   return $links;
}
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'lcs_pro_version_link' );

/**
 * Upgrade submenu page
 */
function lcs_upgrade_submenu_page() {
	add_submenu_page( 'edit.php?post_type=logocarousel', __('Shortcode Generator', 'logo-carousel-slider'), __('Shortcode Generator', 'logo-carousel-slider'), 'manage_options', 'shortcode_generator', 'lcs_upgrade_callback' );
}
add_action('admin_menu', 'lcs_upgrade_submenu_page');

function lcs_upgrade_callback() {
	include('includes/lcs-upgrade.php');
}