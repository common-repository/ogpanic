<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @wordpress-plugin
 * Plugin Name:       OGPanic
 * Plugin URI:        https://ogpanic.com
 * Description:       OGPanic generates beautiful og-images automatically from your post's title, featured image and etc.
 * Version:           1.0.14
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       og-panic
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('OG_PANIC_VERSION', '1.0.14');


if (!function_exists('write_log')) {
	function write_log($log)
	{
		if (is_array($log) || is_object($log)) {
			error_log(print_r($log, true));
		} else {
			error_log($log);
		}
	}
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-og-panic-activator.php
 */
function activate_og_panic()
{
	require_once plugin_dir_path(__FILE__) . 'includes/class-og-panic-activator.php';
	Og_Panic_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-og-panic-deactivator.php
 */
function deactivate_og_panic()
{
	require_once plugin_dir_path(__FILE__) . 'includes/class-og-panic-deactivator.php';
	Og_Panic_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_og_panic');
register_deactivation_hook(__FILE__, 'deactivate_og_panic');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-og-panic.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.5
 */
function run_og_panic()
{

	$plugin = new Og_Panic();
	$plugin->run();
}
run_og_panic();
