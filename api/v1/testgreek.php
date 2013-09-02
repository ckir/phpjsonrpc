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
echo "<meta charset='utf-8'> ";
echo '<pre>' . PHP_EOL;
if (preg_match ( "/localhost/", $_SERVER ["SERVER_NAME"] )) {
	$mode = 'development';
	$scriptUris = array (
			get_base_path () . "/" . "greek.php",
			"http://phpjsonrpc.herokuapp.com/api/v1/greek.php" 
	);
} else {
	$mode = 'production';
	$scriptUris = array (
			"http://phpjsonrpc.herokuapp.com/api/v1/greek.php" 
	);
}

foreach ( $scriptUris as $scriptUri ) {
	try {
		echo PHP_EOL . PHP_EOL;
		echo "*****************************************" . PHP_EOL;
		echo $scriptUri . PHP_EOL;
		echo "*****************************************" . PHP_EOL;
		$client = new Client ( $scriptUri );
		
		try {
			echo PHP_EOL . "Testing: getNamedays" . PHP_EOL;
			$response = $client->call ( "getNamedays" );
			echo $client->getLastRequest ()->toJson () . PHP_EOL;
			var_dump ( $response ) . PHP_EOL . PHP_EOL;
		} catch ( Exception $e ) {
			echo $client->getLastRequest ()->toJson () . PHP_EOL;
			echo $e->getMessage () . PHP_EOL;
		}
		
		try {
			echo PHP_EOL . "Testing: getPhoneInfo" . PHP_EOL;
			$parameters = array (
					"number" => 2109588888 
			);
			$response = $client->call ( "getPhoneInfo", $parameters );
			echo $client->getLastRequest ()->toJson () . PHP_EOL;
			var_dump ( $response ) . PHP_EOL . PHP_EOL;
		} catch ( Exception $e ) {
			echo $client->getLastRequest ()->toJson () . PHP_EOL;
			echo $e->getMessage () . PHP_EOL;
		}
		
		try {
			echo PHP_EOL . "Testing: getStemmed" . PHP_EOL;
			$parameters = array (
					"words" => 'Σε ανακοίνωσή της η ΑΔΕΔΥ κατηγορεί την κυβέρνηση ότι, καθοδηγούμενη από την τρόικα, έχει στόχο «τη διάλυση των Δημοσίων Υπηρεσιών και των δομών του Κοινωνικού Κράτους»',
					"commonwords" => true 
			);
			$response = $client->call ( "getStemmed", $parameters );
			echo $client->getLastRequest ()->toJson () . PHP_EOL;
			var_dump ( $response ) . PHP_EOL . PHP_EOL;
		} catch ( Exception $e ) {
			echo $client->getLastRequest ()->toJson () . PHP_EOL;
			echo $e->getMessage () . PHP_EOL;
		}
		
		try {
			echo PHP_EOL . "Testing: getSlug" . PHP_EOL;
			$parameters = array (
					"string" => 'Σε ανακοίνωσή της η ΑΔΕΔΥ κατηγορεί την κυβέρνηση ότι, καθοδηγούμενη από την τρόικα, έχει στόχο «τη διάλυση των Δημοσίων Υπηρεσιών και των δομών του Κοινωνικού Κράτους»' 
			);
			$response = $client->call ( "getSlug", $parameters );
			echo $client->getLastRequest ()->toJson () . PHP_EOL;
			var_dump ( $response ) . PHP_EOL . PHP_EOL;
		} catch ( Exception $e ) {
			echo $client->getLastRequest ()->toJson () . PHP_EOL;
			echo $e->getMessage () . PHP_EOL;
		}
		
		try {
			echo PHP_EOL . "Testing: getTokens" . PHP_EOL;
			$parameters = array (
					"content" => 'Σε ανακοίνωσή της η "ΑΔΕΔΥ" κατηγορεί την κυβέρνηση ότι, καθοδηγούμενη από την τρόικα, έχει στόχο «τη διάλυση των Δημοσίων Υπηρεσιών και των δομών του Κοινωνικού Κράτους»'
			);
			$response = $client->call ( "getTokens", $parameters );
			echo $client->getLastRequest ()->toJson () . PHP_EOL;
			var_dump ( $response ) . PHP_EOL . PHP_EOL;
			
		} catch ( Exception $e ) {
			echo $client->getLastRequest ()->toJson () . PHP_EOL;
			echo $e->getMessage () . PHP_EOL;
		}
		
		try {
			echo PHP_EOL . "Testing: getGreeglish" . PHP_EOL;
			$parameters = array (
					"text" => 'Σε ανακοίνωσή της η "ΑΔΕΔΥ" κατηγορεί την κυβέρνηση ότι, καθοδηγούμενη από την τρόικα, έχει στόχο «τη διάλυση των Δημοσίων Υπηρεσιών και των δομών του Κοινωνικού Κράτους»'
			);
			$response = $client->call ( "getGreeglish", $parameters );
			echo $client->getLastRequest ()->toJson () . PHP_EOL;
			var_dump ( $response ) . PHP_EOL . PHP_EOL;
				
		} catch ( Exception $e ) {
			echo $client->getLastRequest ()->toJson () . PHP_EOL;
			echo $e->getMessage () . PHP_EOL;
		}
	} catch ( Exception $e ) {
		echo $e->getMessage () . PHP_EOL;
	}
}

echo '</pre>' . PHP_EOL;