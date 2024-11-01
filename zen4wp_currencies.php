<?php
/**
 * currencies Class.
 *
 * @package classes
 * @copyright Copyright 2003-2010 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: currencies.php 15880 2010-04-11 16:24:30Z wilt $
 */
// -----
// Adapted for use with Zen Cart for WordPress (zen4wp) series
// Copyright (c) 2013-2016, Vinos de Frutas Tropicales (lat9@vinosdefrutastropicales.com)
// -----
/**
 * currencies Class
 * Class to handle currencies
 *
 * @package classes
 */
class zen4wp_currencies {
  var $currencies;

  // class constructor
  function __construct() {
    global $wpdb;
    $this->currencies = array();
    $currencies_query = "SELECT code, title, symbol_left, symbol_right, decimal_point, thousands_point, decimal_places, value
                          FROM " . ZEN_TABLE_CURRENCIES;

    $currencies = $wpdb->get_results($currencies_query, ARRAY_A);
    if (is_array($currencies)) {
      foreach ( $currencies as $current_currency ) {
        $currency_code = $current_currency['code'];
        unset($current_currency['code']);
        $this->currencies[$currency_code] = $current_currency;
      }
    }
  }

  // class methods
  function format($number, $calculate_currency_value = true, $currency_type = '', $currency_value = '') {

    if (empty($currency_type)) $currency_type = $_SESSION['currency'];

    if ($calculate_currency_value == true) {
      $rate = (zen4wp_not_null($currency_value)) ? $currency_value : $this->currencies[$currency_type]['value'];
      $format_string = $this->currencies[$currency_type]['symbol_left'] . number_format(zen4wp_round($number * $rate, $this->currencies[$currency_type]['decimal_places']), $this->currencies[$currency_type]['decimal_places'], $this->currencies[$currency_type]['decimal_point'], $this->currencies[$currency_type]['thousands_point']) . $this->currencies[$currency_type]['symbol_right'];
    } else {
      $format_string = $this->currencies[$currency_type]['symbol_left'] . number_format(zen4wp_round($number, $this->currencies[$currency_type]['decimal_places']), $this->currencies[$currency_type]['decimal_places'], $this->currencies[$currency_type]['decimal_point'], $this->currencies[$currency_type]['thousands_point']) . $this->currencies[$currency_type]['symbol_right'];
    }

    if ((DOWN_FOR_MAINTENANCE == 'true' and DOWN_FOR_MAINTENANCE_PRICES_OFF == 'true') and (!strstr(EXCLUDE_ADMIN_IP_FOR_MAINTENANCE, $_SERVER['REMOTE_ADDR']))) {
      $format_string = '';
    }

    return $format_string;
  }
  
  function rateAdjusted($number, $calculate_currency_value = true, $currency_type = '', $currency_value = '') {

    if (empty($currency_type)) $currency_type = $_SESSION['currency'];

    if ($calculate_currency_value == true) {
      $rate = (zen4wp_not_null($currency_value)) ? $currency_value : $this->currencies[$currency_type]['value'];
      $result = zen4wp_round($number * $rate, $this->currencies[$currency_type]['decimal_places']);
    } else {
      $result = zen4wp_round($number, $this->currencies[$currency_type]['decimal_places']);
    }
    return $result;
  }
  
  function value($number, $calculate_currency_value = true, $currency_type = '', $currency_value = '') {

    if (empty($currency_type)) $currency_type = $_SESSION['currency'];

    if ($calculate_currency_value == true) {
      if ($currency_type == DEFAULT_CURRENCY) {
        $rate = (zen4wp_not_null($currency_value)) ? $currency_value : 1/$this->currencies[$_SESSION['currency']]['value'];
      } else {
        $rate = (zen4wp_not_null($currency_value)) ? $currency_value : $this->currencies[$currency_type]['value'];
      }
      $currency_value = zen4wp_round($number * $rate, $this->currencies[$currency_type]['decimal_places']);
    } else {
      $currency_value = zen4wp_round($number, $this->currencies[$currency_type]['decimal_places']);
    }

    return $currency_value;
  }

  function is_set($code) {
    if (isset($this->currencies[$code]) && zen4wp_not_null($this->currencies[$code])) {
      return true;
    } else {
      return false;
    }
  }

  function get_value($code) {
    return $this->currencies[$code]['value'];
  }

  function get_decimal_places($code) {
    return $this->currencies[$code]['decimal_places'];
  }

  function display_price($products_price, $products_tax, $quantity = 1) {
    return $this->format(zen4wp_add_tax($products_price, $products_tax) * $quantity);
  }
}
