<?php
/**
 * Assets class.
 *
 * @package MyPlugin
 */

namespace ThachPN165\MyPlugin\PublicSide;

defined( 'ABSPATH' ) || exit;

use ThachPN165\MyPlugin\Interfaces\HookableInterface;

/**
 * Assets class - handles script and style enqueuing.
 */
class Assets implements HookableInterface {

	/**
	 * Register hooks.
	 */
	public function register_hooks(): void {
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_public_assets' ) );
	}

	/**
	 * Enqueue admin assets.
	 *
	 * @param string $hook Current admin page hook.
	 */
	public function enqueue_admin_assets( string $hook ): void {
		// Only load on plugin pages.
		if ( strpos( $hook, 'my-plugin' ) === false ) {
			return;
		}

		wp_enqueue_style(
			'my-plugin-admin',
			MY_PLUGIN_URL . 'assets/css/admin.css',
			array(),
			MY_PLUGIN_VERSION
		);

		wp_enqueue_script(
			'my-plugin-admin',
			MY_PLUGIN_URL . 'assets/js/admin.js',
			array( 'jquery' ),
			MY_PLUGIN_VERSION,
			true
		);

		wp_localize_script(
			'my-plugin-admin',
			'myPluginAdmin',
			array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'my_plugin_save_settings' ),
				'strings' => array(
					'confirm' => __( 'Are you sure?', 'my-plugin' ),
					'saving'  => __( 'Saving...', 'my-plugin' ),
					'saved'   => __( 'Settings saved.', 'my-plugin' ),
					'error'   => __( 'An error occurred. Please try again.', 'my-plugin' ),
				),
			)
		);
	}

	/**
	 * Enqueue public assets.
	 */
	public function enqueue_public_assets(): void {
		// Check if should load public assets.
		$settings = get_option( 'my_plugin_settings', array() );
		if ( empty( $settings['enable_feature'] ) ) {
			return;
		}

		wp_enqueue_style(
			'my-plugin-public',
			MY_PLUGIN_URL . 'assets/css/public.css',
			array(),
			MY_PLUGIN_VERSION
		);

		wp_enqueue_script(
			'my-plugin-public',
			MY_PLUGIN_URL . 'assets/js/public.js',
			array(),
			MY_PLUGIN_VERSION,
			true
		);
	}
}
