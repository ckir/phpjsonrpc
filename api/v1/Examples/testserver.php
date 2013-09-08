<?php
require_once '../startup.php';

use Zend\Json\Server\Client;

function get_parent_path() {
	$url  = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';
	$url .= $_SERVER['SERVER_NAME'];
	$url .= htmlspecialchars($_SERVER['REQUEST_URI']);
	return dirname(dirname($url));
}
//
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
$a = get_parent_path();

echo '<pre>' . PHP_EOL;
if (preg_match ( "/localhost/", $_SERVER ["SERVER_NAME"] )) {
	$mode = 'development';
	$scriptUris = array (
			get_parent_path() . "/" . "server.php",
			"http://phpjsonrpc.herokuapp.com/api/v1/text.php" 
	);
} else {
	$mode = 'production';
	$scriptUris = array (
			"http://phpjsonrpc.herokuapp.com/api/v1/text.php" 
	);
}

foreach ( $scriptUris as $scriptUri ) {
	try {
		echo PHP_EOL;
		echo str_repeat("*", strlen($scriptUri) + 2) . PHP_EOL;
		echo str_repeat("*", strlen($scriptUri) + 2 ) . PHP_EOL;
		echo $scriptUri . PHP_EOL;
		echo str_repeat("*", strlen($scriptUri) + 2) . PHP_EOL;
		echo str_repeat("*", strlen($scriptUri) + 2) . PHP_EOL;
		$client = new Client ( $scriptUri );
		
		try {
			$m = "Testing: getI18nCldrAll";
			echo PHP_EOL . str_repeat("*", strlen($m) + 2) . PHP_EOL;
			echo $m . PHP_EOL;
			echo str_repeat("*", strlen($m) + 2) . PHP_EOL;
			$parameters = array (
					"language" => "el",
					"return" => "json"
			);
			$response = $client->call ( "getI18nCldrAll", $parameters );
			echo $client->getLastRequest ()->toJson () . PHP_EOL;
			echo json_encode($response) . PHP_EOL;
			// print_r ( $response ); // Huge output
		} catch ( Exception $e ) {
			echo $client->getLastRequest ()->toJson () . PHP_EOL;
			echo "Error return: " . $e->getMessage () . PHP_EOL;
		}
		
		try {
			$m = "Testing: getReadersFeed";
			echo PHP_EOL . str_repeat("*", strlen($m) + 2) . PHP_EOL;
			echo $m . PHP_EOL;
			echo str_repeat("*", strlen($m) + 2) . PHP_EOL;
			$parameters = array (
					"uri" => "http://www.nytimes.com/services/xml/rss/nyt/Europe.xml",
					"format" => "json",
					"purifier" => array (
							true
					),
					"escape" => true
			);
			$response = $client->call ( "getReadersFeed", $parameters );
			echo $client->getLastRequest ()->toJson () . PHP_EOL;
			print_r ( $response );
		} catch ( Exception $e ) {
			echo $client->getLastRequest ()->toJson () . PHP_EOL;
			echo "Error return: " . $e->getMessage () . PHP_EOL;
		}
		
		try {
			$m = "Testing: getReadersUri";
			echo PHP_EOL . str_repeat("*", strlen($m) + 2) . PHP_EOL;
			echo $m . PHP_EOL;
			echo str_repeat("*", strlen($m) + 2) . PHP_EOL;
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
			$response = $client->call ( "getReadersUri", $parameters );
			echo $client->getLastRequest ()->toJson () . PHP_EOL;
			print_r ( $response );
		} catch ( Exception $e ) {
			echo $client->getLastRequest ()->toJson () . PHP_EOL;
			echo "Error return: " . $e->getMessage () . PHP_EOL;
		}
		
		try {
			$m = "Testing: getReadersUriText";
			echo PHP_EOL . str_repeat("*", strlen($m) + 2) . PHP_EOL;
			echo $m . PHP_EOL;
			echo str_repeat("*", strlen($m) + 2) . PHP_EOL;
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
			$response = $client->call ( "getReadersUriText", $parameters );
			echo $client->getLastRequest ()->toJson () . PHP_EOL;
			print_r ( $response );
		} catch ( Exception $e ) {
			echo $client->getLastRequest ()->toJson () . PHP_EOL;
			echo "Error return: " . $e->getMessage () . PHP_EOL;
		}
		
		try {
			$m = "Testing: getTextStemmed";
			echo PHP_EOL . str_repeat("*", strlen($m) + 2) . PHP_EOL;
			echo $m . PHP_EOL;
			echo str_repeat("*", strlen($m) + 2) . PHP_EOL;
			$parameters = array (
					"words" => 'Greek bond yields hit their highest in three weeks today after German Finance Minister Wolfgang Schaeuble said Athens would need a third bailout and would get no more debt haircuts.',
					"commonwords" => true 
			);
			$response = $client->call ( "getTextStemmed", $parameters );
			echo $client->getLastRequest ()->toJson () . PHP_EOL;
			print_r ( $response );
		} catch ( Exception $e ) {
			echo $client->getLastRequest ()->toJson () . PHP_EOL;
			echo "Error return: " . $e->getMessage () . PHP_EOL;
		}
		
		try {
			$m = "Testing: getTextRemoveCommonWords";
			echo PHP_EOL . str_repeat("*", strlen($m) + 2) . PHP_EOL;
			echo $m . PHP_EOL;
			echo str_repeat("*", strlen($m) + 2) . PHP_EOL;
			$parameters = array (
					"input" => 'That still ignores a warning by the International Monetary Fund which said at the end of July the financing gap, which it puts at €11bn to cover the period into 2015, would need to involve some debt relief. In tackling an issue that causes deep anxiety' 
			);
			$response = $client->call ( "getTextRemoveCommonWords", $parameters );
			echo $client->getLastRequest ()->toJson () . PHP_EOL;
			echo $parameters ["input"] . PHP_EOL;
			echo $response . PHP_EOL;
		} catch ( Exception $e ) {
			echo $client->getLastRequest ()->toJson () . PHP_EOL;
			echo "Error return: " . $e->getMessage () . PHP_EOL;
		}
		
		try {
			$m = "Testing: getTextSimilarity";
			echo PHP_EOL . str_repeat("*", strlen($m) + 2) . PHP_EOL;
			echo $m . PHP_EOL;
			echo str_repeat("*", strlen($m) + 2) . PHP_EOL;
			$parameters = array (
					"first" => 'Greece hopes to convince troika to delay decision on new cuts for 2015-16 - Kathimerini',
					"second" => 'Greece hopes to convince troika to delay decision on new cuts for 2015-16',
					"commonwords" => true,
					"stemming" => true 
			);
			$response = $client->call ( "getTextSimilarity", $parameters );
			echo $client->getLastRequest ()->toJson () . PHP_EOL;
			print_r ( $response );
		} catch ( Exception $e ) {
			echo $client->getLastRequest ()->toJson () . PHP_EOL;
			echo "Error return: " . $e->getMessage () . PHP_EOL;
		}
		
		try {
			$m = "Testing: getTextMetaphone";
			echo PHP_EOL . str_repeat("*", strlen($m) + 2) . PHP_EOL;
			echo $m . PHP_EOL;
			echo str_repeat("*", strlen($m) + 2) . PHP_EOL;
			$parameters = array (
					"strings" => explode ( " ", 'Greek bond yields hit their highest in three weeks today after German Finance Minister Wolfgang Schaeuble said Athens would need a third bailout and would get no more debt haircuts.' ),
					"commonwords" => true,
					"stemming" => true 
			);
			$response = $client->call ( "getTextMetaphone", $parameters );
			echo $client->getLastRequest ()->toJson () . PHP_EOL;
			print_r ( $response );
		} catch ( Exception $e ) {
			echo $client->getLastRequest ()->toJson () . PHP_EOL;
			echo "Error return: " . $e->getMessage () . PHP_EOL;
		}
	} catch ( Exception $e ) {
		echo $e->getMessage () . PHP_EOL;
	}
}

echo '</pre>' . PHP_EOL;