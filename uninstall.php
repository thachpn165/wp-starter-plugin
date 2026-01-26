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

// Remove plugin options.
delete_option( 'my_plugin_settings' );

// Clean up any transients.
delete_transient( 'my_plugin_cache' );

// Remove any user meta if needed (uncomment if using user meta).
// delete_metadata( 'user', 0, 'my_plugin_user_meta', '', true );
