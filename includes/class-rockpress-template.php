<?php
/**
 * RockPress Template Handler
 *
 * @since       1.0.0
 * @package RockPress
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RockPress_Template' ) ) :

	/**
	 * RockPress_Template class
	 */
	class RockPress_Template {

		/**
		 * Template
		 *
		 * @var string
		 */
		private $template;

		/**
		 * Plugin path
		 *
		 * @var string
		 */
		private $plugin_path = ROCKPRESS_PLUGIN_DIR;

	    /**
	     * Create a new instance
	     *
	     * @param string $template Template path.
	     * @param string $plugin_path Alternate plugin path.
	     */
	    function __construct( $template, $plugin_path = false ) {

			if ( ! isset( $template ) ) {
				return;
			}

			$this->template = $template;

			if ( $plugin_path ) {
				$this->plugin_path = $plugin_path;
			}

	    }

		/**
		 * Get the path to the template
		 *
		 * @since 1.0.0
		 *
		 * @return string
		 */
		public function path() {

			$template_path = trailingslashit( $this->plugin_path ) . 'templates';
			$override_path = '/rockpress';

			try {

				// Look for the template in the child theme directory.
				if ( file_exists( trailingslashit( get_stylesheet_directory() . $override_path ) . $this->template ) ) {
					return trailingslashit( get_stylesheet_directory() . $override_path ) . $this->template;
				}

				// Look for the template in the parent theme directory.
				if ( file_exists( trailingslashit( get_template_directory() . $override_path ) . $this->template ) ) {
					return trailingslashit( get_template_directory() . $override_path ) . $this->template;
				}

				// Look for the template in the plugin directory.
				if ( file_exists( trailingslashit( $template_path ) . $this->template ) ) {
					return trailingslashit( $template_path ) . $this->template;
				}

				return false;

			} catch ( Exception $e ) {

				// Return the data.
				return false;

			}

		}

	}

endif;
