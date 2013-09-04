<?php
require_once '../startup.php';

use Zend\Json\Server\Client;

function get_parent_path() {
	$url  = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';
	$url .= $_SERVER['SERVER_NAME'];
	$url .= htmlspecialchars($_SERVER['REQUEST_URI']);
	return dirname(dirname($url));
}

ob_end_flush ();
echo "<meta charset='utf-8'>";
echo '<pre>' . PHP_EOL;

if (preg_match ( "/localhost/", $_SERVER ["SERVER_NAME"] )) {
	$mode = 'development';
	$scriptUris = array (
			get_parent_path() . "/" . "servergreek.php",
			"http://phpjsonrpc.herokuapp.com/api/v1/servergreek.php" 
	);
} else {
	$mode = 'production';
	$scriptUris = array (
			"http://phpjsonrpc.herokuapp.com/api/v1/servergreek.php" 
	);
}

foreach ( $scriptUris as $scriptUri ) {
	try {
		echo PHP_EOL . PHP_EOL;
		echo str_repeat("*", strlen($scriptUri) + 2) . PHP_EOL;
		echo str_repeat("*", strlen($scriptUri) + 2 ) . PHP_EOL;
		echo $scriptUri . PHP_EOL;
		echo str_repeat("*", strlen($scriptUri) + 2) . PHP_EOL;
		echo str_repeat("*", strlen($scriptUri) + 2) . PHP_EOL;
		$client = new Client ( $scriptUri );
		
		try {
			$m = "Testing: getGreekNamedays";
			echo PHP_EOL . str_repeat("*", strlen($m) + 2) . PHP_EOL;
			echo $m . PHP_EOL;
			echo str_repeat("*", strlen($m) + 2) . PHP_EOL;
			$response = $client->call ( "getGreekNamedays" );
			echo $client->getLastRequest ()->toJson () . PHP_EOL;
			print_r ( $response );
		} catch ( Exception $e ) {
			echo $client->getLastRequest ()->toJson () . PHP_EOL;
			echo "Error return: " . $e->getMessage () . PHP_EOL;
		}
		
		try {
			$m = "Testing: getGreekPhoneInfo";
			echo PHP_EOL . str_repeat("*", strlen($m) + 2) . PHP_EOL;
			echo $m . PHP_EOL;
			echo str_repeat("*", strlen($m) + 2) . PHP_EOL;
			$parameters = array (
					"number" => 2109588888 
			);
			$response = $client->call ( "getGreekPhoneInfo", $parameters );
			echo $client->getLastRequest ()->toJson () . PHP_EOL;
			print_r ( $response );
			
		} catch ( Exception $e ) {
			echo $client->getLastRequest ()->toJson () . PHP_EOL;
			echo "Error return: " . $e->getMessage () . PHP_EOL;
		}
		
		try {
			$m = "Testing: getGreekStemmed";
			echo PHP_EOL . str_repeat("*", strlen($m) + 2) . PHP_EOL;
			echo $m . PHP_EOL;
			echo str_repeat("*", strlen($m) + 2) . PHP_EOL;
			$parameters = array (
					"words" => 'Σε ανακοίνωσή της η "ΑΔΕΔΥ" κατηγορεί την κυβέρνηση ότι, καθοδηγούμενη από την τρόικα, έχει στόχο «τη διάλυση των Δημοσίων Υπηρεσιών και των δομών του Κοινωνικού Κράτους»',
					"commonwords" => true 
			);
			$response = $client->call ( "getGreekStemmed", $parameters );
			echo $client->getLastRequest ()->toJson () . PHP_EOL;
			print_r ( $response );
		} catch ( Exception $e ) {
			echo $client->getLastRequest ()->toJson () . PHP_EOL;
			echo "Error return: " . $e->getMessage () . PHP_EOL;
		}
		
		try {
			$m = "Testing: getGreekSlug";
			echo PHP_EOL . str_repeat("*", strlen($m) + 2) . PHP_EOL;
			echo $m . PHP_EOL;
			echo str_repeat("*", strlen($m) + 2) . PHP_EOL;
			$parameters = array (
					"string" => 'Σε ανακοίνωσή της η ΑΔΕΔΥ κατηγορεί την κυβέρνηση ότι, καθοδηγούμενη από την τρόικα, έχει στόχο «τη διάλυση των Δημοσίων Υπηρεσιών και των δομών του Κοινωνικού Κράτους»' 
			);
			$response = $client->call ( "getGreekSlug", $parameters );
			echo $client->getLastRequest ()->toJson () . PHP_EOL;
			print_r ( $response );
		} catch ( Exception $e ) {
			echo $client->getLastRequest ()->toJson () . PHP_EOL;
			echo "Error return: " . $e->getMessage () . PHP_EOL;
		}
		
		try {
			$m = "Testing: getGreekTokens";
			echo PHP_EOL . str_repeat("*", strlen($m) + 2) . PHP_EOL;
			echo $m . PHP_EOL;
			echo str_repeat("*", strlen($m) + 2) . PHP_EOL;
			$parameters = array (
					"content" => 'Σε ανακοίνωσή της η "ΑΔΕΔΥ" κατηγορεί την κυβέρνηση ότι, καθοδηγούμενη από την τρόικα, έχει στόχο «τη διάλυση των Δημοσίων Υπηρεσιών και των δομών του Κοινωνικού Κράτους»'
			);
			$response = $client->call ( "getGreekTokens", $parameters );
			echo $client->getLastRequest ()->toJson () . PHP_EOL;
			print_r ( $response );
		} catch ( Exception $e ) {
			echo $client->getLastRequest ()->toJson () . PHP_EOL;
			echo "Error return: " . $e->getMessage () . PHP_EOL;
		}
		
		try {
			$m = "Testing: getGreekGreeglish";
			echo PHP_EOL . str_repeat("*", strlen($m) + 2) . PHP_EOL;
			echo $m . PHP_EOL;
			echo str_repeat("*", strlen($m) + 2) . PHP_EOL;
			$parameters = array (
					"text" => 'Σε ανακοίνωσή της η "ΑΔΕΔΥ" κατηγορεί την κυβέρνηση ότι, καθοδηγούμενη από την τρόικα, έχει στόχο «τη διάλυση των Δημοσίων Υπηρεσιών και των δομών του Κοινωνικού Κράτους»'
			);
			$response = $client->call ( "getGreekGreeglish", $parameters );
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