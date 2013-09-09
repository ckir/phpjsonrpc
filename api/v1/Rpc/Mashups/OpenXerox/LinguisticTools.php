<?php

namespace Rpc\Mashups\OpenXerox;

/**
 * These tools, called xfst, twolc, and lexc, are used in many linguistic applications such as morphological analysis, tokenisation, and shallow parsing of a wide variety of natural languages.
 * The finite state tools here are built on top of a software library that provides algorithms to create automata from regular expressions and equivalent formalisms and contains both classical operations, such as union and composition, and new algorithms such as replacement and local sequentialisation.
 * Finite-state linguistic resources are used in a series of applications and prototypes that range from OCR to terminology extraction, comprehension assistants, digital libraries and authoring and translation systems.
 * The components provided here are:
 * Tokenization
 * Morphology
 * Part of Speech Disambiguation (Tagging)
 */
// From: https://open.xerox.com/Services/fst-nlp-tools
class LinguisticTools {
	
	private $supported_languages;
	
	function __construct() {
		$cache = new \Rpc\Util\Cache\Cache ();
		$cacheadapter = $cache->setParameters ();
		$key = get_class();
		$key = preg_replace("/\\\\/", "_", $key);
		if ($cacheadapter) {
			$cachedinfo = $cache->getItem ( $key );
			if (! empty ( $cachedinfo )) {
				$this->supported_languages = json_decode ( $cachedinfo, true );
				return;
			}
		}
		
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
		
		// call the method
		$result = $client->getSupportedLanguages ();
		$result = $result->getSupportedLanguagesResult->string;
		
		for ($i = 0; $i < count($result); $i++) {
			$result[$i] = mb_strtolower($result[$i]);
		}
		
		$this->supported_languages = $result;
		if ($cacheadapter) {
			$cache->addItem ( $key, json_encode ( $result ) );
		}
		
		
	} // function __construct()
	
	private function isValid($language) {
		$language = mb_strtolower($language);
		if (array_search($language, $this->supported_languages)) {
			return true;
		} else {
			return false;
		}
	} // function isValid()
	
	/**
	 * Tokenize a string via OpenXerox.
	 * 
	 * Supported languages are:
	 * Czech
	 * English
	 * French
	 * German
	 * Greek
	 * Hungarian
	 * Italian
	 * Polish
	 * Russian
	 * Turkish
	 * 
	 * @param string $inputtext The string to tokenize.       	
	 * @param string $language  The language of the string.      	
	 * @return array
	 * @throws \Zend\Json\Exception\RuntimeException
	 * @throws \Zend\Json\Exception\InvalidArgumentException
	 */
	public function Tokenization($inputtext, $language) {
		
		if (! $this->isValid($language)) {
			throw new \Zend\Json\Exception\InvalidArgumentException("Invalid language", \Zend\Json\Server\Error::ERROR_INVALID_PARAMS);
		}
		
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
				"inputtext" => $inputtext,
				"language" => $language 
		) );
		
		// convert to array
		$result = \Zend\Json\Json::fromXml ( $result->TokenizationResult->any, true );
		$result = json_decode ( $result, true );
		return $result;
	} // function Tokenization()
	
	/**
	 * MorphoAnalysis a string via OpenXerox.
	 *
	 * Supported languages are:
	 * Czech
	 * English
	 * French
	 * German
	 * Greek
	 * Hungarian
	 * Italian
	 * Polish
	 * Russian
	 * Turkish
	 *
	 * @param string $inputtext The string to tokenize.
	 * @param string $language  The language of the string.
	 * @return array
	 * @throws \Zend\Json\Exception\RuntimeException
	 * @throws \Zend\Json\Exception\InvalidArgumentException
	 */
	public function MorphoAnalysis($inputtext, $language) {
	
		if (! $this->isValid($language)) {
			throw new \Zend\Json\Exception\InvalidArgumentException("Invalid language", \Zend\Json\Server\Error::ERROR_INVALID_PARAMS);
		}
	
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
		$result = $client->MorphoAnalysis ( array (
				"inputtext" => $inputtext,
				"language" => $language
		) );
	
		// convert to array
		$result = \Zend\Json\Json::fromXml ( $result->MorphoAnalysisResult->any, true );
		$result = json_decode ( $result, true );
		return $result;
	} // function MorphoAnalysis()
	
	/**
	 * PartOfSpeechTagging a string via OpenXerox.
	 *
	 * Supported languages are:
	 * Czech
	 * English
	 * French
	 * German
	 * Greek
	 * Hungarian
	 * Italian
	 * Polish
	 * Russian
	 * Turkish
	 *
	 * @param string $inputtext The string to tokenize.
	 * @param string $language  The language of the string.
	 * @return array
	 * @throws \Zend\Json\Exception\RuntimeException
	 * @throws \Zend\Json\Exception\InvalidArgumentException
	 */
	public function PartOfSpeechTagging($inputtext, $language) {
	
		if (! $this->isValid($language)) {
			throw new \Zend\Json\Exception\InvalidArgumentException("Invalid language", \Zend\Json\Server\Error::ERROR_INVALID_PARAMS);
		}
	
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
		$result = $client->PartOfSpeechTagging ( array (
				"inputtext" => $inputtext,
				"language" => $language
		) );
	
		// convert to array
		$result = \Zend\Json\Json::fromXml ( $result->PartOfSpeechTaggingResult->any, true );
		$result = json_decode ( $result, true );
		return $result;
	} // function PartOfSpeechTagging()
	
} // class LinguisticTools

?>