<?php

namespace Rpc\Util\Config;

/**
 *
 * @author user
 *        
 */
class Config {
	
	public static function getConfig() {
		$configreader = new \Zend\Config\Reader\Ini();
		$configdata   = $configreader->fromFile(__DIR__ . DIRECTORY_SEPARATOR . 'config.ini');
		
		if (preg_match ( "/Rpchost/", $_SERVER ["SERVER_NAME"] )) {
			$mode = 'development';
		} else {
			$mode = 'production';
		}
		return $configdata[$mode];
		
	} // function getConfig()
} // class Config

?>