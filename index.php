<?php
/*
Plugin Name: Link(s) to Wordpress
Description:This PHP class / wordpress plugin will take an array of links to crawl, and will fetch said links and turn them into WP posts. It can also update posts. Most useful when importing content from another CMS.
Version: 0.1
Author: Christian Nikkanen
License: GPL v2
*/

add_action("plugins_loaded",function(){
   global $link2wp;
   require_once dirname( __FILE__ ) ."/link2wp.class.php";
   $link2wp = new Link2WP();
});


// Example usage below:

/*$pages = [
  1 => "http://example.com/sample-post/",
  2 => "http://example.com/sample-post-2/"
];


$crawl_options = [
  ".article__body",
  ".article__title"
];
// CSS selectors for article body and title.

$opt = [
  "post_status" => "publish",
  "post_type" => "post"
];
// Arguments for wp_insert_post

try{
  $link2wp->init($pages,$crawl_options, true, $opt); // update posts instead of creating new ones. Uses array keys as ID! Change true => false if you want to create new posts.
}

catch(Exception $e){
  echo "Error: $e";
}



*/

/**
 * Sample function, create additional post meta after post creation.
 * @param type $args
 */

/*

function link2wp_createpost_callback($args){
  $post_id = $args["postID"];
  $wp_insert_post_args = $args["misc"];
  $original_link = $args["misc"]['link2wp_original_link'];

  $page_contents = Link2WP::fetchPage($original_link);
  $meta_value = Link2WP::getComponentText($page_contents,".article__aftercontent--tags");

  return add_post_meta($post_id,"youtube-link",$meta_value);


}
*/