<?php
/**
 * Plugin Name: Zen4WP - Best Sellers
 * Plugin URI: http://zencart-wordpress-integration.com/
 * Description: Display a list (with links) of the current best-sellers from your Zen Cart store. Visit <a href="admin.php?page=zen4wp_set_options">Zen4WP Settings Page</a> to configure Zen4WP
 * Version: 1.4.0
 * Author: Vinos de Frutas Tropicales and Over the Hill Web Consulting
 * Author URI: http://zencart-wordpress-integration.com/
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */
// -----
// Part of the Zen Cart for WordPress (zen4wp) series
// Copyright (c) 2013-2016, Vinos de Frutas Tropicales (lat9@vinosdefrutastropicales.com)
// -----
// -----
// Load the common functions and definitions
//
require_once(realpath(dirname(__FILE__)) . '/zen4wp.php');  /*v1.1.0c*/

// -----
// Add our function to the widgets_init hook.
//
add_action( 'widgets_init', 'zen4wp_best_sellers' );
function zen4wp_best_sellers() {
  register_widget( 'zen4wp_best_sellers' );
}
// Register stylesheet on initialization
add_action('init', 'zen4wp_best_sellers_register_script');
function zen4wp_best_sellers_register_script(){  
  wp_register_style( 'zen4wp_best_sellers', plugins_url('/css/zen4wp_best_sellers.css', __FILE__), false, '1.1.0', 'all');  /*v1.1.0c*/
}

// use the registered jquery and style above
add_action('wp_enqueue_scripts', 'zen4wp_best_sellers_enqueue_style');
function zen4wp_best_sellers_enqueue_style(){
  wp_enqueue_style( 'zen4wp_best_sellers' );
}

class zen4wp_best_sellers extends WP_Widget {

  // -----
  // Create the widget
  //
  function __construct() {
    $widget_ops = array( 'classname' => 'zen4wp_best_sellers', 'description' => __('Display a list (with links) of the current best-sellers from your Zen Cart store.', 'zen4wp_best_sellers') );
    
    $control_ops = array( 'id_base' => 'zen4wp_best_sellers' );
    
    parent::__construct( 
        'zen4wp_best_sellers' , 
        __('Zen4Wp Best Sellers', 'zen4wp_best_sellers'), 
        $widget_ops, 
        $control_ops 
    );
  }
  
  function widget( $args, $instance ) {
    global $wpdb;
    extract( $args );

    // Our variables from the widget settings.
    $title = apply_filters('widget_title', $instance['title'] );
    $number_to_show = isset( $instance['number_to_show'] ) ? $instance['number_to_show'] : 10;
    $show_count = isset( $instance['show_count'] ) ? $instance['show_count'] : 'off';
    
    $best_sellers = array ();

    // Get the requested number of best sellers from the database
    if (((int)$number_to_show) > 0) {
      $best_sellers_query = "SELECT DISTINCT p.products_id, pd.products_name, p.products_ordered
                             FROM " . ZEN_TABLE_PRODUCTS . " p, " . ZEN_TABLE_PRODUCTS_DESCRIPTION . " pd
                             WHERE p.products_status = '1'
                             AND p.products_ordered > 0
                             AND p.products_id = pd.products_id
                             AND pd.language_id = 1
                             ORDER BY p.products_ordered desc, pd.products_name
                             LIMIT " . (int)$number_to_show;

      $best_sellers = $wpdb->get_results($best_sellers_query, ARRAY_A);
      if (is_array($best_sellers) && sizeof($best_sellers) > 0) {
        $best_seller_info = '<ol class="bestSellers">';
        foreach ($best_sellers as $current_item) {
          $best_seller_info .= '<li><a href="' . zen4wp_href_link( zen4wp_get_info_page( $current_item['products_id'] ), 'products_id=' . $current_item['products_id'] ) . '">' . $current_item['products_name'] . '</a>';
          if ($show_count == 'on') {
            $best_seller_info .= ' (' . $current_item['products_ordered'] . ')';
          }
          $best_seller_info .= '</li>';
        }
        $best_seller_info .= '</ol>';
      }
    }
    
    if (((int)$number_to_show) <= 0 || (is_array($best_sellers) && sizeof($best_sellers) == 0)) {
      $best_seller_info = __('No best sellers found!' , 'zen4wp_best_sellers');
    }
    
    echo $before_widget;

    // Display the widget title 
    if ( $title ) {
      echo $before_title . $title . $after_title;
      
    }
    
    echo $best_seller_info;
    
    echo $after_widget;
  }

  //Update the widget 
   
  function update( $new_instance, $old_instance ) {
    $instance = $old_instance;

    //Strip tags from title and name to remove HTML 
    $instance['title'] = strip_tags( $new_instance['title'] );
    $instance['number_to_show'] = $new_instance['number_to_show'];
    $instance['show_count'] = $new_instance['show_count'];

    return $instance;
  }

  
  function form( $instance ) {

    //Set up some default widget settings.
    $defaults = array( 'title' => __('Best Sellers', 'zen4wp_best_sellers'), 'number_to_show' => 10, 'show_count' => 'on' );
    $instance = wp_parse_args( (array) $instance, $defaults ); 
?>
    <p>
      <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'zen4wp_best_sellers'); ?></label>
      <input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
    </p>

    <p>
      <label for="<?php echo $this->get_field_id( 'number_to_show' ); ?>"><?php _e('Maximum Number to Display:', 'zen4wp_best_sellers'); ?></label>
      <input id="<?php echo $this->get_field_id( 'number_to_show' ); ?>" name="<?php echo $this->get_field_name( 'number_to_show' ); ?>" value="<?php echo $instance['number_to_show']; ?>" style="width:100%;" />
    </p>

    <p>
      <input class="checkbox" type="checkbox" <?php checked( $instance['show_count'], 'on' ); ?> id="<?php echo $this->get_field_id( 'show_count' ); ?>" name="<?php echo $this->get_field_name( 'show_count' ); ?>" /> 
      <label for="<?php echo $this->get_field_id( 'show_count' ); ?>"><?php _e('Display sale counts?', 'zen4wp_best_sellers'); ?></label>
    </p>

  <?php
  }
}
