<?php
/*
Plugin Name: Link(s) to Wordpress
Description:This PHP class / wordpress plugin will take an array of links to crawl, and will fetch said links and turn them into WP posts. It can also update posts. Most useful when importing content from another CMS.
Version: 0.1
Author: Christian Nikkanen
License: GPL v2
*/

include "link2wp.class.php";

$link2wp = new Link2WP(); // Takes one parameter: true, makes it run in debug mode.

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
}*/



