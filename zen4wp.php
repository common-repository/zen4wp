<?php
// ----------------------------------------------------------------------------
// Copyright (c) 2013-2016, Vinos de Frutas Tropicales (lat9@vinosdefrutastropicales.com)
// Part of the Zen Cart for WordPress (zen4wp) series
// ----------------------------------------------------------------------------
// This module represents the main, common functionality to support the Zen Cart widgets
// for WordPress.  It must be 'require_once'd by each zen4wp widget to provide the
// initialization required to link the Zen Cart store's database information to the
// WordPress installation.
// -----
// Pull in the configuration-specific values.
//
require ('zen4wp_config.php');

define('ZEN4WP_VERSION', '1.4.0');

// -----
// Folder-related paths.
//
define('ZEN_DIR_WS_IMAGES', ZEN_HTTP_SERVER . ZEN_DIR_WS_CATALOG . 'images/');  /*v1.0.3c*/
define('ZEN_DIR_WS_TEMPLATES', ZEN_HTTP_SERVER . ZEN_DIR_WS_CATALOG . 'includes/templates/');  /*v1.0.3c*/

// -----
// Database tables used.
//
define('ZEN_TABLE_CATEGORIES', ZEN_DB_PREFIX . 'categories');
define('ZEN_TABLE_CATEGORIES_DESCRIPTION', ZEN_DB_PREFIX . 'categories_description');
define('ZEN_TABLE_CONFIGURATION', ZEN_DB_PREFIX . 'configuration');
define('ZEN_TABLE_CURRENCIES', ZEN_DB_PREFIX . 'currencies');
define('ZEN_TABLE_FEATURED', ZEN_DB_PREFIX . 'featured');
define('ZEN_TABLE_GEO_ZONES', ZEN_DB_PREFIX . 'geo_zones');
define('ZEN_TABLE_HIDE_CATEGORIES', ZEN_DB_PREFIX . 'hide_categories');
define('ZEN_TABLE_MANUFACTURERS', ZEN_DB_PREFIX . 'manufacturers');
define('ZEN_TABLE_PRODUCT_TYPES', ZEN_DB_PREFIX . 'product_types');
define('ZEN_TABLE_PRODUCTS', ZEN_DB_PREFIX . 'products');
define('ZEN_TABLE_PRODUCTS_ATTRIBUTES', ZEN_DB_PREFIX . 'products_attributes');
define('ZEN_TABLE_PRODUCTS_DESCRIPTION', ZEN_DB_PREFIX . 'products_description');
define('ZEN_TABLE_PRODUCTS_OPTIONS', ZEN_DB_PREFIX . 'products_options');
define('ZEN_TABLE_PRODUCTS_TO_CATEGORIES', ZEN_DB_PREFIX . 'products_to_categories');
define('ZEN_TABLE_PRODUCT_TYPE_LAYOUT', ZEN_DB_PREFIX . 'product_type_layout');
define('ZEN_TABLE_REVIEWS', ZEN_DB_PREFIX . 'reviews');
define('ZEN_TABLE_REVIEWS_DESCRIPTION', ZEN_DB_PREFIX . 'reviews_description');
define('ZEN_TABLE_SALEMAKER_SALES', ZEN_DB_PREFIX . 'salemaker_sales');
define('ZEN_TABLE_SPECIALS', ZEN_DB_PREFIX . 'specials');
define('ZEN_TABLE_TAX_RATES', ZEN_DB_PREFIX . 'tax_rates');
define('ZEN_TABLE_TEMPLATE_SELECT', ZEN_DB_PREFIX . 'template_select');
define('ZEN_TABLE_TESTIMONIALS_MANAGER', ZEN_DB_PREFIX . 'testimonials_manager');
define('ZEN_TABLE_ZONES_TO_GEO_ZONES', ZEN_DB_PREFIX . 'zones_to_geo_zones');

// -----
// Pull in the configuration settings from the Zen Cart portion of the database.  This file contains a
// list of all the configuration keys that are used within the zen4wp implementation; only those keys' values
// are loaded from the database!
//
require('zen4wp_init_configuration.php');

// -----
// Pull in all the 'helper' functions; they're named to reflect the Zen Cart module from which they're derived.
//
require('zen4wp_functions_general.php');
require('zen4wp_functions_categories.php');
require('zen4wp_functions_lookups.php');
require('zen4wp_functions_prices.php');
require('zen4wp_functions_taxes.php');
require('zen4wp_html_output.php');
if (file_exists (realpath(dirname(__FILE__) . '/../../plugins/zen4wp-premium') . '/zen4wp_cookie_handler.php')) {
  require (realpath(dirname(__FILE__) . '/../../plugins/zen4wp-premium') . '/zen4wp_cookie_handler.php'); /*v1.3.2*/
  
}

// -----
// Initialize the active Zen Cart template; this creates the global variable $zen4wp_template_select.
//
require('zen4wp_init_templates.php');

// -----
// Set the default currency for the session to the store's default currency, if it's not already set, then
// instantiate the currency class.
//
if (!isset($_SESSION['currency'])) {
  $_SESSION['currency'] = DEFAULT_CURRENCY;
}
require('zen4wp_currencies.php');
$currencies = new zen4wp_currencies();

// -----
// Common, Zen Cart-based constants, used in various functions.
//
if (!defined('TEXT_BASE_PRICE')) {
	define ('TEXT_BASE_PRICE', 'Starting at: ');
}
if (!defined('MORE_INFO_TEXT')) {
	define ('MORE_INFO_TEXT', '... more info');
}
if (!defined('BUTTON_CART_ADD_ALT')) {
	define ('BUTTON_CART_ADD_ALT', 'Add to Cart');
}
if (!defined('BUTTON_BUY_NOW_ALT')) {
	define ('BUTTON_BUY_NOW_ALT', 'Buy Now');
}
if (!defined('TEXT_PRODUCT_FREE_SHIPPING_ICON')) {
	define ('TEXT_PRODUCT_FREE_SHIPPING_ICON', zen4wp_image (ZEN_DIR_WS_TEMPLATE_IMAGES . 'always-free-shipping.gif', 'Always Free Shipping')); // for an image or comment out to use another 
}
if (!defined('TEXT_ONETIME_CHARGE_SYMBOL')) {
	define ('TEXT_ONETIME_CHARGE_SYMBOL', ' *');
}
if (!defined('TEXT_ONETIME_CHARGE_DESCRIPTION')) {
	define ('TEXT_ONETIME_CHARGE_DESCRIPTION', ' One time charges may apply');
}
if (!defined('TEXT_SHOWCASE_ONLY')) {
	define ('TEXT_SHOWCASE_ONLY', 'Contact Us');
}
if (!defined('TEXT_LOGIN_FOR_PRICE_PRICE')) {
	define ('TEXT_LOGIN_FOR_PRICE_PRICE', 'Price Unavailable');
}
if (!defined('TEXT_LOGIN_FOR_PRICE_BUTTON_REPLACE')) {
	define ('TEXT_LOGIN_FOR_PRICE_BUTTON_REPLACE', 'Login for price');
}
if (!defined('TEXT_LOGIN_FOR_PRICE_BUTTON_REPLACE_SHOWROOM')) {
	define ('TEXT_LOGIN_FOR_PRICE_BUTTON_REPLACE_SHOWROOM', 'Show Room Only');
}
if (!defined('TEXT_AUTHORIZATION_PENDING_BUTTON_REPLACE')) {
	define ('TEXT_AUTHORIZATION_PENDING_BUTTON_REPLACE', 'APPROVAL PENDING');
}
if (!defined('TEXT_LOGIN_TO_SHOP_BUTTON_REPLACE')) {
	define ('TEXT_LOGIN_TO_SHOP_BUTTON_REPLACE', 'Login to Shop');
}
if (!defined('TEXT_CALL_FOR_PRICE')) {
	define ('TEXT_CALL_FOR_PRICE', 'Call for price');
}
if (!defined('TEXT_SOLD_OUT')) {
	define ('TEXT_SOLD_OUT', 'Sold Out');
}
if (!defined('PRODUCTS_QUANTITY_MAX_TEXT_LISTING')) {
	define ('PRODUCTS_QUANTITY_MAX_TEXT_LISTING', 'Max: ');
}
if (!defined('PRODUCTS_QUANTITY_MIN_TEXT_LISTING')) {
	define ('PRODUCTS_QUANTITY_MIN_TEXT_LISTING', 'Min: ');
}
if (!defined('PRODUCTS_QUANTITY_UNIT_TEXT_LISTING')) {
	define ('PRODUCTS_QUANTITY_UNIT_TEXT_LISTING', 'Units: ');
}

// -----
// This function returns the current status of the zen4wp cookie-handler, which coordinates communications
// between the Zen Cart and WordPress sessions.
//
function zen4wp_cookie_status() {
  return function_exists ('zen4wp_cookie_configuration') ? zen4wp_cookie_configuration() : false;
  
}

// -----
// This function, used by the Featured, Special and New Products widgets, creates the random count of items,
// displayed in a programmable-sized grid.
//
function zen4wp_display_random($sql, $number_to_show, $number_of_columns, $class_name) {
  global $wpdb;
  $content = '';
  if (((int)$number_to_show) > 0) {
    $sql .= " ORDER BY RAND() LIMIT $number_to_show";
    $sql_results = $wpdb->get_results($sql, ARRAY_A);
    if (is_array($sql_results) && sizeof($sql_results) > 0) {
      $number_of_columns = (((int)$number_of_columns) > 0) ? (int)$number_of_columns : 1;
      $column_width = 100 / $number_of_columns;
      
      $content = '<div class="' . $class_name . '">';
      $display_count = 0;
      foreach ($sql_results as $current_item) {
        if (($display_count % $number_of_columns) == 0) {
          if ($display_count != 0) {
            $content .= '</div>';
          }
          $content .= '<div class="displayRandomRow">';
        }
        $content .= '<div class="displayRandomColumnOuter" style="width: ' . $column_width . '%;">' . "\n" . '<div class="displayRandomColumnInner">';

        $content .=  '<a href="' . zen4wp_href_link(zen4wp_get_info_page($current_item['products_id']), 'cPath=' . zen4wp_get_generated_category_path_rev($current_item['master_categories_id']) . '&products_id=' . $current_item['products_id']) . '">' . zen4wp_image(ZEN_DIR_WS_IMAGES . $current_item['products_image'], $current_item['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT);
        $content .= '<br />' . $current_item['products_name'] . '</a>';
        $content .= '<div>' . zen4wp_get_products_display_price($current_item['products_id']) . '</div>';
        $content .= '</div></div>';

        $display_count++;
      }
      $content .= '</div></div>';
    }
  }
  
  return $content;

}
//-bof-v1.0.1a-lat9

// From the article http://www.wprecipes.com/how-to-show-an-urgent-message-in-the-wordpress-admin-area
/**
 * Generic function to show a message to the user using WP's 
 * standard CSS classes to make use of the already-defined
 * message colour scheme.
 *
 * @param $message The message you want to tell the user.
 * @param $errormsg If true, the message is an error, so use 
 * the red message style. If false, the message is a status 
  * message, so use the yellow information message style.
 */
function zen4wp_show_message($message, $errormsg = false)
{
  if ($errormsg) {
    echo '<div id="message" class="error">';
  }
  else {
    echo '<div id="message" class="updated fade">';
  }

  echo "<p><strong>$message</strong></p></div>";
}   
//-eof-v1.0.1a-lat9
