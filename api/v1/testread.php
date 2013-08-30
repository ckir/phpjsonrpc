<?php
require_once 'startup.php';

use Zend\Json\Server\Client;
function get_base_path() {
	$s = empty ( $_SERVER ["HTTPS"] ) ? '' : ($_SERVER ["HTTPS"] == "on") ? "s" : "";
	$protocol = substr ( strtolower ( $_SERVER ["SERVER_PROTOCOL"] ), 0, strpos ( strtolower ( $_SERVER ["SERVER_PROTOCOL"] ), "/" ) ) . $s;
	$port = ($_SERVER ["SERVER_PORT"] == "80") ? "" : (":" . $_SERVER ["SERVER_PORT"]);
	$uri = explode ( "/", $_SERVER ['REQUEST_URI'] );
	array_pop ( $uri );
	$uri = implode ( "/", $uri );
	return $protocol . "://" . $_SERVER ['SERVER_NAME'] . $port . $uri;
} // function get_base_path

ob_end_flush ();
echo '<pre>' . PHP_EOL;

$scriptUris = array (
		get_base_path () . "/" . "read.php",
		"http://phpjsonrpc.herokuapp.com/api/v1/read.php" 
);

foreach ( $scriptUris as $scriptUri ) {
	try {
		echo $scriptUri . PHP_EOL;
		echo PHP_EOL . "Method: getFeed" . PHP_EOL;
		$client = new Client ( $scriptUri );
		$parameters = array (
				"uri" => "http://www.nytimes.com/services/xml/rss/nyt/Europe.xml",
				"format" => "json",
				"purifier" => array (
						true 
				),
				"escape" => true 
		);
		$response = $client->call ( "getFeed", $parameters );
		var_dump ( $response );
		
		echo PHP_EOL . "Method: getUri" . PHP_EOL;
		$client = new Client ( $scriptUri );
		$parameters = array (
				"uri" => "http://www.bloomberg.com/news/2013-08-20/germany-s-schaeuble-says-greece-needs-new-aid-program.html",
				"xpaths" => array (
						'//*[@id="story"]' 
				),
				"purifier" => array (
						true 
				),
				"escape" => false 
		);
		$response = $client->call ( "getUri", $parameters );
		var_dump ( $response );
		
		echo PHP_EOL . "Method: getUriText" . PHP_EOL;
		$client = new Client ( $scriptUri );
		$parameters = array (
				"uri" => "http://www.bloomberg.com/news/2013-08-20/germany-s-schaeuble-says-greece-needs-new-aid-program.html",
				"xpaths" => array (
						'//*[@id="story"]'
				),
				"purifier" => array (
						true
				),
				"escape" => false
		);
		$response = $client->call ( "getUriText", $parameters );
		var_dump ( $response );
	} catch ( Exception $e ) {
		echo $e->getMessage () . PHP_EOL;
	}
}

echo '</pre>' . PHP_EOL;