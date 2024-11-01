<?php
/**
 * functions_prices
 *
 * @package functions
 * @copyright Copyright 2003-2011 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: functions_prices.php 18697 2011-05-04 14:35:20Z wilt $
 */
// -----
// Adapted for use with Zen Cart for WordPress (zen4wp) series
// Copyright (c) 2013-2014, Vinos de Frutas Tropicales (lat9@vinosdefrutastropicales.com)
// -----

////
//get specials price or sale price
  function zen4wp_get_products_special_price($product_id, $specials_price_only=false) {
    global $wpdb;
    $product = $wpdb->get_row("SELECT products_price, products_model, products_priced_by_attribute FROM " . ZEN_TABLE_PRODUCTS . " where products_id = '" . (int)$product_id . "'", ARRAY_A);

    if ($product != NULL) {
      $product_price = zen4wp_get_products_base_price($product_id);
    } else {
      return false;
    }

    $specials = $wpdb->get_row("SELECT specials_new_products_price FROM " . ZEN_TABLE_SPECIALS . " where products_id = '" . (int)$product_id . "' AND status = '1'", ARRAY_A);
    if ($specials != NULL) {
      $special_price = $specials['specials_new_products_price'];
    } else {
      $special_price = false;
    }

    if (substr($product['products_model'], 0, 4) == 'GIFT') {    //Never apply a salededuction to Ian Wilson's Giftvouchers
      return (zen4wp_not_null($special_price)) ? $special_price : false;
    }

// return special price only
    if ($specials_price_only == true) {
      return (zen4wp_not_null($special_price)) ? $special_price : false;
      
    } else {
// get sale price
      $product_to_categories = $wpdb->get_row("SELECT master_categories_id FROM " . ZEN_TABLE_PRODUCTS . " WHERE products_id = '" . $product_id . "'", ARRAY_A);  /*v1.3.0c*/
      $category = $product_to_categories['master_categories_id'];

      $sale = $wpdb->get_row("SELECT sale_specials_condition, sale_deduction_value, sale_deduction_type 
                               FROM " . ZEN_TABLE_SALEMAKER_SALES . " 
                               WHERE sale_categories_all LIKE '%," . $category . ",%' 
                               AND sale_status = '1' 
                               AND (sale_date_start <= now() OR sale_date_start = '0001-01-01') 
                               AND (sale_date_end >= now() or sale_date_end = '0001-01-01') 
                               AND (sale_pricerange_from <= '" . $product_price . "' or sale_pricerange_from = '0') 
                               AND (sale_pricerange_to >= '" . $product_price . "' or sale_pricerange_to = '0')", ARRAY_A);
      if ($sale == NULL) {
        return $special_price;
      }

      $tmp_special_price = (!$special_price) ? $product_price : $special_price;
      switch ($sale['sale_deduction_type']) {  /*v1.2.3c*/
        case 0:
          $sale_product_price = $product_price - $sale['sale_deduction_value'];
          $sale_special_price = $tmp_special_price - $sale['sale_deduction_value'];
          break;
        case 1:
          $sale_product_price = $product_price - (($product_price * $sale['sale_deduction_value']) / 100);
          $sale_special_price = $tmp_special_price - (($tmp_special_price * $sale['sale_deduction_value']) / 100);
          break;
        case 2:
          $sale_product_price = $sale['sale_deduction_value'];
          $sale_special_price = $sale['sale_deduction_value'];
          break;
        default:
          return $special_price;
      }

      if ($sale_product_price < 0) {
        $sale_product_price = 0;
      }

      if ($sale_special_price < 0) {
        $sale_special_price = 0;
      }

      if (!$special_price) {
        return number_format($sale_product_price, 4, '.', '');
      } else {
        switch($sale['sale_specials_condition']) {  /*v1.2.3c*/
          case 0:
            return number_format($sale_product_price, 4, '.', '');
            break;
          case 1:
            return number_format($special_price, 4, '.', '');
            break;
          case 2:
            return number_format($sale_special_price, 4, '.', '');
            break;
          default:
            return number_format($special_price, 4, '.', '');
        }
      }
    }
  }


////
// computes products_price + option groups lowest attributes price of each group when on
  function zen4wp_get_products_base_price($products_id) {
    global $wpdb;
    $product_check = $wpdb->get_row("SELECT products_price, products_priced_by_attribute FROM " . ZEN_TABLE_PRODUCTS . " WHERE products_id = '" . (int)$products_id . "'", ARRAY_A);

// is there a products_price to add to attributes
    $products_price = ($product_check == NULL) ? 0 : $product_check['products_price'];

    // do not select display only attributes and attributes_price_base_included is true
    $product_att_query = $wpdb->get_results("SELECT options_id, price_prefix, options_values_price, attributes_display_only, attributes_price_base_included, round(concat(price_prefix, options_values_price), 5) AS value 
                                          FROM " . ZEN_TABLE_PRODUCTS_ATTRIBUTES . " 
                                          WHERE products_id = '" . (int)$products_id . "' 
                                          AND attributes_display_only != '1' 
                                          AND attributes_price_base_included='1'". " 
                                          ORDER BY options_id, value", ARRAY_A);

// add attributes price to price
    if ($product_check['products_priced_by_attribute'] == '1' && is_array($product_att_query) ) {
      $the_options_id = 'x';
      $the_base_price = 0;
      foreach ( $product_att_query as $current_attribute ) {
        if ( $the_options_id != $current_attribute['options_id'] ) {
          $the_options_id = $current_attribute['options_id'];
          $the_base_price += (($current_attribute['price_prefix'] == '-') ? -1 : 1) * $current_attribute['options_values_price'];
        }
      }
      $the_base_price = $products_price + $the_base_price;
        
    } else {
      $the_base_price = $products_price;
      
    }
    return $the_base_price;
  }

////
// Display Price Retail
// Specials and Tax Included
  function zen4wp_get_products_display_price($products_id) {
    global $wpdb, $currencies;

    $free_tag = '';
    $call_tag = '';

// CUSTOMERS_APPROVAL values:
// 0 = normal shopping
// 1 = Login to shop
// 2 = Can browse but no prices
// 3 = showroom only
//
// CUSTOMERS_APPROVAL_AUTHORIZATION values:
// 0 = not required
// 1 = must be authorized to buy
// 2 = may browse, but no prices unless authorized
// 3 = can browse and see prices, but must be authorized to buy
/*
    // verify display of prices
      switch (true) {
        case (CUSTOMERS_APPROVAL == '1' and $_SESSION['customer_id'] == ''):
        // customer must be logged in to browse
        return '';
        break;
        case (CUSTOMERS_APPROVAL == '2' and $_SESSION['customer_id'] == ''):
        // customer may browse but no prices
        return TEXT_LOGIN_FOR_PRICE_PRICE;
        break;
        case (CUSTOMERS_APPROVAL == '3' and TEXT_LOGIN_FOR_PRICE_PRICE_SHOWROOM != ''):
        // customer may browse but no prices
        return '';
        break;
        case ((CUSTOMERS_APPROVAL_AUTHORIZATION != '0' and CUSTOMERS_APPROVAL_AUTHORIZATION != '3') and $_SESSION['customer_id'] == ''):
        // customer must be logged in to browse
        return TEXT_AUTHORIZATION_PENDING_PRICE;
        break;
        case ((CUSTOMERS_APPROVAL_AUTHORIZATION != '0' and CUSTOMERS_APPROVAL_AUTHORIZATION != '3') and $_SESSION['customers_authorization'] > '0'):
        // customer must be logged in to browse
        return TEXT_AUTHORIZATION_PENDING_PRICE;
        break;
        default:
        // proceed normally
        break;
      }
*/
    switch (true) {
      case (CUSTOMERS_APPROVAL == '1'): return ''; break;
      case (CUSTOMERS_APPROVAL == '2'): return __('Price Unavailable', 'zen4wp'); break;
      case (CUSTOMERS_APPROVAL_AUTHORIZATION != '0' && CUSTOMERS_APPROVAL_AUTHORIZATION != '3'): return __('Price Unavailable', 'zen4wp'); break;
      default: break;
    }
// show case only
    if (STORE_STATUS == '1') {
      return '';
    }

    // $new_fields = ', product_is_free, product_is_call, product_is_showroom_only';
    $product_check = $wpdb->get_row("SELECT products_tax_class_id, products_price, products_priced_by_attribute, product_is_free, product_is_call, products_type 
                                      FROM " . ZEN_TABLE_PRODUCTS . " 
                                      WHERE products_id = '" . (int)$products_id . "'" . " 
                                      LIMIT 1", ARRAY_A);

    // no prices on Document General
    if ($product_check == NULL || $product_check['products_type'] == 3) {
      return '';
    }

    $show_display_price = '';
    $display_normal_price = zen4wp_get_products_base_price($products_id);
    $display_special_price = zen4wp_get_products_special_price($products_id, true);
    $display_sale_price = zen4wp_get_products_special_price($products_id, false);

    $show_sale_discount = '';
    if (SHOW_SALE_DISCOUNT_STATUS == '1' and ($display_special_price != 0 or $display_sale_price != 0)) {
      if ($display_sale_price) {
        if (SHOW_SALE_DISCOUNT == 1) {
          if ($display_normal_price != 0) {
            $show_discount_amount = number_format(100 - (($display_sale_price / $display_normal_price) * 100), SHOW_SALE_DISCOUNT_DECIMALS);
          } else {
            $show_discount_amount = '';
          }
          $show_sale_discount = '<span class="productPriceDiscount">' . '<br />' . __( 'Save:&nbsp;', 'zen4wp') . $show_discount_amount . __( '% off', 'zen4wp' ) . '</span>';

        } else {
          $show_sale_discount = '<span class="productPriceDiscount">' . '<br />' . __( 'Save:&nbsp;', 'zen4wp') . $currencies->display_price(($display_normal_price - $display_sale_price), zen4wp_get_tax_rate($product_check['products_tax_class_id'])) . __( '&nbsp;off', 'zen4wp') . '</span>';  /*v1.2.3c*/
          
        }
        
      } else {
        if (SHOW_SALE_DISCOUNT == 1) {
          $show_sale_discount = '<span class="productPriceDiscount">' . '<br />' . __( 'Save:&nbsp;', 'zen4wp') . number_format(100 - (($display_special_price / $display_normal_price) * 100),SHOW_SALE_DISCOUNT_DECIMALS) . __( '% off', 'zen4wp' ) . '</span>';
        } else {
          $show_sale_discount = '<span class="productPriceDiscount">' . '<br />' . __( 'Save:&nbsp;', 'zen4wp') . $currencies->display_price(($display_normal_price - $display_special_price), zen4wp_get_tax_rate($product_check['products_tax_class_id'])) . __( '&nbsp;off', 'zen4wp') . '</span>';
        }
      }
    }

    if ($display_special_price) {
      $show_normal_price = '<span class="normalprice">' . $currencies->display_price($display_normal_price, zen4wp_get_tax_rate($product_check['products_tax_class_id'])) . ' </span>';
      if ($display_sale_price && $display_sale_price != $display_special_price) {
        $show_special_price = '&nbsp;' . '<span class="productSpecialPriceSale">' . $currencies->display_price($display_special_price, zen4wp_get_tax_rate($product_check['products_tax_class_id'])) . '</span>';
        if ($product_check['product_is_free'] == '1') {  /*v1.2.3c*/
          $show_sale_price = '<br />' . '<span class="productSalePrice">' . __( 'Sale:&nbsp;', 'zen4wp') . '<span class="strikethru">' . $currencies->display_price($display_sale_price, zen4wp_get_tax_rate($product_check['products_tax_class_id'])) . '</span></span>';
        } else {
          $show_sale_price = '<br />' . '<span class="productSalePrice">' . __( 'Sale:&nbsp;', 'zen4wp') . $currencies->display_price($display_sale_price, zen4wp_get_tax_rate($product_check['products_tax_class_id'])) . '</span>';
        }
      } else {
        if ($product_check['product_is_free'] == '1') {  /*v1.2.3c*/
          $show_special_price = '&nbsp;' . '<span class="productSpecialPrice">' . '<span class="strikethru">' . $currencies->display_price($display_special_price, zen4wp_get_tax_rate($product_check['products_tax_class_id'])) . '</span></span>';  /*v1.2.3c*/
        } else {
          $show_special_price = '&nbsp;' . '<span class="productSpecialPrice">' . $currencies->display_price($display_special_price, zen4wp_get_tax_rate($product_check['products_tax_class_id'])) . '</span>';
        }
        $show_sale_price = '';
      }
    } else {
      if ($display_sale_price) {
        $show_normal_price = '<span class="normalprice">' . $currencies->display_price($display_normal_price, zen4wp_get_tax_rate($product_check['products_tax_class_id'])) . ' </span>';
        $show_special_price = '';
        $show_sale_price = '<br />' . '<span class="productSalePrice">' . __( 'Sale:&nbsp;', 'zen4wp') . $currencies->display_price($display_sale_price, zen4wp_get_tax_rate($product_check['products_tax_class_id'])) . '</span>';
      } else {
        if ($product_check['product_is_free'] == '1') {
          $show_normal_price = '<span class="strikethru">' . $currencies->display_price($display_normal_price, zen4wp_get_tax_rate($product_check['products_tax_class_id'])) . '</span>';
        } else {
          $show_normal_price = $currencies->display_price($display_normal_price, zen4wp_get_tax_rate($product_check['products_tax_class_id']));
        }
        $show_special_price = '';
        $show_sale_price = '';
      }
    }

    if ($display_normal_price == 0) {
      // don't show the $0.00
      $final_display_price = $show_special_price . $show_sale_price . $show_sale_discount;
    } else {
      $final_display_price = $show_normal_price . $show_special_price . $show_sale_price . $show_sale_discount;
    }

    // If Free, Show it
    if ($product_check['product_is_free'] == '1') {
      if (OTHER_IMAGE_PRICE_IS_FREE_ON == '0') {
        $free_tag = '<br />' . __( 'It\'s Free!', 'zen4wp');
      } else {
        $free_tag = '<br />' . zen4wp_image(ZEN_DIR_WS_TEMPLATE_IMAGES . OTHER_IMAGE_PRICE_IS_FREE, __( 'It\'s Free!', 'zen4wp'));
      }
    }

    // If Call for Price, Show it
    if ($product_check['product_is_call']) {
      if (PRODUCTS_PRICE_IS_CALL_IMAGE_ON=='0') {
        $call_tag = '<br />' . __( 'Call for Price', 'zen4wp');
      } else {
        $call_tag = '<br />' . zen4wp_image(ZEN_DIR_WS_TEMPLATE_IMAGES . OTHER_IMAGE_CALL_FOR_PRICE, __( 'Call for Price', 'zen4wp'));
      }
    }

    return $final_display_price . $free_tag . $call_tag;
  }
