<?php
/**
 * Dashboard Tab class.
 *
 * @package MyPlugin
 */

namespace ThachPN165\MyPlugin\Admin\Tabs;

defined( 'ABSPATH' ) || exit;

/**
 * DashboardTab class - renders the dashboard tab content.
 */
class DashboardTab {

	/**
	 * Render the dashboard tab.
	 */
	public static function render(): void {
		?>
		<div class="my-plugin-tab-content active" id="tab-dashboard">
			<h2><?php esc_html_e( 'Welcome to My Plugin', 'my-plugin' ); ?></h2>
			<p class="description"><?php esc_html_e( 'A powerful WordPress plugin boilerplate with modern development practices.', 'my-plugin' ); ?></p>

			<?php
			self::render_notices();
			self::render_plugin_info();
			self::render_usage_guides();
			?>
		</div>
		<?php
	}

	/**
	 * Render dashboard notices/announcements.
	 */
	private static function render_notices(): void {
		$notices = self::get_notices();
		if ( empty( $notices ) ) {
			return;
		}
		?>
		<div class="my-plugin-dashboard-notices">
			<?php foreach ( $notices as $notice ) : ?>
				<div class="my-plugin-notice <?php echo esc_attr( $notice['type'] ); ?>">
					<span class="dashicons dashicons-<?php echo esc_attr( $notice['icon'] ); ?>"></span>
					<div class="my-plugin-notice-content">
						<strong><?php echo esc_html( $notice['title'] ); ?></strong>
						<p><?php echo esc_html( $notice['message'] ); ?></p>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
		<?php
	}

	/**
	 * Get dashboard notices.
	 *
	 * @return array Notices array.
	 */
	private static function get_notices(): array {
		$notices  = array();
		$settings = get_option( 'my_plugin_settings', array() );

		if ( empty( $settings['enable_feature'] ) ) {
			$notices[] = array(
				'type'    => 'info',
				'icon'    => 'info',
				'title'   => __( 'Getting Started', 'my-plugin' ),
				'message' => __( 'Enable the main feature in General settings to get started.', 'my-plugin' ),
			);
		}

		return $notices;
	}

	/**
	 * Render plugin info section.
	 */
	private static function render_plugin_info(): void {
		?>
		<div class="my-plugin-info-box">
			<h3><?php esc_html_e( 'Plugin Information', 'my-plugin' ); ?></h3>
			<ul class="my-plugin-info-list">
				<li>
					<span class="label"><?php esc_html_e( 'Version', 'my-plugin' ); ?></span>
					<span class="value"><?php echo esc_html( MY_PLUGIN_VERSION ); ?></span>
				</li>
				<li>
					<span class="label"><?php esc_html_e( 'PHP Version', 'my-plugin' ); ?></span>
					<span class="value"><?php echo esc_html( PHP_VERSION ); ?></span>
				</li>
				<li>
					<span class="label"><?php esc_html_e( 'WordPress Version', 'my-plugin' ); ?></span>
					<span class="value"><?php echo esc_html( get_bloginfo( 'version' ) ); ?></span>
				</li>
			</ul>
		</div>
		<?php
	}

	/**
	 * Render usage guides accordions.
	 */
	private static function render_usage_guides(): void {
		$guides = self::get_usage_guides();
		?>
		<div class="my-plugin-guides">
			<h3><?php esc_html_e( 'Usage Guides', 'my-plugin' ); ?></h3>
			<div class="my-plugin-accordion">
				<?php foreach ( $guides as $guide ) : ?>
					<div class="my-plugin-accordion-item">
						<button type="button" class="my-plugin-accordion-header">
							<span class="dashicons dashicons-<?php echo esc_attr( $guide['icon'] ); ?>"></span>
							<span class="title"><?php echo esc_html( $guide['title'] ); ?></span>
							<span class="dashicons dashicons-arrow-down-alt2 toggle-icon"></span>
						</button>
						<div class="my-plugin-accordion-content">
							<?php echo wp_kses_post( $guide['content'] ); ?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Get usage guides content.
	 *
	 * @return array Guides array.
	 */
	private static function get_usage_guides(): array {
		return array(
			array(
				'icon'    => 'book',
				'title'   => __( 'Quick Start Guide', 'my-plugin' ),
				'content' => '<ol>
					<li>' . esc_html__( 'Go to General tab and enable the main feature.', 'my-plugin' ) . '</li>
					<li>' . esc_html__( 'Configure your preferred settings.', 'my-plugin' ) . '</li>
					<li>' . esc_html__( 'Click Save Settings to apply changes.', 'my-plugin' ) . '</li>
				</ol>',
			),
			array(
				'icon'    => 'admin-generic',
				'title'   => __( 'Advanced Configuration', 'my-plugin' ),
				'content' => '<p>' . esc_html__( 'The Advanced tab provides options for power users:', 'my-plugin' ) . '</p>
				<ul>
					<li><strong>' . esc_html__( 'API Key', 'my-plugin' ) . '</strong> - ' . esc_html__( 'Required for external service integration.', 'my-plugin' ) . '</li>
					<li><strong>' . esc_html__( 'Debug Mode', 'my-plugin' ) . '</strong> - ' . esc_html__( 'Enable logging for troubleshooting.', 'my-plugin' ) . '</li>
					<li><strong>' . esc_html__( 'Custom CSS', 'my-plugin' ) . '</strong> - ' . esc_html__( 'Add your own styling.', 'my-plugin' ) . '</li>
				</ul>',
			),
			array(
				'icon'    => 'admin-plugins',
				'title'   => __( 'Third-party Integrations', 'my-plugin' ),
				'content' => '<p>' . esc_html__( 'Connect with external services via the Integrations tab:', 'my-plugin' ) . '</p>
				<ul>
					<li><strong>' . esc_html__( 'Analytics', 'my-plugin' ) . '</strong> - ' . esc_html__( 'Track usage and performance metrics.', 'my-plugin' ) . '</li>
					<li><strong>' . esc_html__( 'Webhooks', 'my-plugin' ) . '</strong> - ' . esc_html__( 'Receive real-time notifications.', 'my-plugin' ) . '</li>
				</ul>',
			),
		);
	}
}
