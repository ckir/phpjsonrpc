<?php
require_once 'startup.php';

/**
 * This is the main entry point for the general methods of the api.
 * In case of error, methods in this class should throw the appropriate type of exception.
 * Also an appropriate \Zend\Json\Server\Error should be used.
 *
 * @throws \Zend\Json\Server\Exception\ErrorException
 * @throws \Zend\Json\Server\Exception\HttpException
 * @throws \Zend\Json\Server\Exception\InvalidArgumentException
 * @throws \Zend\Json\Server\Exception\RuntimeException
 */
class apiv1 {
	
	/**
	 * Import a feed by providing a URI.
	 * Uses Cache Support and Intelligent Requests to avoid unnecessary network requests.
	 *
	 * @param string $uri
	 *        	The URI to the feed
	 * @param string $format
	 *        	The output format. Possible values are xml (default) or json.
	 * @param array $purifier
	 *        	Apply HTML Purifier to results.
	 *        	HTMLPurifier works by filtering out all (x)HTML from the data, except for the tags and attributes specifically allowed in a whitelist, and by checking and fixing nesting of tags, ensuring a standards-compliant output.
	 * @param bool $escape
	 *        	Apply Zend\Escaper to results. To help prevent XSS attacks, Zend Framework has a new component Zend\Escaper, which complies to the current OWASP recommendations, and as such, is the recommended tool for escaping HTML tags and attributes.
	 * @return string array
	 * @throws Exception\RuntimeException
	 */
	public function getReadersFeed($uri, $format = "xml", $purifier = array(true), $escape = true) {
		try {
			$feed = new Rpc\Feed\Reader\Reader ();
			return $feed->getFeed ( $uri, $format, $purifier, $escape );
		} catch ( Exception $e ) {
			throw new \Zend\Json\Server\Exception\RuntimeException ( $e->getMessage (), \Zend\Json\Server\Error::ERROR_INTERNAL );
		}
	} // function getReadersFeed()
	
	/**
	 * Fetch and optionally process a page by providing a URI.
	 *
	 * @param string $uri
	 *        	The URI to read.
	 * @param array $xpaths
	 *        	Reduces fetched document by applying a set of xpath queries
	 * @param array $purifier
	 *        	Apply HTML Purifier to results.
	 *        	Possible values:
	 *        	array containing true (default): apply using standard settings,
	 *        	array containing false: do not apply,
	 *        	array containing custom HTMLPurifier's configuration options: Apply using custom options.
	 *        	HTMLPurifier works by filtering out all (x)HTML from the data,
	 *        	except for the tags and attributes specifically allowed in a whitelist,
	 *        	and by checking and fixing nesting of tags, ensuring a standards-compliant output.
	 * @param bool $escape
	 *        	Apply Zend\Escaper to results.
	 *        	To help prevent XSS attacks, Zend Framework has a
	 *        	new component Zend\Escaper, which complies to the current
	 *        	OWASP recommendations, and as such, is the recommended tool
	 *        	for escaping HTML tags and attributes.
	 * @return array
	 * @throws \Zend\Json\Exception\InvalidArgumentException
	 * @throws \Zend\Json\Exception\RuntimeException
	 */
	public function getReadersUri($uri, $xpaths = array(), $purifier = array(false), $escape = false) {
		$HTMLReader = new Rpc\Html\HTMLReader\HTMLReader ();
		$response = $HTMLReader->getUri ( $uri, $xpaths, $purifier, $escape );
		return $response;
	} // function getReadersUri
	
	/**
	 * Fetch -optionally process- and return text from a page by providing a URI.
	 *
	 * @param string $uri
	 *        	The URI to read.
	 * @param array $xpaths
	 *        	Reduces fetched document by applying a set of xpath queries
	 * @param array $purifier
	 *        	Apply HTML Purifier to results.
	 *        	Possible values:
	 *        	array containing true (default): apply using standard settings,
	 *        	array containing false: do not apply,
	 *        	array containing custom HTMLPurifier's configuration options: Apply using custom options.
	 *        	HTMLPurifier works by filtering out all (x)HTML from the data,
	 *        	except for the tags and attributes specifically allowed in a whitelist,
	 *        	and by checking and fixing nesting of tags, ensuring a standards-compliant output.
	 * @param bool $escape
	 *        	Apply Zend\Escaper to results.
	 *        	To help prevent XSS attacks, Zend Framework has a
	 *        	new component Zend\Escaper, which complies to the current
	 *        	OWASP recommendations, and as such, is the recommended tool
	 *        	for escaping HTML tags and attributes.
	 * @return string
	 */
	public function getReadersUriText($uri, $xpaths = array(), $purifier = array(false), $escape = false) {
		try {
			$HTMLReader = new Rpc\Html\HTMLReader\HTMLReader ();
			$response = $HTMLReader->getUriText ( $uri, $xpaths, $purifier, $escape );
			if (is_array ( $response )) {
				return $response;
			} else {
				throw new \Zend\Json\Server\Exception\RuntimeException ( $response, \Zend\Json\Server\Error::ERROR_INTERNAL );
			}
		} catch ( Exception $e ) {
			throw new \Zend\Json\Server\Exception\RuntimeException ( $e->getMessage (), \Zend\Json\Server\Error::ERROR_INTERNAL );
		}
	} // function getReadersUriText
	
	/**
	 * Takes a list of words and returns them reduced to their stems.
	 *
	 * $words can be either a string or an array. If it is a string, it will
	 * be split into separate words on whitespace, commas, or semicolons. If
	 * an array, it assumes one word per element.
	 *
	 * @param string|array $words
	 *        	String or array of word(s) to reduce
	 * @param bool $commonwords
	 *        	Remove common words prior to stemming
	 * @access public
	 * @return array List of word stems
	 */
	public function getTextStemmed($words, $commonwords = false) {
		try {
			$stemmer = new Rpc\Text\Stemmer\PorterStemmer ();
			return $stemmer->getStemmed ( $words, $commonwords );
		} catch ( Exception $e ) {
			throw new \Zend\Json\Server\Exception\InvalidArgumentException ( 'Service unavailable', \Zend\Json\Server\Error::ERROR_INTERNAL );
		}
	} // function getTextStemmed()
	
	/**
	 * Removes common words from strings.
	 *
	 * @param string $input
	 *        	The string to remove common words from.
	 * @return string
	 */
	public function getTextRemoveCommonWords($input) {
		try {
			return \Rpc\Text\CommonWords\CommonWords::removeCommonWords ( $input );
		} catch ( Exception $e ) {
			throw new \Zend\Json\Server\Exception\InvalidArgumentException ( 'Service unavailable', \Zend\Json\Server\Error::ERROR_INTERNAL );
		}
	} // function getTextRemoveCommonWords()
	
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
	 *        	Apply stemming to strings prior to calculation
	 * @return array
	 */
	public function getTextSimilarity($first, $second, $commonwords = false, $stemming = false) {
		try {
			$getSimilarText = new Rpc\Text\Misc\Misc ();
			return $getSimilarText->getSimilarText ( $first, $second, $commonwords, $stemming );
		} catch ( Exception $e ) {
			throw new \Zend\Json\Server\Exception\InvalidArgumentException ( 'Service unavailable', \Zend\Json\Server\Error::ERROR_INTERNAL );
		}
	} // function getTextSimilarity()
	
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
	public function getTextMetaphone($strings, $commonwords = false, $stemming = false) {
		try {
			$getMetaphone = new Rpc\Text\Misc\Misc ();
			return $getMetaphone->getMetaphone ( $strings, $commonwords, $stemming );
		} catch ( Exception $e ) {
			throw new \Zend\Json\Server\Exception\InvalidArgumentException ( 'Service unavailable', \Zend\Json\Server\Error::ERROR_INTERNAL );
		}
	} // function getTextMetaphone()
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