<?php
/**
* Plugin Name: Considerable Recent Post
* Description: When enabled, the plugin grabs the most recent post and adds a "recent post" box to the bottom of every article. 
* Version:     1.0.0
* Author:      Robin Pahlman
* Author URI:   https://electricsauna.net 
**/

// load css and enqueue styles
function load_plugin_css() {
    $plugin_url = plugin_dir_url( __FILE__ );

    wp_enqueue_style( 'style', $plugin_url . 'css/recent-post-style.css' );   
}
// add action
add_action( 'wp_enqueue_scripts', 'load_plugin_css', 9999 );

// get most recent post
function recent_post() {
  $args = array( 'numberposts' => '1', 'category' => CAT_ID );
  $recent_posts = wp_get_recent_posts( $args );
    foreach( $recent_posts as $recent ){
      $image = 'https://i.imgur.com/fniSFQ0.jpg'; // image is hardcoded
      $author = get_the_author_link();
      $categories = get_the_category($recent["ID"]);
      $timestamp = human_time_diff( get_the_time('U'), current_time('timestamp') ) . ' ago';

      return 
      '<div class="card-container">
        <img class="card-image" src="'. $image .'">' . 
        '<div class="card-text">
          <div class="category-wrapper">
            <h4 class="card-category">' . $categories[0]->name . '</h4>
            <h4 class="mobile-show">' .'  | ' . $timestamp .'</h4>
          </div>' . 
        '<h1 class="post-title">
          <a href="' . get_permalink($recent["ID"]) . '">' . ( __($recent["post_title"])) . '</a>
        </h1>
          <div id="byline">By <p id="author">' . $author . '</p>' . ' ' . '<p id="timestamp">' . $timestamp . '</p></div>
        </div>
      </div>';
    }
}

// place recent post box after post content 
function after_post_content($content){
if (is_single()) {	
  $content .= recent_post();
}
	return $content;
}
add_filter( "the_content", "after_post_content" );

?>









