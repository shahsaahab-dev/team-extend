<?php
/**
 * Plugin Name: TeamsExtend
 * Author: StrikeKeys
 * Author URI: https://strikekeys.com
 * Description: Integrating Client Required Functionalities.
 * Text Domain: teams-extend
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if Access Directly.
}

final class TeamsExtend {
	// some required checks.
	const VERSION                     = '1.0';
	const MINIMUM_PHP_VERSION         = '7.0';
	const MINIMUM_WOOCOMMERCE_VERSION = '3.0';

	public function __construct() {
		add_action( 'plugins_loaded', array( $this, 'init' ) );
	}

	public function init() {
		// First Check if Woo is installed.
		if ( ! class_exists( 'WooCommerce' ) ) {
			add_action( 'admin_notices', array( $this, 'woo_missing_message' ) );
		}

		// Check PHP version now
		if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
			add_action( 'admin_notice', array( $this, 'php_version_message' ) );
			return;
		}

		// All Checks Done, its all good. Lets initialize the Plugin Now.
		require_once 'Plugin_Setup.php';
	}

	public function woo_missing_message() {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		// The Message.
		$message = sprintf(
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'woo-extend' ),
			'<strong>' . esc_html__( 'WooExtend Plugin', 'woo-extend' ) . '</strong>',
			'<strong>' . esc_html__( 'WooCommerce', 'woo-extend' ) . '</strong>',
			self::MINIMUM_WOOCOMMERCE_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
	}

	public function php_version_message() {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = sprintf(
			/* translators: 1: Plugin name 2: PHP 3: Required PHP version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'woo-extend' ),
			'<strong>' . esc_html__( 'WooExtend Plugin', 'woo-extend' ) . '</strong>',
			'<strong>' . esc_html__( 'PHP', 'woo-extend' ) . '</strong>',
			self::MINIMUM_PHP_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
	}
}

// Instantiate the Plugin
new TeamsExtend();
