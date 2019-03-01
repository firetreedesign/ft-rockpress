<?php
/**
 * Multiple Select Control for the Customizer
 *
 * @version 1.0.0
 * @package WordPress
 * @subpackage Customizer
 */
if ( class_exists( 'WP_Customize_Control' ) && ! class_exists( 'FT_Multiple_Select_Control' ) ) {
	/**
	 * FT_Multiple_Select_Control Class
	 */
	class FT_Multiple_Select_Control extends WP_Customize_Control {
		/**
		 * Control Type
		 *
		 * @var string
		 */
		public $type = 'ft-multiple-select';
		/**
		 * Render the control
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public function render_content() {
			$selected_values = $this->value();
			if ( ! is_array( $selected_values ) ) {
				$selected_values = explode( ',', $selected_values );
			}
			?>
			<label>
				<?php if ( $this->label ) : ?>
					<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<?php endif; ?>
				<?php if ( $this->description ) : ?>
					<span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
				<?php endif; ?>
				<?php if ( empty( $this->choices ) ) : ?>
				<?php esc_html_e( 'No choices available.', 'ft-customizer-controls' ); ?>
				<?php else : ?>
				<select <?php $this->link(); ?> multiple="multiple" style="height: 100px;">
	                <?php
	                foreach ( $this->choices as $value => $label ) {
	                    $selected = ( in_array( $value, $selected_values, true ) ) ? selected( 1, 1, false ) : '';
	                    echo '<option value="' . esc_attr( $value ) . '"' . esc_html( $selected ) . '>' . esc_html( $label ) . '</option>';
	                }
	                ?>
	            </select>
				<?php endif; ?>
			</label>
			<?php if ( ! empty( $this->choices ) ) : ?>
				<div class="ft-multiple-select-buttons" style="text-align: right;">
					<button type="buttom" class="button ft-select-all">
						<span><?php esc_html_e( 'All', 'ft-customizer-controls' ); ?></span>
					</button>
					<button type="buttom" class="button ft-deselect-all">
						<span><?php esc_html_e( 'None', 'ft-customizer-controls' ); ?></span>
					</button>
				</div>
			<?php endif; ?>
			<?php
		}
		/**
		 * Loads the scripts/styles.
		 *
		 * @since  1.0.0
		 *
		 * @return void
		 */
		public function enqueue() {
			$file_path = __DIR__;
			$url_path = str_replace( $_SERVER['DOCUMENT_ROOT'], '', $file_path );
			wp_enqueue_script( 'ft-multiple-select-control', $url_path . '/js/ft-multiple-select.js', array( 'customize-controls', 'jquery' ), '1.0.0', true );
		}
	}
}