<?php
/**
 * Section Header Control for the Customizer
 *
 * @version 1.0.0
 * @package WordPress
 * @subpackage Customizer
 */
if ( ! class_exists( 'FT_Section_Header_Control' ) && class_exists( 'WP_Customize_Control' ) ) {
	/**
	 * FT_Section_Header_Control Class
	 */
	class FT_Section_Header_Control extends WP_Customize_Control {
		/**
		 * Control Type
		 *
		 * @var string
		 */
		public $type = 'ft-section-header';
		/**
		 * Render the control
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public function render_content() {
			?>
			<hr />
			<?php if ( $this->label ) : ?>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<?php endif; ?>
			<?php if ( $this->description ) : ?>
				<span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
			<?php endif; ?>
			<?php
		}
	}
}
?>