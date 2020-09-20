<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
class Functions_Init {
	public function __construct() {
		require 'admin/DB_Handling.php';
		require 'includes/Ajax_Handling_Core.php';
		add_action( 'init', array( $this, 'teams_extend_hide_login_for_team_player' ) );
		// Creating Table
		add_action( 'init', array( $this, 'teams_extend_table_save_order' ) );
		// Custom ATC Button
		add_action( 'woocommerce_after_shop_loop_item', array( $this, 'teams_extend_custom_atc_btn' ), 10 );

		// Enqueue Scripts
		add_action( 'wp_enqueue_scripts', array( $this, 'teams_extend_public_scripts' ) );

		// Removing Default Cart for Team Player
		add_action( 'woocommerce_before_cart', array( $this, 'teams_extend_custom_cart_page' ) );

		// Creating Messages Page
		add_action( 'init', array( $this, 'teams_extend_messages_page' ) );
	}

	/** Public Enqueueing Scripts */
	public function teams_extend_public_scripts() {
		wp_enqueue_style( 'styles-main', plugin_dir_url( __FILE__ ) . '/admin/assets/css/style.css', array(), '1.0', 'all' );
		wp_enqueue_script( 'order-saving', plugin_dir_url( __FILE__ ) . '/admin/assets/js/order-saving-ajax.js', array( 'jquery' ), '1.0', true );

		// Ajax Localizing
		wp_localize_script(
			'order-saving',
			'ajax_control',
			array(
				'ajaxurl'  => admin_url( 'admin-ajax.php' ),
				'site_url' => home_url(),
				'security' => wp_create_nonce( 'form-controller' ),
			)
		);
	}

	/**
	 * Creating Custom Table for Team Player Order Saving
	 */
	public function teams_extend_table_save_order() {
		$db        = new DB_Handling_Core();
		$sql_query = 'CREATE TABLE IF NOT EXISTS team_player_orders(
			team_player_id INT(9) NOT NULL,
			team_player_name VARCHAR(255),
			team_code VARCHAR(255),
			product_selected_id VARCHAR(255),
			product_selected_qty INT(9)
			)';
		$db->teams_extend_create_table( $sql_query );
	}


	/**
	 * Hiding the ATC Button for Team Player.
	 */
	public function teams_extend_hide_login_for_team_player() {
		$user = wp_get_current_user();
		if ( $user->roles && $user->roles[0] == 'team_player' ) {
			remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
			remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
		}
	}



	/**
	 * Custom Add to Cart Button for Team Player
	 */
	public function teams_extend_custom_atc_btn() {
		global $wpdb;
		$query = $wpdb->get_var( 'SELECT COUNT(*) from team_player_orders WHERE product_selected_id=' . get_the_ID() . '' );
		if ( $query ) {
			echo '<button class="added_to_order" disabled>Added</button>';
		} else {
			echo '<button class="add_to_order" data-user-id=' . get_current_user_id() . ' data-product-id=' . get_the_ID() . '>Add to Order</button>';
		}
	}

	/**
	 * Custom Cart Page
	 */
	public function teams_extend_custom_cart_page() {
		// echo ':uhuhu';
	}


	/**
	 * Create Page for Messages
	 */
	public function teams_extend_messages_page() {
		// Pages_Handling::teams_login_create_pages( 'page', 'dashboard/messages', 'Messages', '' );
	}



}

new Functions_Init();
