<?php
/**
 * initialise template system variables
 * see {@link  http://www.zen-cart.com/wiki/index.php/Developers_API_Tutorials#InitSystem wikitutorials} for more details.
 *
 * Determines current template name for current language, from database<br />
 * Then loads template-specific language file, followed by master/default language file<br />
 * ie: includes/languages/classic/english.php followed by includes/languages/english.php
 *
 * @package initSystem
 * @copyright Copyright 2003-2006 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: init_templates.php 3123 2006-03-06 23:36:46Z drbyte $
 */
// -----
// Adapted for use with Zen Cart for WordPress (zen4wp) series
// Copyright (c) 2013-2014, Vinos de Frutas Tropicales (lat9@vinosdefrutastropicales.com)
// -----
/*
 * Determine the active template name
 */
  $zen_template_dir = '';
  $sql = "SELECT template_dir
            FROM " . ZEN_TABLE_TEMPLATE_SELECT . "
            WHERE template_language = 0";
  $template_query = $wpdb->get_row($sql, ARRAY_A);
  $zen4wp_template_dir = $template_query['template_dir'];

  $sql = "SELECT template_dir
            FROM " . ZEN_TABLE_TEMPLATE_SELECT . "
            WHERE template_language = '" . /* $_SESSION['languages_id'] */ 1 . "'";
  $template_query = $wpdb->get_row($sql, ARRAY_A);
  if (is_array($template_query)) {
    $zen4wp_template_dir = $template_query['template_dir'];
  }

/**
 * The actual template directory to use
 */
  define('ZEN_DIR_WS_TEMPLATE', ZEN_DIR_WS_TEMPLATES . $zen4wp_template_dir . '/');
/**
 * The actual template images directory to use
 */
  define('ZEN_DIR_WS_TEMPLATE_IMAGES', ZEN_DIR_WS_TEMPLATE . 'images/');
/**
 * The actual template icons directory to use
 */
  define('ZEN_DIR_WS_TEMPLATE_ICONS', ZEN_DIR_WS_TEMPLATE_IMAGES . 'icons/');