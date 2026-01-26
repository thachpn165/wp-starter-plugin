<?php
/**
 * Plugin Activator class.
 *
 * @package MyPlugin
 */

namespace ThachPN165\MyPlugin\Core;

defined( 'ABSPATH' ) || exit;

/**
 * Activator class - runs on plugin activation.
 */
class Activator {

	/**
	 * Activate the plugin.
	 */
	public static function activate(): void {
		// Check PHP version.
		if ( version_compare( PHP_VERSION, '7.4', '<' ) ) {
			deactivate_plugins( MY_PLUGIN_BASENAME );
			wp_die(
				esc_html__( 'This plugin requires PHP 7.4 or higher.', 'my-plugin' ),
				'Plugin Activation Error',
				array( 'back_link' => true )
			);
		}

		// Check WP version.
		if ( version_compare( get_bloginfo( 'version' ), '6.0', '<' ) ) {
			deactivate_plugins( MY_PLUGIN_BASENAME );
			wp_die(
				esc_html__( 'This plugin requires WordPress 6.0 or higher.', 'my-plugin' ),
				'Plugin Activation Error',
				array( 'back_link' => true )
			);
		}

		// Create default options.
		if ( false === get_option( 'my_plugin_settings' ) ) {
			add_option(
				'my_plugin_settings',
				array(
					'enable_feature' => 0,
					'api_key'        => '',
				)
			);
		}

		// Flush rewrite rules.
		flush_rewrite_rules();
	}
}
