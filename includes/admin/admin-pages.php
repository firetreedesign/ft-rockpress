<?php
/**
 * RockPress Admin Pages
 *
 * @package RockPress
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * RockPress_Admin_Pages class
 */
class RockPress_Admin_Pages {

	/**
	 * Class constructor
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
	    add_action( 'admin_menu', array( $this, 'admin_menus' ) );
	}

	/**
	 * Register the Admin Pages
	 *
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public function admin_menus() {

	    // Getting Started Page.
	    add_menu_page(
	        __( 'RockPress Options', 'ft-rockpress' ),
	        __( 'RockPress', 'ft-rockpress' ),
	        'manage_options',
	        'rockpress',
	        array( $this, 'welcome_page' ),
	        'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9Im5vIj8+CjwhLS0gQ3JlYXRlZCB3aXRoIElua3NjYXBlIChodHRwOi8vd3d3Lmlua3NjYXBlLm9yZy8pIC0tPgoKPHN2ZwogICB4bWxuczpkYz0iaHR0cDovL3B1cmwub3JnL2RjL2VsZW1lbnRzLzEuMS8iCiAgIHhtbG5zOmNjPSJodHRwOi8vY3JlYXRpdmVjb21tb25zLm9yZy9ucyMiCiAgIHhtbG5zOnJkZj0iaHR0cDovL3d3dy53My5vcmcvMTk5OS8wMi8yMi1yZGYtc3ludGF4LW5zIyIKICAgeG1sbnM6c3ZnPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIKICAgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIgogICB4bWxuczpzb2RpcG9kaT0iaHR0cDovL3NvZGlwb2RpLnNvdXJjZWZvcmdlLm5ldC9EVEQvc29kaXBvZGktMC5kdGQiCiAgIHhtbG5zOmlua3NjYXBlPSJodHRwOi8vd3d3Lmlua3NjYXBlLm9yZy9uYW1lc3BhY2VzL2lua3NjYXBlIgogICB3aWR0aD0iNTcxLjQ0MTQxIgogICBoZWlnaHQ9IjU3MS40NDE0MSIKICAgdmlld0JveD0iMCAwIDU3MS40NDE0MSA1NzEuNDQxNCIKICAgaWQ9InN2ZzIiCiAgIHZlcnNpb249IjEuMSIKICAgaW5rc2NhcGU6dmVyc2lvbj0iMC45MSByMTM3MjUiCiAgIHNvZGlwb2RpOmRvY25hbWU9InJvY2twcmVzcyBjb3B5IDMuc3ZnIgogICBpbmtzY2FwZTpleHBvcnQtZmlsZW5hbWU9Ii9Vc2Vycy9kYW5pZWxtaWxuZXIvRHJvcGJveC9GaXJlVHJlZS9yb2NrcHJlc3MvbG9nby9yb2NrcHJlc3MucG5nIgogICBpbmtzY2FwZTpleHBvcnQteGRwaT0iMTA4IgogICBpbmtzY2FwZTpleHBvcnQteWRwaT0iMTA4Ij4KICA8ZGVmcwogICAgIGlkPSJkZWZzNCI+CiAgICA8Y2xpcFBhdGgKICAgICAgIGlkPSJjbGlwUGF0aDQxNjkiCiAgICAgICBjbGlwUGF0aFVuaXRzPSJ1c2VyU3BhY2VPblVzZSI+CiAgICAgIDxwYXRoCiAgICAgICAgIHN0eWxlPSJjbGlwLXJ1bGU6ZXZlbm9kZCIKICAgICAgICAgaW5rc2NhcGU6Y29ubmVjdG9yLWN1cnZhdHVyZT0iMCIKICAgICAgICAgaWQ9InBhdGg0MTcxIgogICAgICAgICBkPSJtIDAsMCAyMjUwLDAgMCwyMjUwLjc1IC0yMjUwLDAgTCAwLDAgWiIgLz4KICAgIDwvY2xpcFBhdGg+CiAgICA8Y2xpcFBhdGgKICAgICAgIGlkPSJjbGlwUGF0aDQxODEiCiAgICAgICBjbGlwUGF0aFVuaXRzPSJ1c2VyU3BhY2VPblVzZSI+CiAgICAgIDxwYXRoCiAgICAgICAgIHN0eWxlPSJjbGlwLXJ1bGU6ZXZlbm9kZCIKICAgICAgICAgaW5rc2NhcGU6Y29ubmVjdG9yLWN1cnZhdHVyZT0iMCIKICAgICAgICAgaWQ9InBhdGg0MTgzIgogICAgICAgICBkPSJNIDIyNTAsMjI1MCAyMjUwLDAgMCwwIGwgMCwyMjUwIDIyNTAsMCB6IiAvPgogICAgPC9jbGlwUGF0aD4KICAgIDxjbGlwUGF0aAogICAgICAgaWQ9ImNsaXBQYXRoNDE5MyIKICAgICAgIGNsaXBQYXRoVW5pdHM9InVzZXJTcGFjZU9uVXNlIj4KICAgICAgPHBhdGgKICAgICAgICAgc3R5bGU9ImNsaXAtcnVsZTpldmVub2RkIgogICAgICAgICBpbmtzY2FwZTpjb25uZWN0b3ItY3VydmF0dXJlPSIwIgogICAgICAgICBpZD0icGF0aDQxOTUiCiAgICAgICAgIGQ9Ik0gMjI1MCwyMjUwIDIyNTAsMCAwLDAgbCAwLDIyNTAgMjI1MCwwIHoiIC8+CiAgICA8L2NsaXBQYXRoPgogICAgPGNsaXBQYXRoCiAgICAgICBpZD0iY2xpcFBhdGg0MjQxIgogICAgICAgY2xpcFBhdGhVbml0cz0idXNlclNwYWNlT25Vc2UiPgogICAgICA8cGF0aAogICAgICAgICBzdHlsZT0iY2xpcC1ydWxlOmV2ZW5vZGQiCiAgICAgICAgIGlua3NjYXBlOmNvbm5lY3Rvci1jdXJ2YXR1cmU9IjAiCiAgICAgICAgIGlkPSJwYXRoNDI0MyIKICAgICAgICAgZD0iTSAxMzA1LjI3NjksODM1LjI5OTkzIDk5OC42MTQzOCw1NzguODY2MzkgNzQ2Ljk5MTk0LDg3OS43NzUzOSAxMDUzLjY1NDQsMTEzNi4yMDkgMTMwNS4yNzY5LDgzNS4yOTk5MyBaIiAvPgogICAgPC9jbGlwUGF0aD4KICA8L2RlZnM+CiAgPHNvZGlwb2RpOm5hbWVkdmlldwogICAgIGlkPSJiYXNlIgogICAgIHBhZ2Vjb2xvcj0iI2ZmZmZmZiIKICAgICBib3JkZXJjb2xvcj0iIzY2NjY2NiIKICAgICBib3JkZXJvcGFjaXR5PSIxLjAiCiAgICAgaW5rc2NhcGU6cGFnZW9wYWNpdHk9IjAiCiAgICAgaW5rc2NhcGU6cGFnZXNoYWRvdz0iMiIKICAgICBpbmtzY2FwZTp6b29tPSIwLjQ5NDk3NDc1IgogICAgIGlua3NjYXBlOmN4PSI2NzcuMzE4NDMiCiAgICAgaW5rc2NhcGU6Y3k9IjUxMi4yNjYxOSIKICAgICBpbmtzY2FwZTpkb2N1bWVudC11bml0cz0icHgiCiAgICAgaW5rc2NhcGU6Y3VycmVudC1sYXllcj0ibGF5ZXIxIgogICAgIHNob3dncmlkPSJmYWxzZSIKICAgICB1bml0cz0icHgiCiAgICAgZml0LW1hcmdpbi10b3A9IjEwIgogICAgIGZpdC1tYXJnaW4tcmlnaHQ9IjEwIgogICAgIGZpdC1tYXJnaW4tYm90dG9tPSIxMCIKICAgICBmaXQtbWFyZ2luLWxlZnQ9IjEwIgogICAgIGlua3NjYXBlOndpbmRvdy13aWR0aD0iMjUxMyIKICAgICBpbmtzY2FwZTp3aW5kb3ctaGVpZ2h0PSIxMzk2IgogICAgIGlua3NjYXBlOndpbmRvdy14PSI0NyIKICAgICBpbmtzY2FwZTp3aW5kb3cteT0iMCIKICAgICBpbmtzY2FwZTp3aW5kb3ctbWF4aW1pemVkPSIxIiAvPgogIDxtZXRhZGF0YQogICAgIGlkPSJtZXRhZGF0YTciPgogICAgPHJkZjpSREY+CiAgICAgIDxjYzpXb3JrCiAgICAgICAgIHJkZjphYm91dD0iIj4KICAgICAgICA8ZGM6Zm9ybWF0PmltYWdlL3N2Zyt4bWw8L2RjOmZvcm1hdD4KICAgICAgICA8ZGM6dHlwZQogICAgICAgICAgIHJkZjpyZXNvdXJjZT0iaHR0cDovL3B1cmwub3JnL2RjL2RjbWl0eXBlL1N0aWxsSW1hZ2UiIC8+CiAgICAgICAgPGRjOnRpdGxlPjwvZGM6dGl0bGU+CiAgICAgIDwvY2M6V29yaz4KICAgIDwvcmRmOlJERj4KICA8L21ldGFkYXRhPgogIDxnCiAgICAgaW5rc2NhcGU6bGFiZWw9IkxheWVyIDEiCiAgICAgaW5rc2NhcGU6Z3JvdXBtb2RlPSJsYXllciIKICAgICBpZD0ibGF5ZXIxIgogICAgIHRyYW5zZm9ybT0idHJhbnNsYXRlKDE0ODguOTAxMiwyMC42NDAzNTUpIj4KICAgIDxwYXRoCiAgICAgICBzdHlsZT0iZmlsbDojMDAwMDAwO2ZpbGwtb3BhY2l0eToxO3N0cm9rZTojZmZmZmZmO3N0cm9rZS13aWR0aDoxMDtzdHJva2UtbGluZWNhcDpyb3VuZDtzdHJva2UtbGluZWpvaW46cm91bmQ7c3Ryb2tlLW1pdGVybGltaXQ6NDtzdHJva2UtZGFzaGFycmF5Om5vbmU7c3Ryb2tlLW9wYWNpdHk6MSIKICAgICAgIGQ9Im0gLTkyMy4yNzkzLC00Mi4zODA4NTkgYSAyNzAuNzIwODksMjcwLjcyMDg5IDAgMCAwIC0yNjguNzAxMiwyMzkuMjcxNDc5IGMgNy4wNTQzLDQuOTYyNTYgMTQuOTM2MywxMC44NDcxMyAyMy4zODg3LDE3LjUxOTU0IDM0LjA5NzQsMjYuOTE2ODEgNDYuNDQzOSwzMy44Nzk3MyA1NS44MTI1LDMxLjQ3MjY1IDIwLjUyNzgsLTUuMjc0MjMgNjQuNDUwMiwtNTQuNjI0OTYgMTQ0LjM2MTMzLC0xNjIuMjAxMTY5IDE0Ljk4MDMsLTIwLjE2NjQ5IDMyLjE4NDcyLC00MS4yMTc1NjMgMzguMjMyNDIsLTQ2Ljc4MTI1IDEwLjI5NjQsLTkuNDcyMTM5IDExLjQzMjU0LC05Ljk1NDU5MiAxNy44NjUyNCwtNy41ODIwMzIgMTEuMjQwMiw0LjE0NTgzIDIxLjY1NDgxLDE3Ljk2NjY5MiA1NS43NTc4MSw3NC4wMDAwMDEgMzkuMTc2NSw2NC4zNjkwNSA5NC4yNjc5NSwxNDAuMTcwMzggMTEzLjA5Mzc1LDE1NS42MDc0MiAyNS42NTk3NiwyMS4wNDEyNyA1NC4yOTMxMywyMC4xMTUxOCA4OS41NjI1LC0zLjY0ODQ0IGEgMjcwLjcyMDg5LDI3MC43MjA4OSAwIDAgMCAxLjM0NzY2LC0yNi45Mzc1IDI3MC43MjA4OSwyNzAuNzIwODkgMCAwIDAgLTI3MC43MjA3MSwtMjcwLjcyMDY5OSB6IG0gMTYuMTU2MjUsMTExLjMzMzk4NCBjIC00LjczNDU5LDAuMjMxNDc3IC05LjE0ODYxLDUuNDMzNDg0IC0xNy4xMzg2NywxNy40ODYzMjggLTExLjM5OTUsMTcuMTk2MTU3IC0xMS41OTk2OSwxNy45NDMzNTcgLTQuODA4NTksMTcuOTQzMzU3IDguMjc4MywwIDE3Ljc0MzA3LDQuMzU3NjcgMjAuNjM4NjcsOS41MDE5NiAxLjQ3ODEsMi42MjYyMiAzLjUzNzYyLDE4LjA4NzIxIDQuNTc0MjIsMzQuMzU3NDIgMi4wNTg4LDMyLjMxMjk5IDQuNTQxODUsMzguODcyMTUgMTYuOTcyNjUsNDQuODE2NCA5LjEzNSw0LjM2ODM2IDkuMzMwODcsNS4wOTQwMiAzLjcyNjU3LDEzLjg2NzE5IC00LjAyNjgsNi4zMDM4NyAtMy44NzU5MSw3LjAxOTExIDQuMjQ2MDksMTkuOTcyNjYgNi40MDY3LDEwLjIxNzkgOC44MjM4NSwxNy4zODc3OSAxMC4wOTM3NSwyOS45NDcyNiAxLjY0MjIsMTYuMjQ0OTcgMS44MDAzOSwxNi41NDA5OSA5Ljc3MTQ4LDE4LjIwNzAzIDE0LjEzMzQsMi45NTM5NSAxNy4yMDk1NSw3Ljg2NzkzIDE5LjQ2NDg1LDMxLjEwMTU3IDIuMDcwOSwyMS4zMzM0NiA0LjQ4NjAxLDI3LjY3MTQ1IDEzLjY2NjAxLDM1Ljg0NTcgNS4wMzMyLDQuNDgxODEgNS4wMTk3Miw0LjU5MDcgLTEuNzA4OTgsMTIuNjE3MTkgLTkuMjAxNywxMC45NzY0OSAtMTAuNDU4NjUsMTYuMzU5MDIgLTUuNTkzNzUsMjMuOTc0NjEgNC42NTE2LDcuMjgxNCAxNC4wMzkxNCwxMy4yNTI0MSAyOC41MjM0NCwxOC4xNDA2MiA1LjQyMzUsMS44MzA0MSA5Ljg2MTMzLDMuNzc3MTcgOS44NjEzMyw0LjMyNjE3IDAsMC41NDkgLTkuMzU2MjIsMy41MjA3OCAtMjAuNzkxMDIsNi42MDM1MiAtNTAuNDY5NSwxMy42MDYyMiAtMTAyLjUxOTk0LDkuODg2ODkgLTE1My43NDQxNCwtMTAuOTgyNDIgLTIxLjQyMTYsLTguNzI3MzIgLTQyLjMzODk2LC0yMy44NjMwNyAtNDYuODEwNTYsLTMzLjg3MzA1IC0zLjUxMTIsLTcuODYwNzggLTkuNTEwOSwtMTAuMTY4MjIgLTMyLjQxNDEsLTEyLjQ2Mjg5IC0yNS4xNjg2LC0yLjUyMTcgLTI2LjkwOTgsLTMuMTA4NDkgLTI4LjUxNzUsLTkuNjE1MjMgLTAuODI4NiwtMy4zNTQ1NCAtMy43MTg5LC02LjY2ODg1IC02LjQyMTksLTcuMzYzMjkgLTkuNDY4OSwtMi40MzI4OSAtNDMuOTkyOSwtMzYuNzQyNTggLTU4LjM0NTcsLTU3Ljk4NDM3IC0yOS41MTM2LC00My42Nzk2OSAtMzkuNDQ2NSwtNTYuMjIxODkgLTUxLjUwNTksLTU5LjM2MTMzIGEgMjcwLjcyMDg5LDI3MC43MjA4OSAwIDAgMCAtMC42MTUyLDEyLjMyMDMxIDI3MC43MjA4OSwyNzAuNzIwODkgMCAwIDAgMjcwLjcyMDcsMjcwLjcyMDcxIDI3MC43MjA4OSwyNzAuNzIwODkgMCAwIDAgMjIzLjM4MjgyLC0xMTcuNzkxMDIgYyAtMTEuNjQ1NDQsLTMuNDQ0MTkgLTE2LjYwMDIyLC03LjA1MDc2IC0yMS41NTI3NCwtMTMuODQ1NyAtNC42NDg5LC02LjM3ODY1IC02LjUzMDM1LC03LjExNDkyIC0yMS4zNzY5NSwtOC4zNzExIC04Ljk1MzIsLTAuNzU3NTYgLTE3LjM2NzgyLC0yLjI1ODEyIC0xOC42OTkyMiwtMy4zMzU5MyAtNS40MjQ0LC00LjM5MSAyLjQ2Njc2LC0xNC4zNzk5OSAyOC44ODA4NiwtMzYuNTYyNSBsIDI3LjgxNDQ1LC0yMy4zNTkzOCAtMjMuNTAxOTUsLTEyLjUzMzIgYyAtMjguMzU3NSwtMTUuMTIzMTEgLTU5Ljc4MzU0LC00MS4zMDAwMiAtODIuMzA2NjQsLTY4LjU1ODYgLTIwLjQzNTYsLTI0LjczMTg1IC0yOC40NTE2MSwtMzcuNjk3NTMgLTQwLjU3MDMxLC02NS42MTcxOCAtMTIuODkzLC0yOS43MDI2OSAtMzYuMTk1ODMsLTY3LjgyNDggLTQ2LjIwMzEzLC03NS41ODU5NCAtMy45MTMyLC0zLjAzNTAyOSAtNi44NzAxOCwtNC42ODU3NjEgLTkuNzEwOTQsLTQuNTQ2ODc1IHoiCiAgICAgICB0cmFuc2Zvcm09InRyYW5zbGF0ZSgtMjc5LjkwMTIzLDM2Ljc0MDUwNCkiCiAgICAgICBpZD0icGF0aDQxNTIiCiAgICAgICBpbmtzY2FwZTpjb25uZWN0b3ItY3VydmF0dXJlPSIwIiAvPgogIDwvZz4KPC9zdmc+Cg=='
	    );

	    // Remove duplicate menu and add Welcome menu subpage.
	    add_submenu_page(
		    'rockpress',
		    __( 'Welcome', 'ft-rockpress' ),
		    __( 'Welcome', 'ft-rockpress' ),
		    'manage_options',
		    'rockpress',
		    array( $this, 'welcome_page' )
	    );

	    // Settings Page.
		global $rockpress_settings_help_page;
		$rockpress_settings_help_page = add_submenu_page(
		    'rockpress',
		    __( 'Settings', 'ft-rockpress' ),
		    __( 'Settings', 'ft-rockpress' ),
		    'manage_options',
		    'rockpress-settings',
		    array( $this, 'settings_page' )
	    );
		add_action( 'load-' . $rockpress_settings_help_page, array( $this, 'settings_page_help' ) );

		add_submenu_page(
			'rockpress',
			__( 'Add-ons', 'ft-rockpress' ),
			__( 'Add-ons', 'ft-rockpress' ),
			'manage_options',
			'rockpress-addons',
			array( $this, 'addons_page' )
		);

	}

	/**
	 * Render Getting Started Page
	 *
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public function welcome_page() {
		if ( has_filter( 'rockpress_enable_beacon' ) ) {
			wp_enqueue_script( 'rockpress-beacon' );
		}
		$active_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'welcome';
	    ?>
	    <div class="wrap about-wrap rockpress">
	        <h1><?php esc_html_e( 'Welcome to RockPress', 'ft-rockpress' ); ?></h1>
	        <div class="about-text">
				<?php esc_html_e( 'Thank you for using RockPress. RockPress allows you to display content from Rock RMS on your WordPress site.', 'ft-rockpress' ); ?>
			</div>
			<div class="rockpress-badge"><img src="<?php echo esc_attr( ROCKPRESS_PLUGIN_URL ) . 'assets/images/rockpress-mark.png'; ?>" alt="<?php esc_html_e( 'RockPress', 'ft-rockpress' ); ?>" / ></div>
	        <h1 class="nav-tab-wrapper">
	            <a class="nav-tab<?php echo ( 'welcome' === $active_tab ? ' nav-tab-active' : '' ); ?>" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'rockpress' ), 'admin.php' ) ) ); ?>">
	                <?php esc_html_e( 'Welcome', 'rockpress' ); ?>
	            </a>
				<a class="nav-tab<?php echo ( 'getting-started' === $active_tab ? ' nav-tab-active' : '' ); ?>" href="<?php echo esc_url( admin_url( add_query_arg( array( 'tab' => 'getting-started' ), add_query_arg( array( 'page' => 'rockpress' ), 'admin.php' ) ) ) ); ?>">
	                <?php esc_html_e( 'Getting Started', 'ft-rockpress' ); ?>
	            </a>
	        </h1>
			<?php
			switch ( $active_tab ) {
				case 'getting-started':
					echo $this->getting_started_content();
					break;
				default:
					echo $this->welcome_content();
					break;
			}
			?>
	    </div>
	    <?php
	}

	private function welcome_content() {
		?>
		<div class="feature-section one-col">
			<div class="col">
				<h2><?php esc_html_e( 'Widgets', 'ft-rockpress' ); ?></h2>
				<p class="lead-description"><?php esc_html_e( 'We have widgets to display a variety of information.', 'ft-rockpress' ); ?></p>
			</div>
		</div>
		<div class="feature-section has-2-columns">
			<div class="column">
				<h3><?php esc_html_e( 'Service Times Widget', 'ft-rockpress' ); ?></h3>
				<img src="<?php echo esc_attr( ROCKPRESS_PLUGIN_URL ) . '/assets/images/widget-service-times.png'; ?>" />
				<p><?php esc_html_e( 'Display service times for the selected Campus, straight from Rock RMS.', 'ft-rockpress' ); ?></p>
			</div>
			<div class="column">
				<h3><?php esc_html_e( 'Campus Selector Widget', 'ft-rockpress' ); ?></h3>
				<img src="<?php echo esc_attr( ROCKPRESS_PLUGIN_URL ) . '/assets/images/widget-campus-selector.png'; ?>" />
				<p><?php esc_html_e( 'Display links to each campus that is setup in Rock RMS.', 'ft-rockpress' ); ?></p>
			</div>
		</div>
		<hr />
		<div class="feature-section one-col">
			<div class="col">
				<h2><?php esc_html_e( 'Add-ons', 'ft-rockpress' ); ?></h2>
				<p class="lead-description"><?php esc_html_e( 'Extend the functionality of RockPress.', 'ft-rockpress' ); ?><br /><br /><a class="button button-primary" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'rockpress-addons' ), 'admin.php' ) ) ); ?>"><?php esc_html_e( 'Browse Add-ons', 'ft-rockpress' ); ?></a></p>
			</div>
		</div>
		<?php
	}

	private function getting_started_content() {
		?>
		<div class="feature-section one-col">
			<div class="col">
				<h2><?php esc_html_e( 'Connecting to Rock RMS', 'ft-rockpress' ); ?></h2>
				<p class="lead-description"><?php esc_html_e( 'Before you can use the plugin, you need to provide it with the information needed to connect to your Rock RMS site.', 'ft-rockpress' ); ?></p>
			</div>
		</div>
		<div class="feature-section two-col">
			<div class="col">
				<h3><?php esc_html_e( '1. Install the RockPress plugin from the Rock Shop', 'ft-rockpress' ); ?></h3>
				<p><?php esc_html_e( 'Log in to Rock, then navigate to Admin Tools > Rock Shop. Select the Web category and look for RockPress and install the plugin. After the plugin has been installed, navigate to Admin Tools > Installed Plugins > RockPress.', 'ft-rockpress' ); ?></p>
				<p><?php esc_html_e( 'Click on the Generate API Key button to generate an API Key and User for RockPress.', 'ft-rockpress' ); ?></p>
			</div>
			<div class="col">
				<img src="<?php echo esc_attr( ROCKPRESS_PLUGIN_URL ) . '/assets/images/rock-shop-plugin.png'; ?>" />
			</div>
		</div>
		<hr />
		<div class="feature-section two-col">
			<div class="col">
				<h3><?php esc_html_e( '2. Assign the REST Key to each REST Controller', 'ft-rockpress' ); ?></h3>
				<?php echo sprintf( '<p>%s <strong>%s</strong> %s <em>%s</em></p>', esc_html__( 'For this step, you will need get a list of each REST Controller that RockPress needs access to. You can get this list by logging in to your WordPress Admin, and navigating to RockPress > Settings. Click on the', 'ft-rockpress' ), esc_html__( 'REST Controllers', 'ft-rockpress' ), esc_html__( 'button to view a list of the controllers that have been registered with RockPress.', 'ft-rockpress' ), esc_html__( 'This list will change as add-ons and updates are installed/removed. So, you may need to revisit this after installing an update or an add-on.', 'ft-rockpress' ) ); ?>
				<?php echo sprintf( '<p>%s <strong>%s</strong> %s <strong>%s</strong> %s</p>', esc_html__( 'Now that you have the list of REST Controllers, go to your Rock RMS site and navigate to Security > REST Controllers. Find each Controller Name from your list and click on the Permissions (lock) button to the right of the name. Click on the', 'ft-rockpress' ), esc_html__( 'Add User', 'ft-rockpress' ), esc_html__( 'button and search for the', 'ft-rockpress' ), esc_html__( 'RockPress', 'ft-rockpress' ), esc_html__( 'API Key/User that we created in the previous step. Add the user with at least View permissions for each Controller. Repeat this step for each REST Controller.', 'ft-rockpress' ) ); ?>
			</div>
			<div class="col">
				<img src="<?php echo esc_attr( ROCKPRESS_PLUGIN_URL ) . '/assets/images/rock-permissions.png'; ?>" />
			</div>
		</div>
		<hr />
		<div class="feature-section two-col">
			<div class="col">
				<h3><?php esc_html_e( '3. Enter your REST Key information', 'ft-rockpress' ); ?></h3>
				<p><?php esc_html_e( 'Now, you will need to visit the Rock RMS tab and fill in your REST Key information.', 'ft-rockpress' ); ?></p>
				<p><?php esc_html_e( 'You will need to have the following information:', 'ft-rockpress' ); ?></p>
				<ul>
					<li><?php esc_html_e( 'The URL you use to access your Rock RMS site.', 'ft-rockpress' ); ?></li>
					<li><?php esc_html_e( 'The REST Key that you created.', 'ft-rockpress' ); ?></li>
				</ul>
				<p><a class="button" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'rockpress-settings' ), 'admin.php' ) ) ); ?>"><?php esc_html_e( 'Enter Your REST Key', 'ft-rockpress' ); ?></a></p>
			</div>
			<div class="col">
				<img src="<?php echo esc_attr( ROCKPRESS_PLUGIN_URL ) . '/assets/images/rest-key-information.png'; ?>" />
			</div>
		</div>
		<hr />
		<div class="feature-section two-col">
			<div class="col">
				<h3><?php esc_html_e( '4. Add some widgets', 'ft-rockpress' ); ?></h3>
				<p><?php esc_html_e( 'You are now ready to use any of the widgets that come with RockPress.', 'ft-rockpress' ); ?></p>
				<p><a class="button" href="<?php echo esc_url( admin_url( 'widgets.php' ) ); ?>"><?php esc_html_e( 'Manage Widgets', 'ft-rockpress' ); ?></a></p>
			</div>
		</div>
		<hr />
		<div class="feature-section two-col">
			<div class="col">
				<h3><?php esc_html_e( '5. Browse our add-ons', 'ft-rockpress' ); ?></h3>
				<p><?php esc_html_e( 'Feel free to browse our add-ons to add additional functionality to RockPress.', 'ft-rockpress' ); ?></p>
				<p><a class="button" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'rockpress-addons' ), 'admin.php' ) ) ); ?>"><?php esc_html_e( 'Browse Add-ons', 'ft-rockpress' ); ?></a></p>
			</div>
		</div>
		<?php
	}

	/**
	 * Render CCB Connection Page
	 *
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public function settings_page() {
		if ( has_filter( 'rockpress_enable_beacon' ) ) {
			wp_enqueue_script( 'rockpress-beacon' );
		}
		$all_tabs = apply_filters( 'rockpress_settings_page_tabs', array() );
		$active_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : $all_tabs[0]['tab_id'];

		$all_tab_actions = apply_filters( 'rockpress_settings_page_actions', array() );
		$has_tab_actions = false;
		foreach ( $all_tab_actions as $tab_action ) {
			if ( isset( $tab_action['tab_id'] ) && $tab_action['tab_id'] === $active_tab ) {
				$has_tab_actions = true;
			}
		}
		?>
	    <div class="wrap">
			<h1><?php esc_html_e( 'Settings', 'ft-rockpress' ); ?></h1>
	        <h1 class="nav-tab-wrapper">
	            <?php foreach ( $all_tabs as $tab ) : ?>
	                <a class="nav-tab<?php echo ( $active_tab === $tab['tab_id'] ? ' nav-tab-active' : '' ); ?>" href="<?php echo esc_url( admin_url( add_query_arg( array( 'tab' => $tab['tab_id'] ), add_query_arg( array( 'page' => 'rockpress-settings' ), 'admin.php' ) ) ) ); ?>">
	                    <?php echo esc_html( $tab['title'] ); ?>
	                </a>
	            <?php endforeach; ?>
	        </h1>
	        <?php if ( $has_tab_actions ) : ?>
	            <div class="rockpress_tab_actions">
	            <?php foreach ( $all_tab_actions as $tab_action ) : ?>
	                <?php if ( isset( $tab_action['tab_id'] ) && $tab_action['tab_id'] === $active_tab ) : ?>
	                    <a class="button<?php echo is_null( $tab_action['class'] ) ? esc_attr( '' ) : esc_attr( ' ' . $tab_action['class'] ); ?>" href="<?php echo esc_url( $tab_action['link'] ); ?>"<?php echo ( is_null( $tab_action['target'] ) ) ? '' : ' target="' . esc_attr( $tab_action['target'] ) . '"'; ?>><?php echo $tab_action['title']; ?></a>
	                <?php endif; ?>
	            <?php endforeach; ?>
	            </div>
	        <?php endif; ?>
			<div id="rockpress_tab_container" class="metabox-holder">
				<div class="postbox">
					<div class="inside">
		    			<form method="post" action="options.php">
		    				<table class="form-table">
								<?php
								foreach ( $all_tabs as $tab ) {
									if ( isset( $tab['tab_id'] ) && isset( $tab['settings_id'] ) && $tab['tab_id'] === $active_tab ) {
										settings_fields( $tab['settings_id'] );
										do_settings_sections( $tab['settings_id'] );
										if ( true === $tab['submit'] ) {
											submit_button();
										}
										settings_errors();
									}
								}
								?>
		    				</table>
		    			</form>
					</div>
				</div>
			</div><!-- #tab_container-->
	    </div>
	    <?php
	}

	/**
	 * Settings Help Page
	 *
	 * @since 1.0.0
	 *
	 * @return null
	 */
	public function settings_page_help() {

		global $rockpress_settings_help_page;
		$screen = get_current_screen();

		if ( $screen->id !== $rockpress_settings_help_page ) {
			return;
		}

		$all_tabs = apply_filters( 'rockpress_settings_page_tabs', array() );
		$active_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : $all_tabs[0]['tab_id'];

		$help_tabs = apply_filters( 'rockpress_settings_help_tabs', array() );
		foreach ( $help_tabs as $help_tab ) {
			if ( $help_tab['tab_id'] === $active_tab ) {
				$screen->add_help_tab( array(
					'id'		=> $help_tab['tab_id'],
					'title'		=> $help_tab['title'],
					'content'	=> $help_tab['content'],
				) );
			}
		}

	}

	/**
	 * Render Getting Started Page
	 *
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public function addons_page() {
		if ( has_filter( 'rockpress_enable_beacon' ) ) {
			wp_enqueue_script( 'rockpress-beacon' );
		}
		?>
	    <div class="wrap rockpress-addons">
			<h2><?php esc_html_e( 'RockPress Add-ons', 'ft-rockpress' ); ?></h2>
			<p>
				The following are available add-ons to extend RockPress functionality.
			</p>
			<div id="tab_container">
				<?php
				$addons = $this->get_addons_data();
				if ( false !== $addons ) {
					// $addons = json_decode( $addons );
					foreach ( $addons as $addon ) :
					?>
						<div class="rockpress-addon">
							<h3 class="rockpress-addon-title"><?php echo esc_html( $addon->title ); ?></h3>
							<a href="<?php echo esc_attr( $addon->link ); ?>" target="_blank"><img src="<?php echo esc_attr( $addon->thumbnail ); ?>" /></a>
							<p><?php echo esc_html( $addon->excerpt ); ?></p>
							<a href="<?php echo esc_attr( $addon->link ); ?>" target="_blank" class="button">Get this add-on</a>
						</div>
					<?php
					endforeach;
				} else {
					esc_html_e( 'Add-ons for RockPress will be available soon.', 'ft-rockpress' );
				}
				?>
			</div><!-- #tab_container-->
		</div>
		<?php
	}

	private function get_addons_data() {

		$data = get_transient( 'rockpress-addons' );

		if ( false !== $data ) {
			$data = json_decode( $data );

			if ( empty( $data ) ) {
				return false;
			}

			usort( $data, array( $this, 'sort_addons_data' ) );
			return $data;
		}

		$response = wp_remote_get( 'https://rockpresswp.com/wp-json/wp/v2/edd-addons' );

		// Return false if there was an error.
		if ( is_wp_error( $response ) ) {
			return false;
		}

		if ( 200 !== wp_remote_retrieve_response_code( $response ) ) {
			return false;
		}

		// Grab the body from the response.
		$data = wp_remote_retrieve_body( $response );

		// Free up the memory.
		unset( $response );

		set_transient( 'rockpress-addons', $data, 900 );

		$data = json_decode( $data );

		if ( empty( $data ) ) {
			return false;
		}

		usort( $data, array( $this, 'sort_addons_data' ) );

		return $data;

	}

	private function sort_addons_data( $a, $b ) {
		return strcmp( $a->title, $b->title );
	}

}

new RockPress_Admin_Pages();
