<?php
/**
 * Plugin Unit Tests.
 *
 * @package MyPlugin\Tests\Unit
 */

namespace ThachPN165\MyPlugin\Tests\Unit;

use PHPUnit\Framework\TestCase;
use ThachPN165\MyPlugin\Plugin;

/**
 * Test Plugin class.
 */
class PluginTest extends TestCase {

	/**
	 * Test singleton returns same instance.
	 */
	public function test_singleton_returns_same_instance(): void {
		$instance1 = Plugin::instance();
		$instance2 = Plugin::instance();

		$this->assertSame( $instance1, $instance2 );
	}

	/**
	 * Test plugin constants are defined.
	 */
	public function test_plugin_constants_defined(): void {
		$this->assertTrue( defined( 'MY_PLUGIN_VERSION' ) );
		$this->assertTrue( defined( 'MY_PLUGIN_PATH' ) );
		$this->assertTrue( defined( 'MY_PLUGIN_URL' ) );
	}

	/**
	 * Test plugin version is valid semver.
	 */
	public function test_plugin_version_is_valid(): void {
		$this->assertMatchesRegularExpression( '/^\d+\.\d+\.\d+$/', MY_PLUGIN_VERSION );
	}
}
