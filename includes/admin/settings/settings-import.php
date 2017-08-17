<?php
/**
 * RockPress Admin Settings - Import
 *
 * @package RockPress
 * @version 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * RockPress_Settings_Import class
 */
class RockPress_Settings_Import extends RockPress_Settings {

	/**
	 * Class construct
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_action( 'admin_init', array( $this, 'initialize' ) );
	}

	/**
	 * Initialize class
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function initialize() {

		if ( ! RockPress()->rock->is_connected() ) {
			return;
		}

	    // If the option does not exist, then add it.
		if ( false === get_option( 'rockpress_import', false ) ) {
			add_option( 'rockpress_import' );
		}

		// First, we register a section. This is necessary since all future options must belong to one.
		add_settings_section(
			'rockpress_import_section',
			__( 'Data Import', 'rockpress' ),
			array( $this, 'data_import_section_callback' ),
			'rockpress_import'
		);

		$import_schedule = __( 'No import jobs are currently scheduled.', 'rockpress' );
		$import_active = false;

		$import_jobs = apply_filters( 'rockpress_import_jobs', array() );
		if ( 0 < count( $import_jobs ) ) {
			$import_schedule = __( 'Scheduled to run in approximately ', 'rockpress' ) . human_time_diff( strtotime( 'now' ), wp_next_scheduled( 'rockpress_maintenance' ) );
			$import_active = true;
		}

		/**
	     * Automatic Import
	     */
	    add_settings_field(
	        'auto_import',
	        '<strong>' . __( 'Automatic Import', 'rockpress' ) . '</strong>',
	        array( $this, 'text_callback' ),
	        'rockpress_import',
	        'rockpress_import_section',
	        array(
	            'header' => null,
	            'title' => null,
	            'content' => $import_schedule,
	        )
	    );

		if ( true === $import_active ) {

			/**
	    	 * Last Import
	    	 */
			$last_import = get_option( 'rockpress_last_import', 'Never' );
	 		if ( 'Never' !== $last_import ) {
				$last_import = human_time_diff( strtotime( 'now', current_time( 'timestamp' ) ), strtotime( $last_import, current_time( 'timestamp' ) ) ) . ' ago';
	 		}

	    	add_settings_field(
	    		'last_import',
	    		'<strong>' . __( 'Last Import', 'rockpress' ) . '</strong>',
	    		array( $this, 'text_callback' ),
	    		'rockpress_import',
	    		'rockpress_import_section',
				array(
	                'header' => null,
	                'title' => null,
	                'content' => '<span class="rockpress-last-import">' . $last_import . '</span>',
	            )
	    	);

			/**
	    	 * Manual Import
	    	 */
			$import_status = get_option( 'rockpress_import_in_progress', false );
			if ( $import_status ) {
				$import_status = 'running';
			}

	    	add_settings_field(
	    		'import_actions',
	    		'<strong>' . __( 'Actions', 'rockpress' ) . '</strong>',
	    		array( $this, 'text_callback' ),
	    		'rockpress_import',
	    		'rockpress_import_section',
				array(
	                'header' => null,
	                'title' => null,
	                'content' => '<a class="button button-primary" id="rockpress-manual-import-button" data-rockpress-status="' . $import_status . '">Import Now</a> <a class="button button-secondary" id="rockpress-reset-import-button">Reset</a><div id="rockpress-import-status"></div>',
	            )
	    	);

		}

	    // Finally, we register the fields with WordPress.
		register_setting(
			'rockpress_import',		// The group name of the settings being registered.
			'rockpress_import',		// The name of the set of options being registered.
			array( $this, 'sanitize_callback' )	// The name of the function responsible for validating the fields.
		);

	}

	/**
	 * Data Import section callback
	 *
	 * @since 0.2.0
	 *
	 * @return void
	 */
	public function data_import_section_callback() {
		echo '<p>' . esc_html__( 'We will automatically import the data from RockRMS for you every hour.', 'rockpress' ) . '</p>';
	}

	/**
	 * Sanitize callback
	 *
	 * @since 1.0.0
	 *
	 * @param  array $input Input values.
	 *
	 * @return array
	 */
	public function sanitize_callback( $input ) {

	    // Define all of the variables that we'll be using.
		$output = array();

		// Loop through each of the incoming options.
		foreach ( $input as $key => $value ) {

			// Check to see if the current option has a value. If so, process it.
			if ( isset( $input[ $key ] ) ) {

				// Strip all HTML and PHP tags and properly handle quoted strings.
				$output[ $key ] = strip_tags( stripslashes( $input[ $key ] ) );

			}
		}

		// Return the array.
		return $output;

	}

}
new RockPress_Settings_Import();
