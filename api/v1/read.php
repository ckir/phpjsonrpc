<?php
require_once 'startup.php';

class apiv1 {
	
	/**
	 *
	 *
	 *
	 * Import a feed by providing a URI
	 *
	 * @param string $uri
	 *        	The URI to the feed
	 * @param string $format
	 *        	The output format. Possible values xml (default) ot json.
	 * @param mixed[] $purifier
	 *        	Apply HTML Purifier to results. HTMLPurifier works by filtering out all (x)HTML from the data, except for the tags and attributes specifically allowed in a whitelist, and by checking and fixing nesting of tags, ensuring a standards-compliant output.
	 * @param bool $escape
	 *        	Apply Zend\Escaper to results. To help prevent XSS attacks, Zend Framework has a new component Zend\Escaper, which complies to the current OWASP recommendations, and as such, is the recommended tool for escaping HTML tags and attributes.
	 * @return mixed[]
	 * @throws Exception\RuntimeException
	 */
	public function getFeed($uri, $format = "xml", $purifier = array(true), $escape = true) {
		try {
			$feed = new Local\Feed\Reader\Reader ();
			return $feed->getFeed ( $uri, $format, $purifier, $escape );
		} catch ( Exception $e ) {
			throw new \Zend\Json\Server\Exception\RuntimeException ( $e->getMessage (), \Zend\Json\Server\Error::ERROR_INTERNAL );
		}
	} // function getFeed()
	
	/**
	 * Fetch an optionally process a page by providing a URI.
	 *
	 * @param string $uri
	 *        	The URI to read.
	 * @param array $xpaths
	 *        	Reduces fetched document by applying a set of xpath queries
	 * @param unknown $purifier
	 *        	Apply HTML Purifier to results.
	 *        	Possible values:
	 *        	array containing true (default): apply using standard settings,
	 *        	array containing false: do not apply,
	 *        	array containing custom HTMLPurifier's configuration options: Apply using custom options.
	 *        	HTMLPurifier works by filtering out all (x)HTML from the data,
	 *        	except for the tags and attributes specifically allowed in a whitelist,
	 *        	and by checking and fixing nesting of tags, ensuring a standards-compliant output.
	 * @param string $escape
	 *        	Apply Zend\Escaper to results.
	 *        	To help prevent XSS attacks, Zend Framework has a
	 *        	new component Zend\Escaper, which complies to the current
	 *        	OWASP recommendations, and as such, is the recommended tool
	 *        	for escaping HTML tags and attributes.
	 * @return string multitype:string
	 */
	public function getUri($uri, $xpaths = array(), $purifier = array(false), $escape = false) {
		try {
			$HTMLReader = new Local\Html\HTMLReader\HTMLReader ();
			$response = $HTMLReader->getUri ( $uri, $xpaths, $purifier, $escape );
			if (is_array ( $response )) {
				return $response;
			} else {
				throw new \Zend\Json\Server\Exception\RuntimeException ( $response, \Zend\Json\Server\Error::ERROR_INTERNAL );
			}
		} catch ( Exception $e ) {
			throw new \Zend\Json\Server\Exception\RuntimeException ( $e->getMessage (), \Zend\Json\Server\Error::ERROR_INTERNAL );
		}
	} // function getUri
	
	/**
	 * Fetch -optionally process- and return text from a page by providing a URI.
	 *
	 * @param string $uri
	 *        	The URI to read.
	 * @param array $xpaths
	 *        	Reduces fetched document by applying a set of xpath queries
	 * @param unknown $purifier
	 *        	Apply HTML Purifier to results.
	 *        	Possible values:
	 *        	array containing true (default): apply using standard settings,
	 *        	array containing false: do not apply,
	 *        	array containing custom HTMLPurifier's configuration options: Apply using custom options.
	 *        	HTMLPurifier works by filtering out all (x)HTML from the data,
	 *        	except for the tags and attributes specifically allowed in a whitelist,
	 *        	and by checking and fixing nesting of tags, ensuring a standards-compliant output.
	 * @param string $escape
	 *        	Apply Zend\Escaper to results.
	 *        	To help prevent XSS attacks, Zend Framework has a
	 *        	new component Zend\Escaper, which complies to the current
	 *        	OWASP recommendations, and as such, is the recommended tool
	 *        	for escaping HTML tags and attributes.
	 * @return string multitype:string
	 */
	public function getUriText($uri, $xpaths = array(), $purifier = array(false), $escape = false) {
		try {
			$HTMLReader = new Local\Html\HTMLReader\HTMLReader ();
			$response = $HTMLReader->getUriText ( $uri, $xpaths, $purifier, $escape );
			if (is_array ( $response )) {
				return $response;
			} else {
				throw new \Zend\Json\Server\Exception\RuntimeException ( $response, \Zend\Json\Server\Error::ERROR_INTERNAL );
			}
		} catch ( Exception $e ) {
			throw new \Zend\Json\Server\Exception\RuntimeException ( $e->getMessage (), \Zend\Json\Server\Error::ERROR_INTERNAL );
		}
	} // function getUriText
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