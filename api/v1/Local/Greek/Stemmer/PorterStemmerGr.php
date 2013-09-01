<?php

namespace Local\Greek\Stemmer;

/**
 *
 * @author user
 *        
 */
class PorterStemmerGr {
	
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
	public function getStemmed($words, $commonwords = false) {
		$stemmer = new \Contrib\Nlp\Stemmers\gr\PorterStemmer\GreekPorterStemmerUtf8 ();
		$results = array ();
		
		if (! is_array ( $words )) {
			//$words = preg_split ( '/((^\p{P}+)|(\p{P}*\s+\p{P}*)|(\p{P}+$))/', $words, - 1, PREG_SPLIT_NO_EMPTY );
			$words = explode(" ", $words);
		}
		
		foreach ( $words as $word ) {
			$word = \Local\Greek\Helpers\GrHelpers::gr_strtoupper(trim ( $word ), false);
			//$results [] = $stemmer->stem ( $word);
			$results [] = $word;
		}
		
		return $results;
	} // function getStemmed()
	
	private function checkName($tempName)
	{
		$tempName = strtr($tempName, "ΆάΈέΉήΌόΎύΏώ", "ααεεηηοουυωω");
		return strtr($tempName, "αβγδεζηθικλμνξοπρστυφχψως" , "ΑΒΓΔΕΖΗΘΙΚΛΜΝΞΟΠΡΣΤΥΦΧΨΩΣ");
	}
} // class PorterStemmerGr

?>