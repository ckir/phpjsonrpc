<?php
date_default_timezone_set ( "UTC" );
error_reporting ( E_ERROR | E_PARSE );
set_time_limit ( 0 );

$APPLICATION_PATH = realpath ( dirname ( __FILE__ ) ) ;

// Vendor folder is for composer installed libs
$VENDOR = realpath ( $APPLICATION_PATH . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'vendor' );
$ZF2 = realpath ( $VENDOR . DIRECTORY_SEPARATOR . 'zendframework' . DIRECTORY_SEPARATOR . 'zendframework' . DIRECTORY_SEPARATOR . 'library' );
$NLPTOOLS = realpath ( $VENDOR . DIRECTORY_SEPARATOR . 'nlp-tools' . DIRECTORY_SEPARATOR . 'nlp-tools' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'NlpTools');
// contrib folder is for third party libs not available for composer installation
$LIBS = realpath ( $APPLICATION_PATH . DIRECTORY_SEPARATOR . 'Contrib' );
$HTMLPURIFIER = realpath ( $LIBS . DIRECTORY_SEPARATOR . 'htmlpurifier' . DIRECTORY_SEPARATOR . '4.5.0' );

// library folder is for local to project classes
$LOCAL = realpath ( $APPLICATION_PATH . DIRECTORY_SEPARATOR . 'Local' );

$INCLUDE_PATH = implode ( PATH_SEPARATOR, array (
		$VENDOR,
		$ZF2,
		$NLPTOOLS,
		$LIBS,
		$HTMLPURIFIER,
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

$loader->registerNamespace('Vendor', $VENDOR);
$loader->registerNamespace('Contrib', $LIBS);
$loader->registerNamespace('Local', $LOCAL); 
$loader->registerNamespace('NlpTools', $NLPTOOLS);
$loader->register();






