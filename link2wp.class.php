  <?php

require "vendor/autoload.php";

class Link2WP {

	protected static $debug_mode;

	/**
	 * Determine if code should run in "debug" mode.
	 * @param type|bool $debug_mode
	 * @return type
	 */

	function __construct($debug_mode = false){
		if($debug_mode){
			self::$debug_mode = true;
		}
		else{
			self::$debug_mode = false;
		}
	}

	/**
	 * Parse page from string, ie. cURL result (or file_get_contents)
	 * @param type $page_contents
	 * @param type|string $bodypart
	 * @param type|string $titlepart
	 * @return type
	 */

	public function parsePage($page_contents,$bodypart = "body", $titlepart = 'title'){

		if(is_null($titlepart)) $titlepart = 'title';

		$crawler = new Symfony\Component\DomCrawler\Crawler($page_contents);

		$parsed_page = new stdClass();

		$parsed_page->title = $crawler->filter($titlepart)->eq(0)->text();

		$crawler = $crawler->filter($bodypart);
		foreach ($crawler as $domElement) {
				$parsed_page->content = $domElement->nodeValue;
		}

		if(self::$debug_mode){
			echo "Parsed page:".PHP_EOL;
			print_r($parsed_page);
		}

		return $parsed_page;

	}

	/**
	 * Create post to Wordpress.
	 * @param type|string $title
	 * @param type|string $content
	 * @param type|array $opt
	 * @return type
	 */

	public function createPost($title = "(no title)", $content = "(no content)",$opt = array()){
		$p = array(
			"post_content" => $content,
			"post_title" => $title,
		); // arguments for wp_insert_post();

		foreach($opt as $option_key => $option_value){
			$p[$option_key] = $option_value;
		}

		if(self::$debug_mode){
			echo "Post creation is disabled in debug mode. Arguments: ".PHP_EOL;
			var_dump($p);
			return;
		}

		$post_id = wp_insert_post($p);

		//call_user_func_array("link2wp_createpost_callback",array($post_id,link??));

		return $post_id;
	}

	/**
	 * Update post in wordpress
	 * @param type|null $id
	 * @param type|string $title
	 * @param type|string $content
	 * @param type|array $opt
	 * @return type
	 */

	public function updatePost($id = null, $title = "(no title)", $content = "(no content)", $opt = array()){
		if(is_null($id)) throw new Exception("ID cannot be null!", 1);

		$p = array(
			"ID" => $id,
			"post_content" => $content,
			"post_title" => $title,
		); // arguments for wp_insert_post();

		foreach($opt as $option_key => $option_value){
			$p[$option_key] = $option_value;
		}

		if(self::$debug_mode){
			echo "Post updates are disabled in debug mode. Arguments: ".PHP_EOL;
			var_dump($p);
			return;
		}

		$post_id = wp_update_post($p);

		return $post_id;
	}

	/**
	 * Run the class functions.
	 * @param type|array $links
	 * @param type $crawl_options
	 * @param type|bool $update
	 * @param type|array $opt
	 * @return type
	 */

	public function init($links = array(), $crawl_options, $update = false, $opt = array()){
		// update is false by default, create new posts

		if(is_array($crawl_options)){
			$bodypart = $crawl_options[0];
			$titlepart = $crawl_options[1];
		}
		else{
			$bodypart = $crawl_options;
		}

		foreach($links as $id => $link){
			$page_contents = $this->fetchPage($link);
			if(!$page_contents) throw new Exception("Request timed out / 404 at link: $link", 1);

			$parsed_page = $this->parsePage($page_contents,$bodypart, (isset($titlepart) ? $titlepart : null));

			$opt['link2wp_original_link'] = $link; // Pass the original link (callback could use it)

			if($update){
				$this->updatePost($id,$parsed_page->title,$parsed_page->content,$opt);
			}

			else{
				$this->createPost($parsed_page->title,$parsed_page->content,$opt);
			}
		}

		if(self::$debug_mode){
			echo "Links array: ".PHP_EOL;
			var_dump($links);

			echo "Crawl options: ".PHP_EOL;
			var_dump($crawl_options);

			echo "Is it update mode?: ".PHP_EOL;
			var_dump($update);

			echo "Optional arguments: ".PHP_EOL;
			var_dump($opt);
		}

	}

	/**
	 * Fetches page, timeout is 10 seconds
	 * @param type $url
	 * @param type $timeout
	 * @return type
	 */

	public function fetchPage($url,$timeout = 10){

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);

		$data = curl_exec($ch);
		curl_close($ch);

		return $data;
	}


}
