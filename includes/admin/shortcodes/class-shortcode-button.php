<?php
/**
 * RockPress Shortcode Button
 *
 * @package RockPress
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * RockPress Shortcode Button
 */
final class RockPress_Shortcode_Button {

	/**
	 * All shortcode tags
	 *
	 * @var array
	 */
	public static $shortcodes;

	/**
	 * Class constructor
	 */
	public function __construct() {

		if ( version_compare( get_bloginfo( 'version' ), '3.5', '<' ) ) {
			return;
		}

		if ( is_admin() ) {
			add_filter( 'mce_external_plugins', array( $this, 'mce_external_plugins' ), 15 );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_assets' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_localize_scripts' ), 13 );
			add_action( 'media_buttons', array( $this, 'shortcode_button' ) );
		}
		add_action( "wp_ajax_rockpress_shortcode", array( $this, 'shortcode_ajax' ) );
		add_action( "wp_ajax_nopriv_rockpress_shortcode", array( $this, 'shortcode_ajax' ) );
	}

	/**
	 * Register any TinyMCE plugins
	 *
	 * @param array $plugin_array
	 *
	 * @return array|bool
	 *
	 * @since 1.0
	 */
	public function mce_external_plugins( $plugin_array ) {
		if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {
			return false;
		}
		$plugin_array['rockpress_shortcode'] = ROCKPRESS_PLUGIN_URL . 'assets/js/admin/tinymce/mce-plugin.js';
		return $plugin_array;
	}

	/**
	 * Enqueue the admin assets
	 *
	 * @return void
	 *
	 * @since 1.0
	 */
	public function admin_enqueue_assets() {
		wp_enqueue_script(
			'rockpress_shortcode',
			ROCKPRESS_PLUGIN_URL . 'assets/js/admin/admin-shortcodes.js',
			array( 'jquery' ),
			ROCKPRESS_VERSION,
			true
		);
	}

	/**
	 * Localize the admin scripts
	 *
	 * @return void
	 *
	 * @since 1.0
	 */
	public function admin_localize_scripts() {
		if ( ! empty( self::$shortcodes ) ) {
			$variables = array();
			foreach ( self::$shortcodes as $shortcode => $values ) {
				if ( ! empty( $values['required'] ) ) {
					$variables[ $shortcode ] = $values['required'];
				}
			}
			wp_localize_script( 'rockpress_shortcode', 'RockPressShortcodes', $variables );
		}
	}

	/**
	 * Adds the "RockPress" button above the TinyMCE Editor on add/edit screens.
	 *
	 * @return string|bool
	 *
	 * @since 1.0
	 */
	public function shortcode_button() {
		$screen = get_current_screen();
		// If we load wp editor by ajax then $screen will be empty which generate notice if we treat $screen as WP_Screen object.
		// For example we are loading wp editor by ajax in repeater field.
		if ( ! ( $screen instanceof WP_Screen ) ) {
			return false;
		}
		$shortcode_button_pages = apply_filters( 'rockpress_shortcode_button_pages', array(
			'post.php',
			'page.php',
			'post-new.php',
			'post-edit.php',
			'edit.php',
			'edit.php?post_type=page',
		) );
		// Only run in admin post/page creation and edit screens
		if ( in_array( $screen->parent_file, $shortcode_button_pages )
		     && apply_filters( 'rockpress_shortcode_button_condition', true )
		     && ! empty( self::$shortcodes )
		) {
			$shortcodes = array();
			foreach ( self::$shortcodes as $shortcode => $values ) {
				/**
				 * Filters the condition for including the current shortcode
				 *
				 * @since 1.0
				 */
				if ( apply_filters( sanitize_title( $shortcode ) . '_condition', true ) ) {
					$shortcodes[ $shortcode ] = sprintf(
						'<div class="rockpress-sc-shortcode mce-menu-item rockpress-shortcode-item-%1$s" data-shortcode="%s">%s</div>',
						$shortcode,
						$values['label'],
						$shortcode
					);
				}
			}
			if ( ! empty( $shortcodes ) ) {
				// check current WP version
				$img = '<span class="wp-media-buttons-icon" id="rockpress-media-button"></span>';
				reset( $shortcodes );
				if ( count( $shortcodes ) === 1 ) {
					$shortcode = key( $shortcodes );
					printf(
						'<button class="button rockpress-sc-shortcode" data-shortcode="%s">%s</button>',
						$shortcode,
						sprintf( '%s %s %s',
							$img,
							esc_html__( 'Insert', 'rockpress' ),
							self::$shortcodes[ $shortcode ]['label']
						)
					);
				} else {
					printf(
						'<div class="rockpress-sc-wrap">' .
						'<button class="button rockpress-sc-button">%s %s</button>' .
						'<div class="rockpress-sc-menu mce-menu">%s</div>' .
						'</div>',
						$img,
						esc_html__( 'RockPress', 'rockpress' ),
						implode( '', array_values( $shortcodes ) )
					);
				}
			}
		}
	}

	/**
	 * Load the shortcode dialog fields via AJAX
	 *
	 * @return void
	 *
	 * @since 1.0
	 */
	public function shortcode_ajax() {
		$shortcode = isset( $_POST['shortcode'] ) ? $_POST['shortcode'] : false;
		$response  = false;
		if ( $shortcode && array_key_exists( $shortcode, self::$shortcodes ) ) {
			$data = self::$shortcodes[ $shortcode ];
			if ( ! empty( $data['errors'] ) ) {
				$data['btn_okay'] = array( esc_html__( 'OK', 'rockpress' ) );
			}
			$response = array(
				'body'      => $data['fields'],
				'close'     => $data['btn_close'],
				'ok'        => $data['btn_okay'],
				'shortcode' => $shortcode,
				'title'     => $data['title'],
			);
		} else {
			// todo: handle error
			error_log( print_r( 'AJAX error!', 1 ) );
		}
		wp_send_json( $response );
	}

}

new RockPress_Shortcode_Button;
