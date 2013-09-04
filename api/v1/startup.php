<?php
date_default_timezone_set ( "UTC" );
error_reporting ( E_ERROR | E_PARSE );
set_time_limit ( 0 );

$APPLICATION_PATH = realpath ( dirname ( __FILE__ ) ) ;

$VENDOR = realpath ( $APPLICATION_PATH . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'vendor' );
$ZF2 = realpath ( $VENDOR . DIRECTORY_SEPARATOR . 'zendframework' . DIRECTORY_SEPARATOR . 'zendframework' . DIRECTORY_SEPARATOR . 'library' );
$NLPTOOLS = realpath ( $VENDOR . DIRECTORY_SEPARATOR . 'nlp-tools' . DIRECTORY_SEPARATOR . 'nlp-tools' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'NlpTools');
$CONTRIB = realpath ( $APPLICATION_PATH . DIRECTORY_SEPARATOR . 'Contrib' );
$HTMLPURIFIER = realpath ( $CONTRIB . DIRECTORY_SEPARATOR . 'HTML'. DIRECTORY_SEPARATOR . 'htmlpurifier' . DIRECTORY_SEPARATOR . '4.5.0' );

$RPC = realpath ( $APPLICATION_PATH . DIRECTORY_SEPARATOR . 'Rpc' );

$INCLUDE_PATH = implode ( PATH_SEPARATOR, array (
		$VENDOR,
		$ZF2,
		$NLPTOOLS,
		$CONTRIB,
		$HTMLPURIFIER,
		$RPC,
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

// Vendor Namespace is for composer installed libs
$loader->registerNamespace('Vendor', $VENDOR);

// Contrib Namespace is for third party libs not available for composer installation
// Should follow structure from phpclasses.org/version
$loader->registerNamespace('Contrib', $CONTRIB);

// Rpc Namespace is for local to project classes
$loader->registerNamespace('Rpc', $RPC); 
$loader->registerNamespace('NlpTools', $NLPTOOLS);
$loader->register();






