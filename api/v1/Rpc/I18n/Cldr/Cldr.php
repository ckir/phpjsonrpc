<?php

namespace Rpc\I18n\Cldr;

/**
 *
 * @author user
 *        
 */
class Cldr {
	
	/**
	 * cldr data location
	 * @var string
	 */
	private $datadir;
	/**
	 */
	function __construct() {
		$this->datadir = __DIR__ . DIRECTORY_SEPARATOR . "data" . DIRECTORY_SEPARATOR . "core" . DIRECTORY_SEPARATOR . "common" . DIRECTORY_SEPARATOR . "main" . DIRECTORY_SEPARATOR;
	} // function __construct()
	
	/**
	 * Verify language code
	 * 
	 * @param string $language
	 * @return boolean
	 */
	private function isValid($language) {
		if (mb_strlen ( $language ) === 2) {
			return true;
		} else {
			return false;
		}
	} // function isValid
	
	/**
	 * Returns all available information for a given language.
	 *
	 * @param string $language
	 *        	The language code
	 * @param string $return
	 *        	The return type. Could be "json" (default) or "xml"
	 * @throws \Zend\Json\Server\Exception\InvalidArgumentException
	 * @throws \Zend\Json\Server\Exception\RuntimeException
	 * @return array|string
	 */
	public function getAll($language, $return = "json") {
		if (! $this->isValid ( $language )) {
			throw new \Zend\Json\Server\Exception\InvalidArgumentException ( "Invalid language code", \Zend\Json\Server\Error::ERROR_INVALID_PARAMS );
		}
		
		$data = file_get_contents ( $this->datadir . $language . ".xml" );
		if (! $data) {
			throw new \Zend\Json\Server\Exception\RuntimeException ( "Data unavailable", \Zend\Json\Server\Error::ERROR_INTERNAL );
		}
		
		if ($return === "json") {
			return json_decode(\Zend\Json\Json::fromXml ( $data, true ), true);
		} else {
			return $data;
		}
	} // function getAll()
} // class Cldr

?>