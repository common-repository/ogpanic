<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://ogpanic.com
 * @since      1.0.5
 *
 * @package    Og_Panic
 * @subpackage Og_Panic/admin/partials
 */
?>

<?php
$options = array('shibuya', 'shinagawa', 'aoyama', 'asakusa', 'yoyogi', 'meguro');
$logo_id = esc_attr(get_option('ogpanic_logo_id'));
if ($logo_url = wp_get_attachment_image_src($logo_id, 'medium')) {
  $logo_url = $logo_url[0];
}
if (!$logo_id || !$logo_url) {
  $logo_url = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';
}
$intro = __('<a href="https://ogpanic.com" target="_blank">OGPanic</a> is an easy-to-use web service for generating beautiful og-images. OGPanic is still in beta test, go to the <a href="https://ogpanic.com">website</a> to get invitation.', 'og-panic');

$visuals = array('nl', 'na', 'wc');
$visual_desc = array(
  'nl' => __('Hide site logo', 'og-panic'),
  'na' => __('Hide author of the post', 'og-panic'),
  'wc' => __('Show category name of the post', 'og-panic')
);
$visual_ops = array_map('esc_attr', get_option('ogpanic_visual', array()));

$preview_url = 'https://demo-nov7vew.ogpanic.com/og/{template}/cashless-32752.jpeg';
$preview_args = urlencode(implode(' ', $visual_ops));
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap">
  <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
  <p><?php echo $intro; ?></p>
  <form method="post" action="<?php echo esc_html(admin_url('admin-post.php')); ?>">
    <table class="form-table" role="presentation">
      <tbody>
        <tr>
          <th scope="row"><label for="ogpanic_endpoint"><?php echo __('Endpoint URL', 'og-panic'); ?></label></th>
          <td><input name="ogpanic_endpoint" type="text" id="ogpanic_endpoint" class="regular-text ltr" value="<?php echo esc_url(get_option('ogpanic_endpoint')); ?>"></td>
        </tr>
        <tr>
          <th scope="row"><label for="ogpanic_api_token"><?php echo __('API Token', 'og-panic'); ?></label></th>
          <td><input name="ogpanic_api_token" type="text" id="ogpanic_api_token" class="regular-text ltr" value="<?php echo get_option('ogpanic_api_token'); ?>"></td>
        </tr>
        <tr>
          <th scope="row"><label for="ogpanic_theme"><?php _e('Theme', 'og-panic'); ?></label></th>
          <td class="ogpanic-theme-selector">
            <?php
            foreach ($options as $o) {
              echo '<input type="radio" id="check-' . $o . '" name="ogpanic_theme" value="' . $o .
                '" ' . (get_option('ogpanic_theme') === $o ? 'checked' : '') . ' /><label for="check-' . $o . '">' . ucfirst($o) .
                '<img src="' . str_replace('{template}', $o, $preview_url . '?v=' . $preview_args) . '"/>' .
                '</label>';
            }
            ?>
          </td>
        </tr>
        <tr>
          <th scope="row"><label for="og"><?php echo __('Visual Options', 'og-panic'); ?></label></th>
          <td>
            <fieldset id="ogpanic-visual-options">
              <legend class="screen-reader-text"><span>Other visual settings</span></legend>
              <?php
              foreach ($visuals as $v) {
                echo '<label for="show_' . $v . '">' .
                  '<input type="checkbox" name="ogpanic_visual[]" id="show_' . $v .
                  '" value="' . $v . '" ' . (in_array($v, $visual_ops) ? 'checked' : '') . '>' .
                  $visual_desc[$v] .
                  '</label><br>';
              }
              ?>
            </fieldset>
          </td>
        </tr>
        <tr>
          <th scope="row"><label for="ogpanic_logo_url"><?php echo __('Logo Image', 'og-panic'); ?></label></th>
          <td>
            <div class="image-preview-wrapper">
              <img id="image-preview" src="<?php echo $logo_url; ?>" style="max-height: 50px; margin-bottom: 5px;">
            </div>
            <input id="ogpanic_logo_id_button" type="button" class="button" value="<?php _e('Choose Image', 'og-panic'); ?>" />
            <input type="hidden" name="ogpanic_logo_id" id="ogpanic_logo_id" value="<?php echo $logo_id; ?>">
            <p class="description"><?php _e('Your site logo will be used automatically, but you can specify your own for better visial effect.', 'og-panic'); ?></p>
          </td>
        </tr>
        <tr>
          <th><?php _e('Danger Zone', 'og-panic') ?></th>
          <td>
            <label for="ogpanic_upload_all">
              <input type="checkbox" id="ogpanic_upload_all" name="ogpanic_upload_all" value="1">
              <?php _e('Upload all posts\' meta data', 'og-panic') ?>
            </label>
            <p class="description">
              <?php _e('Upload all posts\' title, featured image url and other public infomation to OGPanic. If you have a lot of posts, it takes some time.', 'og-panic') ?>
            </p>
          </td>
        </tr>
      </tbody>
    </table>

    <?php
    wp_nonce_field('ogpanic-settings-save', 'ogpanic-custom-endpoint');
    submit_button();
    ?>
    <input type="hidden" name="_preview_query" value="<?php echo implode(' ', $visual_ops) ?>" />
  </form>
</div>