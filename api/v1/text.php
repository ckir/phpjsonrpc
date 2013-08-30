<?php
require_once 'startup.php';
$stemmer = new apiv1();
$a = $stemmer->getStemmed("antonis samaras", true);
// $logger = new Zend\Log\Logger;
// $writer = new Zend\Log\Writer\Stream('log.txt');

// $logger->addWriter($writer);

// Zend\Log\Logger::registerErrorHandler($logger);
// Zend\Log\Logger::registerExceptionHandler($logger);

class apiv1 {
	
	/**
	 * Takes a list of words and returns them reduced to their stems.
	 *
	 * $words can be either a string or an array. If it is a string, it will
	 * be split into separate words on whitespace, commas, or semicolons. If
	 * an array, it assumes one word per element.
	 *
	 * @param mixed $words
	 *        	String or array of word(s) to reduce
	 * @param bool $commonwords
	 *        	Remove common words prior to stemming
	 * @access public
	 * @return array List of word stems
	 */
	public function getStemmed($words, $commonwords = false) {
		try {
			$stemmer = new Local\Text\Stemmer\PorterStemmer ();
			return $stemmer->getStemmed ( $words, $commonwords );
		} catch ( Exception $e ) {
			throw new \Zend\Json\Server\Exception\InvalidArgumentException ( 'Service unavailable', \Zend\Json\Server\Error::ERROR_INTERNAL );
		}
	} // function getStemmed()
	
	/**
	 * Removes common words from strings.
	 *
	 * @param string $input
	 *        	The string to remove common words from.
	 * @return string
	 */
	public function removeCommonWords($input) {
		try {
			return \Local\Text\CommonWords\CommonWords::removeCommonWords ( $input );
		} catch ( Exception $e ) {
			throw new \Zend\Json\Server\Exception\InvalidArgumentException ( 'Service unavailable', \Zend\Json\Server\Error::ERROR_INTERNAL );
		}
	} // function removeCommonWords()
	
	/**
	 * Calculate the similarity between two strings
	 *
	 * @param string $first
	 *        	First string
	 * @param string $second
	 *        	Second string
	 * @param bool $commonwords
	 *        	Remove common words prior to calculation
	 * @param bool $stemming
	 *        	Apply stemming to strings prior to calculation.
	 * @return array
	 */
	public function getSimilarText($first, $second, $commonwords = false, $stemming = false) {
		try {
			$getSimilarText = new Local\Text\Misc\Misc ();
			return $getSimilarText->getSimilarText ( $first, $second, $commonwords, $stemming );
		} catch ( Exception $e ) {
			throw new \Zend\Json\Server\Exception\InvalidArgumentException ( 'Service unavailable', \Zend\Json\Server\Error::ERROR_INTERNAL );
		}
	} // function getSimilarText()
	
	/**
	 * Calculate the metaphone keys of strings in a array
	 *
	 * @param array $strings
	 *        	Array of the input strings.
	 * @param bool $commonwords
	 *        	Remove common words prior to calculation
	 * @param bool $stemming
	 *        	Apply stemming to strings prior to calculation.
	 * @return array
	 */
	public function getMetaphone($strings, $commonwords = false, $stemming = false) {
		try {
			$getMetaphone = new Local\Text\Misc\Misc ();
			return $getMetaphone->getMetaphone ( $strings, $commonwords, $stemming );
		} catch ( Exception $e ) {
			throw new \Zend\Json\Server\Exception\InvalidArgumentException ( 'Service unavailable', \Zend\Json\Server\Error::ERROR_INTERNAL );
		}
	} // function getMetaphone()
} // class apiv1

$server = new Zend\Json\Server\Server ();
$server->setClass ( new apiv1 () );

if ('GET' == $_SERVER ['REQUEST_METHOD']) {
	// Indicate the URL endpoint, and the JSON-RPC version used:
	$server->setTarget ( '/json-rpc.php' )->setEnvelope ( Zend\Json\Server\Smd::ENV_JSONRPC_2 );
	
	// Grab the SMD
	$smd = $server->getServiceMap ();
	
	// Return the SMD to the client
	header ( 'Content-Type: application/json' );
	echo $smd;
	return;
}

$server->handle ();