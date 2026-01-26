<?php
/**
 * Admin Menu class.
 *
 * @package MyPlugin
 */

namespace ThachPN165\MyPlugin\Admin;

defined( 'ABSPATH' ) || exit;

use ThachPN165\MyPlugin\Interfaces\HookableInterface;

/**
 * AdminMenu class - handles admin menu registration and AJAX settings save.
 */
class AdminMenu implements HookableInterface {

	/**
	 * Register hooks.
	 */
	public function register_hooks(): void {
		add_action( 'admin_menu', array( $this, 'add_menu_page' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'wp_ajax_my_plugin_save_settings', array( $this, 'ajax_save_settings' ) );
	}

	/**
	 * Add menu page.
	 */
	public function add_menu_page(): void {
		add_menu_page(
			__( 'My Plugin Settings', 'my-plugin' ),
			__( 'My Plugin', 'my-plugin' ),
			'manage_options',
			'my-plugin',
			array( SettingsPage::class, 'render' ),
			'dashicons-admin-generic',
			80
		);
	}

	/**
	 * Register settings.
	 */
	public function register_settings(): void {
		register_setting(
			'my_plugin_settings_group',
			'my_plugin_settings',
			array(
				'type'              => 'array',
				'sanitize_callback' => array( $this, 'sanitize_settings' ),
				'default'           => $this->get_default_settings(),
			)
		);
	}

	/**
	 * Handle AJAX settings save.
	 */
	public function ajax_save_settings(): void {
		// Verify nonce.
		if ( ! check_ajax_referer( 'my_plugin_save_settings', 'my_plugin_nonce', false ) ) {
			wp_send_json_error(
				array( 'message' => __( 'Security check failed.', 'my-plugin' ) ),
				403
			);
		}

		// Check permissions.
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error(
				array( 'message' => __( 'Permission denied.', 'my-plugin' ) ),
				403
			);
		}

		// Get and sanitize form data.
		// phpcs:disable WordPress.Security.NonceVerification.Missing -- Nonce verified above.
		$input = array(
			'enable_feature'      => ! empty( $_POST['enable_feature'] ) ? 1 : 0,
			'plugin_mode'         => sanitize_text_field( wp_unslash( $_POST['plugin_mode'] ?? 'basic' ) ),
			'cache_duration'      => absint( $_POST['cache_duration'] ?? 3600 ),
			'api_key'             => sanitize_text_field( wp_unslash( $_POST['api_key'] ?? '' ) ),
			'debug_mode'          => ! empty( $_POST['debug_mode'] ) ? 1 : 0,
			'custom_css'          => wp_strip_all_tags( wp_unslash( $_POST['custom_css'] ?? '' ) ),
			'enable_analytics'    => ! empty( $_POST['enable_analytics'] ) ? 1 : 0,
			'third_party_api_url' => esc_url_raw( wp_unslash( $_POST['third_party_api_url'] ?? '' ) ),
			'webhook_url'         => esc_url_raw( wp_unslash( $_POST['webhook_url'] ?? '' ) ),
		);
		// phpcs:enable WordPress.Security.NonceVerification.Missing

		// Sanitize settings.
		$sanitized = $this->sanitize_settings( $input );

		// Update option.
		$updated = update_option( 'my_plugin_settings', $sanitized );

		if ( false === $updated ) {
			// Check if the settings are the same (no change needed).
			$current = get_option( 'my_plugin_settings', array() );
			if ( $current === $sanitized ) {
				wp_send_json_success(
					array( 'message' => __( 'Settings saved.', 'my-plugin' ) )
				);
			}

			wp_send_json_error(
				array( 'message' => __( 'Failed to save settings.', 'my-plugin' ) ),
				500
			);
		}

		wp_send_json_success(
			array( 'message' => __( 'Settings saved.', 'my-plugin' ) )
		);
	}

	/**
	 * Sanitize settings.
	 *
	 * @param array $input Input data.
	 * @return array Sanitized data.
	 */
	public function sanitize_settings( array $input ): array {
		$sanitized = array();

		// General settings.
		$sanitized['enable_feature'] = ! empty( $input['enable_feature'] ) ? 1 : 0;
		$sanitized['plugin_mode']    = in_array( $input['plugin_mode'] ?? '', array( 'basic', 'advanced', 'pro' ), true )
			? $input['plugin_mode']
			: 'basic';
		$sanitized['cache_duration'] = absint( $input['cache_duration'] ?? 3600 );

		// Advanced settings.
		// SECURITY: Encrypt API key before storing in database.
		// For production: always use encryption for sensitive credentials.
		$api_key = sanitize_text_field( $input['api_key'] ?? '' );
		$sanitized['api_key'] = ! empty( $api_key ) ? self::encrypt_api_key( $api_key ) : '';
		$sanitized['debug_mode'] = ! empty( $input['debug_mode'] ) ? 1 : 0;
		$sanitized['custom_css'] = wp_strip_all_tags( $input['custom_css'] ?? '' );

		// Integrations settings.
		$sanitized['enable_analytics']    = ! empty( $input['enable_analytics'] ) ? 1 : 0;
		$sanitized['third_party_api_url'] = esc_url_raw( $input['third_party_api_url'] ?? '' );
		$sanitized['webhook_url']         = esc_url_raw( $input['webhook_url'] ?? '' );

		return $sanitized;
	}

	/**
	 * Get default settings.
	 *
	 * @return array Default settings.
	 */
	private function get_default_settings(): array {
		return array(
			'enable_feature'      => 0,
			'plugin_mode'         => 'basic',
			'cache_duration'      => 3600,
			'api_key'             => '',
			'debug_mode'          => 0,
			'custom_css'          => '',
			'enable_analytics'    => 0,
			'third_party_api_url' => '',
			'webhook_url'         => '',
		);
	}

	/**
	 * Encrypt API key before storing.
	 *
	 * SECURITY BEST PRACTICE: Always encrypt sensitive data like API keys.
	 * Uses WordPress AUTH_KEY as encryption key (defined in wp-config.php).
	 *
	 * @param string $key Plain text API key.
	 * @return string Encrypted API key (base64 encoded).
	 */
	public static function encrypt_api_key( string $key ): string {
		if ( empty( $key ) ) {
			return '';
		}

		// Use AUTH_KEY from wp-config.php as encryption key.
		if ( ! defined( 'AUTH_KEY' ) || AUTH_KEY === 'put your unique phrase here' ) {
			// Fallback: base64 encode if AUTH_KEY not properly configured.
			return base64_encode( $key ); // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode
		}

		$method    = 'AES-256-CBC';
		$iv_length = openssl_cipher_iv_length( $method );
		$iv        = openssl_random_pseudo_bytes( $iv_length );
		$encrypted = openssl_encrypt( $key, $method, AUTH_KEY, 0, $iv );

		// Combine IV + encrypted data and base64 encode.
		return base64_encode( $iv . $encrypted ); // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode
	}

	/**
	 * Decrypt API key when retrieving.
	 *
	 * Usage example in your code:
	 * $settings = get_option( 'my_plugin_settings' );
	 * $api_key = AdminMenu::decrypt_api_key( $settings['api_key'] ?? '' );
	 *
	 * @param string $encrypted Encrypted API key.
	 * @return string Decrypted plain text API key.
	 */
	public static function decrypt_api_key( string $encrypted ): string {
		if ( empty( $encrypted ) ) {
			return '';
		}

		// Fallback for non-encrypted (base64 only) keys.
		if ( ! defined( 'AUTH_KEY' ) || AUTH_KEY === 'put your unique phrase here' ) {
			return base64_decode( $encrypted ); // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_decode
		}

		$data      = base64_decode( $encrypted ); // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_decode
		$method    = 'AES-256-CBC';
		$iv_length = openssl_cipher_iv_length( $method );
		$iv        = substr( $data, 0, $iv_length );
		$encrypted = substr( $data, $iv_length );

		$decrypted = openssl_decrypt( $encrypted, $method, AUTH_KEY, 0, $iv );

		return false !== $decrypted ? $decrypted : '';
	}
}
