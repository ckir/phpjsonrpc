<?php

namespace Rpc\Text\LanguageDetect;

/**
 *
 * @author user
 *
 */
class LanguageDetect
{
    public function getLanguage($text)
    {
        $analysisText = $text;
        // Strip multiply spaces
        $analysisText = preg_replace ( '/\s+/m', ' ', $analysisText );
        // Remove new lines
        $analysisText = preg_replace ( '/(\r|\n)/m', ' ', $analysisText );
        // Escape single quotes
        $analysisText = str_replace ( "'", "\\'", $analysisText );

        $yahoo = new curlYahoo ( );
        $yahooresponse = $yahoo->handle ( $analysisText );

        return $yahooresponse;
    }
}
class curlYahoo
{
    private $api_url = "http://query.yahooapis.com/v1/public/yql";
    private $timeout = 15;

    /**
     *
     * @param string $content
     */
    public function handle($content)
    {
        $response = $this->getCurl ( $content );

        return $response;
    }
    /**
     * Returns analyzed content from yahoo
     *
     * @param  string   $content
     * @param  string   $format
     * @return resource
     */
    private function getCurl($content, $format = "json")
    {
        $query = "SELECT * FROM contentanalysis.analyze WHERE text=" . "'" . $content . "'";

        $query = http_build_query ( array (
                "q" => $query,
                "format" => $format
        ) );

        $ch = curl_init ();

        curl_setopt ( $ch, CURLOPT_URL, $this->api_url );
        curl_setopt ( $ch, CURLOPT_POST, 1 );
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $query );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt ( $ch, CURLOPT_AUTOREFERER, true );
        curl_setopt ( $ch, CURLOPT_TIMEOUT, $this->timeout );

        $response = curl_exec ( $ch );

        // Check if any error occured
        if (curl_errno ( $ch )) {
            $error = curl_error ( $ch );
            curl_close ( $ch );
            throw new \Zend\Json\Exception\RuntimeException ( $error, \Zend\Json\Server\Error::ERROR_OTHER );
        }

        $info = curl_getinfo ( $ch );

        // Check if any error occured
        if ($info ['http_code'] != 200) {
            $error = json_encode ( $info );
            curl_close ( $ch );
            throw new \Zend\Json\Exception\RuntimeException ( $error, \Zend\Json\Server\Error::ERROR_OTHER );
        }

        // Everything is OK
        curl_close ( $ch );

        $response = json_decode ( $response, true );
        $response = $response ['query'] ['lang'];

        return $response;
    } // function getCurl
} // class curlYahoo
