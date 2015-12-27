# Link(s) to Wordpress
Simple PHP class / Wordpress plugin that takes an array of links and turns them into Wordpress posts.

Usage
-----
    $link2wp = new Link2WP(); // Takes one parameter: true, makes it run in debug mode.
    $pages = [
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

Background
----------
Recently I found myself neck-deep in a project that involved importing a whole site from Drupal to Wordpress. Just looking at Drupal's DB schema is enough to feel nauseous, so I didn't want to go the manual labor route. [This plugin](https://github.com/jpSimkins/Drupal2WordPress-Plugin) by Jeremy Simkins did an awesome job, and it imported all posts and users to the Wordpress database, but unfortunately (most likely heavy customizations), some post types we're missing all content.

That gave me the idea of looping through all imported posts and updating their contents with the old site contents. (Hacking into plugin code and adding a meta-field that has a direct link to the correct post at the old site was easy enough, so looping was a breeze)

Thanks
------
Special thanks to Symfony framework, their dom crawler & css-selector libraries are awesome and made this plugin possible.