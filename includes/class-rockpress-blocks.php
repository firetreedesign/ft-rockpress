<?php
/**
 * Blocks Initializer
 *
 * Enqueue CSS/JS of all the blocks.
 *
 * @since 1.0.11
 * @package RockPress
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * RockPress Blocks class
 */
class RockPress_Blocks {

	/**
	 * Initialize the class
	 *
	 * @since 1.0.11
	 *
	 * @return void
	 */
	public static function init() {
		if ( version_compare( $GLOBALS['wp_version'], '5.8alpha', '>=' ) ) {
			add_filter( 'block_categories_all', __CLASS__ . '::block_categories_all', 10, 2 );
		} else {
			add_filter( 'block_categories', __CLASS__ . '::block_categories', 10, 2 );
		}
	}

	/**
	 * Register a new block category for RockPress.
	 *
	 * @since 1.0.11
	 *
	 * @return array
	 */
	public static function block_categories( $categories, $post ) {
		return array_merge(
			$categories,
			array(
				array(
					'slug' => 'rockpress',
					'title' => __( 'RockPress', 'ft-rockpress' ),
				),
			)
		);
	}

	/**
	 * Register a new block category for RockPress.
	 *
	 * @since 1.0.17
	 * @param array  $block_categories Block categories.
	 * @param object $editor_context Editor context.
	 * @return array
	 */
	public static function block_categories_all( $block_categories, $editor_context ) {
		array_push(
			$block_categories,
			array(
				'slug'  => 'rockpress',
				'title' => __( 'RockPress', 'ft-rockpress' ),
				'icon'  => null,
			)
		);
		return $block_categories;
	}
}
RockPress_Blocks::init();
