<?php

namespace Rpc\Mashups\OpenXerox;

/**
 *
 * @author user
 *
 */
class LanguageIdentifier
{
    /**
     * Identify language via OpenXerox
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
     * @param  string                                $text
     * @return string
     * @throws \Zend\Json\Exception\RuntimeException
     */
    public function GetLanguageForString($text)
    {
        /*
         * Simple Language Identifier service (no proxy set & no login)
         */

        // WSDL URL & autentication
        $wsdl = "https://services.open.xerox.com/Wsdl.svc/LanguageIdentifier";
        $user = NULL;
        $password = NULL;

        // proxy configuration
        $proxy_host = NULL;
        $proxy_port = NULL;

        // SOAP client configuration
        $client = new OpenXeroxSoapClient ( $wsdl, $user, $password, $proxy_host, $proxy_port );
        $client->connect ();

        // call the language identifier
        $result = $client->GetLanguageForString ( array (
                "document" => $text
        ) );

        return $result->GetLanguageForStringResult;
    } // public function GetLanguageForString()

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
     * @param  array                                 $texts
     * @return array
     * @throws \Zend\Json\Exception\RuntimeException
     */
    public function GetLanguageForStrings($texts)
    {
        /*
         * Simple Language Identifier service (no proxy set & no login)
         */

        // WSDL URL & autentication
        $wsdl = "https://services.open.xerox.com/Wsdl.svc/LanguageIdentifier";
        $user = NULL;
        $password = NULL;

        // proxy configuration
        $proxy_host = NULL;
        $proxy_port = NULL;

        // SOAP client configuration
        $client = new OpenXeroxSoapClient ( $wsdl, $user, $password, $proxy_host, $proxy_port );
        $client->connect ();

        $response = array ();
        foreach ($texts as $text) {
            // call the language identifier
            $result = $client->GetLanguageForString ( array (
                    "document" => $text
            ) );
            $response [$text] = $result->GetLanguageForStringResult;
        }

        return $response;
    } // public function GetLanguageForStrings()
} // class LanguageIdentifier
