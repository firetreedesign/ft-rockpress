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
				// check current WP version.
				$img = '<span class="wp-media-buttons-icon" id="rockpress-media-button" style="background-image: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9Im5vIj8+CjwhLS0gQ3JlYXRlZCB3aXRoIElua3NjYXBlIChodHRwOi8vd3d3Lmlua3NjYXBlLm9yZy8pIC0tPgoKPHN2ZwogICB4bWxuczpkYz0iaHR0cDovL3B1cmwub3JnL2RjL2VsZW1lbnRzLzEuMS8iCiAgIHhtbG5zOmNjPSJodHRwOi8vY3JlYXRpdmVjb21tb25zLm9yZy9ucyMiCiAgIHhtbG5zOnJkZj0iaHR0cDovL3d3dy53My5vcmcvMTk5OS8wMi8yMi1yZGYtc3ludGF4LW5zIyIKICAgeG1sbnM6c3ZnPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIKICAgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIgogICB4bWxuczpzb2RpcG9kaT0iaHR0cDovL3NvZGlwb2RpLnNvdXJjZWZvcmdlLm5ldC9EVEQvc29kaXBvZGktMC5kdGQiCiAgIHhtbG5zOmlua3NjYXBlPSJodHRwOi8vd3d3Lmlua3NjYXBlLm9yZy9uYW1lc3BhY2VzL2lua3NjYXBlIgogICB3aWR0aD0iNTcxLjQ0MTQxIgogICBoZWlnaHQ9IjU3MS40NDE0MSIKICAgdmlld0JveD0iMCAwIDU3MS40NDE0MSA1NzEuNDQxNCIKICAgaWQ9InN2ZzIiCiAgIHZlcnNpb249IjEuMSIKICAgaW5rc2NhcGU6dmVyc2lvbj0iMC45MSByMTM3MjUiCiAgIHNvZGlwb2RpOmRvY25hbWU9InJvY2twcmVzcy1zaG9ydGNvZGUtaWNvbi5zdmciCiAgIGlua3NjYXBlOmV4cG9ydC1maWxlbmFtZT0iL1VzZXJzL2RhbmllbG1pbG5lci9Ecm9wYm94L0ZpcmVUcmVlL3JvY2twcmVzcy9sb2dvL3JvY2twcmVzcy5wbmciCiAgIGlua3NjYXBlOmV4cG9ydC14ZHBpPSIxMDgiCiAgIGlua3NjYXBlOmV4cG9ydC15ZHBpPSIxMDgiPgogIDxkZWZzCiAgICAgaWQ9ImRlZnM0Ij4KICAgIDxjbGlwUGF0aAogICAgICAgaWQ9ImNsaXBQYXRoNDE2OSIKICAgICAgIGNsaXBQYXRoVW5pdHM9InVzZXJTcGFjZU9uVXNlIj4KICAgICAgPHBhdGgKICAgICAgICAgc3R5bGU9ImNsaXAtcnVsZTpldmVub2RkIgogICAgICAgICBpbmtzY2FwZTpjb25uZWN0b3ItY3VydmF0dXJlPSIwIgogICAgICAgICBpZD0icGF0aDQxNzEiCiAgICAgICAgIGQ9Im0gMCwwIDIyNTAsMCAwLDIyNTAuNzUgLTIyNTAsMCBMIDAsMCBaIiAvPgogICAgPC9jbGlwUGF0aD4KICAgIDxjbGlwUGF0aAogICAgICAgaWQ9ImNsaXBQYXRoNDE4MSIKICAgICAgIGNsaXBQYXRoVW5pdHM9InVzZXJTcGFjZU9uVXNlIj4KICAgICAgPHBhdGgKICAgICAgICAgc3R5bGU9ImNsaXAtcnVsZTpldmVub2RkIgogICAgICAgICBpbmtzY2FwZTpjb25uZWN0b3ItY3VydmF0dXJlPSIwIgogICAgICAgICBpZD0icGF0aDQxODMiCiAgICAgICAgIGQ9Ik0gMjI1MCwyMjUwIDIyNTAsMCAwLDAgbCAwLDIyNTAgMjI1MCwwIHoiIC8+CiAgICA8L2NsaXBQYXRoPgogICAgPGNsaXBQYXRoCiAgICAgICBpZD0iY2xpcFBhdGg0MTkzIgogICAgICAgY2xpcFBhdGhVbml0cz0idXNlclNwYWNlT25Vc2UiPgogICAgICA8cGF0aAogICAgICAgICBzdHlsZT0iY2xpcC1ydWxlOmV2ZW5vZGQiCiAgICAgICAgIGlua3NjYXBlOmNvbm5lY3Rvci1jdXJ2YXR1cmU9IjAiCiAgICAgICAgIGlkPSJwYXRoNDE5NSIKICAgICAgICAgZD0iTSAyMjUwLDIyNTAgMjI1MCwwIDAsMCBsIDAsMjI1MCAyMjUwLDAgeiIgLz4KICAgIDwvY2xpcFBhdGg+CiAgICA8Y2xpcFBhdGgKICAgICAgIGlkPSJjbGlwUGF0aDQyNDEiCiAgICAgICBjbGlwUGF0aFVuaXRzPSJ1c2VyU3BhY2VPblVzZSI+CiAgICAgIDxwYXRoCiAgICAgICAgIHN0eWxlPSJjbGlwLXJ1bGU6ZXZlbm9kZCIKICAgICAgICAgaW5rc2NhcGU6Y29ubmVjdG9yLWN1cnZhdHVyZT0iMCIKICAgICAgICAgaWQ9InBhdGg0MjQzIgogICAgICAgICBkPSJNIDEzMDUuMjc2OSw4MzUuMjk5OTMgOTk4LjYxNDM4LDU3OC44NjYzOSA3NDYuOTkxOTQsODc5Ljc3NTM5IDEwNTMuNjU0NCwxMTM2LjIwOSAxMzA1LjI3NjksODM1LjI5OTkzIFoiIC8+CiAgICA8L2NsaXBQYXRoPgogIDwvZGVmcz4KICA8c29kaXBvZGk6bmFtZWR2aWV3CiAgICAgaWQ9ImJhc2UiCiAgICAgcGFnZWNvbG9yPSIjZmZmZmZmIgogICAgIGJvcmRlcmNvbG9yPSIjNjY2NjY2IgogICAgIGJvcmRlcm9wYWNpdHk9IjEuMCIKICAgICBpbmtzY2FwZTpwYWdlb3BhY2l0eT0iMCIKICAgICBpbmtzY2FwZTpwYWdlc2hhZG93PSIyIgogICAgIGlua3NjYXBlOnpvb209IjAuNDk0OTc0NzUiCiAgICAgaW5rc2NhcGU6Y3g9IjIwMS41MzY1OCIKICAgICBpbmtzY2FwZTpjeT0iNTEyLjI2NjE5IgogICAgIGlua3NjYXBlOmRvY3VtZW50LXVuaXRzPSJweCIKICAgICBpbmtzY2FwZTpjdXJyZW50LWxheWVyPSJsYXllcjEiCiAgICAgc2hvd2dyaWQ9ImZhbHNlIgogICAgIHVuaXRzPSJweCIKICAgICBmaXQtbWFyZ2luLXRvcD0iMTAiCiAgICAgZml0LW1hcmdpbi1yaWdodD0iMTAiCiAgICAgZml0LW1hcmdpbi1ib3R0b209IjEwIgogICAgIGZpdC1tYXJnaW4tbGVmdD0iMTAiCiAgICAgaW5rc2NhcGU6d2luZG93LXdpZHRoPSIyNTEzIgogICAgIGlua3NjYXBlOndpbmRvdy1oZWlnaHQ9IjEzOTYiCiAgICAgaW5rc2NhcGU6d2luZG93LXg9IjQ3IgogICAgIGlua3NjYXBlOndpbmRvdy15PSIwIgogICAgIGlua3NjYXBlOndpbmRvdy1tYXhpbWl6ZWQ9IjEiIC8+CiAgPG1ldGFkYXRhCiAgICAgaWQ9Im1ldGFkYXRhNyI+CiAgICA8cmRmOlJERj4KICAgICAgPGNjOldvcmsKICAgICAgICAgcmRmOmFib3V0PSIiPgogICAgICAgIDxkYzpmb3JtYXQ+aW1hZ2Uvc3ZnK3htbDwvZGM6Zm9ybWF0PgogICAgICAgIDxkYzp0eXBlCiAgICAgICAgICAgcmRmOnJlc291cmNlPSJodHRwOi8vcHVybC5vcmcvZGMvZGNtaXR5cGUvU3RpbGxJbWFnZSIgLz4KICAgICAgICA8ZGM6dGl0bGUgLz4KICAgICAgPC9jYzpXb3JrPgogICAgPC9yZGY6UkRGPgogIDwvbWV0YWRhdGE+CiAgPGcKICAgICBpbmtzY2FwZTpsYWJlbD0iTGF5ZXIgMSIKICAgICBpbmtzY2FwZTpncm91cG1vZGU9ImxheWVyIgogICAgIGlkPSJsYXllcjEiCiAgICAgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoMTQ4OC45MDEyLDIwLjY0MDM1NSkiPgogICAgPHBhdGgKICAgICAgIHN0eWxlPSJmaWxsOiM4ODg4ODg7ZmlsbC1vcGFjaXR5OjE7c3Ryb2tlOiNmZmZmZmY7c3Ryb2tlLXdpZHRoOjEwO3N0cm9rZS1saW5lY2FwOnJvdW5kO3N0cm9rZS1saW5lam9pbjpyb3VuZDtzdHJva2UtbWl0ZXJsaW1pdDo0O3N0cm9rZS1kYXNoYXJyYXk6bm9uZTtzdHJva2Utb3BhY2l0eToxIgogICAgICAgZD0ibSAtOTIzLjI3OTMsLTQyLjM4MDg1OSBhIDI3MC43MjA4OSwyNzAuNzIwODkgMCAwIDAgLTI2OC43MDEyLDIzOS4yNzE0NzkgYyA3LjA1NDMsNC45NjI1NiAxNC45MzYzLDEwLjg0NzEzIDIzLjM4ODcsMTcuNTE5NTQgMzQuMDk3NCwyNi45MTY4MSA0Ni40NDM5LDMzLjg3OTczIDU1LjgxMjUsMzEuNDcyNjUgMjAuNTI3OCwtNS4yNzQyMyA2NC40NTAyLC01NC42MjQ5NiAxNDQuMzYxMzMsLTE2Mi4yMDExNjkgMTQuOTgwMywtMjAuMTY2NDkgMzIuMTg0NzIsLTQxLjIxNzU2MyAzOC4yMzI0MiwtNDYuNzgxMjUgMTAuMjk2NCwtOS40NzIxMzkgMTEuNDMyNTQsLTkuOTU0NTkyIDE3Ljg2NTI0LC03LjU4MjAzMiAxMS4yNDAyLDQuMTQ1ODMgMjEuNjU0ODEsMTcuOTY2NjkyIDU1Ljc1NzgxLDc0LjAwMDAwMSAzOS4xNzY1LDY0LjM2OTA1IDk0LjI2Nzk1LDE0MC4xNzAzOCAxMTMuMDkzNzUsMTU1LjYwNzQyIDI1LjY1OTc2LDIxLjA0MTI3IDU0LjI5MzEzLDIwLjExNTE4IDg5LjU2MjUsLTMuNjQ4NDQgYSAyNzAuNzIwODksMjcwLjcyMDg5IDAgMCAwIDEuMzQ3NjYsLTI2LjkzNzUgMjcwLjcyMDg5LDI3MC43MjA4OSAwIDAgMCAtMjcwLjcyMDcxLC0yNzAuNzIwNjk5IHogbSAxNi4xNTYyNSwxMTEuMzMzOTg0IGMgLTQuNzM0NTksMC4yMzE0NzcgLTkuMTQ4NjEsNS40MzM0ODQgLTE3LjEzODY3LDE3LjQ4NjMyOCAtMTEuMzk5NSwxNy4xOTYxNTcgLTExLjU5OTY5LDE3Ljk0MzM1NyAtNC44MDg1OSwxNy45NDMzNTcgOC4yNzgzLDAgMTcuNzQzMDcsNC4zNTc2NyAyMC42Mzg2Nyw5LjUwMTk2IDEuNDc4MSwyLjYyNjIyIDMuNTM3NjIsMTguMDg3MjEgNC41NzQyMiwzNC4zNTc0MiAyLjA1ODgsMzIuMzEyOTkgNC41NDE4NSwzOC44NzIxNSAxNi45NzI2NSw0NC44MTY0IDkuMTM1LDQuMzY4MzYgOS4zMzA4Nyw1LjA5NDAyIDMuNzI2NTcsMTMuODY3MTkgLTQuMDI2OCw2LjMwMzg3IC0zLjg3NTkxLDcuMDE5MTEgNC4yNDYwOSwxOS45NzI2NiA2LjQwNjcsMTAuMjE3OSA4LjgyMzg1LDE3LjM4Nzc5IDEwLjA5Mzc1LDI5Ljk0NzI2IDEuNjQyMiwxNi4yNDQ5NyAxLjgwMDM5LDE2LjU0MDk5IDkuNzcxNDgsMTguMjA3MDMgMTQuMTMzNCwyLjk1Mzk1IDE3LjIwOTU1LDcuODY3OTMgMTkuNDY0ODUsMzEuMTAxNTcgMi4wNzA5LDIxLjMzMzQ2IDQuNDg2MDEsMjcuNjcxNDUgMTMuNjY2MDEsMzUuODQ1NyA1LjAzMzIsNC40ODE4MSA1LjAxOTcyLDQuNTkwNyAtMS43MDg5OCwxMi42MTcxOSAtOS4yMDE3LDEwLjk3NjQ5IC0xMC40NTg2NSwxNi4zNTkwMiAtNS41OTM3NSwyMy45NzQ2MSA0LjY1MTYsNy4yODE0IDE0LjAzOTE0LDEzLjI1MjQxIDI4LjUyMzQ0LDE4LjE0MDYyIDUuNDIzNSwxLjgzMDQxIDkuODYxMzMsMy43NzcxNyA5Ljg2MTMzLDQuMzI2MTcgMCwwLjU0OSAtOS4zNTYyMiwzLjUyMDc4IC0yMC43OTEwMiw2LjYwMzUyIC01MC40Njk1LDEzLjYwNjIyIC0xMDIuNTE5OTQsOS44ODY4OSAtMTUzLjc0NDE0LC0xMC45ODI0MiAtMjEuNDIxNiwtOC43MjczMiAtNDIuMzM4OTYsLTIzLjg2MzA3IC00Ni44MTA1NiwtMzMuODczMDUgLTMuNTExMiwtNy44NjA3OCAtOS41MTA5LC0xMC4xNjgyMiAtMzIuNDE0MSwtMTIuNDYyODkgLTI1LjE2ODYsLTIuNTIxNyAtMjYuOTA5OCwtMy4xMDg0OSAtMjguNTE3NSwtOS42MTUyMyAtMC44Mjg2LC0zLjM1NDU0IC0zLjcxODksLTYuNjY4ODUgLTYuNDIxOSwtNy4zNjMyOSAtOS40Njg5LC0yLjQzMjg5IC00My45OTI5LC0zNi43NDI1OCAtNTguMzQ1NywtNTcuOTg0MzcgLTI5LjUxMzYsLTQzLjY3OTY5IC0zOS40NDY1LC01Ni4yMjE4OSAtNTEuNTA1OSwtNTkuMzYxMzMgYSAyNzAuNzIwODksMjcwLjcyMDg5IDAgMCAwIC0wLjYxNTIsMTIuMzIwMzEgMjcwLjcyMDg5LDI3MC43MjA4OSAwIDAgMCAyNzAuNzIwNywyNzAuNzIwNzEgMjcwLjcyMDg5LDI3MC43MjA4OSAwIDAgMCAyMjMuMzgyODIsLTExNy43OTEwMiBjIC0xMS42NDU0NCwtMy40NDQxOSAtMTYuNjAwMjIsLTcuMDUwNzYgLTIxLjU1Mjc0LC0xMy44NDU3IC00LjY0ODksLTYuMzc4NjUgLTYuNTMwMzUsLTcuMTE0OTIgLTIxLjM3Njk1LC04LjM3MTEgLTguOTUzMiwtMC43NTc1NiAtMTcuMzY3ODIsLTIuMjU4MTIgLTE4LjY5OTIyLC0zLjMzNTkzIC01LjQyNDQsLTQuMzkxIDIuNDY2NzYsLTE0LjM3OTk5IDI4Ljg4MDg2LC0zNi41NjI1IGwgMjcuODE0NDUsLTIzLjM1OTM4IC0yMy41MDE5NSwtMTIuNTMzMiBjIC0yOC4zNTc1LC0xNS4xMjMxMSAtNTkuNzgzNTQsLTQxLjMwMDAyIC04Mi4zMDY2NCwtNjguNTU4NiAtMjAuNDM1NiwtMjQuNzMxODUgLTI4LjQ1MTYxLC0zNy42OTc1MyAtNDAuNTcwMzEsLTY1LjYxNzE4IC0xMi44OTMsLTI5LjcwMjY5IC0zNi4xOTU4MywtNjcuODI0OCAtNDYuMjAzMTMsLTc1LjU4NTk0IC0zLjkxMzIsLTMuMDM1MDI5IC02Ljg3MDE4LC00LjY4NTc2MSAtOS43MTA5NCwtNC41NDY4NzUgeiIKICAgICAgIHRyYW5zZm9ybT0idHJhbnNsYXRlKC0yNzkuOTAxMjMsMzYuNzQwNTA0KSIKICAgICAgIGlkPSJwYXRoNDE1MiIKICAgICAgIGlua3NjYXBlOmNvbm5lY3Rvci1jdXJ2YXR1cmU9IjAiIC8+CiAgPC9nPgo8L3N2Zz4K); background-size: cover;"></span>';
				reset( $shortcodes );
				if ( count( $shortcodes ) === 1 ) {
					$shortcode = key( $shortcodes );
					printf(
						'<button class="button rockpress-sc-shortcode" data-shortcode="%s">%s</button>',
						$shortcode,
						sprintf( '%s %s %s',
							$img,
							esc_html__( 'Insert', 'ft-rockpress' ),
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
						esc_html__( 'RockPress', 'ft-rockpress' ),
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
				$data['btn_okay'] = array( esc_html__( 'OK', 'ft-rockpress' ) );
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
