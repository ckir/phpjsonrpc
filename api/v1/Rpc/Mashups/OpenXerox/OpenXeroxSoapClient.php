<?php

namespace Rpc\Mashups\OpenXerox;

/**
 *
 * This class provides SOAP access to an Open Xerox Soap Service
 *        
 */
// From: http://open.xerox.com/Help/Service%20Call%20Examples#Php

class OpenXeroxSoapClient extends \SoapClient {
 
    var $wsdl_string;
    var $user;
    var $password;
    var $proxy_host;
    var $proxy_port;
    var $wrap_access_token;
 
    /**
      * COnstructor: WSDl is mandatory. Other filds for autentication and proxy are not.
      */
    public function __construct($wsdl, $user = NULL, $password = NULL, $proxy_host = NULL, $proxy_port = NULL){
        $this->wsdl_string = $wsdl;
        $this->user = $user;
        $this->password = $password;
        $this->proxy_host = $proxy_host;
        $this->proxy_port = $proxy_port;
    }
     
    /**
      * Add the proxy and authentication information for all the requests.
      */
    function __doRequest($request, $location, $action, $version, $one_way = 0){
        $headers = array(
            'Method: POST',
            'User-Agent: OpenXerox PHP Client',
            'Content-Type: text/xml',
            'Authorization: WRAP access_token="'.$this->wrap_access_token.'"',
            'SOAPAction: "'.$action.'"'
        );
  
        $ch = curl_init($location);
        curl_setopt_array($ch,array(
            CURLOPT_VERBOSE=>false,
            CURLOPT_RETURNTRANSFER=>true,
            CURLOPT_POST=>true,
            CURLOPT_FORBID_REUSE => 1,
            CURLOPT_POSTFIELDS=>$request,
            CURLOPT_HEADER=>false,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_SSL_VERIFYPEER => FALSE,  
            CURLOPT_PROXY => $this->proxy_host,
            CURLOPT_PROXYPORT => $this->proxy_port,      
            CURLOPT_HTTPHEADER=>$headers
        ));
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }
     
     
    /**
     * Send a POST requst using cURL - used for getting the WRAP token.
     * @param string $url to request
     * @param array $post values to send
     * @param array $options for cURL
     * @throws \Zend\Json\Exception\RuntimeException
     * @return string
     */
    private function curl_post($url, array $post = NULL, array $options = array()) {
        $defaults = array(
            CURLOPT_POST => 1,
            CURLOPT_HEADER => 0,
            CURLOPT_URL => $url,
            CURLOPT_FRESH_CONNECT => 1,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_TIMEOUT => 4,
            CURLOPT_FORBID_REUSE => TRUE,
            CURLOPT_SSL_VERIFYPEER => FALSE,
            CURLOPT_SSL_VERIFYHOST => FALSE,
            CURLOPT_PROXY => $this->proxy_host,
            CURLOPT_PROXYPORT =>$this->proxy_port,
            CURLOPT_POSTFIELDS => http_build_query($post)
        );
 
        $ch = curl_init();
        curl_setopt_array($ch, ($options + $defaults));
        if( ! $result = curl_exec($ch)) {
        	throw new \Zend\Json\Exception\RuntimeException ( curl_error($ch), \Zend\Json\Server\Error::ERROR_OTHER );
            //trigger_error(curl_error($ch));
        }
        curl_close($ch);
        return $result;
    }
    /**
     * Connect - Establish the connection to the server. This is done in the constructor
     * of the soap_client class
     */
    function connect() {
        // WSDL url verification
        if (!$this->wsdl_string) {
            if (defined("WSDL_URL")) {
                $this->wsdl_string = WSDL_URL;
            } else {
                print("SOAP: URL of the WSDL is not defined. Please set your WSDL_URL environment variable.");
            }
        }
 
        try {
            // SOAP Client options
            $options = array();
            if ($this->proxy_host && $this->proxy_port) {
                $options['proxy_host'] = $this->proxy_host;
                $options['proxy_port'] = (int)$this->proxy_port;
            }
            // OAuth WRAP implementation
            $post = array();
            if($this->user && $this->password){
                $post['wrap_name'] = $this->user;
                $post['wrap_password'] = $this->password;
                $options["login"] = $this->user;
                $options["password"] = $this->password;
                // get auth token
                $auth_url="https://services.open.xerox.com/Auth.svc/Authenticate";
                $auth_content="wrap_name=".$post['wrap_name']."&wrap_password=".$post['wrap_password'];
                $res = $this->curl_post($auth_url, $post);
                $this->wrap_access_token = substr($res,18);
                $options['Authorization'] = 'WRAP access_token='.$this->wrap_access_token;
            }
             
            // construct the SOAP client
            parent::__construct($this->wsdl_string, $options);
            $header = new \SoapHeader('https://open.xerox.com/', 'Authorization', $this->wrap_access_token);
            parent::__setSoapHeaders($header);
 
        } catch (\SoapFault $fault) {
        	throw new \Zend\Json\Exception\RuntimeException ( $fault->__toString(), \Zend\Json\Server\Error::ERROR_OTHER );
            //print_r($fault);
        }
    }
} // class OpenXeroxSoapClient

?>