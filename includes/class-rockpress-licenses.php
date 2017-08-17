<?php
/**
 * RockPress License Handler
 *
 * @package RockPress
 * @since       1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'RockPress_License' ) ) :

	/**
	 * RockPress License class
	 */
	class RockPress_License {

		/**
		 * File path
		 *
		 * @var string
		 */
		private $file;

		/**
		 * License
		 *
		 * @var string
		 */
		private $license;

		/**
		 * Item name
		 *
		 * @var string
		 */
		private $item_name;

		/**
		 * Item ID
		 *
		 * @var string
		 */
		private $item_id;

		/**
		 * Item short name
		 *
		 * @var string
		 */
		private $item_shortname;

		/**
		 * Version number
		 *
		 * @var string
		 */
		private $version;

		/**
		 * Author
		 *
		 * @var string
		 */
		private $author;

		/**
		 * API URL
		 *
		 * @var string
		 */
		private $api_url = 'https://rockpresswp.com/';

	    /**
	     * Create a new instance
	     *
	     * @param string $_file      File path.
	     * @param string $_item_id   Item ID.
	     * @param string $_item_name Item name.
	     * @param string $_version   Version number.
	     * @param string $_author    Author.
	     */
	    function __construct( $_file, $_item_id, $_item_name, $_version, $_author ) {

	        $this->file				= $_file;
			$this->item_name		= $_item_name;
			$this->item_id			= $_item_id;
			$this->item_shortname	= 'rockpress_' . $this->item_id;
			$this->version			= $_version;
			$this->author			= $_author;
			$this->license			= trim( $this->get_license_key() );

			$this->includes();
			$this->hooks();

	    }

		/**
		 * Include the EDD Sofitware Licensing updater class
		 *
		 * @since 1.0.0
		 *
		 * @access private
		 *
		 * @return void
		 */
		private function includes() {
			if ( ! class_exists( 'EDD_SL_Plugin_Updater' ) ) {
				require ROCKPRESS_PLUGIN_DIR . 'lib/EDD_SL_Plugin_Updater.php';
			}
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

			// Register our license key setting.
			add_filter( 'rockpress_license_keys', array( $this, 'licenses' ) );

			// Activate the license key when settings are saved.
			add_action( 'admin_init', array( $this, 'activate_license' ) );

			// Deactivate the license key.
			add_action( 'admin_init', array( $this, 'deactivate_license' ) );

			// Register the auto updater.
			add_action( 'admin_init', array( $this, 'auto_updater' ), 0 );

		}

		/**
		 * Get license key
		 *
		 * @since 1.0.0
		 *
		 * @return string
		 */
		private function get_license_key() {
			$licenses = get_option( 'rockpress_licenses', array() );
			if ( isset( $licenses[ $this->item_shortname . '_license_key' ] ) ) {
				return $licenses[ $this->item_shortname . '_license_key' ];
			}
			return false;
		}

		/**
		 * Auto updater
		 *
		 * @since 1.0.0
		 *
		 * @access private
		 *
		 * @return void
		 */
		public function auto_updater() {

			if ( 'valid' !== get_option( $this->item_shortname . '_license_key_active' ) ) {
			 	return;
			}

			$edd_updater = new EDD_SL_Plugin_Updater(
				$this->api_url,
				$this->file,
				array(
					'version'	=> $this->version,
					'license'	=> $this->license,
					'item_name'	=> $this->item_name,
					'author'	=> $this->author,
					'url'		=> home_url(),
				)
			);

		}

		/**
		 * Activate the license key
		 *
		 * @since 1.0.0
		 *
		 * @access public
		 *
		 * @return void
		 */
		public function activate_license() {

			if ( ! isset( $_POST['rockpress_licenses'] ) ) {
				return;
			}

			if ( ! isset( $_POST['rockpress_licenses'][ $this->item_shortname . '_license_key' ] ) ) {
				return;
			}

			if ( ! wp_verify_nonce( $_REQUEST[ $this->item_shortname . '_license_key-nonce' ], $this->item_shortname . '_license_key-nonce' ) ) {
				wp_die( esc_html__( 'Nonce verification failed', 'rockpress' ), esc_html__( 'Error', 'rockpress' ), array( 'response' => 403 ) );
			}

			foreach ( $_POST as $key => $value ) {
				if ( false !== strpos( $key, 'license_key_deactivate' ) ) {
					return;
				}
			}

			if ( 'valid' === get_option( $this->item_shortname . '_license_key_active' ) ) {
				return;
			}

			$license = sanitize_text_field( $_POST['rockpress_licenses'][ $this->item_shortname . '_license_key' ] );

			if ( empty( $license ) ) {
				return;
			}

			$api_params = array(
				'edd_action'	=> 'activate_license',
				'license'		=> $license,
				'item_name'		=> urlencode( $this->item_name ),
				'url'			=> home_url(),
			);

			$response = wp_remote_post(
				$this->api_url,
				array(
					'timeout'	=> 15,
					'sslverify'	=> false,
					'body'		=> $api_params,
				)
			);

			// Check for errors.
			if ( is_wp_error( $response ) ) {
				return;
			}

			// Make WordPress look for updates.
			set_site_transient( 'update_plugins', null );

			$license_data = json_decode( wp_remote_retrieve_body( $response ) );

			update_option( $this->item_shortname . '_license_key_active', $license_data->license );

		}

		/**
		 * Deactivate the license key
		 *
		 * @since 1.0.0
		 *
		 * @access public
		 *
		 * @return void
		 */
		public function deactivate_license() {

			if ( ! isset( $_POST['ccbpress_licenses'] ) ) {
				return;
			}

			if ( ! isset( $_POST['ccbpress_licenses'][ $this->item_shortname . '_license_key' ] ) ) {
				return;
			}

			if ( ! wp_verify_nonce( $_REQUEST[ $this->item_shortname . '_license_key-nonce' ], $this->item_shortname . '_license_key-nonce' ) ) {
				wp_die( esc_html__( 'Nonce verification failed', 'rockpress' ), esc_html__( 'Error', 'rockpress' ), array( 'response' => 403 ) );
			}

			if ( isset( $_POST[ $this->item_shortname . '_license_key_deactivate' ] ) ) {

				$api_params = array(
					'edd_action'	=> 'deactivate_license',
					'license'		=> $this->license,
					'item_name'		=> urlencode( $this->item_name ),
					'url'			=> home_url(),
				);

				$response = wp_remote_post(
					$this->api_url,
					array(
						'timeout'	=> 15,
						'sslverify'	=> false,
						'body'		=> $api_params,
					)
				);

				// Check for errors.
				if ( is_wp_error( $response ) ) {
					return;
				}

				$license_data = json_decode( wp_remote_retrieve_body( $response ) );
				update_option( 'rockpress_license_data', $license_data );
				delete_option( $this->item_shortname . '_license_key_active' );

			}

		}

		/**
		 * Add the license key field to the settings
		 *
		 * @since 1.0.0
		 *
		 * @param array $licenses Array of license data.
		 *
		 * @return array
		 */
		public function licenses( $licenses ) {

			$licenses[] = array(
				'id'	=> $this->item_shortname,
				'name'	=> $this->item_name,
				'notes'	=> '',
			);

			return $licenses;

		}

	}

endif;
