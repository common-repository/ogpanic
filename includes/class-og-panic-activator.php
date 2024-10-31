<?php

/**
 * Fired during plugin activation
 *
 * @link       https://ogpanic.com
 * @since      1.0.5
 *
 * @package    Og_Panic
 * @subpackage Og_Panic/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.5
 * @package    Og_Panic
 * @subpackage Og_Panic/includes
 * @author     Rakuraku Jyo <jyo@ogpanic.com>
 */
class Og_Panic_Activator
{

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.5
	 */
	public static function activate()
	{
		add_option('ogpanic_activated', 1);
	}
}
