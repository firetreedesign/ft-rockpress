<?php
/**
 * RockPress Admin Scripts
 *
 * @package RockPress
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * RockPress_Admin_Scripts class
 */
class RockPress_Admin_Scripts {

	/**
	 * Class construct
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
	}

	/**
	 * Enqueue admin scripts
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function admin_enqueue_scripts() {
		$this->enqueue();
		$this->register();
		$this->localize();
	}

	/**
	 * Enqueue scripts
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function enqueue() {

		wp_enqueue_script( 'rockpress-admin', ROCKPRESS_PLUGIN_URL . 'assets/js/admin/admin.js' );

		if ( ! isset( $_GET['page'] ) ) {
			return;
		}
		if ( ! isset( $_GET['tab'] ) ) {
			return;
		}
		if ( 'rockpress-settings' !== $_GET['page'] ) {
			return;
		}
		if ( 'import' !== $_GET['tab'] ) {
			return;
		}
		wp_enqueue_script( 'rockpress-import', ROCKPRESS_PLUGIN_URL . 'assets/js/admin/import.js' );

	}

	/**
	 * Register scripts
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function register() {
		wp_register_script( 'rockpress-beacon', ROCKPRESS_PLUGIN_URL . 'assets/js/help.js', array(), '1.0.0', true );
	}

	/**
	 * Localize the script
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	private function localize() {

		wp_localize_script( 'rockpress-admin', 'rockpress_vars', array(
			'nonce' => wp_create_nonce( 'rockpress-nonce' ),
			'reset_import_dialog' => __( 'Are you sure that you want to reset the last import time?', 'ft-rockpress' ),
		) );

		$current_user = wp_get_current_user();
		wp_localize_script( 'rockpress-beacon', 'rockpress_beacon_vars', array(
			'customer_name'		=> $current_user->display_name,
			'customer_email'	=> $current_user->user_email,
			'ccbpress_ver'		=> RockPress()->version,
			'wp_ver'			=> get_bloginfo( 'version' ),
			'php_ver'			=> phpversion(),
			'topics'			=> apply_filters( 'rockpress_support_topics', array(
				array(
					'val'	=> 'general',
					'label'	=> __( 'General question', 'ft-rockpress' ),
				),
				array(
					'val'	=> 'rock-api-key',
					'label'	=> __( 'Rock RMS API Key', 'ft-rockpress' ),
				),
				array(
					'val'	=> 'bug',
					'label'	=> __( 'I think I found a bug', 'ft-rockpress' ),
				),
			) ),
		) );
	}

}
new RockPress_Admin_Scripts();
