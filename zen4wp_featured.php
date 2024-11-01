<?php
/**
 * Plugin Name: Zen4WP - Featured products
 * Plugin URI: http://zencart-wordpress-integration.com/
 * Description: Display a random number of featured products from the your Zen Cart store. Visit <a href="admin.php?page=zen4wp_set_options">Zen4WP Settings Page</a> to configure Zen4WP.
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
add_action( 'widgets_init', 'zen4wp_featured' );
function zen4wp_featured() {
  register_widget( 'zen4wp_featured' );
}

// Register stylesheet on initialization
add_action('init', 'zen4wp_featured_register_script');
function zen4wp_featured_register_script(){  
  wp_register_style( 'zen4wp_featured', plugins_url('/css/zen4wp_featured.css', __FILE__), false, '1.1.0', 'all');  /*v1.1.0c*/
}
 
// use the registered jquery and style above
add_action('wp_enqueue_scripts', 'zen4wp_featured_enqueue_style');
function zen4wp_featured_enqueue_style(){
  wp_enqueue_style( 'zen4wp_featured' );
}

class zen4wp_featured extends WP_Widget {

  // -----
  // Create the widget
  //
  function __construct() {
    $widget_ops = array( 'classname' => 'zen4wp_featured', 'description' => __('Display a random list of featured products from the associated Zen Cart store.', 'zen4wp_featured') );
    
    $control_ops = array( 'id_base' => 'zen4wp_featured' );

    parent::__construct( 
        'zen4wp_featured' , 
        __('Zen4Wp Featured Products', 'zen4wp_featured'), 
        $widget_ops, 
        $control_ops 
    );
  }
  
  function widget( $args, $instance ) {
    global $wpdb;
    extract( $args );

    // Our variables from the widget settings.
    $title = apply_filters('widget_title', $instance['title'] );
    $number_to_show = isset( $instance['number_to_show'] ) ? $instance['number_to_show'] : 2;
    $number_of_columns = isset( $instance['number_of_columns'] ) ? $instance['number_of_columns'] : 1;

    // Get the requested number of featured products from the database
    $featured_info = '';
    if (((int)$number_to_show) > 0) {
      $random_featured_products_query = "SELECT p.products_id, p.products_image, pd.products_name, p.master_categories_id
                                         FROM (" . ZEN_TABLE_PRODUCTS . " p
                                         LEFT JOIN " . ZEN_TABLE_FEATURED . " f on p.products_id = f.products_id
                                         LEFT JOIN " . ZEN_TABLE_PRODUCTS_DESCRIPTION . " pd on p.products_id = pd.products_id )
                                         WHERE p.products_id = f.products_id
                                         AND p.products_id = pd.products_id
                                         AND p.products_status = 1
                                         AND f.status = 1";
      $featured_info = zen4wp_display_random( $random_featured_products_query, $number_to_show, $number_of_columns, 'featuredProducts');

    }
    // -----
    // Display the featured products' information ... only if there's something to display!
    //
    if ($featured_info != '') {
      echo $before_widget;

      // Display the widget title 
      if ( $title )
        echo $before_title . $title . $after_title;
      
      echo $featured_info;
      
      echo $after_widget;
    }
  }

  //Update the widget 
   
  function update( $new_instance, $old_instance ) {
    $instance = $old_instance;

    //Strip tags from title and name to remove HTML 
    $instance['title'] = strip_tags( $new_instance['title'] );
    $instance['number_to_show'] = $new_instance['number_to_show'];
    $instance['number_of_columns'] = $new_instance['number_of_columns'];

    return $instance;
  }

  
  function form( $instance ) {

    //Set up some default widget settings.
    $defaults = array( 'title' => __('Featured Products', 'zen4wp_featured'), 'number_to_show' => 2, 'number_of_columns' => 1 );
    $instance = wp_parse_args( (array) $instance, $defaults ); 
?>
    <p>
      <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'zen4wp_featured'); ?></label>
      <input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
    </p>

    <p>
      <label for="<?php echo $this->get_field_id( 'number_to_show' ); ?>"><?php _e('Maximum Number to Display:', 'zen4wp_featured'); ?></label>
      <input id="<?php echo $this->get_field_id( 'number_to_show' ); ?>" name="<?php echo $this->get_field_name( 'number_to_show' ); ?>" value="<?php echo $instance['number_to_show']; ?>" style="width:100%;" />
    </p>
    
    <p>
      <label for="<?php echo $this->get_field_id( 'number_of_columns' ); ?>"><?php _e('Number of Columns for the Display:', 'zen4wp_featured'); ?></label>
      <input id="<?php echo $this->get_field_id( 'number_of_columns' ); ?>" name="<?php echo $this->get_field_name( 'number_of_columns' ); ?>" value="<?php echo $instance['number_of_columns']; ?>" style="width:100%;" />
    </p>

  <?php
  }
}
