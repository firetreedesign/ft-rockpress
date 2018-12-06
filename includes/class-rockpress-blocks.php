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
        add_filter( 'block_categories', __CLASS__ . '::block_categories', 10, 2 );
    }

    /**
     * Register a new block category for RockPress.
     * 
     * @since 1.0.11
     * 
     * @return void
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
}
RockPress_Blocks::init();