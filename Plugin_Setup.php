<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if Access Directly.
}
/**
 * Here Will be initialize all the required files. This will be fired only if WooCommerce is active.
 */

class Plugin_Setup {
	private static $_instance = null;
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function woo_extend_style_scripts() {
		wp_enqueue_style( 'custom-css', plugin_dir_url( __FILE__ ) . '/admin/assets/style.css', array(), '1.0', 'all' );
	}

	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'woo_extend_style_scripts' ) );
		// Database Functions

		// Run All Functions
		require 'Functions_Init.php';
	}


}
// Instantiate The Plugin
Plugin_Setup::instance();
