<?php
/**
 * functions_categories.php
 *
 * @package functions
 * @copyright Copyright 2003-2009 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: functions_categories.php 14141 2009-08-10 19:34:47Z wilt $
 */
// -----
// Adapted for use with Zen Cart for WordPress (zen4wp) series
// Copyright (c) 2013-2014, Vinos de Frutas Tropicales (lat9@vinosdefrutastropicales.com)
// -----

// -----
// Based on zen_get_generated_category_path_rev (from functions_categories.php)
//
function zen4wp_get_generated_category_path_rev($this_categories_id) {
  $categories = array();
  zen4wp_get_parent_categories($categories, $this_categories_id);

  $categories = array_reverse($categories);

  $categories_imploded = implode('_', $categories);

  if (zen4wp_not_null($categories_imploded)) $categories_imploded .= '_';
  $categories_imploded .= $this_categories_id;

  return $categories_imploded;
}

// -----
// Based on zen_get_parent_categories (from functions_categories.php).
//
function zen4wp_get_parent_categories(&$categories, $categories_id) {
  global $wpdb;
  $parent_categories_query = "SELECT parent_id
                              FROM " . ZEN_TABLE_CATEGORIES . "
                              WHERE categories_id = '" . (int)$categories_id . "'";

  $parent_categories = $wpdb->get_results($parent_categories_query, ARRAY_A);

  for ($i = 0, $n = sizeof($parent_categories); $i < $n; $i++) {
    if ($parent_categories[$i]['parent_id'] == 0) return true;
    $categories[sizeof($categories)] = $parent_categories[$i]['parent_id'];
    if ($parent_categories[$i]['parent_id'] != $categories_id) {
      zen4wp_get_parent_categories($categories, $parent_categories[$i]['parent_id']);
    }
  }
}

////
// Return the number of products in a category
// TABLES: products, products_to_categories, categories
  function zen4wp_count_products_in_category($category_id /*, $include_inactive = false*/) {
    global $wpdb, $hide_categories_installed;
    $products_count = 0;
/*
    if ($include_inactive == true) {
      $products_query = "select count(*) as total
                         from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c
                         where p.products_id = p2c.products_id
                         and p2c.categories_id = '" . (int)$category_id . "'";

    } else {
*/
      $hc_from = ($hide_categories_installed) ? (', ' . ZEN_TABLE_HIDE_CATEGORIES . ' hc ') : '';
      $hc_and = ($hide_categories_installed) ? (' AND hc.categories_id = c.categories_id AND hc.visibility_status < 1 ') : '';
      $products_query = "select count(*) as total
                         from " . ZEN_TABLE_PRODUCTS . " p, " . ZEN_TABLE_PRODUCTS_TO_CATEGORIES . " p2c" . $hc_from . "
                         where p.products_id = p2c.products_id
                         and p.products_status = '1' 
                         and p2c.categories_id = '" . (int)$category_id . "'" . $hc_and;
/*
    }
*/
    $products = $wpdb->get_row($products_query, ARRAY_A);
    $products_count += $products['total'];

    $child_categories_query = "select categories_id
                               from " . ZEN_TABLE_CATEGORIES . $hc_from . "
                               where parent_id = '" . (int)$category_id . "'" . $hc_and;

    $child_categories = $wpdb->get_results($child_categories_query, ARRAY_A);

    if( is_array( $child_categories ) && count( $child_categories ) > 0 ) {
      foreach( $child_categories as $current_child ) {
        $products_count += zen4wp_count_products_in_category($current_child['categories_id'] /*, $include_inactive*/ );
      }
    }

    return $products_count;
  }