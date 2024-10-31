<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://ogpanic.com
 * @since      1.0.5
 *
 * @package    Og_Panic
 * @subpackage Og_Panic/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Og_Panic
 * @subpackage Og_Panic/admin
 * @author     Rakuraku Jyo <jyo@ogpanic.com>
 */
class Og_Panic_Admin
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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.5
	 */
	public function enqueue_styles()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Og_Panic_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Og_Panic_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/og-panic-admin.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.5
	 */
	public function enqueue_scripts()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Og_Panic_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Og_Panic_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_media();

		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/og-panic-admin.js', array('jquery'), $this->version, false);
	}

	public function add_options_page()
	{
		add_options_page(
			__('OGPanic Settings', 'og-panic'),
			__('OGPanic', 'og-panic'),
			'manage_options',
			'ogpanic/admin/partials/og-panic-admin-display.php',
			'',
			10
		);
	}


	public static function create_post_data()
	{
		$data = array();
		$data['site'] = get_bloginfo('name');

		$custom_logo_id = esc_attr(get_option('ogpanic_logo_id'));
		if (!$custom_logo_id) {
			$custom_logo_id = get_theme_mod('custom_logo');
		}
		$image = wp_get_attachment_image_src($custom_logo_id, 'medium');
		if (!empty($image)) {
			$data['site_logo'] = $image[0];
		} else {
			// jin theme
			$image_1 = esc_url(get_theme_mod('topnavi_logo_image_url'));
			$image_2 = esc_url(get_theme_mod('sp_logo_image_url'));
			if ($image_1) {
				$data['site_logo'] = $image_1;
			} else if ($image_2) {
				$data['site_logo'] = $image_2;
			}
		}

		return function ($post) use ($data) {
			$slug = $post->post_name;
			$data['title'] = $post->post_title;
			$categories = wp_get_post_categories($post->ID);
			$data['category'] = ''; // make sure no empty
			if (!empty($categories)) {
				if ($cat = get_category($categories[0])) {
					$data['category'] = $cat->name;
				}
			}

			$data['author'] = get_the_author_meta('display_name', $post->post_author);
			$data['author_avatar'] = get_avatar_url($post->post_author);
			list($src) = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'large');
			if (!empty($src)) {
				$data['image'] = $src;
			}
			return array(
				'id' => $slug,
				'data' => $data,
			);
		};
	}


	/**
	 * Validates the incoming nonce value, verifies the current user has
	 * permission to save the value from the options page and saves the
	 * option to the database.
	 */
	public function save_options()
	{
		// First, validate the nonce and verify the user as permission to save.
		if (!($this->has_valid_nonce() && current_user_can('manage_options'))) {
			// TODO: Display an error message.
		} else {
			// If the above are valid, sanitize and save the option.
			if (key_exists('ogpanic_endpoint', $_POST)) {
				$value = trim(esc_url_raw($_POST['ogpanic_endpoint']));
				update_option('ogpanic_endpoint', $value);
			}

			if (key_exists('ogpanic_api_token', $_POST)) {
				$value = sanitize_text_field(trim($_POST['ogpanic_api_token']));
				update_option('ogpanic_api_token', $value);
			}

			if (key_exists('ogpanic_theme', $_POST)) {
				$value = sanitize_key($_POST['ogpanic_theme']);
				update_option('ogpanic_theme', $value);
			}

			if (key_exists('ogpanic_logo_id', $_POST)) {
				$value = sanitize_option('site_icon', $_POST['ogpanic_logo_id']);
				update_option('ogpanic_logo_id', $value);
			}

			if (key_exists('ogpanic_visual', $_POST)) {
				$value = array_map('sanitize_key', $_POST['ogpanic_visual']);
				update_option('ogpanic_visual', $value);
			} else {
				update_option('ogpanic_visual', array());
			}
			$this->add_admin_message('success', __('Options saved.', 'og-panic'));

			if (key_exists('ogpanic_upload_all', $_POST)) {
				write_log('Uploading all data...');
				$paged = 1;
				$error = false;
				do {
					$query = new WP_Query(array(
						'post_type' => 'post',
						'post_status' => 'publish',
						'posts_per_page' => 200,
						'paged' => $paged,
					));
					$total = $query->max_num_pages;
					$payload = array_map($this->create_post_data(), $query->posts);
					$rv = $this->post_meta($payload);
					if ($rv) {
						write_log("Page {$paged} of {$total} finished");
					} else {
						$text = __('Error happened when sending data to API. Endpoint or Token is not correct.', 'og-panic');
						$this->add_admin_message('error', $text);
						$error = true;
						break;
					}
				} while ($paged++ < $total);
				if (!$error) $this->add_admin_message('success', __('Meta data uploaded.', 'og-panic'));
			}
		}
		wp_safe_redirect(esc_url_raw($_POST['_wp_http_referer']));
	}


	private function get_option_settings_link()
	{
		$page = plugin_basename(plugin_dir_path(__FILE__)) . '/partials/og-panic-admin-display.php';
		return add_query_arg(array('page' => $page), admin_url('options-general.php'));
	}


	public function plugin_add_settings_link($links)
	{
		$settings_link = '<a href="' . $this->get_option_settings_link() . '">' . __('Settings') . '</a>';
		array_push($links, $settings_link);
		return $links;
	}


	private function add_admin_message($type, $text)
	{
		$messages = get_option('ogpanic_admin_messages', array());
		array_push($messages, array("type" => $type, "text" => $text));
		update_option('ogpanic_admin_messages', $messages);
	}


	/**
	 * Determines if the nonce variable associated with the options page is set
	 * and is valid.
	 *
	 * @access private
	 *
	 * @return boolean False if the field isn't set or the nonce value is invalid;
	 *                 otherwise, true.
	 */
	private function has_valid_nonce()
	{

		// If the field isn't even in the $_POST, then it's invalid.
		if (!isset($_POST['ogpanic-custom-endpoint'])) { // Input var okay.
			return false;
		}

		$field  = wp_unslash($_POST['ogpanic-custom-endpoint']);
		$action = 'ogpanic-settings-save';

		return wp_verify_nonce($field, $action);
	}


	public function save_post($post_id, $post, $update)
	{
		if (!$update) return; // save_post got called twice
		if ($post->post_status !== 'publish') return;

		$basic_info_fn = $this->create_post_data();
		$this->post_meta($basic_info_fn($post));
	}


	private function post_meta($data)
	{
		// if is associative array (dict)
		if (array_keys($data) !== range(0, count($data) - 1)) {
			$data = array($data);
		}

		$endpoint = esc_url(get_option('ogpanic_endpoint'));
		$token = esc_attr(get_option('ogpanic_api_token'));
		$url = $endpoint . '/db';
		$body = json_encode($data);

		write_log('Sending to ' . $url . ' with ' . $body);
		$response = wp_remote_post(
			$url,
			array(
				'body' => $body,
				'headers' => array(
					'x-ogaas-token' => $token,
					'Accept-Encoding' => 'gzip, deflate',
					'Content-Type' => 'application/json',
				)
			)
		);
		if (is_wp_error($response)) {
			write_log($response->get_error_message());
			return false;
		} else {
			$code = $response['response']['code'];
			if ($code === 200) {
				return true;
			} else {
				write_log('Response is ' . $code);
				return false;
			}
		}
	}


	public function ogpanic_editor_plugin_register()
	{
		$asset_file = include(plugin_dir_path(__FILE__) . 'build/post-editor.asset.php');
		wp_register_script(
			'og-panic-post-editor',
			plugins_url('build/post-editor.js', __FILE__),
			$asset_file['dependencies'],
			$asset_file['version']
		);

		wp_set_script_translations(
			'og-panic-post-editor',
			'og-panic',
			dirname(plugin_dir_path(__FILE__)) . '/languages'
		);

		register_post_meta('post', 'ogpanic_post_meta_disable', array(
			'type' => 'boolean',
			'single' => true,
			'show_in_rest' => true,
		));

		register_post_meta('post', 'ogpanic_post_meta_theme', array(
			'type' => 'string',
			'single' => true,
			'show_in_rest' => true
		));
	}


	function ogpanic_editor_plugin_script_enqueue()
	{
		wp_enqueue_script('og-panic-post-editor');
	}


	public function admin_notices()
	{
		$messages = get_option('ogpanic_admin_messages', array());
		foreach ($messages as $msg) {
			echo '<div class="notice notice-' . $msg['type'] . ' is-dismissible"><p>' . $msg['text'] . '</p></div>';
		}
		delete_option('ogpanic_admin_messages');
	}


	function admin_child_plugin_notice()
	{
		echo '<div class="error"><p>Sorry, but OGPanic Plugin requires the Open-Graph-Protocol-Framework plugin to be installed and active.</p></div>';
	}


	function child_plugin_has_parent_plugin()
	{
		if (is_admin() && current_user_can('activate_plugins')) {
			if (!is_plugin_active('open-graph-protocol-framework/open-graph-protocol-framework.php')) {
				add_action('admin_notices', array($this, 'admin_child_plugin_notice'));
				deactivate_plugins(plugin_basename('ogpanic/og-panic.php'));

				if (isset($_GET['activate'])) {
					unset($_GET['activate']);
				}
			} else {
				if (get_option('ogpanic_activated')) {
					delete_option('ogpanic_activated');
					if (!headers_sent()) {
						wp_redirect($this->get_option_settings_link());
					}
				}
			}
		}
	}
}
