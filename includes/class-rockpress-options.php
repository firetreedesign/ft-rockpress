<?php
/**
 * RockPress Options Handler
 *
 * @package RockPress
 * @since   1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RockPress_Options' ) ) :

	/**
	 * RockPress Options class
	 */
	class RockPress_Options {

		/**
		 * Options
		 *
		 * @var array
		 */
		private $options;

	    /**
	     * Create a new instance
	     *
	     * @param array $_args Arguments.
	     */
	    function __construct( $_args ) {

			$this->options = $_args;
			$this->hooks();

	    }

		/**
		 * Setup our hooks
		 *
		 * @since 1.0.0
		 *
		 * @access private
		 *
		 * @return void
		 */
		private function hooks() {

			add_filter( 'rockpress_settings_page_tabs', array( $this, 'settings_page_tabs' ) );
			add_filter( 'rockpress_settings_page_actions', array( $this, 'settings_page_actions' ) );

		}

		/**
		 * Add the settings page tabs
		 *
		 * @since 1.0.0
		 *
		 * @param  array $tabs Tabs.
		 *
		 * @return array
		 */
		public function settings_page_tabs( $tabs ) {

			if ( isset( $this->options['settings'] ) && is_array( $this->options['settings'] ) && isset( $this->options['settings']['tabs'] ) && is_array( $this->options['settings']['tabs'] ) ) {

				foreach ( $this->options['settings']['tabs'] as $tab ) {
					$tabs[] = array(
						'tab_id'		=> $tab['tab_id'],
						'settings_id'	=> $tab['settings_id'],
						'title'			=> $tab['title'],
						'submit'		=> $tab['submit'],
					);
				}
			}

			return $tabs;

		}

		/**
		 * Add the settings page actions
		 *
		 * @since 1.0.0
		 *
		 * @param  array $actions Actions.
		 *
		 * @return array
		 */
		public function settings_page_actions( $actions ) {

			if ( isset( $this->options['settings'] ) && is_array( $this->options['settings'] ) && isset( $this->options['settings']['actions'] ) && is_array( $this->options['settings']['actions'] ) ) {

				$defaults = array(
					'type'		=> 'secondary',
					'class'		=> null,
					'target'	=> null,
				);

				foreach ( $this->options['settings']['actions'] as $action ) {

					$new_action = wp_parse_args( $action, $defaults );

					$actions[] = array(
						'tab_id'	=> $new_action['tab_id'],
						'type'		=> $new_action['type'],
						'class'		=> $new_action['class'],
						'link'		=> $new_action['link'],
						'target'	=> $new_action['target'],
						'title'		=> $new_action['title'],
					);

				}
			}

			return $actions;

		}

	}

endif;
