<?php

namespace Rpc\Greek\Stemmer;

/**
 *
 * @author user
 *
 */
class PorterStemmerGr
{
    /**
     * Takes a list of words and returns them reduced to their stems.
     *
     * $words can be either a string or an array. If it is a string, it will
     * be split into separate words on whitespace, commas, or semicolons. If
     * an array, it assumes one word per element.
     *
     * @param mixed $words
     *        	String or array of word(s) to reduce
     * @param bool $commonwords
     *        	Remove common words prior to stemming
     * @access public
     * @return array List of word stems
     */
    public function getStemmed($words, $commonwords = false)
    {
        $stemmer = new \Contrib\Nlp\Stemmers\gr\PorterStemmer\GreekPorterStemmerUtf8 ();
        $results = array ();

        if (! is_array ( $words )) {
            $tokenizer = new \Rpc\Greek\Nlp\Tokenizers\WhitespaceTokenizer\WhitespaceTokenizer ();
            $words = $tokenizer->tokenize ( $words );
        }

        foreach ($words as $word) {
            $word = \Rpc\Greek\Helpers\GrHelpers::gr_strtoupper ( trim ( $word ), false );
            $results [] = $stemmer->stem ( $word );
        }

        return $results;
    } // function getStemmed()
} // class PorterStemmerGr
