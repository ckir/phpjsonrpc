<?php
date_default_timezone_set ( "UTC" );
error_reporting ( E_ERROR | E_PARSE );
set_time_limit ( 0 );

$APPLICATION_PATH = realpath ( dirname ( __FILE__ ) ) ;
$VENDOR = realpath ( $APPLICATION_PATH . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'vendor' );
$ZF2 = realpath ( $VENDOR . DIRECTORY_SEPARATOR . 'zendframework' . DIRECTORY_SEPARATOR . 'zendframework' . DIRECTORY_SEPARATOR . 'library' );
$LIBS = realpath ( $APPLICATION_PATH . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'libs' );
//$ZF2 = realpath ( $LIBS . DIRECTORY_SEPARATOR . 'zf2' . DIRECTORY_SEPARATOR . '2.2.2' . DIRECTORY_SEPARATOR . 'library' );
$LOCAL = realpath ( $APPLICATION_PATH . DIRECTORY_SEPARATOR . 'library' );
$INCLUDE_PATH = implode ( PATH_SEPARATOR, array (
		$LIBS,
		$ZF2,
		$LOCAL,
		get_include_path () 
) );

if (! set_include_path ( $INCLUDE_PATH )) {
	die("Failed to set include path");
}

use Zend\Loader\StandardAutoloader;
require_once 'Zend/Loader/StandardAutoloader.php';

$loader = new StandardAutoloader ();
$loader->setOptions ( array (
		'autoregister_zf' => true
) );

$loader->registerNamespace('Local', $LOCAL);
$loader->registerNamespace('Vendor', $VENDOR);

$loader->register();

$configreader = new Zend\Config\Reader\Ini();
$configdata   = $configreader->fromFile(__DIR__ . '/config.ini');

if (preg_match ( "/localhost/", $_SERVER ["SERVER_NAME"] )) {
	define ( 'MODE', 'development' );
} else {
	define ( 'MODE', 'production' );
}




