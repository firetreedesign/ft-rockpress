<?php
/**
 * Adds a fallback layer to the transient data that allows a background hook
 * to update the transient without the end user having to wait.
 *
 * @package RockPress
 * @author Daniel Milner
 * @version 1.0.2
 */

/**
 * RockPress Transients
 */
class RockPress_Transients {

	/**
	 * Transient prefix
	 *
	 * @var string
	 */
	private $prefix;

	/**
	 * Fallback expiration in minutes
	 *
	 * @var int
	 */
	private $fallback_expiration;

	/**
	 * Create a new instance
	 */
	function __construct() {

		$this->prefix				= 'ropr_'; // Must be 7 characters or less.
		$this->fallback_expiration	= 10080;

		// Adds a hook to access the cleanup function.
		add_action( 'rockpress_transient_cache_cleanup', array( $this, 'cleanup' ) );

		/**
		 * If the cleanup hook is not scheduled, then add a one-time event.
		 * This is done in order to avoid having to hook into plugin activate/deactive.
		 */
		if ( ! wp_get_schedule( 'rockpress_transient_cache_cleanup' ) ) {
			wp_clear_scheduled_hook( 'rockpress_transient_cache_cleanup' );
			wp_schedule_event( time(), 'daily', 'rockpress_transient_cache_cleanup' );
		}

	}

	/**
	 * Get the data from the transient and/or schedule new data to be retrieved.
	 *
	 * @param string $transient	The name of the transient. Must be 43 characters or less including $this->transient_prefix.
	 * @param string $hook		The name of the hook to retrieve new data.
	 * @param array  $args		An array of arguments to pass to the function.
	 *
	 * @return	mixed	Either false or the data from the transient.
	 */
	public function get_transient( $transient, $hook, $args ) {

		$args['refresh_cache'] = 1;

		// Build the transient names.
		$transient			= $this->prefix . $transient;
		$fallback_transient	= $transient . '_';

		if ( is_multisite() ) {
			if ( false === ( $data = get_site_transient( $transient ) ) ) {

				$data = get_site_transient( $fallback_transient );

				if ( ! wp_get_schedule( $hook, $args ) ) {
					wp_clear_scheduled_hook( $hook, $args );
					wp_schedule_single_event( time(), $hook, $args );
				}

				return $data;

			} else {
				return $data;
			}
		} else {
			if ( false === ( $data = get_transient( $transient ) ) ) {

				$data = get_transient( $fallback_transient );

				if ( ! wp_get_schedule( $hook, $args ) ) {
					wp_clear_scheduled_hook( $hook, $args );
					wp_schedule_single_event( time(), $hook, $args );
				}

				return $data;

			} else {
				return $data;
			}
		}

	}

	/**
	 * Sets the data in both the transient and the fallback transient.
	 *
	 * @param string $transient		The name of the transient. Must be 43 characters or less including $this->transient_prefix.
	 * @param mixed	 $value			Transient value.
	 * @param int    $expiration	How long you want the transient to live. In minutes.
	 *
	 * @return boolean
	 */
	public function set_transient( $transient, $value, $expiration ) {

		// Build the transient names.
		$transient			= $this->prefix . $transient;
		$fallback_transient	= $transient . '_';

		// Set the transients and store the results.
		if ( is_multisite() ) {
			$result = set_site_transient( $transient, $value, $expiration * MINUTE_IN_SECONDS );
			$fallback_result = set_site_transient( $fallback_transient, $value, $this->fallback_expiration * MINUTE_IN_SECONDS );
		} else {
			$result = set_transient( $transient, $value, $expiration * MINUTE_IN_SECONDS );
			$fallback_result = set_transient( $fallback_transient, $value, $this->fallback_expiration * MINUTE_IN_SECONDS );
		}

		if ( $result && $fallback_result ) {

			return true;

		} else {

			// Delete both transients in case only one was successful.
			if ( is_multisite() ) {
				delete_site_transient( $transient );
				delete_site_transient( $fallback_transient );
			} else {
				delete_transient( $transient );
				delete_transient( $fallback_transient );
			}

			return false;

		}

	}

	/**
	 * Sets the data in both the transient and the fallback transient.
	 *
	 * @param string $transient The name of the transient. Must be 43 characters or less including $this->transient_prefix.
	 *
	 * @return boolean
	 */
	public function delete_transient( $transient ) {

		// Build the transient names.
		$transient			= $this->prefix . $transient;
		$fallback_transient	= $transient . '_';

		// Delete the transients.
		if ( is_multisite() ) {
			delete_site_transient( $transient );
			delete_site_transient( $fallback_transient );
		} else {
			delete_transient( $transient );
			delete_transient( $fallback_transient );
		}

		return true;

	}

	/**
	 * Purges expired transients. By using $this->prefix, we only purge transients created by this class.
	 *
	 * @return	boolean	true/false
	 */
	public function cleanup() {

		global $wpdb;

		$now = current_time( 'timestamp' );
		$expired  = $wpdb->get_col( "SELECT option_name FROM $wpdb->options where option_name LIKE '%_transient_timeout_$this->prefix%' AND option_value+0 < $now" );

		if ( empty( $expired ) ) {
			return false;
		}

		foreach ( $expired as $transient ) {

			$name = str_replace( '_transient_timeout_', '', $transient );

			if ( is_multisite() ) {
				delete_site_transient( $name );
			} else {
				delete_transient( $name );
			}
		}

		return true;

	}

}
