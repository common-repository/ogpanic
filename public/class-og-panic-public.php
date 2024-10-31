<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://ogpanic.com
 * @since      1.0.5
 *
 * @package    Og_Panic
 * @subpackage Og_Panic/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Og_Panic
 * @subpackage Og_Panic/public
 * @author     Rakuraku Jyo <jyo@ogpanic.com>
 */
class Og_Panic_Public
{

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.5
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.5
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.5
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	public function update_metas($metas)
	{
		global $post;
		$disabled = get_post_meta($post->ID, 'ogpanic_post_meta_disable', true);
		if ($metas['og:type'] === 'article' && $post && !$disabled) {
			$endpoint = esc_url(get_option('ogpanic_endpoint'));
			$visual_keys = array_map('esc_attr', get_option('ogpanic_visual'));
			$visual = urlencode(implode(' ', $visual_keys));
			$theme = esc_attr(get_post_meta($post->ID, 'ogpanic_post_meta_theme', true));
			$global_theme = esc_attr(get_option('ogpanic_theme', 'shibuya'));
			$theme = $theme ? $theme : $global_theme;

			$url = "{$endpoint}/i/{$theme}/{$post->post_name}.jpeg" . ($visual ? "?v={$visual}" : '');
			$metas['og:image'] = $url;
			$metas['og:image:width'] = 1200;
			$metas['og:image:height'] = 630;
			$metas['twitter:image'] = $url;
			$metas['twitter:card'] = 'summary_large_image';
		}

		return $metas;
	}
}
