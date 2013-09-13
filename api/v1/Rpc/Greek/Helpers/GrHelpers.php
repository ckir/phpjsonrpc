<?php

namespace Rpc\Greek\Helpers;

/**
 *
 * @author user
 *
 */
class GrHelpers
{
    public static $alphaL = array('α', 'ά', 'ἀ', 'ἄ', 'ἂ', 'ἆ', 'ἁ', 'ἅ', 'ἃ', 'ἇ', 'ὰ', 'ᾶ');
    public static $alphaU = array('Α', 'Ά', 'Ἀ', 'Ἄ', 'Ἂ', 'Ἆ', 'Ἁ', 'Ἅ', 'Ἃ', 'Ἇ', 'Ὰ', 'Ᾱ ');

    public static $betaL = array(	'β');
    public static $betaU = array('Β');

    public static $gammaL = array('γ');
    public static $gammaU = array('Γ');

    public static $deltaL = array('δ');
    public static $deltaU = array('Δ');

    public static $epsilonL = array('ε', 'έ', 'ἐ', 'ἔ', 'ἒ', 'ἑ', 'ἕ', 'ἓ', 'ὲ');
    public static $epsilonU = array('Ε', 'Έ', 'Ἐ', 'Ἔ', 'Ἒ', 'Ἑ', 'Ἕ', 'Ἓ', 'Ὲ');

    public static $zetaL = array('ζ');
    public static $zetaU = array('Ζ');

    public static $etaL = array('η', 'ή', 'ἠ', 'ἤ', 'ἢ', 'ἦ', 'ἡ', 'ἥ', 'ἣ', 'ἧ', 'ὴ', 'ῆ');
    public static $etaU = array('Η', 'Ή', 'Ἠ', 'Ἤ', 'Ἢ', 'Ἦ', 'Ἡ', 'Ἥ', 'Ἣ', 'Ἧ', 'Ὴ', ' ');

    public static $thetaL = array('θ');
    public static $thetaU = array('Θ');

    public static $iotaL = array('ι', 'ί', 'ϊ', 'ΐ', 'ἰ', 'ἴ', 'ἲ', 'ἶ', 'ἱ', 'ἵ', 'ἳ', 'ἷ', 'ὶ', 'ῖ', 'ῒ', 'ῗ');
    public static $iotaU = array('Ι', 'Ί', 'Ϊ', ' ', 'Ἰ', 'Ἴ', 'Ἲ', 'Ἶ', 'Ἱ', 'Ἵ', 'Ἳ', 'Ἷ', 'Ὶ', ' ', ' ', ' ');

    public static $kappaL = array('κ');
    public static $kappaU = array('Κ');

    public static $lambdaL = array('λ');
    public static $lambdaU = array('Λ');

    public static $muL = array('μ');
    public static $muU = array('Μ');

    public static $nuL = array('ν');
    public static $nuU = array('Ν');

    public static $xiL = array('ξ');
    public static $xiU = array('Ξ');

    public static $omicronL = array('ο', 'ό', 'ὄ', 'ὂ', 'ὃ', 'ὸ');
    public static $omicronU = array('Ο', 'Ό', 'Ὄ', 'Ὂ', 'Ὃ', 'Ὸ');

    public static $piL = array('π');
    public static $piU = array('Π');

    public static $rhoL = array('ρ');
    public static $rhoU = array('Ρ');

    public static $sigmaL = array('σ', 'ς');
    public static $sigmaU = array('Σ', 'Σ');

    public static $tauL = array('τ');
    public static $tauU = array('Τ');

    public static $upsilonL = array('υ', 'ύ', 'ϋ', 'ΰ', 'ὐ', 'ὔ', 'ὒ', 'ὖ', 'ὑ', 'ὕ', 'ὓ', 'ὗ', 'ὺ', 'ῦ', 'ῢ', 'ῧ');
    public static $upsilonU = array('Υ', 'Ύ', 'Ϋ', ' ', ' ', ' ', ' ', ' ', 'Ὑ', 'Ὕ', 'Ὓ', 'Ὗ', 'Ὺ', ' ', ' ', ' ');

    public static $phiL = array('φ');
    public static $phiU = array('Φ');

    public static $chiL = array('χ');
    public static $chiU = array('Χ');

    public static $psiL = array('ψ');
    public static $psiU = array('Ψ');

    public static $omegaL = array('ω', 'ώ', 'ὤ', 'ὢ', 'ὦ', 'ὥ', 'ὣ', 'ὧ', 'ὼ', 'ῶ');
    public static $omegaU = array('Ω', 'Ώ', 'Ὤ', 'Ὢ', 'Ὦ', 'Ὥ', 'Ὣ', 'Ὧ', 'Ὼ', ' ');

    /**
     * Make a greek/english string uppercase.
     *
     * @param string $string
     *        	The string being converted.
     * @param boolean $tones
     *        	Set to false to remove tones.
     * @return string
     */
    public static function gr_strtoupper($string, $tones = true)
    {
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
                'ψ' => 'Ψ',
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
    public static function gr_isalphanumeric($string, $extras = array())
    {
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
                'ό',
                'Ό',
                'ύ',
                'Ύ',
                'ϋ',
                'Ϋ',
                'ΰ',
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

        foreach ($characters as $character) {
            if (! preg_match ( "/$character/", $alphanumeric )) {
                return false;
            }
        }

        return true;
    } // function gr_isalphanumeric()

    /**
     * Greeglish
     * https://github.com/skapator/greeklish/blob/master/src/Skapator/Greeklish/Greeklish.php
     *
     * @param string $text
     *        	- the greek text
     * @param bool $stop_one
     *        	- if true removes one letter words
     * @param bool $stop_two
     *        	- if true removes two letter words
     * @access public
     */
    public static function gr_greeglish($text, $stop_one = false, $stop_two = false)
    {
        $expressions = array (
                '/[αΑ][ιίΙΊ]/u' => 'e',
                '/[οΟΕε][ιίΙΊ]/u' => 'i',

                '/[αΑ][υύΥΎ]([θΘκΚξΞπΠσςΣτTφΡχΧψΨ]|\s|$)/u' => 'af$1',
                '/[αΑ][υύΥΎ]/u' => 'av',
                '/[εΕ][υύΥΎ]([θΘκΚξΞπΠσςΣτTφΡχΧψΨ]|\s|$)/u' => 'ef$1',
                '/[εΕ][υύΥΎ]/u' => 'ev',
                '/[οΟ][υύΥΎ]/u' => 'ou',

                '/[μΜ][πΠ]/u' => 'mp',
                '/[νΝ][τΤ]/u' => 'nt',
                '/[τΤ][σΣ]/u' => 'ts',
                '/[τΤ][ζΖ]/u' => 'tz',
                '/[γΓ][γΓ]/u' => 'ng',
                '/[γΓ][κΚ]/u' => 'gk',
                '/[ηΗ][υΥ]([θΘκΚξΞπΠσςΣτTφΡχΧψΨ]|\s|$)/u' => 'if$1',
                '/[ηΗ][υΥ]/u' => 'iu',

                '/[θΘ]/u' => 'th',
                '/[χΧ]/u' => 'ch',
                '/[ψΨ]/u' => 'ps',

                '/[αάΑΆ]/u' => 'a',
                '/[βΒ]/u' => 'v',
                '/[γΓ]/u' => 'g',
                '/[δΔ]/u' => 'd',
                '/[εέΕΈ]/u' => 'e',
                '/[ζΖ]/u' => 'z',
                '/[ηήΗΉ]/u' => 'i',
                '/[ιίϊΐΙΊΪ]/u' => 'i',
                '/[κΚ]/u' => 'k',
                '/[λΛ]/u' => 'l',
                '/[μΜ]/u' => 'm',
                '/[νΝ]/u' => 'n',
                '/[ξΞ]/u' => 'x',
                '/[οόΟΌ]/u' => 'o',
                '/[πΠ]/u' => 'p',
                '/[ρΡ]/u' => 'r',
                '/[σςΣ]/u' => 's',
                '/[τΤ]/u' => 't',
                '/[υύϋΰΥΎΫ]/u' => 'i',
                '/[φΦ]/iu' => 'f',
                '/[ωώ]/iu' => 'o'
        );

        $text = preg_replace ( array_keys ( $expressions ), array_values ( $expressions ), $text );

        if ($stop_one == true) {
            $text = preg_replace ( '/\s+\D{1}(?!\S)|(?<!\S)\D{1}\s+/', '', $text );
        }

        if ($stop_two == true) {
            $text = preg_replace ( '/\s+\D{2}(?!\S)|(?<!\S)\D{2}\s+/', '', $text );
        }

        return $text;
    } // function gr_greeglish(
} // class GrHelpers
