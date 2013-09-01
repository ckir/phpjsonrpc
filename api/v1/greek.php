<?php
require_once 'startup.php';

class apiv1 {
	
	/**
	 * Get a list of namedays for today, tomorrow and the day after tomorrow.
	 *
	 * @return Ambigous <multitype:, string>
	 */
	public function getNamedays() {
		try {
			$namedays = new Local\Greek\Info\Namedays\Namedays ();
			return $namedays->getNamedays ();
		} catch ( Exception $e ) {
			throw new \Zend\Json\Server\Exception\InvalidArgumentException ( 'Service unavailable', \Zend\Json\Server\Error::ERROR_INTERNAL );
		}
	} // function getNamedays()
	
	/**
	 * Lookup for a phone number at "http://11888.ote.gr/web/guest/white-pages/search?who="
	 *
	 * @param number $phone        	
	 * @return multitype:unknown string Ambigous <string, mixed> multitype:unknown |string
	 */
	public function getPhoneInfo($number) {
		try {
			$phone = new Local\Greek\Info\Phones\Phones ();
			$response = $phone->lookup ( $number );
			if (is_array ( $response )) {
				return $response;
			} else {
				throw new \Zend\Json\Server\Exception\InvalidArgumentException ( $response, \Zend\Json\Server\Error::ERROR_INTERNAL );
			}
		} catch ( Exception $e ) {
			throw new \Zend\Json\Server\Exception\InvalidArgumentException ( 'Service unavailable', \Zend\Json\Server\Error::ERROR_INTERNAL );
		}
	} // function getPhoneInfo()
	
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
			$stemmer = new Local\Greek\Stemmer\PorterStemmerGr ();
			return $stemmer->getStemmed ( $words, $commonwords );
		} catch ( Exception $e ) {
			throw new \Zend\Json\Server\Exception\InvalidArgumentException ( 'Service unavailable', \Zend\Json\Server\Error::ERROR_INTERNAL );
		}
	} // function getStemmed()
	
	/**
	 * Generates a slug (pretty url) based on a string, which is typically a page/article title
	 *
	 * @param string $string
	 * @return string the generated slug
	 *
	 */
	public function getSlug($string) {
		try {
			$slug = new Local\Greek\Slugs\GreekSlugGenerator();
			return $slug->get_slug($string);
		} catch ( Exception $e ) {
			throw new \Zend\Json\Server\Exception\InvalidArgumentException ( 'Service unavailable', \Zend\Json\Server\Error::ERROR_INTERNAL );
		}
	} // function getSlug()
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