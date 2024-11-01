<?php
/**
 * functions_lookups.php
 * Lookup Functions for various Zen Cart activities such as countries, prices, products, product types, etc
 *
 * @package functions
 * @copyright Copyright 2003-2011 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: functions_lookups.php 19352 2011-08-19 16:13:43Z ajeh $
 */
// -----
// Adapted for use with Zen Cart for WordPress (zen4wp) series
// Copyright (c) 2013-2014, Vinos de Frutas Tropicales (lat9@vinosdefrutastropicales.com)
// -----

/*
 *  Check if product has attributes
 */
  function zen4wp_has_product_attributes ($products_id, $not_readonly = 'true') {
    global $wpdb;
    $products_id = (int)$products_id;

    if (PRODUCTS_OPTIONS_TYPE_READONLY_IGNORED == '1' and $not_readonly == 'true') {
      // don't include READONLY attributes to determin if attributes must be selected to add to cart
      $attributes_query = "SELECT pa.products_attributes_id
                           FROM " . ZEN_TABLE_PRODUCTS_ATTRIBUTES . " pa left join " . ZEN_TABLE_PRODUCTS_OPTIONS . " po on pa.options_id = po.products_options_id
                           where pa.products_id = $products_id and po.products_options_type != '" . PRODUCTS_OPTIONS_TYPE_READONLY . "' LIMIT 1";
    } else {
      // regardless of READONLY attributes no add to cart buttons
      $attributes_query = "SELECT pa.products_attributes_id
                           FROM " . ZEN_TABLE_PRODUCTS_ATTRIBUTES . " pa
                           where pa.products_id = $products_id LIMIT 1";
    }

    $attributes = $wpdb->get_row ($attributes_query, ARRAY_A);

    return ($attributes != NULL);
    
  }
  // -----
  // Based on zen_get_info_page (from functions_lookups.php)
  //
  function zen4wp_get_info_page($product_id) {
    global $wpdb;
    $product_id = (int)$product_id;
    $page_type = 'product_info';
    
    $info_page = $wpdb->get_row ("SELECT pt.type_handler FROM " . ZEN_TABLE_PRODUCTS . " p, " . ZEN_TABLE_PRODUCT_TYPES . " pt
                                   WHERE p.products_id = $product_id
                                     AND pt.type_id = p.products_type LIMIT 1", ARRAY_A);
    if ($info_page != NULL) {
      $page_type = $info_page['type_handler'] . '_info';
      
    }

    return $page_type;
    
  }
  
  // -----
  // Functions like the similar-named function in the Zen Cart /includes/functions/functions_lookups.php.
  //
  function zen4wp_get_show_product_switch ($products_id, $field, $suffix = 'SHOW_', $prefix = '_INFO', $field_prefix = '_', $field_suffix = '') {
    global $wpdb;
    $show_key = $wpdb->get_row ("SELECT pt.type_handler FROM " . ZEN_TABLE_PRODUCTS . " p, " . ZEN_TABLE_PRODUCT_TYPES . " pt
                                  WHERE p.products_id=$products_id
                                    AND p.products_type = pt.type_id LIMIT 1", ARRAY_A);
                                    
    $product_layout_key = strtoupper ($suffix . $show_key['type_handler'] . $prefix . $field_prefix . $field . $field_suffix);

    $key_value = $wpdb->get_row ("SELECT configuration_value FROM " . ZEN_TABLE_PRODUCT_TYPE_LAYOUT . " WHERE configuration_key='$product_layout_key' LIMIT 1", ARRAY_A);
    if ($key_value != NULL) {
      $return_value = $key_value['configuration_value'];
      
    } else {
      $key_value = $wpdb->get_row ("SELECT configuration_value FROM " . ZEN_TABLE_CONFIGURATION . " WHERE configuration_key='$product_layout_key' LIMIT 1", ARRAY_A);
      if ($key_value != NULL) {
        $return_value = $key_value['configuration_value'];
      } else {
        $return_value = false;
      }
    }
    
    return $return_value;

  }

// build date range for new products
  function zen4wp_get_new_date_range($time_limit = false) {
    if ($time_limit == false) {
      $time_limit = SHOW_NEW_PRODUCTS_LIMIT;
    }
    // 120 days; 24 hours; 60 mins; 60secs
    $date_range = time() - ($time_limit * 24 * 60 * 60);
    $upcoming_mask_range = time();
    $upcoming_mask = date('Ymd', $upcoming_mask_range);

// echo 'Now:      '. date('Y-m-d') ."<br />";
// echo $time_limit . ' Days: '. date('Ymd', $date_range) ."<br />";
    $zc_new_date = date('Ymd', $date_range);
    switch (true) {
    case (SHOW_NEW_PRODUCTS_LIMIT == 0):
      $new_range = '';
      break;
    case (SHOW_NEW_PRODUCTS_LIMIT == 1):
      $zc_new_date = date('Ym', time()) . '01';
      $new_range = ' and p.products_date_added >=' . $zc_new_date;
      break;
    default:
      $new_range = ' and p.products_date_added >=' . $zc_new_date;
    }

    if (SHOW_NEW_PRODUCTS_UPCOMING_MASKED == 0) {
      // do nothing upcoming shows in new
    } else {
      // do not include upcoming in new
      $new_range .= " and (p.products_date_available <=" . $upcoming_mask . " or p.products_date_available IS NULL)";
    }
    return $new_range;
  }
