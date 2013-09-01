<?php

namespace Local\Greek\Info\Phones;

/**
 *
 * @author user
 *        
 */
class Phones {
	
	/**
	 * Lookup for a phone number at "http://11888.ote.gr/web/guest/white-pages/search?who="
	 *
	 * @param number $phone        	
	 * @return multitype:unknown string Ambigous <string, mixed> multitype:unknown |string
	 */
	public function lookup($phone) {
		if (is_numeric ( $phone ) && strlen ( $phone ) == 10) {
			

			$cache = new \Local\Util\Cache\Cache ();
			$cacheadapter = $cache->setParameters ();
			if ($cacheadapter) {
				$cachedinfo = $cache->getItem ( $phone );
				if (! empty ( $cachedinfo )) {
					return json_decode ( $cachedinfo, true );
				}
			}
			
			$fil = file_get_contents ( "http://11888.ote.gr/web/guest/white-pages/search?who=" . $phone );
			$fil = mb_convert_encoding ( $fil, 'HTML-ENTITIES', "UTF-8" );
			
			$xmldoc = new \DOMDocument ();
			@$xmldoc->loadHTML ( $fil );
			
			$xpathvar = new \Domxpath ( $xmldoc );
			$queryResult = $xpathvar->query ( '//div[@class="details"]/h3/text()' );
			foreach ( $queryResult as $result ) {
				$v = $this->greeklish ( trim ( $result->textContent ) );
				break;
			}
			$queryResult = $xpathvar->query ( '//div[@class="details"]/div[@class="summary"]/text()' );
			foreach ( $queryResult as $result ) {
				$a = $this->greeklish ( trim ( $result->textContent ) );
				break;
			}
			
			if (isset ( $a )) {
				$addr = explode ( " ", $a );
				
				foreach ( $addr as $key => $value ) {
					if (is_numeric ( $value )) {
						// magic
						$st = join ( " ", array_slice ( $addr, 0, $key ) );
						$ct = join ( " ", array_slice ( $addr, $key + 1 ) );
						$nr = $value;
						break;
					}
					if (strtoupper ( $value ) != $value) {
						$st = join ( " ", array_slice ( $addr, 0, $key ) );
						$ct = join ( " ", array_slice ( $addr, $key ) );
						break;
					}
				}
			}
		}
		
		if (isset ( $v ) && (! empty ( $v ))) {
			$response = array (
					"phone" => $phone,
					"name" => $v,
					"raw" => $a,
					"address" => array (
							"street" => $st,
							"number" => $nr,
							"city" => $ct 
					) 
			);
			
			if ($cacheadapter) {
				$cache->addItem ( $phone, json_encode ( $response ) );
			}
			
			return $response;
		} else {
			return "Lookup Failed";
		}
	} // function lookup()
	
	/**
	 * Converts a greek string to greeklish.
	 *
	 * @param string $Name
	 *        	String to convert.
	 * @return string
	 */
	private function greeklish($Name) {
		$greek = array (
				'α',
				'ά',
				'Ά',
				'Α',
				'β',
				'Β',
				'γ',
				'Γ',
				'δ',
				'Δ',
				'ε',
				'έ',
				'Ε',
				'Έ',
				'ζ',
				'Ζ',
				'η',
				'ή',
				'Η',
				'θ',
				'Θ',
				'ι',
				'ί',
				'ϊ',
				'ΐ',
				'Ι',
				'Ί',
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
				'ό',
				'Ο',
				'Ό',
				'π',
				'Π',
				'ρ',
				'Ρ',
				'σ',
				'ς',
				'Σ',
				'τ',
				'Τ',
				'υ',
				'ύ',
				'Υ',
				'Ύ',
				'φ',
				'Φ',
				'χ',
				'Χ',
				'ψ',
				'Ψ',
				'ω',
				'ώ',
				'Ω',
				'Ώ',
				' ',
				"'",
				"'",
				',' 
		);
		$english = array (
				'a',
				'a',
				'A',
				'A',
				'b',
				'B',
				'g',
				'G',
				'd',
				'D',
				'e',
				'e',
				'E',
				'E',
				'z',
				'Z',
				'i',
				'i',
				'I',
				'th',
				'TH',
				'i',
				'i',
				'i',
				'i',
				'I',
				'I',
				'k',
				'K',
				'l',
				'L',
				'm',
				'M',
				'n',
				'N',
				'x',
				'X',
				'o',
				'o',
				'O',
				'O',
				'p',
				'P',
				'r',
				'R',
				's',
				's',
				'S',
				't',
				'T',
				'u',
				'u',
				'Y',
				'Y',
				'f',
				'F',
				'ch',
				'CH',
				'ps',
				'PS',
				'o',
				'o',
				'O',
				'O',
				' ',
				'\'',
				'\'',
				',' 
		);
		$string = str_replace ( $greek, $english, $Name );
		return $string;
	} // function greeklish
} // class Phones

?>