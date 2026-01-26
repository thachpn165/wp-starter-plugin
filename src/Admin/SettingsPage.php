<?php
/**
 * Settings Page class.
 *
 * @package MyPlugin
 */

namespace ThachPN165\MyPlugin\Admin;

use ThachPN165\MyPlugin\Admin\Tabs\DashboardTab;
use ThachPN165\MyPlugin\Admin\Tabs\GeneralTab;
use ThachPN165\MyPlugin\Admin\Tabs\AdvancedTab;
use ThachPN165\MyPlugin\Admin\Tabs\IntegrationsTab;

defined( 'ABSPATH' ) || exit;

/**
 * SettingsPage class - renders the settings page with tabbed layout.
 */
class SettingsPage {

	/**
	 * Render the settings page.
	 */
	public static function render(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have permission to access this page.', 'my-plugin' ) );
		}

		// Add frame-busting headers to prevent clickjacking.
		if ( ! headers_sent() ) {
			header( 'X-Frame-Options: SAMEORIGIN' );
			header( 'Content-Security-Policy: frame-ancestors \'self\'' );
		}

		$settings = get_option( 'my_plugin_settings', array() );
		$tabs     = self::get_tabs();
		?>
		<div class="wrap my-plugin-settings-wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

			<!-- Toast notification container -->
			<div class="my-plugin-toast" id="my-plugin-toast"></div>

			<div class="my-plugin-settings-container">
				<?php self::render_sidebar( $tabs ); ?>
				<?php self::render_content( $settings ); ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Get tabs configuration.
	 *
	 * @return array Tabs configuration.
	 */
	private static function get_tabs(): array {
		return array(
			'dashboard'    => array(
				'label' => __( 'Dashboard', 'my-plugin' ),
				'icon'  => 'dashicons-dashboard',
			),
			'general'      => array(
				'label' => __( 'General', 'my-plugin' ),
				'icon'  => 'dashicons-admin-settings',
			),
			'advanced'     => array(
				'label' => __( 'Advanced', 'my-plugin' ),
				'icon'  => 'dashicons-admin-tools',
			),
			'integrations' => array(
				'label' => __( 'Integrations', 'my-plugin' ),
				'icon'  => 'dashicons-admin-plugins',
			),
		);
	}

	/**
	 * Render sidebar with tabs.
	 *
	 * @param array $tabs Tabs configuration.
	 */
	private static function render_sidebar( array $tabs ): void {
		?>
		<div class="my-plugin-sidebar">
			<ul class="my-plugin-tabs">
				<?php
				$first = true;
				foreach ( $tabs as $tab_id => $tab ) :
					?>
					<li data-tab="<?php echo esc_attr( $tab_id ); ?>" class="<?php echo $first ? 'active' : ''; ?>">
						<span class="dashicons <?php echo esc_attr( $tab['icon'] ); ?>"></span>
						<?php echo esc_html( $tab['label'] ); ?>
					</li>
					<?php
					$first = false;
				endforeach;
				?>
			</ul>
		</div>
		<?php
	}

	/**
	 * Render content area with tab panels.
	 *
	 * @param array $settings Current settings.
	 */
	private static function render_content( array $settings ): void {
		?>
		<div class="my-plugin-content">
			<?php DashboardTab::render(); ?>

			<form id="my-plugin-settings-form">
				<?php wp_nonce_field( 'my_plugin_save_settings', 'my_plugin_nonce' ); ?>

				<?php GeneralTab::render( $settings ); ?>
				<?php AdvancedTab::render( $settings ); ?>
				<?php IntegrationsTab::render( $settings ); ?>

				<div class="my-plugin-form-actions">
					<button type="submit" class="button button-primary my-plugin-save-btn">
						<span class="my-plugin-save-text"><?php esc_html_e( 'Save Settings', 'my-plugin' ); ?></span>
						<span class="my-plugin-save-loading spinner"></span>
					</button>
				</div>
			</form>
		</div>
		<?php
	}
}
