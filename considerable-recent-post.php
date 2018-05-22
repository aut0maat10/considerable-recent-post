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
function con_recent_post() {
  $args = array( 'numberposts' => '1');
  $recent_posts = wp_get_recent_posts( $args );
    foreach( $recent_posts as $recent ){
      $image = wp_get_attachment_url(get_post_thumbnail_id($recent['ID']));
      $author = get_the_author_link();
      $categories = get_the_category($recent["ID"]);
      $timestamp = human_time_diff( get_the_time('U'), current_time('timestamp') ) . ' ago';

      return 
      '<div class="card-container">
        <div class="img-container">
          <img class="card-image" src="'. $image .'">' . 
        '</div>
        <div class="card-text">
          <div class="category-wrapper">
            <h4 class="card-category">' . $categories[0]->name . '</h4>
            <div class="mobile-show">
              <h4 class="mobile-timestamp">' .'  | ' . $timestamp .'</h4>
            </div>
          </div>' . 
          '<h2 class="post-title">
            <a href="' . get_permalink($recent["ID"]) . '">' . $recent["post_title"] . '</a>
          </h2>
          <div class="byline">
            By <p class="author">' . $author . '</p>' . ' ' . '<p class="timestamp">' . $timestamp . '</p>
          </div>
        </div>
      </div>';
    }
}

// place recent post box after post content 
function after_post_content($content){
if (is_single()) {	
  $content .= con_recent_post();
}
	return $content;
}
add_filter( "the_content", "after_post_content" );











