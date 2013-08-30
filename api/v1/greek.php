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
			$namedays = new Local\Greek\Namedays\Namedays();
			return $namedays->getNamedays();
		} catch (Exception $e) {
			throw new \Zend\Json\Server\Exception\InvalidArgumentException ( 'Service unavailable', \Zend\Json\Server\Error::ERROR_INTERNAL );
		}

	} // function getNamedays()
	
} // class apiv1


$server = new Zend\Json\Server\Server();
$server->setClass( new apiv1() );

if ('GET' == $_SERVER['REQUEST_METHOD']) {
	// Indicate the URL endpoint, and the JSON-RPC version used:
	$server->setTarget('/json-rpc.php')
	->setEnvelope(Zend\Json\Server\Smd::ENV_JSONRPC_2);

	// Grab the SMD
	$smd = $server->getServiceMap();

	// Return the SMD to the client
	header('Content-Type: application/json');
	echo $smd;
	return;
}

$server->handle();