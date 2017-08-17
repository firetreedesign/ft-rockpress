<?php
/**
 * RockPress Admin Styles
 *
 * @package RockPress
 * @version 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * RockPress_Admin_Styles class
 */
class RockPress_Admin_Styles {

	/**
	 * Class construct
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_action( 'admin_print_styles', array( $this, 'admin_styles' ) );
	}

	/**
	 * Admin styles
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function admin_styles() {
	    wp_enqueue_style( 'rockpress-admin', ROCKPRESS_PLUGIN_URL . 'assets/css/admin.css' );
	}

}
new RockPress_Admin_Styles();
