<?php

namespace Rpc\Greek\Nlp\Tokenizers\WhitespaceTokenizer;

/**
 *
 * @author user
 *        
 */
class WhitespaceTokenizer {
	/**
	 * Simple white space tokenizer.
	 * Break on every white space
	 *
	 * @param string $content
	 *        	String to tokenize
	 * @return array
	 */
	public function tokenize($content) {
		$tokenizer = new \NlpTools\Tokenizers\WhitespaceTokenizer ();
		$tokens = $tokenizer->tokenize ( $content );
		for($i = 0; $i < count ( $tokens ); $i ++) {
			$token = trim ( $tokens [$i] );
			$token = \Rpc\Helpers\Helpers\Helpers::unquote ( $token, '""\'\'' );
			$first = mb_substr ( $token, 0, 1, 'UTF-8' );
			if (! \Rpc\Greek\Helpers\GrHelpers::gr_isalphanumeric ( $first )) {
				$token = mb_substr ( $token, 1, 'UTF-8' );
			}
			$last = mb_substr ( $token, - 1, 1, 'UTF-8' );
			if (! \Rpc\Greek\Helpers\GrHelpers::gr_isalphanumeric ( $last )) {
				$token = mb_substr ( $token, 0, - 1, 'UTF-8' );
			}
			$tokens [$i] = $token;
		}
		$tokens = array_filter ( $tokens );
		return $tokens;
	} // function tokenize()
} // class WhitespaceTokenizer

?>