<?php
/**
 * Integrations Tab class.
 *
 * @package MyPlugin
 */

namespace ThachPN165\MyPlugin\Admin\Tabs;

defined( 'ABSPATH' ) || exit;

/**
 * IntegrationsTab class - renders the integrations tab content.
 */
class IntegrationsTab {

	/**
	 * Render the integrations tab.
	 *
	 * @param array $settings Current settings.
	 */
	public static function render( array $settings ): void {
		?>
		<div class="my-plugin-tab-content" id="tab-integrations">
			<h2><?php esc_html_e( 'Integrations', 'my-plugin' ); ?></h2>
			<p class="description"><?php esc_html_e( 'Configure third-party integrations.', 'my-plugin' ); ?></p>

			<table class="form-table">
				<tr>
					<th scope="row">
						<label for="enable_analytics"><?php esc_html_e( 'Enable Analytics', 'my-plugin' ); ?></label>
					</th>
					<td>
						<label class="my-plugin-toggle">
							<input type="checkbox" id="enable_analytics" name="enable_analytics" value="1"
								<?php checked( 1, $settings['enable_analytics'] ?? 0 ); ?> />
							<span class="my-plugin-toggle-slider"></span>
						</label>
						<p class="description"><?php esc_html_e( 'Enable analytics tracking.', 'my-plugin' ); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="third_party_api_url"><?php esc_html_e( 'Third-party API URL', 'my-plugin' ); ?></label>
					</th>
					<td>
						<input type="url" id="third_party_api_url" name="third_party_api_url"
							value="<?php echo esc_url( $settings['third_party_api_url'] ?? '' ); ?>"
							class="regular-text" placeholder="https://api.example.com" />
						<p class="description"><?php esc_html_e( 'URL for third-party API integration.', 'my-plugin' ); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="webhook_url"><?php esc_html_e( 'Webhook URL', 'my-plugin' ); ?></label>
					</th>
					<td>
						<input type="url" id="webhook_url" name="webhook_url"
							value="<?php echo esc_url( $settings['webhook_url'] ?? '' ); ?>"
							class="regular-text" placeholder="https://example.com/webhook" />
						<p class="description"><?php esc_html_e( 'URL to receive webhook notifications.', 'my-plugin' ); ?></p>
					</td>
				</tr>
			</table>
		</div>
		<?php
	}
}
