<?php
/**
 * RockPress Admin Settings - Licenses
 *
 * @package RockPress
 * @version 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * RockPress_Settings_Licenses
 */
class RockPress_Settings_Licenses extends RockPress_Settings {

	/**
	 * Class construct
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
	    add_action( 'admin_init', array( $this, 'initialize' ) );
	}

	/**
	 * Initialize the class
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function initialize() {

	    // First, we register a section. This is necessary since all future options must belong to one.
		add_settings_section(
			'rockpress_settings_licenses_section',
			__( 'License Keys', 'ft-rockpress' ),
			array( $this, 'section_callback' ),
			'rockpress_settings_licenses'
		);

	    // If the option does not exist, then add it.
		if ( false === get_option( 'rockpress_licenses' ) ) {
			add_option( 'rockpress_licenses' );
		}

		$license_keys = apply_filters( 'rockpress_license_keys', array() );
		foreach ( $license_keys as $license ) {
			add_settings_field(
	    		$license['id'] . '_license_key',
	    		'<strong>' . $license['name'] . '</strong>',
	    		array( $this, 'license_key_callback' ),
	    		'rockpress_settings_licenses',
	    		'rockpress_settings_licenses_section',
	    		array(
	    			'field_id'  => $license['id'] . '_license_key',
	    			'page_id'   => 'rockpress_licenses',
	                'size'      => 'regular',
					'label'		=> $license['notes'],
	    		)
	    	);
		}

	    // Finally, we register the fields with WordPress.
		register_setting(
			'rockpress_settings_licenses',		// The group name of the settings being registered.
			'rockpress_licenses',				// The name of the set of options being registered.
			array( $this, 'sanitize_callback' )	// The name of the function responsible for validating the fields.
		);

	}

	/**
	 * Section callback
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function section_callback() {
	    echo '<p>' . esc_html( 'Please enter your license keys in order to receive updates and support.', 'ft-rockpress' ) . '</p>';
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
new RockPress_Settings_Licenses();
