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
		get_base_path () . "/" . "greek.php",
		"http://phpjsonrpc.herokuapp.com/api/v1/greek.php" 
);

foreach ( $scriptUris as $scriptUri ) {
	try {
		echo $scriptUri . PHP_EOL;
		echo PHP_EOL . "Testing: getNamedays" . PHP_EOL;
		$client = new Client ( $scriptUri );
		$response = $client->call ( "getNamedays" );
		var_dump ( $response );
	} catch ( Exception $e ) {
		echo $e->getMessage () . PHP_EOL;
	}
}

echo '</pre>' . PHP_EOL;