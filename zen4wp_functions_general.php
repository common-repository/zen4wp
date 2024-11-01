<?php
/**
 * functions_general.php
 * General functions used throughout Zen Cart
 *
 * @package functions
 * @copyright Copyright 2003-2014 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version GIT: $Id: Author: Ian Wilson  Modified in v1.5.4 $
 */
// -----
// Adapted for use with Zen Cart for WordPress (zen4wp) series
// Copyright (c) 2013-2015, Vinos de Frutas Tropicales (lat9@vinosdefrutastropicales.com)
// -----

/**
 * Parse the data used in the html tags to ensure the tags will not break
 * Basically just an extension to the php strstr function
 * @param string The string to be parsed
 * @param string The needle to find
*/
// Parse the data used in the html tags to ensure the tags will not break
  function zen4wp_parse_input_field_data($data, $parse) {
    return strtr(trim($data), $parse);
  }
  
////
// Wrapper function for round()
  function zen4wp_round($value, $precision) {
    $value =  round($value *pow(10,$precision),0);
    $value = $value/pow(10,$precision);
    return $value;
  }
  
/**
 * Returns a string with conversions for security.
 * @param string The string to be parsed
 * @param string contains a string to be translated, otherwise just quote is translated
 * @param boolean Do we run htmlspecialchars over the string
*/
  function zen4wp_output_string($string, $translate = false, $protected = false) {
    if ($protected == true) {
      return htmlspecialchars($string, ENT_COMPAT, ZEN_CHARSET, TRUE);
    } else {
      if ($translate == false) {
        return zen4wp_parse_input_field_data($string, array('"' => '&quot;'));
      } else {
        return zen4wp_parse_input_field_data($string, $translate);
      }
    }
  }
  
/**
 * Returns a string with conversions for security.
 *
 * Simply calls the zen_ouput_string function
 * with parameters that run htmlspecialchars over the string
 * and converts quotes to html entities
 *
 * @param string The string to be parsed
*/
  function zen4wp_output_string_protected($string) {
    return zen4wp_output_string($string, false, true);
  }
  
// -----
// Based on zen_not_null
//
function zen4wp_not_null($value) {
  if (is_array($value)) {
    if (sizeof($value) > 0) {
      return true;
    } else {
      return false;
    }
  } else {
    if (($value != '') && (strtolower($value) != 'null') && (strlen(trim($value)) > 0)) {
      return true;
    } else {
      return false;
    }
  }
}
////
// Truncate a string
  function zen4wp_trunc_string($str = "", $len = 150, $more = 'true') {
    if ($str == "") return $str;
    if (is_array($str)) return $str;
    $str = trim($str);
    // if it's les than the size given, then return it
    if (strlen($str) <= $len) return $str;
    // else get that size of text
    $str = substr($str, 0, $len);
    // backtrack to the end of a word
    if ($str != "") {
      // check to see if there are any spaces left
      if (!substr_count($str , " ")) {
        if ($more == 'true') $str .= "...";
        return $str;
      }
      // backtrack
      while(strlen($str) && ($str[strlen($str)-1] != " ")) {
        $str = substr($str, 0, -1);
      }
      $str = substr($str, 0, -1);
      if ($more == 'true') $str .= "...";
      if ($more != 'true' and $more != 'false') $str .= $more;
    }
    return $str;
  }

////
// remove common HTML from text for display as paragraph
  function zen4wp_clean_html($clean_it, $extraTags = '') {
    if (!is_array($extraTags)) $extraTags = array($extraTags);

    // remove any embedded javascript
    $clean_it = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $clean_it);

    $clean_it = preg_replace('/\r/', ' ', $clean_it);
    $clean_it = preg_replace('/\t/', ' ', $clean_it);
    $clean_it = preg_replace('/\n/', ' ', $clean_it);

    $clean_it= nl2br($clean_it);

// update breaks with a space for text displays in all listings with descriptions
    while (strstr($clean_it, '<br>'))   $clean_it = str_replace('<br>',   ' ', $clean_it);
    while (strstr($clean_it, '<br />')) $clean_it = str_replace('<br />', ' ', $clean_it);
    while (strstr($clean_it, '<br/>'))  $clean_it = str_replace('<br/>',  ' ', $clean_it);
    while (strstr($clean_it, '<p>'))    $clean_it = str_replace('<p>',    ' ', $clean_it);
    while (strstr($clean_it, '</p>'))   $clean_it = str_replace('</p>',   ' ', $clean_it);

// temporary fix more for reviews than anything else
    while (strstr($clean_it, '<span class="smallText">')) $clean_it = str_replace('<span class="smallText">', ' ', $clean_it);
    while (strstr($clean_it, '</span>')) $clean_it = str_replace('</span>', ' ', $clean_it);

// clean general and specific tags:
    $taglist = array('strong','b','u','i','em');
    $taglist = array_merge($taglist, (is_array($extraTags) ? $extraTags : array($extraTags)));
    foreach ($taglist as $tofind) {
      if ($tofind != '') $clean_it = preg_replace("/<[\/\!]*?" . $tofind . "[^<>]*?>/si", ' ', $clean_it);
    }

// remove any double-spaces created by cleanups:
    while (strstr($clean_it, '  ')) $clean_it = str_replace('  ', ' ', $clean_it);

// remove other html code to prevent problems on display of text
    $clean_it = strip_tags($clean_it);
    return $clean_it;
  }