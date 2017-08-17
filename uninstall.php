<?php
/**
 * Uninstall RockPress
 *
 * @package		RockPress
 * @subpackage	Uninstall
 * @copyright	Copyright (c) 2017, FireTree Design, LLC
 * @license		http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since		1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

$rockpress_settings = get_option( 'rockpress_settings', array() );

if ( isset( $rockpress_settings['remove_data'] ) ) {

	// Delete the options.
	delete_option( 'rockpress_settings' );
	delete_option( 'rockpress_rock' );
	delete_option( 'rockpress_licenses' );
	delete_option( 'rockpress_import' );

}
