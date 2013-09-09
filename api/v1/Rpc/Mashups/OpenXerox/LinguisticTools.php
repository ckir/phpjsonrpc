<?php

namespace Rpc\Mashups\OpenXerox;

/**
 *
 * @author user
 *        
 */
class LinguisticTools {
	public function Tokenization($inputtext, $language) {
		
		// WSDL URL & autentication
		$wsdl = "https://services.open.xerox.com/Wsdl.svc/fst-nlp-tools";
		$user = NULL;
		$password = NULL;
		
		// proxy configuration
		$proxy_host = NULL;
		$proxy_port = NULL;
		
		// SOAP client configuration
		$client = new OpenXeroxSoapClient ( $wsdl, $user, $password, $proxy_host, $proxy_port );
		$client->connect ();
		
		// call the language identifier
		$result = $client->Tokenization ( array (
				"inputtext" => base64_encode($inputtext),
				"language" => $language
		) );
		
		return $result->TokenizationResult;
	} // function Tokenization()
} // class LinguisticTools

?>