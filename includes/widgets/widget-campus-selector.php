<?php
/**
 * RockPress Campus Selector Widget
 *
 * @package RockPress
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RockPress_Widget_Campus_Selector' ) ) :

	class RockPress_Widget_Campus_Selector extends WP_Widget {

		/**
		 * Register the widget with WordPress
		 *
		 * @since 1.0.0
		 */
		function __construct() {

			parent::__construct(
				'rockpress_widget_campus_selector',
				__( 'Campus Selector (RockPress)', 'ft-rockpress' ),
				array( 'description' => __( 'Display a list of your campuses with links to each one.', 'ft-rockpress' ) )
			);

		}

		/**
		 * Front-end display of the widget
		 *
		 * @since 1.0.0
		 *
		 * @param  array $args     Widget arguments.
		 * @param  array $instance Saved values from database.
		 *
		 * @return void
		 */
		public function widget( $args, $instance ) {

			wp_enqueue_style( 'rockpress' );

			$campuses_data = json_decode( RockPress()->rock->get( array(
				'controller' => 'Campuses',
			) ) );

			echo $args['before_widget'];

			if ( ! empty( $instance['title'] ) ) {
				echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
			}

			$campuses_array = array();

			foreach ( $campuses_data as $campus ) {
				if ( 0 === strlen( $campus->Url ) ) {
					continue;
				}

				$campuses_array[] = array(
					'name'	=> $campus->Name,
					'url'	=> $campus->Url,
				);
			}

			if ( empty( $campuses_array ) ) {
				return;
			}

			// Echo the service times data and apply any filters.
			echo $this->rockpress_get_template( $campuses_array );

			echo $args['after_widget'];

		}

		/**
		 * Back-end widget form
		 *
		 * @since 1.0.0
		 *
		 * @see WP_Widget::form()
		 *
		 * @param  array $instance Previously saved values from database
		 *
		 * @return void
		 */
		public function form( $instance ) {

			$title = ! empty( $instance['title'] ) ? $instance['title'] : '';
			?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'ft-rockpress' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
			</p>
			<?php

		}

		/**
		 * Sanitize widget form values as they are saved.
		 *
		 * @since 1.0.0
		 *
		 * @see WP_Widget::update()
		 *
		 * @param  array $new_instance Values sent to be saved.
		 * @param  array $old_instance Previously saved values from database.
		 *
		 * @return array               Updated safe values to be saved.
		 */
		public function update( $new_instance, $old_instance ) {

			$instance = array();
			$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
			return $instance;

		}

		/**
		 * Get the display template
		 *
		 * @since 1.0.0
		 *
		 * @param  array $data Service times.
		 *
		 * @return string
		 */
		public function rockpress_get_template( $data ) {

			ob_start();

			$template = new RockPress_Template( 'campus-selector.php', ROCKPRESS_PLUGIN_DIR );

			if ( false !== ( $template_path = $template->path() ) ) {
				include( $template_path ); // Include the template.
			} else {
				esc_html_e( 'Template not found. Please reinstall RockPress.', 'ft-rockpress' );
			}

			// Return the output.
			return ob_get_clean();

		}

	}

	/**
	 * Register the RockPress Campus Selector Widget
	 *
	 * @since 0.2.0
	 * @return void
	 */
	function register_rockpress_widget_campus_selector() {

		if ( ! RockPress()->rock->is_connected() ) {
			return;
		}

		register_widget( 'RockPress_Widget_Campus_Selector' );

	}
	add_action( 'widgets_init', 'register_rockpress_widget_campus_selector' );

endif;
