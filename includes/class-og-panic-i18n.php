<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://ogpanic.com
 * @since      1.0.5
 *
 * @package    Og_Panic
 * @subpackage Og_Panic/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.5
 * @package    Og_Panic
 * @subpackage Og_Panic/includes
 * @author     Rakuraku Jyo <jyo@ogpanic.com>
 */
class Og_Panic_i18n
{


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.5
	 */
	public function load_plugin_textdomain()
	{
		load_plugin_textdomain(
			'og-panic',
			false,
			dirname(dirname(plugin_basename(__FILE__))) . '/languages/'
		);
	}
}
