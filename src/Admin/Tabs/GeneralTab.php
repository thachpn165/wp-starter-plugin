<?php
/**
 * General Tab class.
 *
 * @package MyPlugin
 */

namespace ThachPN165\MyPlugin\Admin\Tabs;

defined( 'ABSPATH' ) || exit;

/**
 * GeneralTab class - renders the general settings tab content.
 */
class GeneralTab {

	/**
	 * Render the general tab.
	 *
	 * @param array $settings Current settings.
	 */
	public static function render( array $settings ): void {
		?>
		<div class="my-plugin-tab-content" id="tab-general">
			<h2><?php esc_html_e( 'General Settings', 'my-plugin' ); ?></h2>
			<p class="description"><?php esc_html_e( 'Configure the basic plugin settings.', 'my-plugin' ); ?></p>

			<table class="form-table">
				<tr>
					<th scope="row">
						<label for="enable_feature"><?php esc_html_e( 'Enable Feature', 'my-plugin' ); ?></label>
					</th>
					<td>
						<label class="my-plugin-toggle">
							<input type="checkbox" id="enable_feature" name="enable_feature" value="1"
								<?php checked( 1, $settings['enable_feature'] ?? 0 ); ?> />
							<span class="my-plugin-toggle-slider"></span>
						</label>
						<p class="description"><?php esc_html_e( 'Enable the main feature.', 'my-plugin' ); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="plugin_mode"><?php esc_html_e( 'Plugin Mode', 'my-plugin' ); ?></label>
					</th>
					<td>
						<select id="plugin_mode" name="plugin_mode" class="regular-text">
							<option value="basic" <?php selected( 'basic', $settings['plugin_mode'] ?? 'basic' ); ?>>
								<?php esc_html_e( 'Basic', 'my-plugin' ); ?>
							</option>
							<option value="advanced" <?php selected( 'advanced', $settings['plugin_mode'] ?? '' ); ?>>
								<?php esc_html_e( 'Advanced', 'my-plugin' ); ?>
							</option>
							<option value="pro" <?php selected( 'pro', $settings['plugin_mode'] ?? '' ); ?>>
								<?php esc_html_e( 'Professional', 'my-plugin' ); ?>
							</option>
						</select>
						<p class="description"><?php esc_html_e( 'Select the plugin operation mode.', 'my-plugin' ); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="cache_duration"><?php esc_html_e( 'Cache Duration', 'my-plugin' ); ?></label>
					</th>
					<td>
						<input type="number" id="cache_duration" name="cache_duration"
							value="<?php echo esc_attr( $settings['cache_duration'] ?? 3600 ); ?>"
							class="small-text" min="0" step="60" />
						<span class="description"><?php esc_html_e( 'seconds', 'my-plugin' ); ?></span>
						<p class="description"><?php esc_html_e( 'How long to cache data (0 to disable).', 'my-plugin' ); ?></p>
					</td>
				</tr>
			</table>
		</div>
		<?php
	}
}
