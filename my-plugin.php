<?php
/**
 * Plugin Name: My Plugin
 * Plugin URI:  https://example.com/my-plugin
 * Description: A WordPress plugin boilerplate.
 * Version:           1.0.0
 * Author:      ThachPN165
 * Author URI:  https://example.com
 * License:     GPL-2.0+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: my-plugin
 * Domain Path: /languages
 * Requires at least: 6.0
 * Requires PHP: 7.4
 *
 * @package MyPlugin
 */

defined( 'ABSPATH' ) || exit;

// Plugin constants.
define( 'MY_PLUGIN_VERSION', '1.0.0' );
define( 'MY_PLUGIN_FILE', __FILE__ );
define( 'MY_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'MY_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'MY_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

// Composer autoload.
if ( file_exists( MY_PLUGIN_PATH . 'vendor/autoload.php' ) ) {
	require_once MY_PLUGIN_PATH . 'vendor/autoload.php';
}

// Initialize plugin.
add_action(
	'plugins_loaded',
	function () {
		\ThachPN165\MyPlugin\Plugin::instance();
	}
);

// Activation/Deactivation hooks.
register_activation_hook( __FILE__, array( \ThachPN165\MyPlugin\Core\Activator::class, 'activate' ) );
register_deactivation_hook( __FILE__, array( \ThachPN165\MyPlugin\Core\Deactivator::class, 'deactivate' ) );
