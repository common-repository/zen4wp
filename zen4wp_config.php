<?php 
// ----------------------------------------------------------------------------
// Copyright (c) 2013-2014, Vinos de Frutas Tropicales (lat9@vinosdefrutastropicales.com)
// Part of the Zen Cart for WordPress (zen4wp) series
// ----------------------------------------------------------------------------
//
// This module contains the configuration settings that should be customized for each installation.
//
add_action('admin_menu', 'add_zen4wp_settings_page'); 

function add_zen4wp_settings_page() {
  add_options_page('Set Zen4Wp Options', 'Set Zen4Wp Options', 'manage_options', 'zen4wp_set_options', 'zen4wp_options_form');
}

function zen4wp_options_form() {
  update_display_zen4wp_options();
}

function update_display_zen4wp_options($update_only = false) {
  $options = array ( 'zen4wp_http_server'                => 'http://localhost',
                     'zen4wp_https_server'               => 'https://localhost',
                     'zen4wp_dir_ws_catalog'             => '/',
                     'zen4wp_dir_ws_https_catalog'       => '/',
                     'zen4wp_enable_ssl'                 => 'false',
                     'zen4wp_db_prefix'                  => '',
                     'zen4wp_dir_fs_catalog'             => '/',
                     'zen4wp_other_image_price_is_free'  => 'free.gif',
                     'zen4wp_other_image_call_for_price' => 'call_for_prices.jpg' );

  if (isset($_POST['zen4wp_http_server'])) {
    foreach ($options as $varname => $default) {
      update_option($varname, $_POST[$varname]);
    }
    
    if (!$update_only) {
?>  
  <div class="updated"><p><strong><?php _e('Your Zen4Wp Options have been saved.' ); ?></strong></p></div>  
<?php
    }
  }
  
  if (!$update_only) {
    // -----
    // Get each variable's current setting, setting the default value if blank.
    //
    foreach ($options as $varname => $default) {
      $$varname = get_option($varname);
      if ($$varname == '') {
        $$varname = $default;
      }
    }
?>
  <div class="wrap">
    <h2><?php echo __( 'Set Zen4Wp Options', 'zen4wp_config' ); ?></h2>
    <form name="zen4wp_config" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
      <h4><?php echo __( 'Set these settings to the value of the like-named entry in your Zen Cart\'s /includes/configure.php file:'); ?></h4>
      <table class="form-table">
       
        <tr>
          <th scope="row"><label for="zen4wp_http_server"><?php _e("HTTP_SERVER: "); ?></label></th>
          <td><input type="text" name="zen4wp_http_server" value="<?php echo $zen4wp_http_server; ?>" size="50"><?php _e(" e.g. http://localhost"); ?></td>
        </tr>
        <tr>
          <th scope="row"><label for="zen4wp_https_server"><?php _e("HTTPS_SERVER: "); ?></label></th>
          <td><input type="text" name="zen4wp_https_server" value="<?php echo $zen4wp_https_server; ?>" size="50"><?php _e(" e.g. https://localhost"); ?></td>
        </tr> 
        <tr>
          <th scope="row"><label for="zen4wp_dir_ws_catalog"><?php _e("DIR_WS_CATALOG: " ); ?></label></th>
          <td><input type="text" name="zen4wp_dir_ws_catalog" value="<?php echo $zen4wp_dir_ws_catalog; ?>" size="20"><?php _e(" e.g. / or /foldername/" ); ?></td>
        </tr>
        <tr>
          <th scope="row"><label for="zen4wp_dir_ws_https_catalog"><?php _e("DIR_WS_HTTPS_CATALOG: " ); ?></label></th>
          <td><input type="text" name="zen4wp_dir_ws_https_catalog" value="<?php echo $zen4wp_dir_ws_https_catalog; ?>" size="20"><?php _e(" e.g. / or /foldername/" ); ?></td>
        </tr>
        <tr>
          <th scope="row"><label for="zen4wp_enable_ssl"><?php _e("ENABLE_SSL: " ); ?></label></th>
          <td><select name="zen4wp_enable_ssl"><option value="true"<?php echo ($zen4wp_enable_ssl == 'true') ? ' selected="selected"' : ''; ?>>true</option><option value="false"<?php echo ($zen4wp_enable_ssl == 'false') ? ' selected="selected"' : ''; ?>>false</option></select><?php _e(" Choose whether your Zen Cart store uses SSL (true) or not (false)." ); ?></td>
        </tr>
        <tr>
          <th scope="row"><label for="zen4wp_db_prefix"><?php _e("DB_PREFIX: "); ?></label></th>
          <td><input type="text" name="zen4wp_db_prefix" value="<?php echo $zen4wp_db_prefix; ?>" size="5"><?php _e(" The prefix value (e.g. zen_) including any underscore or leave this field blank if you don't use a prefix."); ?></td>
        </tr>

        <tr>
          <th scope="row"><label for="zen4wp_dir_fs_catalog"><?php _e("DIR_FS_CATALOG: " ); ?></label></th>
          <td><input type="text" name="zen4wp_dir_fs_catalog" value="<?php echo $zen4wp_dir_fs_catalog; ?>" size="50"><?php _e(" This is the full file-system path to your Zen Cart store." ); ?></td>
        </tr>
      </table>
      <hr />
      <h4><?php echo __( 'Set these settings to the value of the like-named entry in your Zen Cart\'s /includes/languages/english/other_image_names.php file:'); ?></h4>
      <table>
        <tr>
          <th scope="row"><label for="zen4wp_other_image_price_is_free"><?php _e("OTHER_IMAGE_PRICE_IS_FREE: " ); ?></label></th>
          <td><input type="text" name="zen4wp_other_image_price_is_free" value="<?php echo $zen4wp_other_image_price_is_free; ?>" size="20"><?php _e(" Enter the name of the image to display if the price of a product is free." ); ?></td>
        </tr>
        <tr>
          <th scope="row"><label for="zen4wp_other_image_call_for_price"><?php _e("OTHER_IMAGE_CALL_FOR_PRICE: " ); ?></label></th>
          <td><input type="text" name="zen4wp_other_image_call_for_price" value="<?php echo $zen4wp_other_image_call_for_price; ?>" size="20"><?php _e(" Enter the name of the image to display if the price of a product is \"Call for Price\"." ); ?></td>
        </tr>
      </table>

      <p class="submit"><input type="submit" name="Submit" class="button button-primary" value="<?php _e('Update Zen4Wp Options', 'zen4wp_config' ) ?>" /></p>
    </form>
  </div>
  
<?php
  }
}

// -----
// Update any zen4wp options that might have been changed before setting those values for the rest of the widgets to use.
//
update_display_zen4wp_options(true);

// -----
// Create the constants used by the common functions based on the current WordPress option values.
//
define('ZEN_HTTP_SERVER', get_option('zen4wp_http_server'));
define('ZEN_HTTPS_SERVER', get_option('zen4wp_https_server'));
define('ZEN_DIR_WS_CATALOG', get_option('zen4wp_dir_ws_catalog'));
define('ZEN_DIR_WS_HTTPS_CATALOG', get_option('zen4wp_dir_ws_https_catalog'));
define('ZEN_ENABLE_SSL', get_option('zen4wp_enable_ssl'));
define('ZEN_DB_PREFIX', get_option('zen4wp_db_prefix'));
define('ZEN_DIR_FS_CATALOG', get_option('zen4wp_dir_fs_catalog'));
define('ZEN_CHARSET', 'utf-8');
if (!defined('OTHER_IMAGE_PRICE_IS_FREE')) {
	define('OTHER_IMAGE_PRICE_IS_FREE', get_option('zen4wp_other_image_price_is_free'));
}
if (!defined('OTHER_IMAGE_CALL_FOR_PRICE')) {
	define('OTHER_IMAGE_CALL_FOR_PRICE', get_option('zen4wp_other_image_call_for_price'));
}