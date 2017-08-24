<?php
/**
 * Class to run GET requests in the background.
 *
 * @package RockPress
 * @author Daniel Milner
 * @version 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * RockPress Background Get class
 */
class RockPress_Background_Get extends WP_Background_Process {

	/**
	 * Action name
	 *
	 * @var string
	 */
	protected $action = 'rockpress_get';

	/**
	 * Task to run
	 *
	 * @param mixed $item Queue item to iterate over.
	 *
	 * @return mixed
	 */
	protected function task( $item ) {

		// Remove if no task is set.
		if ( ! isset( $item ) || ! is_array( $item ) || ! isset( $item['controller'] ) ) {
			return false;
		}

		$defaults = array(
			'controller'		=> null,
			'id'				=> null,
			'filter'			=> null,
			'top'				=> null,
			'skip'				=> null,
			'load_attributes'	=> null,
			'cache_lifespan'	=> null,
			'refresh_cache'		=> 0,
		);
		$item = wp_parse_args( $item, $defaults );

		/**
		 * Update our import status
		 */
		if ( ! is_null( $item['top'] ) && ! is_null( $item['skip'] ) ) {
			update_option( 'rockpress_import_in_progress', 'Processing top ' . $item['top'] . ' from ' . $item['controller'] . ' at offset ' . $item['skip'] . '...' );
		} else {
			update_option( 'rockpress_import_in_progress', 'Processing ' . $item['controller'] . '...' );
		}

		$response = RockPress()->rock->get( $item );

		$controller = strtolower( $item['controller'] );
		$response = apply_filters( "rockpress_background_get_{$controller}", $response, $item );

		$response = json_decode( $response, true );

		if ( ! is_null( $item['top'] ) && ! is_null( $item['skip'] ) ) {
			if ( ! empty( $response ) ) {
				$item['skip'] += $item['top'];
				return $item;
			}
		}

		/**
		 * Done
		 */
		return false;

	}

	/**
	 * Complete
	 */
	protected function complete() {
		do_action( 'rockpress_background_get_complete' );
		parent::complete();
	}

}
