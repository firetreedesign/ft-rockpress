<?php
/**
 * RockPress Admin Ajax
 *
 * @package RockPress
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * RockPress_Admin_Ajax class
 */
class RockPress_Admin_Ajax {

	/**
	 * Class init
	 *
	 * @since 0.2.0
	 */
	public function init() {
		add_action( 'wp_ajax_rockpress_check_services', array( $this, 'check_services' ) );
	}

	/**
	 * Check the services
	 *
	 * @return void
	 */
	public function check_services() {

		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'rockpress-nonce' ) ) {
			die( esc_html__( 'Insufficient Permissions', 'ft-rockpress' ) );
		}

		$type = 'notice notice-error';
		$icon = '<span class="dashicons dashicons-no"></span> ';
		$message = __( 'Failed to retrieve data from Rock.', 'ft-rockpress' );

		if ( RockPress()->rock->test() ) {
			$type = 'notice notice-success';
			$icon = '<span class="dashicons dashicons-yes"></span> ';
			$message = __( 'Successfully retrieved data from Rock.', 'ft-rockpress' );
		}

		echo sprintf( '<br /><div class="%s"><p>%s %s</p></div>', esc_html( $type ), $icon, esc_html( $message ) );
		
		wp_die();

	}

}
$rockpress_admin_ajax = new RockPress_Admin_Ajax();
$rockpress_admin_ajax->init();
