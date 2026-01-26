<?php
/**
 * Advanced Tab class.
 *
 * @package MyPlugin
 */

namespace ThachPN165\MyPlugin\Admin\Tabs;

use ThachPN165\MyPlugin\Admin\AdminMenu;

defined( 'ABSPATH' ) || exit;

/**
 * AdvancedTab class - renders the advanced settings tab content.
 */
class AdvancedTab {

	/**
	 * Render the advanced tab.
	 *
	 * @param array $settings Current settings.
	 */
	public static function render( array $settings ): void {
		?>
		<div class="my-plugin-tab-content" id="tab-advanced">
			<h2><?php esc_html_e( 'Advanced Settings', 'my-plugin' ); ?></h2>
			<p class="description"><?php esc_html_e( 'Advanced configuration options for power users.', 'my-plugin' ); ?></p>

			<table class="form-table">
				<tr>
					<th scope="row">
						<label for="api_key"><?php esc_html_e( 'API Key', 'my-plugin' ); ?></label>
					</th>
					<td>
						<?php
						// Decrypt API key for display (stored encrypted in database).
						$api_key = AdminMenu::decrypt_api_key( $settings['api_key'] ?? '' );
						?>
						<input type="password" id="api_key" name="api_key"
							value="<?php echo esc_attr( $api_key ); ?>"
							class="regular-text" autocomplete="off" />
						<p class="description"><?php esc_html_e( 'Enter your API key for external services.', 'my-plugin' ); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="debug_mode"><?php esc_html_e( 'Debug Mode', 'my-plugin' ); ?></label>
					</th>
					<td>
						<input type="hidden" name="debug_mode" value="0" />
						<label class="my-plugin-toggle">
							<input type="checkbox" id="debug_mode" name="debug_mode" value="1"
								<?php checked( 1, $settings['debug_mode'] ?? 0 ); ?> />
							<span class="my-plugin-toggle-slider"></span>
						</label>
						<p class="description"><?php esc_html_e( 'Enable debug logging for troubleshooting.', 'my-plugin' ); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="custom_css"><?php esc_html_e( 'Custom CSS', 'my-plugin' ); ?></label>
					</th>
					<td>
						<textarea id="custom_css" name="custom_css" rows="6" class="large-text code"><?php echo esc_textarea( $settings['custom_css'] ?? '' ); ?></textarea>
						<p class="description"><?php esc_html_e( 'Add custom CSS styles.', 'my-plugin' ); ?></p>
					</td>
				</tr>
			</table>
		</div>
		<?php
	}
}
