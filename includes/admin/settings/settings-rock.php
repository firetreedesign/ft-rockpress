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
 * RockPress_Settings_Rock class
 */
class RockPress_Settings_Rock extends RockPress_Settings {

	/**
	 * Class construct
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_action( 'admin_init', array( $this, 'initialize' ) );
		add_filter( 'rockpress_settings_help_tabs', array( $this, 'help_tabs' ) );
	}

	/**
	 * Initilize the settings
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function initialize() {

		// First, we register a section. This is necessary since all future options must belong to one.
		add_settings_section(
			'rockpress_settings_rock_section',
			__( 'REST Key Information', 'ft-rockpress' ),
			array( $this, 'rock_section_callback' ),
			'rockpress_settings_rock'
		);

		// If the option does not exist, then add it.
		if ( false === ( $rockpress_rock = get_option( 'rockpress_rock' ) ) ) {
			add_option( 'rockpress_rock' );
		}

		/**
		 * The Rock RMS Domain field
		 */
		add_settings_field(
			'domain',
			'<strong>' . __('Rock RMS URL', 'ft-rockpress') . '</strong>',
			array( $this, 'input_callback' ),
			'rockpress_settings_rock',
			'rockpress_settings_rock_section',
			array(
				'field_id'	=> 'domain',
				'page_id'	=> 'rockpress_rock',
	            'size'		=> 'regular',
				'label'		=> __( 'The URL you use to access your Rock RMS site.', 'ft-rockpress' ),
	            'before'	=> null,
	            'after'		=> null,
			)
		);

		/**
		 * The Rock RMS REST API Key
		 */
		add_settings_field(
			'rest_key',
			'<strong>' . __( 'REST API Key', 'ft-rockpress' ) . '</strong>',
			array( $this, 'input_callback' ),
			'rockpress_settings_rock',
			'rockpress_settings_rock_section',
			array(
				'field_id'  => 'rest_key',
				'page_id'   => 'rockpress_rock',
	            'size'      => 'regular',
				'label'		=> __( 'The REST API Key that you created on your Rock RMS site.', 'ft-rockpress' ),
			)
		);

		if ( RockPress()->rock->is_connected() ) {

			add_settings_section(
				'rockpress_settings_rock_connection_section',
				__( 'Connection Test', 'rockpress' ),
				array( $this, 'connection_section_callback' ),
				'rockpress_settings_rock'
			);

			add_settings_field(
	            'check_connection',
	            '<strong>' . __( 'Connection Test', 'ft-rockpress' ) . '</strong>',
	            array( $this, 'text_callback' ),
	            'rockpress_settings_rock',
	            'rockpress_settings_rock_connection_section',
	            array(
	                'header' => null,
	                'title' => null,
	                'content' => '<button class="button" id="rockpress-rock-connection-test-button">Run Test Now</button><div id="rockpress-rock-connection-test-results"></div>',
	            )
	        );

		}

	    // Finally, we register the fields with WordPress.
		register_setting(
			'rockpress_settings_rock',			// The group name of the settings being registered.
			'rockpress_rock',					// The name of the set of options being registered.
			array( $this, 'sanitize_callback' )	// The name of the function responsible for validating the fields.
		);

	}

	/**
	 * Rock section callback
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function rock_section_callback() {
	    echo '<p>' . esc_html( 'These are the settings for the REST API connection to Rock RMS.', 'ft-rockpress' ) . '</p>';
	}

	/**
	 * Connection section callback
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function connection_section_callback() {
	    echo '<p>' . esc_html( 'Use this tool to test the connection to your Rock RMS site.', 'ft-rockpress' ) . '</p>';
	}

	/**
	 * Sanitize callback
	 *
	 * @since 1.0.0
	 *
	 * @param  array $input Input values.
	 *
	 * @return array        Sanitized input values
	 */
	public function sanitize_callback( $input ) {

		$output = array();

		// Loop through each of the incoming options.
		foreach ( $input as $key => $value ) {

			// Check to see if the current option has a value. If so, process it.
			if ( isset( $input[ $key ] ) ) {

				switch ( $key ) {

					case 'domain':
						if ( 0 === strlen( trim( $input[ $key ] ) ) ) {
							break;
						}
						$input[ $key ] = trailingslashit( esc_url( $input[ $key ], array( 'http', 'https' ) ) );

				}

				// Strip all HTML and PHP tags and properly handle quoted strings.
				$output[ $key ] = strip_tags( stripslashes( $input[ $key ] ) );

			}
		}

		// Return the array.
		return $output;

	}

	/**
	 * Help tabs
	 *
	 * @since 1.0.0
	 *
	 * @param  array $help_tabs Help tabs.
	 *
	 * @return array
	 */
	public function help_tabs( $help_tabs ) {

		$controllers = apply_filters( 'rockpress_rest_controllers', array() );
		sort( $controllers );

		ob_start();
		?>
		<p>Your REST Key must have permission to use the following REST Controllers:
		<ul>
		<?php foreach ( $controllers as $controller ) : ?>
		    <li><?php echo $controller; ?></li>
		<?php endforeach; ?>
		<?php if ( count( $controllers ) === 0 ) : ?>
			<li><?php _e( 'There are no REST Controllers registered with RockPress.', 'ft-rockpress' ); ?></li>
		<?php endif; ?>
		</ul></p>
		<?php
		$content = ob_get_clean();

		$help_tabs[] = array(
			'id'		=> 'rockpress-rest-controllers',
			'tab_id'	=> 'rock',
			'title'		=> __( 'REST Controllers', 'ft-rockpress' ),
			'content'	=> $content,
		);

		return $help_tabs;

	}

}
new RockPress_Settings_Rock();
