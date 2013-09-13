<?php

namespace Rpc\Feed\Reader;

/**
 * A class to consume RSS and Atom feeds of any version,
 * including RDF/RSS 1.0, RSS 2.0, Atom 0.3 and Atom 1.0.
 * Performance is assisted in three ways. First of all,
 * Zend\Feed\Reader\Reader supports caching using Zend\Cache
 * to maintain a copy of the original feed XML. This allows you
 * to skip network requests for a feed URI if the cache is valid.
 * Second, the Feed and Entry level API is backed by an
 * internal cache (non-persistent) so repeat API calls for the same feed
 * will avoid additional DOM or XPath use.
 * Thirdly, importing feeds from a URI can take advantage of
 * HTTP Conditional GET requests which allow servers to issue
 * an empty 304 response when the requested feed has not changed
 * since the last time you requested it. In the final case,
 * an instance of Zend\Cache will hold the last received feed
 * along with the ETag and Last-Modified header values sent in
 * the HTTP response.
 *
 * @author user
 *
 */
class Reader
{
    /**
     */
    public function __construct()
    {
    }

    /**
     * Import a feed by providing a URI
     *
     * @param string $uri
     *        	The URI to the feed
     * @param string $format
     *        	The output format. Possible values xml (default) ot json.
     * @param mixed[] $purifier
     *        	Apply HTML Purifier to results. HTMLPurifier works by filtering out all (x)HTML from the data, except for the tags and attributes specifically allowed in a whitelist, and by checking and fixing nesting of tags, ensuring a standards-compliant output.
     * @param bool $escape
     *        	Apply Zend\Escaper to results. To help prevent XSS attacks, Zend Framework has a new component Zend\Escaper, which complies to the current OWASP recommendations, and as such, is the recommended tool for escaping HTML tags and attributes.
     * @return string                                        array
     * @throws \Zend\Json\Exception\InvalidArgumentException
     * @throws \Zend\Json\Exception\RuntimeException
     */
    public function getFeed($uri, $format = "xml", $purifier = array(true), $escape = true)
    {
        $zuri = \Zend\Uri\UriFactory::factory($uri);
        if (! $zuri->isValid()) {
            throw new \Zend\Json\Exception\InvalidArgumentException("Invalid Uri", \Zend\Json\Server\Error::ERROR_INVALID_PARAMS);
        }

        $cache = \Zend\Cache\StorageFactory::factory ( array (
                'adapter' => array (
                        'name' => 'filesystem',
                        'options' => array (
                                'cache_dir' => __DIR__ . DIRECTORY_SEPARATOR . 'cache',
                                'ttl' => 3600
                        )
                ),
                'plugins' => array (
                        array (
                                'name' => 'serializer',
                                'options' => array ()
                        )
                )
        ) );

        \Zend\Feed\Reader\Reader::setCache ( $cache );
        \Zend\Feed\Reader\Reader::useHttpConditionalGet ();

        try {
            $feed = \Zend\Feed\Reader\Reader::import ( $uri );
        } catch ( \Zend\Feed\Exception\RuntimeException $e ) {
            throw new \Zend\Json\Exception\RuntimeException ( $e->getMessage (), \Zend\Json\Server\Error::ERROR_OTHER );
        }

        // Fix relative links
        $feedhost = $feed->getLink ();
        $feedhost = parse_url ( $feedhost, PHP_URL_HOST );
        if (! $feedhost) {
            $feedhost = parse_url ( $_POST ['feed'], PHP_URL_HOST );
            $feedhost = explode ( ".", $feedhost );
            $feedhost [0] = "www";
            $feedhost = implode ( ".", $feedhost );
        }

        $feeddata = $feed->saveXml ();

        $doc = new \DOMDocument ( '1.0' );
        $doc->formatOutput = true;
        @$doc->loadXML ( $feeddata );
        $xpath = new \DOMXpath ( $doc );
        foreach ( $xpath->query ( '//link' ) as $node ) {
            $link = $node->nodeValue;
            $link = \Rpc\Helpers\Helpers\Helpers::unquote ( trim ( $link ), '""\'\'' );
            $link = rawurldecode ( $link );
            $link = html_entity_decode ( $link, ENT_QUOTES, "UTF-8" );
            $link = htmlspecialchars_decode ( $link, ENT_QUOTES );
            $link = filter_var ( $link, FILTER_SANITIZE_URL );
            $linkhost = parse_url ( $link, PHP_URL_HOST );
            $node->nodeValue = htmlentities ( $link );
            if ($linkhost) {
                continue;
            }
            $link = "http://" . $feedhost . $link;
            $node->nodeValue = htmlentities ( $link );
        }

        $newdata = $doc->saveXML ();

        // Return XML
        if ($format === "xml") {
            return $newdata;
        }

        $feed = \Zend\Feed\Reader\Reader::importString ( $newdata );

        $data = array (
                'id' => $feed->getId (),
                'title' => $feed->getTitle (),
                'description' => $feed->getDescription (),
                'link' => $feed->getLink (),
                'linkFeed' => $feed->getFeedLink (),
                'authors' => $feed->getAuthors ()->getArrayCopy (),
                'dateCreated' => (array) $feed->getDateCreated (),
                'dateModified' => (array) $feed->getDateModified (),
                'dateLastBuild' => (array) $feed->getLastBuildDate (),
                'language' => $feed->getLanguage (),
                'encoding' => $feed->getEncoding (),
                'generator' => $feed->getGenerator (),
                'copyright' => $feed->getCopyright (),
                'hubs' => $feed->getHubs (),
                // 'categories' => $feed->getCategories()->getArrayCopy(),
                'image' => $feed->getImage (),
                'entries' => array ()
        );

        foreach ($feed as $entry) {
            $edata = array (
                    'id' => $entry->getId (),
                    'title' => $entry->getTitle (),
                    'description' => $entry->getDescription (),
                    'link' => $entry->getLink (),
                    'permaLink' => $entry->getPermaLink (),
                    'authors' => $entry->getAuthors ()->getArrayCopy (),
                    'dateCreated' => (array) $entry->getDateCreated (),
                    'dateModified' => (array) $entry->getDateModified (),
                    'content' => $entry->getContent (),
                    'enclosure' => $entry->getEnclosure (),
                    'commentCount' => $entry->getCommentCount (),
                    'commentLink' => $entry->getCommentLink (),
                    'categories' => $entry->getCategories ()->getArrayCopy ()
            );
            $data ['entries'] [] = $edata;
        }

        // User selected NOT to use HTML Purifier
        if ((isset ( $purifier [0] )) && ($purifier [0] !== false)) {
            // User selected to use HTML Purifier default configuration
            if (isset ( $purifier [0] ) && $purifier [0] === true) {
                // Setting HTMLPurifier's options
                $options = array (
                        // Allow only paragraph tags
                        // and anchor tags wit the href attribute
                        array (
                                'HTML.Allowed',
                                'p,a[href]'
                        ),
                        // Format end output with Tidy
                        array (
                                'Output.TidyFormat',
                                true
                        ),
                        // Assume XHTML 1.0 Strict Doctype
                        array (
                                'HTML.Doctype',
                                'XHTML 1.0 Strict'
                        ),
                        // Disable cache, but see note after the example
                        array (
                                'Cache.SerializerPath',
                                __DIR__ . DIRECTORY_SEPARATOR . 'cache'
                        )
                );
            } else {
                // User provided custom options for HTML Purifier
                $options = $purifier;
            }
            require_once 'HTMLPurifier.standalone.php';
            $config = \HTMLPurifier_Config::createDefault ();
            foreach ($options as $option) {
                $config->set ( $option [0], $option [1] );
            }

            // Creating a HTMLPurifier with it's config
            $purifier = new \HTMLPurifier ( $config );

            $data ["title"] = $purifier->purify ( $data ["title"] );
            $data ["description"] = $purifier->purify ( $data ["description"] );
            for ($i = 0; $i < count ( $data ['entries'] ); $i ++) {
                $data ['entries'] [$i] ["title"] = $purifier->purify ( $data ['entries'] [$i] ["title"] );
                $data ['entries'] [$i] ["description"] = $purifier->purify ( $data ['entries'] [$i] ["description"] );
            }
        } // User selected NOT to use HTML Purifier

        // User selected to escape output
        if (isset ( $escape ) && ($escape === true)) {
            $escaper = new \Zend\Escaper\Escaper ( (! empty ( $data ["encoding"] ) ? $data ["encoding"] : 'utf-8') );
            $data ["title"] = $escaper->escapeHtml ( $data ["title"] );
            $data ["link"] = $escaper->escapeHtml ( $data ["link"] );
            $data ["linkFeed"] = $escaper->escapeHtml ( $data ["linkFeed"] );
            $data ["description"] = $escaper->escapeHtml ( $data ["description"] );
            for ($i = 0; $i < count ( $data ['entries'] ); $i ++) {
                $data ['entries'] [$i] ["title"] = $escaper->escapeHtml ( $data ['entries'] [$i] ["title"] );
                $data ['entries'] [$i] ["description"] = $escaper->escapeHtml ( $data ['entries'] [$i] ["description"] );
                $data ['entries'] [$i] ["link"] = $escaper->escapeHtml ( $data ['entries'] [$i] ["link"] );
            }
        }

        return $data;
    } // function getFeed
} // class Reader
