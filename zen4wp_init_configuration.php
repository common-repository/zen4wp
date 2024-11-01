<?php
// -----
// Copyright (c) 2013 Vinos de Frutas Tropicales (lat9@vinosdefrutastropicales.com)
// License: http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
// -----

// -----
// This module initializes the database configuration constants (i.e. PHP defined values) that are used by the various
// functions that support the Zen Cart widgets for Wordpress
//

// -----
// Check to see if the hide_categories table is present in the database; set a global flag for the widgets' use
//
global $wpdb;  /*v1.3.0a*/
$hc_count = $wpdb->query( "SHOW TABLES like '" . ZEN_TABLE_HIDE_CATEGORIES . "'" );
$hide_categories_installed = ($hc_count > 0) ? true : false;

// -----
// This define contains the list of Zen Cart configuration_keys whose values are used by the processing functions. 
//
define('ZEN_CONFIGURATION_ITEMS', "'PRODUCTS_IMAGE_NO_IMAGE_STATUS', 'PRODUCTS_IMAGE_NO_IMAGE', 'IMAGE_REQUIRED', 'CONFIG_CALCULATE_IMAGE_SIZE', 'PROPORTIONAL_IMAGES_STATUS', 'SMALL_IMAGE_WIDTH', 'SMALL_IMAGE_HEIGHT', 'IMAGE_ROLLOVER_CLASS', 'STORE_STATUS', 'CUSTOMERS_APPROVAL', 'CUSTOMERS_APPROVAL_AUTHORIZATION', 'SHOW_SALE_DISCOUNT_STATUS', 'SHOW_SALE_DISCOUNT', 'SHOW_SALE_DISCOUNT_DECIMALS', 'OTHER_IMAGE_PRICE_IS_FREE_ON', 'OTHER_IMAGE_PRICE_IS_FREE', 'PRODUCTS_PRICE_IS_CALL_IMAGE_ON', 'OTHER_IMAGE_CALL_FOR_PRICE', 'STORE_PRODUCT_TAX_BASIS', 'STORE_ZONE', 'STORE_COUNTRY', 'DEFAULT_CURRENCY', 'DOWN_FOR_MAINTENANCE', 'DOWN_FOR_MAINTENANCE_PRICES_OFF', 'EXCLUDE_ADMIN_IP_FOR_MAINTENANCE', 'DISPLAY_PRICE_WITH_TAX', 'SHOW_NEW_PRODUCTS_LIMIT', 'SHOW_PRODUCTS_SOLD_OUT_IMAGE',  'SHOW_NEW_PRODUCTS_UPCOMING_MASKED', 'ZEN4WP_ENABLED', 'ZEN4WP_DOMAIN', 'ZEN4WP_SECRET', 'SHOW_COUNTS', 'CATEGORIES_COUNT_ZERO', 'CATEGORIES_COUNT_PREFIX', 'CATEGORIES_COUNT_SUFFIX', 'CATEGORIES_SEPARATOR', 'SEARCH_ENGINE_FRIENDLY_URLS', 'PRODUCTS_MANUFACTURERS_STATUS', 'MAX_DISPLAY_MANUFACTURER_NAME_LEN', 'MAX_MANUFACTURERS_LIST', 'MAX_DISPLAY_TESTIMONIALS_MANAGER_TITLES', 'TESTIMONIAL_IMAGE_WIDTH', 'TESTIMONIAL_IMAGE_HEIGHT', 'TESTIMONIALS_MANAGER_DESCRIPTION_LENGTH', 'DISPLAY_ALL_TESTIMONIALS_TESTIMONIALS_MANAGER_LINK', 'DISPLAY_ADD_TESTIMONIAL_LINK', 'DISPLAY_TESTIMONIALS_MANAGER_TRUNCATED_TEXT', 'PRODUCT_LIST_PRICE_BUY_NOW', 'PRODUCTS_OPTIONS_TYPE_READONLY_IGNORED', 'PRODUCTS_OPTIONS_TYPE_READONLY'");  /*v1.3.0c*/

// -----
// Pull the keys and their values from the database and then create PHP define values for each of the configuration keys.
//
$config_key_values = $wpdb->get_results( 'SELECT configuration_key, configuration_value 
                                          FROM ' . ZEN_TABLE_CONFIGURATION . '
                                          WHERE configuration_key IN (' . ZEN_CONFIGURATION_ITEMS . ')', ARRAY_A );
if ( is_array( $config_key_values ) && sizeof( $config_key_values ) != 0 ) {
  foreach ( $config_key_values as $config_info ) {
    if (!defined ($config_info['configuration_key'])) {
      define ( $config_info['configuration_key'], $config_info['configuration_value'] );
      
    }
  }
}

//-bof-v1.0.1a-lat9
// -----
// Do a little error-checking on the configuration ...
//
// 1) If the ZEN_DIR_FS_CATALOG is correct, then we should find /includes/configure.php present.  If not, issue an admin error message.
//
if ( !file_exists( ZEN_DIR_FS_CATALOG . 'includes/configure.php' ) ) {  /*v1.0.3c*/
  add_action( 'admin_notices', 'zen4wp_init_bad_fs' );
  
}
// 2) If none of the configuration keys were found, then there's an issue either with ZEN_DB_PREFIX or the database ...
//
if ( !is_array( $config_key_values ) || sizeof( $config_key_values ) == 0 ) {
  add_action( 'admin_notices', 'zen4wp_database_issue' );
  
}
//-eof-v1.0.1a-lat9

unset($config_key_values);

//-bof-v1.0.1a-lat9
// -----
// Show a message indicating that the ZEN_DIR_FS_CATALOG value is not correct.
//
function zen4wp_init_bad_fs() {
  zen4wp_show_message( 'The value you have specified for ZEN_DIR_FS_CATALOG (' . ZEN_DIR_FS_CATALOG . ') does not reference the root of a Zen Cart installation. Go to <a href="admin.php?page=zen4wp_set_options">Zen4WP Settings Page</a> to check your settings and properly configure Zen4WP.', true ); 

}

// -----
// Show a message indicating that either the ZEN_DB_PREFIX value is not correct or the Zen Cart/Wordpress installations don't share a database.
//
function zen4wp_database_issue() {
  zen4wp_show_message( 'No configuration keys were found in the table <em>' . ZEN_TABLE_CONFIGURATION . '</em>.  Either the value you entered for ZEN_DB_PREFIX on the <a href="admin.php?page=zen4wp_set_options">Zen4WP Settings Page</a> is not correct or Wordpress and Zen Cart do not share the same database (a REQUIRED prerequisite as CLEARLY stated in the Zen4WP readme.txt). Go to <a href="admin.php?page=zen4wp_set_options">Zen4WP Settings Page</a> to check your settings and properly configure Zen4WP.', true );
}
//-eof-v1.0.1a-lat9