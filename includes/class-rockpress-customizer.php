<?php
/**
 * RockPress Customizer Handler
 *
 * @package RockPress
 *
 * @since       1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * RockPress_Customizer class
 */
class RockPress_Customizer {

	/**
	 * Create a new instance
	 */
	function __construct() {
	    $this->hooks();
	}

	/**
	 * Hooks
	 *
	 * @return void
	 */
	private function hooks() {
		add_action( 'customize_register', array( $this, 'customize_register' ) );
		add_action( 'wp_footer', array( $this, 'customize_css' ) );
	}

	/**
	 * Customize register
	 *
	 * @param  object $wp_customize WP Customize object.
	 *
	 * @return void
	 */
	public function customize_register( $wp_customize ) {
		$this->add_panels( $wp_customize );
		$this->add_sections( $wp_customize );
		$this->add_settings( $wp_customize );
	}

	/**
	 * Add the panel
	 *
	 * @param object $wp_customize WP Customize object.
	 */
	private function add_panels( $wp_customize ) {
		$wp_customize->add_panel( 'rockpress', array(
			'title'			=> __( 'RockPress', 'ft-rockpress' ),
			'description'	=> __( '<p>This screen is used to manage the RockPress settings that affect the look of your website.</p>', 'ft-rockpress' ),
			'priority'		=> 160,
		) );
	}

	/**
	 * Add the sections
	 *
	 * @param object $wp_customize WP Customize object.
	 */
	private function add_sections( $wp_customize ) {
		$wp_customize->add_section( 'rockpress_service_times', array(
			'title'			=> __( 'Service Times', 'ft-rockpress' ),
			'panel'			=> 'rockpress',
			'priority'		=> 10,
		) );
	}

	/**
	 * Add the settings
	 *
	 * @param object $wp_customize WP Customize object.
	 */
	private function add_settings( $wp_customize ) {

		/**
		 * Service Times - Colors Section
		 */
		$wp_customize->add_setting( 'rockpress_service_times_colors_section', array(
			'type'				=> 'option',
			'transport'			=> 'refresh',
		) );

		$wp_customize->add_control(  new FT_Section_Header_Control( $wp_customize, 'rockpress_service_times_colors_section', array(
			'label'			=> __( 'Colors', 'ft-rockpress' ),
			'description'	=> __( 'Here you can override some of the colors.', 'ft-rockpress' ),
			'section'		=> 'rockpress_service_times',
			'priority'		=> 10,
		) ) );

		/**
		 * Service Times - Time Background Color
		 */
		$wp_customize->add_setting( 'rockpress_service_times_background_color', array(
			'type'				=> 'theme_mod',
			'transport'			=> 'refresh',
			'default'			=> '#f7f7f7',
		) );

		$wp_customize->add_control(
			new WP_Customize_Color_Control( $wp_customize, 'rockpress_service_times_background_color', array(
					'label'		=> __( 'Background Color', 'ft-rockpress' ),
					'section'	=> 'rockpress_service_times',
					'priority'	=> '20',
				)
			)
		);

		/**
		 * Service Times - Time Foreground Color
		 */
		$wp_customize->add_setting( 'rockpress_service_times_foreground_color', array(
			'type'				=> 'theme_mod',
			'transport'			=> 'refresh',
			'default'			=> '#000000',
		) );

		$wp_customize->add_control(
			new WP_Customize_Color_Control( $wp_customize, 'rockpress_service_times_foreground_color', array(
					'label'		=> __( 'Foreground Color', 'ft-rockpress' ),
					'section'	=> 'rockpress_service_times',
					'priority'	=> '30',
				)
			)
		);
	}

	/**
	 * CSS output from the customizer
	 *
	 * @since 1.0.0
	 */
	public function customize_css() {

		$background_color = get_theme_mod( 'rockpress_service_times_background_color', '#f7f7f7' );
		$foreground_color = get_theme_mod( 'rockpress_service_times_foreground_color', '#000000' );
		?>
		<style type="text/css">
			/* Service Times */
			.rockpress-service-times-time { background-color: <?php echo esc_attr( $background_color ); ?>; color: <?php echo esc_attr( $foreground_color ); ?>; }
		</style>
		<?php
	}

}
new RockPress_Customizer();
