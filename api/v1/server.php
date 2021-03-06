<?php
require_once 'startup.php';
// $a = new \Rpc\Mashups\OpenXerox\LanguageIdentifier();
// $response = $a->GetLanguageForStrings(array("bonjour", "Ένα βίντεο που τράβηξαν κάποιοι Έλληνες"));
// $a = new \Rpc\Text\Unicode\Unicode ();

// $languageDetect = new \Rpc\Text\LanguageDetect\LanguageDetect ();
// $response = $languageDetect->getLanguage ( "Επιστήμες και η Βιολογία που είναι βασική επιστήμη και δεν ξέρω πως μπορούν σε μια σειρά από ειδικότητες να απουσιάζει η Βιολογία ή να υποβαθμίζονται οι Φυσικές Επιστήμες»" );
$m = get_class_methods ( new apiv1 () );
$m1 = implode ( ",", $m );
asort ( $m );
$m2 = implode ( ",", $m );

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
	 * Returns all available information from cldr data for a given language.
	 *
	 * @param string $language
	 *        	The language code
	 * @param string $return
	 *        	The return type. Could be "json" (default) or "xml"
	 * @throws \Zend\Json\Server\Exception\InvalidArgumentException
	 * @throws \Zend\Json\Server\Exception\RuntimeException
	 * @return array string
	 */
	public function getI18nCldrAll($language, $return = "json") {
		$cldr = new \Rpc\I18n\Cldr\Cldr ();
		$response = $cldr->getAll ( $language, $return );
		return $response;
	} // function getI18nCldrAll()
	
	/**
	 * Identify languages via OpenXerox
	 *
	 * This service works for the following list of languages:
	 * Arabic
	 * Bulgarian (български език)
	 * Breton (Brezhoneg)
	 * Catalan; Valencian (Català)
	 * Chinese (中文)
	 * Croatian (Hrvatski)
	 * Czech (Česky)
	 * Danish (Dansk)
	 * Dutch (Nederlands)
	 * English (English)
	 * Esperanto (Esperanto)
	 * Estonian (Eesti keel)
	 * Basque (Euskara)
	 * Finnish (Suomen kieli)
	 * French (Français)
	 * Georgian (ქართული)
	 * German (Deutsch)
	 * Greek (Ελληνικά)
	 * Hebrew (he)
	 * Hindi (हिन्दी)
	 * Hungarian (Magyar)
	 * Icelandic (Íslenska)
	 * Indonesian (Bahasa Indonesia)
	 * Italian (Italiano)
	 * Irish (Gaeilge)
	 * Japanese (日本語)
	 * Korean (한국어)
	 * Latin (Latine)
	 * Lithuanian (Lietuvių kalba)
	 * Latvian (Latviešu valoda)
	 * Malay (Bahasa Melayu)
	 * Maltese (Malti)
	 * Norwegian (Norsk)
	 * Polish (Polski)
	 * Portuguese (Português)
	 * Romanian (Română)
	 * Russian (русский язык)
	 * Slovak (Slovenčina)
	 * Slovenian (Slovenščina)
	 * Spanish; Castilian (Español)
	 * Albanian (Shqip)
	 * Swahili (Kiswahili)
	 * Swedish (Svenska)
	 * Turkish (Türkçe)
	 * Ukrainian (українська мова)
	 * Welsh (Cymraeg)
	 * Vietnamese (Tiếng Việt)
	 *
	 * @param array $texts        	
	 * @return array
	 * @throws \Zend\Json\Exception\RuntimeException
	 */
	public function getI18nLanguageForStrings($texts) {
		$languageForStrings = new \Rpc\Mashups\OpenXerox\LanguageIdentifier ();
		$response = $languageForStrings->GetLanguageForStrings ( $texts );
		return $response;
	} // function getI18nLanguageForStrings()
	
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
	 * @param string $inputtext
	 *        	The string to tokenize.
	 * @param string $language
	 *        	The language of the string.
	 * @return array
	 * @throws \Zend\Json\Exception\RuntimeException
	 * @throws \Zend\Json\Exception\InvalidArgumentException
	 */
	public function getTextMorphoAnalysis($inputtext, $language) {
		$morphoAnalysis = new Rpc\Mashups\OpenXerox\LinguisticTools ();
		$response = $morphoAnalysis->MorphoAnalysis ( $inputtext, $language );
		return $response;
	} // function getTextMorphoAnalysis()
	
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
	 * @param string $inputtext
	 *        	The string to tokenize.
	 * @param string $language
	 *        	The language of the string.
	 * @return array
	 * @throws \Zend\Json\Exception\RuntimeException
	 * @throws \Zend\Json\Exception\InvalidArgumentException
	 */
	public function getTextPartOfSpeechTagging($inputtext, $language) {
		$partOfSpeechTagging = new Rpc\Mashups\OpenXerox\LinguisticTools ();
		$response = $partOfSpeechTagging->PartOfSpeechTagging ( $inputtext, $language );
		return $response;
	} // function getTextPartOfSpeechTagging()
	
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
	 * @param string $inputtext
	 *        	The string to tokenize.
	 * @param string $language
	 *        	The language of the string.
	 * @return array
	 * @throws \Zend\Json\Exception\RuntimeException
	 * @throws \Zend\Json\Exception\InvalidArgumentException
	 */
	public function getTextTokenization($inputtext, $language) {
		$tokenization = new Rpc\Mashups\OpenXerox\LinguisticTools ();
		$response = $tokenization->Tokenization ( $inputtext, $language );
		return $response;
	} // function getTextTokenization()
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