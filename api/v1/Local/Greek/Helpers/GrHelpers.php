<?php

namespace Local\Greek\Helpers;

/**
 *
 * @author user
 *        
 */
class GrHelpers {
	/**
	 * Make a greek/english string uppercase.
	 *
	 * @param string $string
	 *        	The string being converted.
	 * @param boolean $tones
	 *        	Set to false to remove tones.
	 * @return string
	 */
	public static function gr_strtoupper($string, $tones = true) {
		$ellhnika = array (
				'α' => 'Α',
				'β' => 'Β',
				'γ' => 'Γ',
				'δ' => 'Δ',
				'ε' => 'Ε',
				'ζ' => 'Ζ',
				'η' => 'Η',
				'θ' => 'Θ',
				'ι' => 'Ι',
				'κ' => 'Κ',
				'λ' => 'Λ',
				'μ' => 'Μ',
				'ν' => 'Ν',
				'ξ' => 'Ξ',
				'ο' => 'Ο',
				'π' => 'Π',
				'ρ' => 'Ρ',
				'σ' => 'Σ',
				'ς' => 'Σ',
				'τ' => 'Τ',
				'υ' => 'Υ',
				'φ' => 'Φ',
				'χ' => 'Χ',
				'ψ' => 'ψ',
				'ω' => 'Ω' 
		);
		
		$tonesno = array (
				'ά' => 'Α',
				'έ' => 'Ε',
				'ή' => 'Η',
				'ί' => 'Ι',
				'ϊ' => 'Ι',
				'ΐ' => 'Ι',
				'ό' => 'Ο',
				'ύ' => 'Υ',
				'ϋ' => 'Υ',
				'ΰ' => 'Υ',
				'ώ' => 'Ω' 
		);
		
		$tonesyes = array (
				'ά' => 'Ά',
				'έ' => 'Έ',
				'ή' => 'Ή',
				'ί' => 'Ί',
				'ϊ' => 'Ϊ',
				'ΐ' => 'Ϊ',
				'ό' => 'Ό',
				'ύ' => 'Ύ',
				'ϋ' => 'Ϋ',
				'ΰ' => 'Ϋ',
				'ώ' => 'Ώ' 
		);
		
		if ($tones) {
			$ellhnika = array_merge ( $ellhnika, $tonesyes );
		} else {
			$ellhnika = array_merge ( $ellhnika, $tonesno );
		}
		
		$search = array_keys ( $ellhnika );
		$text = str_replace ( $search, $ellhnika, $string );
		
		// Fix any latin or other characters
		$text = mb_convert_case ( $text, MB_CASE_UPPER, "UTF-8" );
		
		return $text;
	} // function gr_strtoupper()
	
	/**
	 * Finds whether a variable is a greek/english alphanumeric string.
	 *
	 * @param string $string
	 *        	The variable being evaluated.
	 * @param array $extras
	 *        	Extra characters to be considered as valid
	 * @return boolean
	 */
	public static function gr_isalphanumeric($string, $extras = array()) {
		$ellhnika = array (
				'α',
				'Α',
				'β',
				'Β',
				'γ',
				'Γ',
				'δ',
				'Δ',
				'ε',
				'Ε',
				'ζ',
				'Ζ',
				'η',
				'Η',
				'θ',
				'Θ',
				'ι',
				'Ι',
				'κ',
				'Κ',
				'λ',
				'Λ',
				'μ',
				'Μ',
				'ν',
				'Ν',
				'ξ',
				'Ξ',
				'ο',
				'Ο',
				'π',
				'Π',
				'ρ',
				'Ρ',
				'σ',
				'Σ',
				'ς',
				'Σ',
				'τ',
				'Τ',
				'υ',
				'Υ',
				'φ',
				'Φ',
				'χ',
				'Χ',
				'ψ',
				'ψ',
				'ω',
				'Ω',
				'ά',
				'Ά',
				'έ',
				'Έ',
				'ή',
				'Ή',
				'ί',
				'Ί',
				'ϊ',
				'Ϊ',
				'ΐ',
				'Ϊ',
				'ό',
				'Ό',
				'ύ',
				'Ύ',
				'ϋ',
				'Ϋ',
				'ΰ',
				'Ϋ',
				'ώ',
				'Ώ' 
		);
		
		$english = array (
				'a',
				'A',
				'b',
				'B',
				'c',
				'C',
				'd',
				'D',
				'e',
				'E',
				'f',
				'F',
				'g',
				'G',
				'h',
				'H',
				'i',
				'I',
				'j',
				'J',
				'k',
				'K',
				'l',
				'L',
				'm',
				'M',
				'n',
				'N',
				'o',
				'O',
				'p',
				'P',
				'q',
				'Q',
				'r',
				'R',
				's',
				'S',
				't',
				'T',
				'u',
				'U',
				'w',
				'W',
				'x',
				'X',
				'y',
				'Y',
				'z',
				'Z' 
		);
		
		$numbers = array (
				'0',
				'1',
				'2',
				'3',
				'4',
				'5',
				'6',
				'7',
				'8',
				'9' 
		);
		
		$alphanumeric = array_merge ( $ellhnika, $english, $numbers, $extras );
		$alphanumeric = implode ( "", $alphanumeric );
		$characters = str_split ( $string );
		
		foreach ( $characters as $character ) {
			if (! preg_match ( "/$character/", $alphanumeric )) {
				return false;
			}
		}
		
		return true;
	} // function gr_isalphanumeric()
} // class GrHelpers

?>