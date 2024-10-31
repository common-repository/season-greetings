<?php
/*
Plugin Name: Season Greetings
Plugin URI: http://projects.karthik.sg/wp/
Author: Alagappan Karthikeyan
Author URI: http://www.karthik.sg/
Description: Season`s Greetings for your wordpress blog. See the <a href="http://projects.karthik.sg/wp/sgdemo/">live demo</a>.
Version: 1.0
*/

/**
 * Check who we are and load stuff
 */
function seasongreets_load() {
	// http://codex.wordpress.org/Determining_Plugin_and_Content_Directories
	// Pre-2.6 compatibility
	if ( ! defined( 'WP_CONTENT_URL' ) )
	      define( 'WP_CONTENT_URL', get_option( 'siteurl' ) . '/wp-content' );
	if ( ! defined( 'WP_CONTENT_DIR' ) )
	      define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );
	if ( ! defined( 'WP_PLUGIN_URL' ) )
	      define( 'WP_PLUGIN_URL', WP_CONTENT_URL. '/plugins' );
	if ( ! defined( 'WP_PLUGIN_DIR' ) )
	      define( 'WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins' );
	global $seasongreets;
	$seasongreets = array(
		'path' => WP_PLUGIN_DIR . '/' . str_replace( basename( __FILE__ ), "", plugin_basename( __FILE__ ) ),
		'url' => WP_PLUGIN_URL . '/' . str_replace( basename( __FILE__ ), "", plugin_basename( __FILE__ ) ),
	);

	// TODO split translations?
	add_action( 'init', 'seasongreets_load_translation_file' );

	if ( is_admin() ) {
		require_once( 'inc/admin.php' );
		register_activation_hook( __FILE__, 'seasongreets_activate' );
		register_uninstall_hook( __FILE__, 'seasongreets_uninstall' );
		add_action( 'admin_menu', 'seasongreets_add_pages' );
	}
	require_once( 'inc/page.php' );
}

/**
 * Load Translations
 *
 * @todo maybe split the two sentences for the frontend into a different file?
 */
function seasongreets_load_translation_file() {
	global $seasongreets;
	$translation_path = basename ( $seasongreets['path'] ) . '/translations';
	load_plugin_textdomain( 'seasongreets', '', $translation_path );
}

seasongreets_load();
