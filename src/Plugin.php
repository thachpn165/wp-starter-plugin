<?php
/**
 * Main Plugin class.
 *
 * @package MyPlugin
 */

namespace ThachPN165\MyPlugin;

defined( 'ABSPATH' ) || exit;

use ThachPN165\MyPlugin\Traits\SingletonTrait;
use ThachPN165\MyPlugin\Core\Loader;
use ThachPN165\MyPlugin\Admin\AdminMenu;
use ThachPN165\MyPlugin\PublicSide\Assets;

/**
 * Plugin class - main entry point.
 */
class Plugin {

	use SingletonTrait;

	/**
	 * Hook loader instance.
	 *
	 * @var Loader
	 */
	private Loader $loader;

	/**
	 * Constructor.
	 */
	private function __construct() {
		$this->loader = new Loader();
		$this->define_hooks();
		$this->loader->run();
	}

	/**
	 * Define all hooks.
	 */
	private function define_hooks(): void {
		// Admin hooks.
		if ( is_admin() ) {
			$admin_menu = new AdminMenu();
			$admin_menu->register_hooks();
		}

		// Assets.
		$assets = new Assets();
		$assets->register_hooks();
	}

	/**
	 * Get loader instance.
	 *
	 * @return Loader
	 */
	public function get_loader(): Loader {
		return $this->loader;
	}
}
