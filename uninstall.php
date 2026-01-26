<?php
/**
 * Uninstall script.
 *
 * Fired when the plugin is uninstalled.
 *
 * @package MyPlugin
 */

// Exit if not called by WordPress.
defined( 'WP_UNINSTALL_PLUGIN' ) || exit;

// Securely wipe sensitive data before deletion.
$settings = get_option( 'my_plugin_settings', array() );
if ( ! empty( $settings['api_key'] ) ) {
	$settings['api_key'] = str_repeat( '0', strlen( $settings['api_key'] ) );
	update_option( 'my_plugin_settings', $settings );
}

// Remove plugin options.
delete_option( 'my_plugin_settings' );

// Clean up all plugin transients.
global $wpdb;

// Delete rate limiting and cache transients.
$wpdb->query(
	$wpdb->prepare(
		"DELETE FROM {$wpdb->options} WHERE option_name LIKE %s OR option_name LIKE %s",
		$wpdb->esc_like( '_transient_my_plugin_' ) . '%',
		$wpdb->esc_like( '_transient_timeout_my_plugin_' ) . '%'
	)
);

// Remove any user meta if needed (uncomment if using user meta).
// delete_metadata( 'user', 0, 'my_plugin_user_meta', '', true );
