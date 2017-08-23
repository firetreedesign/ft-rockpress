<?php
/**
 * RockPress Service Times Widget
 *
 * @package RockPress
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RockPress_Widget_Service_Times' ) ) :

	class RockPress_Widget_Service_Times extends WP_Widget {

		/**
		 * Register the widget with WordPress
		 *
		 * @since 1.0.0
		 */
		function __construct() {

			parent::__construct(
				'rockpress_widget_service_times',
				__( 'Service Times (RockPress)', 'ft-rockpress' ),
				array( 'description' => __( 'Displays the service times for a campus.', 'ft-rockpress' ) )
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
			wp_enqueue_style( 'dashicons' );

			if ( empty( $instance['campus'] ) ) {
				return;
			}

			$campus_data = json_decode( RockPress()->rock->get( array(
				'endpoint'	=> 'Campuses',
				'id'		=> $instance['campus'],
			) ) );

			echo $args['before_widget'];

			if ( ! empty( $instance['title'] ) ) {
				echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
			}

			$service_times = explode( '|', $campus_data->ServiceTimes );

			if ( false === $service_times ) {
				return;
			}

			$service_times_array = array();

			foreach ( $service_times as $service_time ) {
				$when = explode( '^', $service_time );
				if ( false === $when ) {
					continue;
				}
				$service_times_array[ $when[0] ][] = $when[1];
			}

			// Echo the service times data and apply any filters.
			echo $this->rockpress_get_template( $service_times_array );

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
			$campus = ! empty( $instance['campus'] ) ? $instance['campus'] : '';

			$campuses = json_decode( RockPress()->rock->get( array(
				'endpoint' => 'Campuses',
			) ) );
			?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'ft-rockpress' ); ?></label>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'campus' ) ); ?>"><?php esc_html_e( 'Campus:', 'ft-rockpress' ); ?></label>
				<select id="<?php echo esc_attr( $this->get_field_id( 'campus' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'campus' ) ); ?>" class="widefat">
			<?php
			if ( is_array( $campuses ) ) {
				?>

				<?php
				foreach ( $campuses as $campus ) {
					if ( true !== $campus->IsActive ) {
						continue;
					}
					?>
					<option <?php selected( ( (string) $campus->Id === $campus ) ); ?> value="<?php echo esc_attr( $campus->Id ); ?>"><?php echo esc_html( $campus->Name ); ?></option>
					<?php
				}
			}
			?>
				</select>
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
			$instance['campus'] = ( ! empty( $new_instance['campus'] ) ) ? strip_tags( $new_instance['campus'] ) : '';
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

			$template = new RockPress_Template( 'service-times.php', ROCKPRESS_PLUGIN_DIR );

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
	 * Register the RockPress Service Times Widget
	 *
	 * @since 0.2.0
	 * @return void
	 */
	function register_rockpress_widget_service_times() {

		if ( ! RockPress()->rock->is_connected() ) {
			return;
		}

		register_widget( 'RockPress_Widget_Service_Times' );

	}
	add_action( 'widgets_init', 'register_rockpress_widget_service_times' );

endif;
