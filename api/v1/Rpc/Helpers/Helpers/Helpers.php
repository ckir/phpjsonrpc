<?php

namespace Rpc\Helpers\Helpers;

/**
 *
 * @author user
 *
 */
class Helpers
{
    /**
     * Unquote a string and optionally return the quote removed.
     *
     * from http://razzed.com/2009/01/14/top-5-most-useful-non-native-php-functions/
     *
     * @param string $s
     *        	A string to unquote
     * @param string $quotes
     *        	A list of quote pairs to unquote
     * @param string $left_quote
     *        	Returns the quotes removed
     * @return Unquoted string, or same string if quotes not found
     */
    public static function unquote($s, $quotes = "''\"\"", &$left_quote = null)
    {
        if (is_array ( $s )) {
            $result = array ();
            foreach ($s as $k => $ss) {
                $result [$k] = \Rpc\Helpers\Helpers\Helpers::unquote ( $ss, $quotes, $left_quote );
            }

            return $result;
        }
        if (strlen ( $s ) < 2) {
            $left_quote = false;

            return $s;
        }
        $q = substr ( $s, 0, 1 );
        $qleft = strpos ( $quotes, $q );
        if ($qleft === false) {
            $left_quote = false;

            return $s;
        }
        $qright = $quotes {$qleft + 1};
        if (substr ( $s, - 1 ) === $qright) {
            $left_quote = $quotes {$qleft};

            return substr ( $s, 1, - 1 );
        }

        return $s;
    } // function unquote

} // class Helpers
