<?php
/**
 * html_output.php
 * HTML-generating functions used throughout the core
 *
 * @package functions
 * @copyright Copyright 2003-2011 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: html_output.php 19355 2011-08-21 21:12:09Z drbyte $
 */
// -----
// Adapted for use with Zen Cart for WordPress (zen4wp) series
// Copyright (c) 2013-2014, Vinos de Frutas Tropicales (lat9@vinosdefrutastropicales.com)
// -----

  function zen4wp_href_link($page = '', $parameters = '', $connection = 'NONSSL', $add_session_id = true, $search_engine_safe = true, $static = false, $use_dir_ws_catalog = true) {
    if (!zen4wp_not_null($page)) {
      die('</td></tr></table></td></tr></table><br /><br /><strong class="note">Error!<br /><br />Unable to determine the page link!</strong><br /><br /><!--' . $page . '<br />' . $parameters . ' -->');
    }

    if ($connection == 'NONSSL') {
      $link = ZEN_HTTP_SERVER;
    } elseif ($connection == 'SSL') {
      if (ZEN_ENABLE_SSL == 'true') {
        $link = ZEN_HTTPS_SERVER ;
      } else {
        $link = ZEN_HTTP_SERVER;
      }
    } else {
      die('</td></tr></table></td></tr></table><br /><br /><strong class="note">Error!<br /><br />Unable to determine connection method on a link!<br /><br />Known methods: NONSSL SSL</strong><br /><br />');
    }

    if ($use_dir_ws_catalog) {
      if ($connection == 'SSL' && ZEN_ENABLE_SSL == 'true') {  /*v1.3.0c*/
        $link .= ZEN_DIR_WS_HTTPS_CATALOG;
      } else {
        $link .= ZEN_DIR_WS_CATALOG;
      }
    }

    if (!$static) {
      if (zen4wp_not_null($parameters)) {
        $link .= 'index.php?main_page='. $page . "&" . zen4wp_output_string($parameters);
      } else {
        $link .= 'index.php?main_page=' . $page;
      }
    } else {
      if (zen4wp_not_null($parameters)) {
        $link .= $page . "?" . zen4wp_output_string($parameters);
      } else {
        $link .= $page;
      }
    }

    $separator = '&';

// clean up the link before processing
    while (strstr($link, '&&')) $link = str_replace('&&', '&', $link);
    while (strstr($link, '&amp;&amp;')) $link = str_replace('&amp;&amp;', '&amp;', $link);

    if ( (SEARCH_ENGINE_FRIENDLY_URLS == 'true') && ($search_engine_safe == true) ) {
      while (strstr($link, '&&')) $link = str_replace('&&', '&', $link);

      $link = str_replace('&amp;', '/', $link);
      $link = str_replace('?', '/', $link);
      $link = str_replace('&', '/', $link);
      $link = str_replace('=', '/', $link);

      $separator = '?';
    }

    if (isset($sid)) {
      $link .= $separator . zen4wp_output_string($sid);
    }

// clean up the link after processing
    while (strstr($link, '&amp;&amp;')) $link = str_replace('&amp;&amp;', '&amp;', $link);

    $link = preg_replace('/&/', '&amp;', $link);
    return $link;
  }

/*
 * The HTML image wrapper function for non-proportional images
 * used when "proportional images" is turned off or if calling from a template directory
 */
function zen4wp_image_OLD($src, $alt = '', $width = '', $height = '', $parameters = '') {
  global $zen4wp_template_dir;

//auto replace with defined missing image
  if ($src == ZEN_DIR_WS_IMAGES and PRODUCTS_IMAGE_NO_IMAGE_STATUS == '1') {
    $src = ZEN_DIR_WS_IMAGES . PRODUCTS_IMAGE_NO_IMAGE;
  }

  if ( (empty($src) || ($src == ZEN_DIR_WS_IMAGES)) && (IMAGE_REQUIRED == 'false') ) {
    return false;
  }

  // -----
  // Create the file-system version of the source file's name so that the file-related functions will work; we'll
  // still reference the file in the <img tag using the full http reference.
  //
  $src_file = str_replace(ZEN_HTTP_SERVER /*-bof-v1.0.3a*/ . ZEN_DIR_WS_CATALOG /*-eof-v1.0.3a*/, ZEN_DIR_FS_CATALOG, $src);
  
  // if not in current template switch to template_default
  if (!file_exists($src_file)) {
    $src = str_replace(ZEN_DIR_WS_TEMPLATES . $zen4wp_template_dir, ZEN_DIR_WS_TEMPLATES . 'template_default', $src);
    $src_file = str_replace(ZEN_HTTP_SERVER /*-bof-v1.0.3a*/ . ZEN_DIR_WS_CATALOG /*-eof-v1.0.3a*/, ZEN_DIR_FS_CATALOG, $src);
  }

// alt is added to the img tag even if it is null to prevent browsers from outputting
// the image filename as default
  $image = '<img src="' . zen4wp_output_string($src) . '" alt="' . zen4wp_output_string($alt) . '"';

  if (zen4wp_not_null($alt)) {
    $image .= ' title=" ' . zen4wp_output_string($alt) . ' "';
  }

  if ( (CONFIG_CALCULATE_IMAGE_SIZE == 'true') && (empty($width) || empty($height)) ) {
    if ($image_size = @getimagesize($src_file)) {
      if (empty($width) && zen4wp_not_null($height)) {
        $ratio = $height / $image_size[1];
        $width = $image_size[0] * $ratio;
      } elseif (zen4wp_not_null($width) && empty($height)) {
        $ratio = $width / $image_size[0];
        $height = $image_size[1] * $ratio;
      } elseif (empty($width) && empty($height)) {
        $width = $image_size[0];
        $height = $image_size[1];
      }
    } elseif (IMAGE_REQUIRED == 'false') {
      return false;
    }
  }

  if (zen4wp_not_null($width) && zen4wp_not_null($height)) {
    $image .= ' width="' . zen4wp_output_string($width) . '" height="' . zen4wp_output_string($height) . '"';
  }

  if (zen4wp_not_null($parameters)) $image .= ' ' . $parameters;

  $image .= ' />';

  return $image;
}

// -----
// Based on zen_image (from html_output.php)
//
/*
 * The HTML image wrapper function
 */
function zen4wp_image($src, $alt = '', $width = '', $height = '', $parameters = '') {
  global $zen4wp_template_dir;

  // soft clean the alt tag
  $alt = zen4wp_clean_html($alt);

  // use old method on template images
  if (strstr($src, 'includes/templates') or strstr($src, 'includes/languages') or PROPORTIONAL_IMAGES_STATUS == '0') {
    return zen4wp_image_OLD($src, $alt, $width, $height, $parameters);
  }

//auto replace with defined missing image
  if ($src == ZEN_DIR_WS_IMAGES and PRODUCTS_IMAGE_NO_IMAGE_STATUS == '1') {
    $src = ZEN_DIR_WS_IMAGES . PRODUCTS_IMAGE_NO_IMAGE;
  }

  if ( (empty($src) || ($src == ZEN_DIR_WS_IMAGES)) && (IMAGE_REQUIRED == 'false') ) {
    return false;
  }

  // -----
  // Create the file-system version of the source file's name so that the file-related functions will work; we'll
  // still reference the file in the <img tag using the full http reference.
  //
  $src_file = str_replace(ZEN_HTTP_SERVER /*-bof-v1.0.3a*/ . ZEN_DIR_WS_CATALOG /*-eof-v1.0.3a*/, ZEN_DIR_FS_CATALOG, $src);
  
  // if not in current template switch to template_default
  if (!file_exists($src_file)) {
    $src = str_replace(ZEN_DIR_WS_TEMPLATES . $zen4wp_template_dir, ZEN_DIR_WS_TEMPLATES . 'template_default', $src);
    $src_file = str_replace(ZEN_HTTP_SERVER /*-bof-v1.0.3a*/ . ZEN_DIR_WS_CATALOG /*-eof-v1.0.3a*/, ZEN_DIR_FS_CATALOG, $src);
  }

  // hook for handle_image() function such as Image Handler etc
  if (function_exists('handle_image')) {
    $newimg = handle_image($src, $alt, $width, $height, $parameters);
    list($src, $alt, $width, $height, $parameters) = $newimg;
  }

  // Convert width/height to int for proper validation.
  // intval() used to support compatibility with plugins like image-handler
  $width = empty($width) ? $width : intval($width);
  $height = empty($height) ? $height : intval($height);

// alt is added to the img tag even if it is null to prevent browsers from outputting
// the image filename as default
  $image = '<img src="' . zen4wp_output_string($src) . '" alt="' . zen4wp_output_string($alt) . '"';

  if (zen4wp_not_null($alt)) {
    $image .= ' title=" ' . zen4wp_output_string($alt) . ' "';
  }
  
  if ( ((CONFIG_CALCULATE_IMAGE_SIZE == 'true') && (empty($width) || empty($height))) ) {
    if ($image_size = @getimagesize($src_file)) {
      if (empty($width) && zen4wp_not_null($height)) {
        $ratio = $height / $image_size[1];
        $width = $image_size[0] * $ratio;
      } elseif (zen4wp_not_null($width) && empty($height)) {
        $ratio = $width / $image_size[0];
        $height = $image_size[1] * $ratio;
      } elseif (empty($width) && empty($height)) {
        $width = $image_size[0];
        $height = $image_size[1];
      }
    } elseif (IMAGE_REQUIRED == 'false') {
      return false;
    }
  }

  if (zen4wp_not_null($width) && zen4wp_not_null($height) and file_exists($src_file)) {
//      $image .= ' width="' . zen_output_string($width) . '" height="' . zen_output_string($height) . '"';
// proportional images
    $image_size = @getimagesize($src_file);
    // fix division by zero error
    $ratio = ($image_size[0] != 0 ? $width / $image_size[0] : 1);
    if ($image_size[1]*$ratio > $height) {
      $ratio = $height / $image_size[1];
      $width = $image_size[0] * $ratio;
    } else {
      $height = $image_size[1] * $ratio;
    }
// only use proportional image when image is larger than proportional size
    if ($image_size[0] < $width and $image_size[1] < $height) {
      $image .= ' width="' . $image_size[0] . '" height="' . intval($image_size[1]) . '"';
    } else {
      $image .= ' width="' . round($width) . '" height="' . round($height) . '"';
    }
  } else {
     // override on missing image to allow for proportional and required/not required
    if (IMAGE_REQUIRED == 'false') {
      return false;
    } else if (substr($src, 0, 4) != 'http') {
      $image .= ' width="' . intval(SMALL_IMAGE_WIDTH) . '" height="' . intval(SMALL_IMAGE_HEIGHT) . '"';
    }
  }

  // inject rollover class if one is defined. NOTE: This could end up with 2 "class" elements if $parameters contains "class" already.
  if (defined('IMAGE_ROLLOVER_CLASS') && IMAGE_ROLLOVER_CLASS != '') {
    $parameters .= (zen4wp_not_null($parameters) ? ' ' : '') . 'class="rollover"';
  }
  // add $parameters to the tag output
  if (zen4wp_not_null($parameters)) $image .= ' ' . $parameters;
  
  $image .= ' />';

  return $image;
}