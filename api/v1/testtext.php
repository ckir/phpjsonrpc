<?php
require_once 'startup.php';

use Zend\Json\Server\Client;

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
echo '<pre>' . PHP_EOL;

$scriptUris = array (
		get_base_path () . "/" . "text.php",
		"http://phpjsonrpc.herokuapp.com/api/v1/text.php" 
);

foreach ( $scriptUris as $scriptUri ) {
	try {
		echo $scriptUri . PHP_EOL;
		$client = new Client ( $scriptUri );
		
		echo PHP_EOL . "Testing: getStemmed" . PHP_EOL;
		$parameters = array (
				"words" => 'Greek bond yields hit their highest in three weeks today after German Finance Minister Wolfgang Schaeuble said Athens would need a third bailout and would get no more debt haircuts.',
				"commonwords" => true 
		);
		$response = $client->call ( "getStemmed", $parameters );
		var_dump ( $response );
		
		echo PHP_EOL . "Testing: removeCommonWords" . PHP_EOL;
		$parameters = array (
				"input" => 'That still ignores a warning by the International Monetary Fund which said at the end of July the financing gap, which it puts at €11bn to cover the period into 2015, would need to involve some debt relief. In tackling an issue that causes deep anxiety',
		);
		$response = $client->call ( "removeCommonWords", $parameters );
		echo $parameters["input"] . PHP_EOL;
		echo $response . PHP_EOL;
		
		echo PHP_EOL . "Testing: getSimilarText" . PHP_EOL;
		$parameters = array (
				"first" => 'Greece hopes to convince troika to delay decision on new cuts for 2015-16 - Kathimerini',
				"second" => 'Greece hopes to convince troika to delay decision on new cuts for 2015-16',
				"commonwords" => true,
				"stemming" => true
		);
		$response = $client->call ( "getSimilarText", $parameters );
		var_dump ( $response );

		echo PHP_EOL . "Testing: getMetaphone" . PHP_EOL;
		$parameters = array (
				"strings" => explode(" ", 'Greek bond yields hit their highest in three weeks today after German Finance Minister Wolfgang Schaeuble said Athens would need a third bailout and would get no more debt haircuts.'),
				"commonwords" => true,
				"stemming" => true
		);
		$response = $client->call ( "getMetaphone", $parameters );
		var_dump ( $response );
		
	} catch ( Exception $e ) {
		echo $e->getMessage () . PHP_EOL;
	}
}

echo '</pre>' . PHP_EOL;