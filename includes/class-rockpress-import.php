<?php
/**
 * RockPress - Import
 *
 * @since	0.2.0
 * @package	RockPress
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * RockPress Import class
 */
class RockPress_Import {

	/**
	 * Initialize the class
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function init() {
		add_action( 'rockpress_maintenance',				__CLASS__ . '::run' );
		add_action( 'rockpress_import_job_queued',			__CLASS__ . '::import_job_queued' );
		add_action( 'rockpress_import_jobs_dispatched',		__CLASS__ . '::import_jobs_dispatched' );
		add_action( 'rockpress_background_get_complete',	__CLASS__ . '::import_complete', 100 );
		add_action( 'wp_ajax_rockpress_import',				__CLASS__ . '::ajax_run' );
		add_action( 'wp_ajax_rockpress_import_status',		__CLASS__ . '::ajax_status' );
		add_action( 'wp_ajax_rockpress_last_import',		__CLASS__ . '::ajax_last_import' );
		add_action( 'wp_ajax_rockpress_reset_import', 		__CLASS__ . '::ajax_reset_import' );

	}

	/**
	 * Run the import
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function run() {

		if ( ! RockPress()->rock->is_connected() ) {
			delete_option( 'rockpress_last_import' );
			delete_option( 'rockpress_import_in_progress' );
			return;
		}

		$jobs = apply_filters( 'rockpress_import_jobs', array() );

		if ( ! is_array( $jobs ) ) {
			return;
		}

		if ( 0 === count( $jobs ) ) {
			return;
		}

		foreach ( $jobs as $job ) {
			do_action( 'rockpress_import_job_queued', $job );
			RockPress()->get->push_to_queue( $job );
		}

		do_action( 'rockpress_import_jobs_dispatched' );
		RockPress()->get->save()->dispatch();

	}

	/**
	 * Import job queued
	 *
	 * @param array $job Job array.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function import_job_queued( $job ) {
		update_option( 'rockpress_import_in_progress', __( 'Pushing job to the queue...', 'rockpress' ) );
	}

	/**
	 * Import job dispatched
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function import_jobs_dispatched() {
		update_option( 'rockpress_import_in_progress', __( 'Import job has been dispatched...', 'rockpress' ) );
	}

	/**
	 * Import complete
	 *
	 * @since 1.0.3
	 *
	 * @return void
	 */
	public static function import_complete() {

		/**
		 * Update our import status
		 */
		delete_option( 'rockpress_import_in_progress' );
		update_option( 'rockpress_last_import', date( 'Y-m-d H:i:s', current_time( 'timestamp' ) ) );

	}

	/**
	 * Run the import via ajax
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function ajax_run() {

		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'rockpress-nonce' ) ) {
			die( esc_html__( 'Insufficient Permissions', 'rockpress' ) );
		}

		self::run();

		echo 'started';
		wp_die();

	}

	/**
	 * Get the status via ajax
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function ajax_status() {

		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'rockpress-nonce' ) ) {
			die( esc_html__( 'Insufficient Permissions', 'rockpress' ) );
		}

		$status = array();
		$progress = get_option( 'rockpress_import_in_progress', false );

		if ( false === $progress ) {
			wp_send_json( 'false' );
		}

		array_push( $status, array(
			'text' => $progress,
			'element'	=> 'strong',
		) );
		array_push( $status, array(
			'text'		=> esc_html__( 'Import is running in the background. Leaving this page will not interrupt the process.', 'rockpress' ),
			'element'	=> 'i',
		) );

		wp_send_json( $status );

	}

	/**
	 * Get the last import via ajax
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public static function ajax_last_import() {

		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'rockpress-nonce' ) ) {
			die( esc_html__( 'Insufficient Permissions', 'rockpress' ) );
		}

		$last_import = get_option( 'rockpress_last_import', 'Never' );
		if ( 'Never' === $last_import ) {
			echo esc_html( $last_import );
		} else {
			echo esc_html( human_time_diff( strtotime( 'now', current_time( 'timestamp' ) ), strtotime( $last_import, current_time( 'timestamp' ) ) ) . ' ago' );
		}

		wp_die();

	}

	/**
	 * Reset last import date
	 *
	 * @return void
	 */
	public static function ajax_reset_import() {
		delete_option( 'rockpress_last_import' );
		esc_html_e( 'Never', 'rockpress' );
		wp_die();
	}

}
RockPress_Import::init();
