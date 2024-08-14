<?php
/**
 * Plugin Name: Test Plugin
 * Plugin URI:  https://welabs.dev
 * Description: Custom plugin by weLabs
 * Version: 0.0.1
 * Author: WeLabs
 * Author URI: https://welabs.dev
 * Text Domain: test-plugin
 * WC requires at least: 5.0.0
 * Domain Path: /languages/
 * License: GPL2
 */
use WeLabs\TestPlugin\TestPlugin;

// don't call the file directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! defined( 'TEST_PLUGIN_FILE' ) ) {
    define( 'TEST_PLUGIN_FILE', __FILE__ );
}

require_once __DIR__ . '/vendor/autoload.php';

/**
 * Load Test_Plugin Plugin when all plugins loaded
 *
 * @return \WeLabs\TestPlugin\TestPlugin
 */
function welabs_test_plugin() {
    return TestPlugin::init();
}

// Lets Go....
welabs_test_plugin();
