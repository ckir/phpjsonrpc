<?php

namespace Local\Greek\Helpers;

/**
 *
 * @author user
 *        
 */
class GrHelpers {
	
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
		$text = mb_convert_case($text, MB_CASE_UPPER, "UTF-8");
		
		return $text;
	} // function gr_strtoupper()
	
	/**
	 * Greek string to uppercase
	 *
	 * Correctly converts greek letters to uppercase.
	 * https://github.com/vdw/Greek-string-to-uppercase/blob/master/MY_string_helper.php
	 * 
	 * @access public
	 * @param
	 *        	string
	 * @return string
	 */
	public static function grstrtoupper($string) {
		$latin_check = '/[\x{0030}-\x{007f}]/u';
		
		if (preg_match ( $latin_check, $string )) {
			
			$string = strtoupper ( $string );
		}
		
		$letters = array (
				'α',
				'β',
				'γ',
				'δ',
				'ε',
				'ζ',
				'η',
				'θ',
				'ι',
				'κ',
				'λ',
				'μ',
				'ν',
				'ξ',
				'ο',
				'π',
				'ρ',
				'σ',
				'τ',
				'υ',
				'φ',
				'χ',
				'ψ',
				'ω' 
		);
		$letters_accent = array (
				'ά',
				'έ',
				'ή',
				'ί',
				'ό',
				'ύ',
				'ώ' 
		);
		$letters_upper_accent = array (
				'Ά',
				'Έ',
				'Ή',
				'Ί',
				'Ό',
				'Ύ',
				'Ώ' 
		);
		$letters_upper_solvents = array (
				'ϊ',
				'ϋ' 
		);
		$letters_other = array (
				'ς' 
		);
		
		$letters_to_uppercase = array (
				'Α',
				'Β',
				'Γ',
				'Δ',
				'Ε',
				'Ζ',
				'Η',
				'Θ',
				'Ι',
				'Κ',
				'Λ',
				'Μ',
				'Ν',
				'Ξ',
				'Ο',
				'Π',
				'Ρ',
				'Σ',
				'Τ',
				'Υ',
				'Φ',
				'Χ',
				'Ψ',
				'Ω' 
		);
		$letters_accent_to_uppercase = array (
				'Α',
				'Ε',
				'Η',
				'Ι',
				'Ο',
				'Υ',
				'Ω' 
		);
		$letters_upper_accent_to_uppercase = array (
				'Α',
				'Ε',
				'Η',
				'Ι',
				'Ο',
				'Υ',
				'Ω' 
		);
		$letters_upper_solvents_to_uppercase = array (
				'Ι',
				'Υ' 
		);
		$letters_other_to_uppercase = array (
				'Σ' 
		);
		
		$lowercase = array_merge ( $letters, $letters_accent, $letters_upper_accent, $letters_upper_solvents, $letters_other );
		$uppercase = array_merge ( $letters_to_uppercase, $letters_accent_to_uppercase, $letters_upper_accent_to_uppercase, $letters_upper_solvents_to_uppercase, $letters_other_to_uppercase );
		
		$uppecase_string = str_replace ( $lowercase, $uppercase, $string );
		
		return $uppecase_string;
	} // function grstrtoupper()
	
} // class GrHelpers

?>