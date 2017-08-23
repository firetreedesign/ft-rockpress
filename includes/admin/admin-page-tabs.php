<?php
/**
 * RockPress Admin Page Tabs
 *
 * @package RockPress
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * RockPress_Admin_Page_Tabs class
 */
class RockPress_Admin_Page_Tabs {

	/**
	 * Class constructor
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_filter( 'rockpress_settings_page_tabs', array( $this, 'settings_page_tabs_late' ), 100 );
		add_filter( 'rockpress_settings_page_tabs', array( $this, 'settings_page_tabs' ) );
		add_filter( 'rockpress_settings_page_actions', array( $this, 'settings_page_actions' ) );
	}

	/**
	 * Settings Page Tabs
	 *
	 * @since 1.0.0
	 *
	 * @param  array $tabs Tabs array.
	 *
	 * @return array       New tabs array
	 */
	public function settings_page_tabs( $tabs ) {

		$tabs[] = array(
			'tab_id'		=> 'rock',
			'settings_id'	=> 'rockpress_settings_rock',
			'title'			=> __( 'Rock RMS', 'ft-rockpress' ),
			'submit'		=> true,
		);

		$tabs[] = array(
			'tab_id'		=> 'rockpress',
			'settings_id'	=> 'rockpress_settings',
			'title'			=> __( 'RockPress', 'ft-rockpress' ),
			'submit'		=> true,
		);

		if ( RockPress()->rock->is_connected() ) {

			$tabs[] = array(
				'tab_id'		=> 'import',
				'settings_id'	=> 'rockpress_import',
				'title'			=> __( 'Data Import', 'ft-rockpress' ),
				'submit'		=> false,
			);

		}

		return $tabs;

	}

	/**
	 * Settings Page Tabs Late
	 *
	 * @since 1.0.0
	 *
	 * @param  array $tabs Tabs array.
	 *
	 * @return array       New tabs array
	 */
	public function settings_page_tabs_late( $tabs ) {

		if ( has_filter( 'rockpress_license_keys' ) ) {
			$tabs[] = array(
				'tab_id'		=> 'licenses',
				'settings_id'	=> 'rockpress_settings_licenses',
				'title'			=> __( 'Licenses', 'ft-rockpress' ),
				'submit'		=> true,
			);
		}

		return $tabs;

	}

	/**
	 * Settings Page Actions
	 *
	 * @since 1.0.0
	 *
	 * @param  array $actions Actions array.
	 *
	 * @return array          New actions array
	 */
	public function settings_page_actions( $actions ) {

		$actions[] = array(
			'tab_id'	=> 'rock',
			'type'		=> 'secondary',
			'class'		=> NULL,
			'link'		=> 'https://rockrms.com/',
			'target'	=> '_blank',
			'title'		=> __( 'Rock RMS Website', 'ft-rockpress' ),
		);

		$actions[] = array(
			'tab_id'	=> 'licenses',
			'type'		=> 'secondary',
			'class'		=> NULL,
			'link'		=> 'https://rockpresswp.com/account/',
			'target'	=> '_blank',
			'title'		=> __( 'Your Account', 'ft-rockpress' ),
		);

		return $actions;

	}

}
new RockPress_Admin_Page_Tabs();
