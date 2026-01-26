<?php
/**
 * Hookable Interface.
 *
 * @package MyPlugin
 */

namespace ThachPN165\MyPlugin\Interfaces;

defined( 'ABSPATH' ) || exit;

/**
 * Interface for classes that register WordPress hooks.
 */
interface HookableInterface {

	/**
	 * Register hooks with WordPress.
	 */
	public function register_hooks(): void;
}
