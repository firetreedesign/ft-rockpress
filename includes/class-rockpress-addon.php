<?php
/**
 * RockPress Addon Handler
 *
 * @since	1.0.0
 * @package	RockPress
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RockPress_Addon' ) ) :

	/**
	 * RockPress Addon class
	 */
	class RockPress_Addon {

		/**
		 * Endpoints
		 *
		 * @var array
		 */
		private $controllers;

		/**
		 * Support Topics
		 *
		 * @var array
		 */
		private $support_topics;

		/**
		 * Import jobs
		 *
		 * @var array
		 */
		private $import_jobs;

		/**
		 * Uninstall Variables
		 *
		 * @var array
		 */
		private $uninstall;

	    /**
	     * Create a new instance
	     *
	     * @param	array $args	Arguments to initialize the class.
	     */
	    function __construct( $args ) {

			if ( ! isset( $args['controllers'] ) ) {
				return;
			}

			$this->controllers = $args['controllers'];

			if ( isset( $args['support_topics'] ) ) {
				$this->support_topics = $args['support_topics'];
			}

			if ( isset( $args['import_jobs'] ) ) {
				$this->import_jobs = $args['import_jobs'];
			}

			if ( isset( $args['uninstall'] ) ) {
				$this->uninstall = $args['uninstall'];
			}

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

			add_filter( 'rockpress_rest_controllers', array( $this, 'setup_controllers' ) );
			add_filter( 'rockpress_support_topics', array( $this, 'support_topics' ) );
			add_filter( 'rockpress_import_jobs', array( $this, 'import_jobs' ) );
			add_filter( 'rockpress_uninstall_settings', array( $this, 'uninstall_settings' ) );

		}

		/**
		 * Add the REST Controllers
		 *
		 * @since 1.0.0
		 *
		 * @param  array $controllers	REST Controllers.
		 *
		 * @return array
		 */
		public function setup_controllers( $controllers ) {

			if ( is_array( $this->controllers ) ) {

				foreach ( $this->controllers as $controller ) {
					if ( ! in_array( $controller, $controllers, true ) ) {
						$controllers[] = $controller;
					}
				}
			}

			return $controllers;

		}

		/**
		 * Add the HS Beacon support topics
		 *
		 * @since 1.0.0
		 *
		 * @param  array $topics The topics.
		 *
		 * @return array         The new topics
		 */
		public function support_topics( $topics ) {

			add_filter( 'rockpress_enable_beacon', function() {} );

			if ( is_array( $this->support_topics ) ) {
				foreach ( $this->support_topics as $topic ) {
					$topics[] = array(
						'val'	=> $topic['val'],
						'label'	=> $topic['label'],
					);
				}
			}

			return $topics;

		}

		/**
		 * Add the import options
		 *
		 * @since 1.0.0
		 *
		 * @param  array $jobs The import options.
		 *
		 * @return array         The new import options
		 */
		public function import_jobs( $jobs ) {

			if ( ! is_array( $this->import_jobs ) ) {
				return $jobs;
			}

			foreach ( $this->import_jobs as $job ) {
				if ( ! isset( $job['controller'] ) ) {
					continue;
				}
				if ( in_array( $job['controller'], $jobs, true ) ) {
					continue;
				}
				$jobs[ $job['controller'] ] = $job;
			}

			return $jobs;

		}

		/**
		 * Add the Uninstall Settings
		 *
		 * @since 1.0.3
		 *
		 * @param  array $settings	Uninstall settings.
		 *
		 * @return array
		 */
		public function uninstall_settings( $settings ) {

			if ( is_array( $this->uninstall ) && isset( $this->uninstall['id'] ) && isset( $this->uninstall['name'] ) ) {

				$settings[] = array(
					'id'	=> $this->uninstall['id'],
					'name'	=> $this->uninstall['name'],
				);

			}

			return $settings;

		}

	}

endif;
