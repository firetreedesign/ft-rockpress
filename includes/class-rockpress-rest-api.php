<?php
/**
 * Rock RMS REST API
 *
 * @package     RockPress
 * @copyright   Copyright (c) 2017, FireTree Design, LLC
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * RockPress Rock REST API
 */
class RockPress_Rock_REST_API {

	/**
	 * Rock RMS Domain
	 *
	 * @var string
	 */
	public $domain;

	/**
	 * Rock RMS REST API Key
	 *
	 * @var string
	 */
	private $rest_key;

	/**
	 * Transient Prefix
	 *
	 * @var string
	 */
	private $transient_prefix;

	/**
	 * Transient Fallback Class
	 *
	 * @var object
	 */
	private $transient_fallback;

	/**
	 * Image Cache Directory
	 *
	 * @var string
	 */
	private $image_cache_dir;

	/**
	 * Create a new instance
	 */
	function __construct() {

		$rockpress_rock = get_option( 'rockpress_rock' );

		$domain = '';
		if ( isset( $rockpress_rock['domain'] ) ) {
			$domain = $rockpress_rock['domain'];
		}

		$rest_key = '';
		if ( isset( $rockpress_rock['rest_key'] ) ) {
			$rest_key = $rockpress_rock['rest_key'];
		}

		$this->domain				= trailingslashit( $domain ) . 'api/';
		$this->rest_key				= $rest_key;
		$this->transient_prefix		= 'ropr_';
		$this->image_cache_dir		= 'rockpress';
		$this->transient_fallback	= RockPress()->transients;

	}

	/**
	 * GET data from Rock
	 *
	 * @since 1.0.0
	 *
	 * @param array $args Arguments.
	 *
	 * @return	string	JSON string containing the data.
	 */
	public function get( $args = array() ) {

		if ( false === $this->is_connected() ) {
			return false;
		}

		$defaults = array(
			'endpoint'			=> null,
			'id'				=> null,
			'filter'			=> null,
			'top'				=> null,
			'skip'				=> null,
			'load_attributes'	=> null,
			'cache_lifespan'	=> null,
			'refresh_cache'		=> 0,
			'raw_response'		=> false,
		);
		$args = wp_parse_args( $args, $defaults );

		// Construct the URL.
		$get_url = trailingslashit( $this->domain ) . $args['endpoint'];

		// If there is an ID, then add it to the URL.
		if ( ! is_null( $args['id'] ) ) {
			$get_url = trailingslashit( $get_url ) . $args['id'];
		}

		// If there is a $filter, then add it to the URL.
		if ( ! is_null( $args['filter'] ) ) {
			$get_url = add_query_arg( '$filter', $args['filter'], $get_url );
		}

		// If there is a $top, then add it to the URL.
		if ( ! is_null( $args['top'] ) ) {
			$get_url = add_query_arg( '$top', $args['top'], $get_url );
		}

		// If there is a $skip, then add it to the URL.
		if ( ! is_null( $args['skip'] ) ) {
			$get_url = add_query_arg( '$skip', $args['skip'], $get_url );
		}

		// If there is a $skip, then add it to the URL.
		if ( ! is_null( $args['load_attributes'] ) ) {
			$get_url = add_query_arg( 'LoadAttributes', $args['load_attributes'], $get_url );
		}

		// If no $cache_lifespan is specified, then retrive it from the filter.
		if ( is_null( $args['cache_lifespan'] ) ) {
			$args['cache_lifespan'] = apply_filters( 'rockpress_cache_' . strtolower( $args['endpoint'] ), 60 );
		}

		// Setup our variables.
		$transient_name = md5( $get_url );
		$rock_data = false;

		// Check the transient cache if the cache is not set to 0.
		if ( $args['cache_lifespan'] > 0 && 0 === $args['refresh_cache'] ) {
			$rock_data = $this->transient_fallback->get_transient( $transient_name, 'rockpress_schedule_get', $args );
		}

		// Check for a cached copy in the transient data.
		if ( false !== $rock_data ) {
			return $rock_data;
		}

		$get_args = array(
			'headers' => array(
				'Authorization-Token' => $this->rest_key,
			),
			'timeout' => 300,
		);

		$response = wp_remote_get( $get_url, $get_args );

		// Return false if there was an error.
		if ( is_wp_error( $response ) ) {
			return false;
		}

		// Grab the body from the response.
		$rock_data = wp_remote_retrieve_body( $response );

		// Save the transient data according to the $cache_lifespan.
		if ( $args['cache_lifespan'] > 0 ) {
			$this->transient_fallback->set_transient( $transient_name, $rock_data, $args['cache_lifespan'] );
		}

		$endpoint = strtolower( $args['endpoint'] );
		do_action( "rockpress_after_rock_get_{$endpoint}", $rock_data, $args );

		if ( true === $args['raw_response'] ) {
			return $response;
		}

		// Free up the memory.
		unset( $response );

		return $rock_data;

	}

	/**
	 * POST data to Rock
	 *
	 * @since 1.0.0
	 *
	 * @param array $args Arguments.
	 *
	 * @return	string	JSON string containing the data.
	 */
	public function post( $args = array() ) {

		if ( false === $this->is_connected() ) {
			return false;
		}

		$defaults = array(
			'endpoint'			=> null,
			'id'				=> null,
			'body'				=> null,
			'cache_lifespan'	=> null,
			'refresh_cache'		=> 0,
			'raw_response'		=> false,
		);
		$args = wp_parse_args( $args, $defaults );

		// Construct the URL.
		$url = trailingslashit( $this->domain ) . $args['endpoint'];

		// If there is an ID, then add it to the URL.
		if ( ! is_null( $args['id'] ) ) {
			$url = trailingslashit( $url ) . $args['id'];
		}

		// If no $cache_lifespan is specified, then retrive it from the filter.
		if ( is_null( $args['cache_lifespan'] ) ) {
			$args['cache_lifespan'] = apply_filters( 'rockpress_cache_' . strtolower( $args['endpoint'] ), 60 );
		}

		// Setup our variables.
		$transient_name = md5( $url . $args['body'] );
		$rock_data = false;

		// Check the transient cache if the cache is not set to 0.
		if ( $args['cache_lifespan'] > 0 && 0 === $args['refresh_cache'] ) {
			$rock_data = $this->transient_fallback->get_transient( $transient_name, 'rockpress_schedule_get', $args );
		}

		// Check for a cached copy in the transient data.
		if ( false !== $rock_data ) {
			return $rock_data;
		}

		$post_args = array(
			'headers' => array(
				'Authorization-Token' => $this->rest_key,
			),
			'timeout' => 300,
		);

		if ( ! is_null( $args['body'] ) ) {
			$post_args['body'] = $args['body'];
		}

		$response = wp_safe_remote_post( $url, $post_args );

		// Return false if there was an error.
		if ( is_wp_error( $response ) ) {
			return false;
		}

		// Grab the body from the response.
		$rock_data = wp_remote_retrieve_body( $response );

		// Save the transient data according to the $cache_lifespan.
		if ( $args['cache_lifespan'] > 0 ) {
			$this->transient_fallback->set_transient( $transient_name, $rock_data, $args['cache_lifespan'] );
		}

		$endpoint = strtolower( $args['endpoint'] );
		do_action( "rockpress_after_rock_get_{$endpoint}", $rock_data, $args );

		if ( true === $args['raw_response'] ) {
			return $response;
		}

		// Free up the memory.
		unset( $response );

		return $rock_data;

	}

	/**
	 * Check if we are connected to Rock
	 *
	 * @since 0.2.0
	 *
	 * @return boolean Answer
	 */
	public function is_connected() {
		$rockpress_rock = get_option( 'rockpress_rock', array() );

		if ( ! isset( $rockpress_rock['domain'] ) ) {
			return false;
		}

		if ( ! isset( $rockpress_rock['rest_key'] ) ) {
			return false;
		}

		if ( '' === $rockpress_rock['domain'] ) {
			return false;
		}

		if ( '' === $rockpress_rock['rest_key'] ) {
			return false;
		}

		return true;
	}

	/**
	 * Test the connection to Rock
	 *
	 * @since 0.2.0
	 *
	 * @return boolean Answer
	 */
	public function test() {

		$response = $this->get( array(
			'endpoint'		=> 'Campuses',
			'refresh_cache'	=> 1,
			'raw_response'	=> true,
		) );

		if ( 200 === wp_remote_retrieve_response_code( $response ) ) {
			return true;
		}

		return false;

	}

	/**
	 * Return the default cache lifespan for a endpoint.
	 *
	 * @param	string $endpoint	The Rock endpoint.
	 *
	 * @return	int
	 */
	public function cache_lifespan( $endpoint ) {
		return apply_filters( 'rockpress_cache_' . strtolower( $endpoint ), 60 );
	}

}
