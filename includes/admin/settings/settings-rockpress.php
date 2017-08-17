<?php
/**
 * RockPress Admin Settings
 *
 * @package RockPress
 * @version 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * RockPress_Settings_RockPress class
 */
class RockPress_Settings_RockPress extends RockPress_Settings {

	/**
	 * Class construct
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_action( 'admin_init', array( $this, 'initialize' ) );
	}

	public function initialize() {

	    // If the option does not exist, then add it.
		if ( false === get_option( 'rockpress_settings' ) ) {
			add_option( 'rockpress_settings' );
		}

	    // First, we register a section. This is necessary since all future options must belong to one.
		add_settings_section(
			'rockpress_settings_uninstall_section',
			__( 'Uninstall', 'rockpress' ),
			array( $this, 'rockpress_uninstall_section_callback' ),
			'rockpress_settings'
		);

	    // The Remove Data field.
		add_settings_field(
			'remove_data',
			'<strong>' . __('RockPress', 'rockpress') . '</strong>',
			array( $this, 'checkbox_callback' ),
			'rockpress_settings',
			'rockpress_settings_uninstall_section',
			array(
				'field_id'  => 'remove_data',
				'page_id'   => 'rockpress_settings',
				'label'     => __( 'Remove all of its data when the plugin is deleted.', 'rockpress' ),
			)
		);

		$uninstall_settings = apply_filters( 'rockpress_uninstall_settings', array() );
		foreach ( $uninstall_settings as $setting ) {
			add_settings_field(
	    		$setting['id'] . '_remove_data',
	    		'<strong>' . $setting['name'] . '</strong>',
	    		array( $this, 'checkbox_callback' ),
	    		'rockpress_settings',
	    		'rockpress_settings_uninstall_section',
	    		array(
	    			'field_id'  => $setting['id'] . '_remove_data',
	    			'page_id'   => 'rockpress_settings',
					'label'		=> __( 'Remove all of its data when the plugin is deleted.', 'rockpress' ),
	    		)
	    	);
		}

	    // Finally, we register the fields with WordPress.
		register_setting(
			'rockpress_settings',				// The group name of the settings being registered.
			'rockpress_settings',				// The name of the set of options being registered.
			array( $this, 'sanitize_callback' )	// The name of the function responsible for validating the fields.
		);

	}

	/**
	 * RockPress Uninstall section callback
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function rockpress_uninstall_section_callback() {
	    echo '<p>' . esc_html( 'Upon deletion of RockPress, you can optionally remove any custom tables, settings, and license keys that have been entered.', 'rockpress' ) . '</p>';
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
new RockPress_Settings_RockPress();
