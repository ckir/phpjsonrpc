<?php

namespace Local\Html\HTMLReader;

/**
 *
 * @author user
 *        
 */
class HTMLReader {
	private $moves = array ();
	
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
		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_URL, $uri );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt ( $ch, CURLOPT_AUTOREFERER, true );
		curl_setopt ( $ch, CURLOPT_MAXREDIRS, 5 );
		curl_setopt ( $ch, CURLOPT_FOLLOWLOCATION, true );
		curl_setopt ( $ch, CURLOPT_TIMEOUT, 20 );
		
		$results = curl_exec ( $ch );
		
		// Check if any error occured
		if (curl_errno ( $ch )) {
			$error = curl_error($ch);
			curl_close ( $ch );
			return $error;
		}
		
		$info = curl_getinfo ( $ch );
		
		// Check if any error occured
		if ($info ['http_code'] !== 200) {
			$error =json_encode($info) . PHP_EOL . $results;
			curl_close ( $ch );
			return $error;
		}

		$results = html_entity_decode ( $results, ENT_QUOTES, "UTF-8" );
		$results = htmlspecialchars_decode ( $results, ENT_QUOTES );
		
		// User wants back a fragment of the original document
		if (is_array ( $xpaths ) && count ( $xpaths ) > 0) {
			// From http://www.php.net/manual/en/domdocument.loadhtml.php
			$dom = new \DOMDocument ();
			@$dom->loadHTML ( '<?xml encoding="UTF-8">' . $results );
			// dirty fix
			foreach ( $dom->childNodes as $item )
				if ($item->nodeType == XML_PI_NODE)
					$dom->removeChild ( $item ); // remove hack
			$doc->encoding = 'UTF-8'; // insert proper
			
			$results = "";
			
			$xpath = new \DOMXpath ( $dom );
			foreach ( $xpaths as $xpathquery ) {
				$nodes = $xpath->query ( $xpathquery );
				if (! $nodes) {
					return "Bad xpath " . $xpathquery;
				}
				foreach ( $nodes as $node ) {
					$results = $results . $dom->saveHtml ( $node );
				}
			}
		} // if is_array($xpaths)
		  
		// User selected NOT to use HTML Purifier
		if ((isset ( $purifier [0] )) && ($purifier [0] !== false)) {
			// User selected to use HTML Purifier default configuration
			if (isset ( $purifier [0] ) && $purifier [0] === true) {
				// Setting HTMLPurifier's options
				$options = $this->HTMLPurifierConfig ();
			} else {
				// User provided custom options for HTML Purifier
				$options = $purifier;
			}
			require_once 'HTMLPurifier.standalone.php';
			$config = \HTMLPurifier_Config::createDefault ();
			foreach ( $options as $option ) {
				$config->set ( $option [0], $option [1] );
			}
			
			// Creating a HTMLPurifier with it's config
			$purifier = new \HTMLPurifier ( $config );
			
			$results = $purifier->purify ( $results );
		} // User selected NOT to use HTML Purifier
		  
		// User selected to escape output
		if (isset ( $escape ) && ($escape === true)) {
			$escaper = new \Zend\Escaper\Escaper ( 'utf-8' );
			$results = $escaper->escapeHtml ( $results );
		}
		
		return array (
				"info" => $info,
				"content" => $results 
		);
	} // function getUri()
	
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
	public function getUriText($uri, $xpaths = array(), $purifier = array(true), $escape = false) {
		$results = $this->getUri ( $uri, $xpaths, $purifier, $escape );
		if (! is_array ( $results )) {
			return $results;
		}
		$results ["content"] = $this->get_text ( $results ["content"] );
		return $results;
	} // function getUriText()
	
	/**
	 * Returns HTMLPurifier's default configuration.
	 *
	 * @return multitype:multitype:string multitype:string boolean
	 */
	private function HTMLPurifierConfig() {
		return array (
				// Allow only paragraph tags
				// and anchor tags wit the href attribute
				array (
						'HTML.Allowed',
						'p,a[href]' 
				),
				// Format end output with Tidy
				array (
						'Output.TidyFormat',
						true 
				),
				// Assume XHTML 1.0 Strict Doctype
				array (
						'HTML.Doctype',
						'XHTML 1.0 Strict' 
				),
				// Enable cache
				array (
						'Cache.SerializerPath',
						__DIR__ . DIRECTORY_SEPARATOR . 'cache' 
				) 
		);
	} // function HTMLPurifierConfig()
	
	/**
	 * Get the text from an html string
	 *
	 * @param string $text        	
	 * @return string
	 *
	 */
	private function get_text($text) {
		require_once 'HTMLPurifier.standalone.php';
		$text = html_entity_decode ( $text, ENT_QUOTES, "UTF-8" );
		$text = htmlspecialchars_decode ( $text, ENT_QUOTES );
		
		$options = $this->HTMLPurifierConfig ();
		$options [0] ['HTML.Allowed'] = '';
		$config = \HTMLPurifier_Config::createDefault ();
		foreach ( $options as $option ) {
			$config->set ( $option [0], $option [1] );
		}
		$purifier = new \HTMLPurifier ( $config );
		$text = $purifier->purify ( $text );
		$text = preg_replace ( "/<((\w+:\/\/)[-a-zA-Z0-9:@;?&=\/%\+\.\*!'\(\),\$_\{\}\^~\[\]`#|]+)/im", " ", $text );
		// Remove tags
		$text = preg_replace ( '/<[^>]+>/im', " ", $text );
		$text = preg_replace ( '/([a-z0-9_\-]{1,5}:\/\/)?(([a-z0-9_\-]{1,}):([a-z0-9_\-]{1,})\@)?((www\.)|([a-z0-9_\-]{1,}\.)+)?([a-z0-9_\-]{3,})(\.[a-z]{2,4})(\/([a-z0-9_\-]{1,}\/)+)?([a-z0-9_\-]{1,})?(\.[a-z]{2,})?(\?)?(((\&)?[a-z0-9_\-]{1,}(\=[a-z0-9_\-]{1,})?)+)?/i', " ", $text );
		$text = strip_tags ( $text );
		// Strip multiply spaces
		$text = preg_replace ( '/\s+/m', ' ', $text );
		return $text;
	} // function get_text
} // class HTMLReader

?>