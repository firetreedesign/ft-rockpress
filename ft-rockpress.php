<?php
/**
 * Plugin Name: RockPress
 * Plugin URI: http://rockpresswp.com/
 * Description: Display information from Rock RMS on your WordPress site.
 * Version: 1.0.1
 * Author: FireTree Design, LLC <support@firetreedesign.com>
 * Author URI: https://firetreedesign.com/
 * Text Domain: ft-rockpress
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * @package RockPress
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RockPress' ) ) :

	register_activation_hook( __FILE__, array( 'RockPress', 'schedule_cron' ) );
	register_deactivation_hook( __FILE__, array( 'RockPress', 'unschedule_cron' ) );

	/**
	 * RockPress class
	 */
	class RockPress {

	    /**
	     * Instance
	     *
	     * @var RockPress The one true RockPress
	     * @since 1.0.0
	     */
		private static $instance;

	    /**
		 * RockPress Transients Object
		 *
		 * @var object
		 * @since 1.0.0
		 */
		public $transients;

		/**
	     * Rock Object
	     *
	     * @var object
	     * @since 1.0.0
	     */
		public $rock;

		/**
		 * Background Get Object
		 *
		 * @var object
		 * @since 1.0.0
		 */
		public $get;

		/**
	     * RockPress Version
	     *
	     * @var string
	     * @since 1.0.0
	     */
	    public $version = '1.0.1';

		/**
	     * Main RockPress Instance
	     *
	     * Insures that only one instance of RockPress exists in memory at any
	     * one time.
	     *
	     * @since 1.0
	     * @static
	     * @staticvar array $instance
	     * @uses RockPress::includes() Include the required files
	     * @see RockPress()
	     * @return The one true RockPress
	     */
	    public static function instance() {

	        if ( ! isset( self::$instance ) && ! ( self::$instance instanceof RockPress ) ) {

	            self::$instance = new RockPress;
	            self::$instance->setup_constants();
	            self::$instance->includes();
				self::$instance->actions();
				self::$instance->register_addon();

	            self::$instance->transients	= new RockPress_Transients();
	            self::$instance->rock		= new RockPress_Rock_REST_API();
				self::$instance->get		= new RockPress_Background_Get();

	        }

	        return self::$instance;

	    }

		/**
		 * Setup plugin constants
		 *
		 * @access private
		 * @since 1.0.0
		 * @return void
		 */
		private function setup_constants() {

			// Plugin Version.
	        if ( ! defined( 'ROCKPRESS_VERSION' ) ) {
	            define( 'ROCKPRESS_VERSION', $this->version );
	        }

			// Plugin File.
	        if ( ! defined( 'ROCKPRESS_PLUGIN_FILE' ) ) {
	            define( 'ROCKPRESS_PLUGIN_FILE', __FILE__ );
	        }

	        // Plugin Folder Path.
	        if ( ! defined( 'ROCKPRESS_PLUGIN_DIR' ) ) {
	            define( 'ROCKPRESS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
	        }

	        // Plugin Folder URL.
			if ( ! defined( 'ROCKPRESS_PLUGIN_URL' ) ) {
				define( 'ROCKPRESS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
			}

		}

		/**
		 * Register the addon with RockPress
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		private function register_addon() {

			$addon = new RockPress_Addon( array(
				'controllers' => array(
					'Campuses',
				),
			) );

		}

		/**
		 * Include required files
		 *
		 * @access private
		 * @since 1.0
		 * @return void
		 */
		private function includes() {

			require_once ROCKPRESS_PLUGIN_DIR . 'includes/schedule-get.php';
			require_once ROCKPRESS_PLUGIN_DIR . 'includes/class-rockpress-transients.php';
	        require_once ROCKPRESS_PLUGIN_DIR . 'includes/class-rockpress-rest-api.php';
			require_once ROCKPRESS_PLUGIN_DIR . 'includes/class-rockpress-licenses.php';
			require_once ROCKPRESS_PLUGIN_DIR . 'includes/class-rockpress-addon.php';
			require_once ROCKPRESS_PLUGIN_DIR . 'includes/class-rockpress-options.php';
			require_once ROCKPRESS_PLUGIN_DIR . 'includes/class-rockpress-customizer.php';
	        require_once ROCKPRESS_PLUGIN_DIR . 'includes/admin/admin-settings.php';
	        require_once ROCKPRESS_PLUGIN_DIR . 'includes/admin/settings/settings-rock.php';
			require_once ROCKPRESS_PLUGIN_DIR . 'includes/admin/settings/settings-import.php';
	        require_once ROCKPRESS_PLUGIN_DIR . 'includes/admin/settings/settings-rockpress.php';
			require_once ROCKPRESS_PLUGIN_DIR . 'includes/admin/settings/settings-licenses.php';
			require_once ROCKPRESS_PLUGIN_DIR . 'includes/admin/admin-ajax.php';
			require_once ROCKPRESS_PLUGIN_DIR . 'lib/wp-background-processing/wp-async-request.php';
			require_once ROCKPRESS_PLUGIN_DIR . 'lib/wp-background-processing/wp-background-process.php';
			require_once ROCKPRESS_PLUGIN_DIR . 'includes/class-rockpress-background-get.php';
			require_once ROCKPRESS_PLUGIN_DIR . 'includes/class-rockpress-template.php';
			require_once ROCKPRESS_PLUGIN_DIR . 'includes/class-rockpress-import.php';
			require_once ROCKPRESS_PLUGIN_DIR . 'includes/widgets/widget-service-times.php';
			require_once ROCKPRESS_PLUGIN_DIR . 'includes/widgets/widget-campus-selector.php';

	        if ( is_admin() ) {
				require_once ROCKPRESS_PLUGIN_DIR . 'includes/admin/admin-page-tabs.php';
				require_once ROCKPRESS_PLUGIN_DIR . 'includes/admin/admin-pages.php';
	            require_once ROCKPRESS_PLUGIN_DIR . 'includes/admin/admin-scripts.php';
				require_once ROCKPRESS_PLUGIN_DIR . 'includes/admin/admin-styles.php';
				require_once ROCKPRESS_PLUGIN_DIR . 'includes/admin/shortcodes/class-shortcode-button.php';
				require_once ROCKPRESS_PLUGIN_DIR . 'includes/admin/shortcodes/class-shortcode-generator.php';
	        }

	    }

		/**
		 * Actions
		 *
		 * @since 0.2.0
		 *
		 * @return void
		 */
		private function actions() {
			add_action( 'wp_enqueue_scripts', array( $this, 'register_styles' ) );
			add_action( 'plugins_loaded', array( $this, 'plugin_textdomain' ) );
		}

		/**
		 * Register Styles
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public function register_styles() {
			wp_register_style( 'rockpress', ROCKPRESS_PLUGIN_URL . 'assets/css/display.css' );
		}

		/**
		 * Loads the plugin text domain for translation
		 *
		 * @since 0.9.8
		 *
		 * @return void
		 */
		public function plugin_textdomain() {
			load_plugin_textdomain( 'ft-rockpress', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
		}

		/**
		 * Schedule daily mantenance tasks
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public static function schedule_cron() {

			if ( false === wp_next_scheduled( 'rockpress_maintenance' ) ) {
				wp_schedule_event( current_time( 'timestamp' ), 'hourly', 'rockpress_maintenance' );
			}

		}

		/**
		 * Unschedule daily mantenance tasks
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public static function unschedule_cron() {
			wp_clear_scheduled_hook( 'rockpress_maintenance' );
			wp_clear_scheduled_hook( 'rockpress_transient_cache_cleanup' );
		}

	}

endif; // End if class_exists check.

/**
 * Initialize the RockPress class
 */
function rockpress() {
	return RockPress::instance();
}
rockpress();
