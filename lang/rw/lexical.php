<?php
/**
 * This file is part of Kumva.
 *
 * Kumva is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Kumva is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Kumva.  If not, see <http://www.gnu.org/licenses/>.
 *
 * Copyright Rowan Seymour 2010
 * 
 * Purpose: Lexical functions for Kinyarwanda
 */

global $kumva_rw_soundmaps, $kumva_rw_suffixes;
 
$kumva_rw_soundmaps = array('SHY' => 'SH', 'JY' => 'J', 'CY' => 'C',							// Subtle y's
							'N[KT]' => 'NH', 'MH' => 'MP',
							'[KG]I' => 'JI', '[KG]E' => 'JE',									// Soft k's and g's
							'CH' => 'J', 'C' => 'J',											// Soft c's
							'GU' => 'KU', 'DU' => 'TU',											// Change down rule
							'NB' => 'MB', 'NF' => 'MF', 'NV' => 'MV',							// n > m before labial rule
							'RGW' => 'GW', 'RW' => 'GW', 'RY' => 'DY',
							'BW' => 'BG',														// bw sometimes written bg
							'KWU' => 'KU', 'KWO' => 'KO',										// ku+u and ku+o in different orthographies
							'AA' => 'A', 'EE' => 'E', 'II' => 'I', 'OO' => 'O', 'UU' => 'U',	// Double vowels sometimes used to show accentation
							'PH' => 'F',														// Loan words
							'SIT' => 'ST',														// e.g. sitade = stade
							'KIR' => 'KR',														// e.g. umukiristo = umukristo
							'R' =>'L',															//
							'([^AEIOU])[AEIOU]([AEIOU])' => '\1\2');							// CVV > CV, e.g. ni aho = naho
							
$kumva_rw_suffixes = array('mo', 'yo', 'ho');

/**
 * Gets the stem of the given Kinyarwanda form... not really able to do this yet so we just remove punctuation
 * @param string text the text to stem
 * @return string the stem
 */
function kumva_rw_stem($text) {
	global $kumva_rw_suffixes;
	
	$text = strtolower($text);
	$text = Lexical::stripPunctuation($text);
	
	// If its long enough then strip any pronominal suffixes
	if (strlen($text) >= 6)
		Lexical::removeSuffixes($text, $kumva_rw_suffixes);

	// Remove final vowel (lets -e verb endings match)
	if (strlen($text) >= 4)
		$text = Lexical::removeFinalVowel($text);
	
	return $text;
}

/**
 * Gets a sounding representation of the given Kinyarwanda form
 * @param string text the text
 * @return string the sounding
 */
function kumva_rw_sound($text) {
	global $kumva_rw_soundmaps;
	
	$text = strtoupper($text);
	
	// Strip initial vowel as these are often very short or dropped in Kinyarwanda
	$text = Lexical::removeInitialVowel($text);
	
	// Remove punctuation and do sound map
   	$text = Lexical::stripPunctuation($text);
   	$text = Lexical::applySoundmap($text, $kumva_rw_soundmaps);
	
	return $text;
}

/**
 * Gets search suggestions for the given text
 * @param string text the text
 * @return array the array of suggestions
 */
function kumva_rw_suggestions($text) {
	global $kumva_rw_suffixes;
	$suggestions = array();
	
	// Remove pronominal suffixes for first suggestion
	foreach ($kumva_rw_suffixes as $suffix) {
		if (aka_endswith($text, $suffix)) {
			$text = substr($text, 0, strlen($text) - strlen($suffix));
			$suggestions[] = $text;
			break;
		}
	}
	
	// Replace diminutive prefixes aka/utu with other classes
	if (aka_startswith($text, 'aka')) {
		$stem = substr($text, 3);
		$suggestions[] = 'umu'.$stem;	
		$suggestions[] = 'iki'.$stem;
		$suggestions[] = 'in'.$stem;
	}
	elseif (aka_startswith($text, 'aga')) {
		$stem = substr($text, 3);
		$suggestions[] = 'umu'.$stem;
		$suggestions[] = 'igi'.$stem;
		$suggestions[] = 'in'.$stem;
	}
	elseif (aka_startswith($text, 'ak')) {
		$stem = substr($text, 2);
		$suggestions[] = 'umw'.$stem;
		$suggestions[] = 'icy'.$stem;
		$suggestions[] = 'inz'.$stem;
	}
	if (aka_startswith($text, 'utu') || aka_startswith($text, 'ugu')) {
		$stem = substr($text, 3);
		$suggestions[] = 'aba'.$stem;
		$suggestions[] = 'imi'.$stem;
		$suggestions[] = 'ibi'.$stem;
	}
	elseif (aka_startswith($text, 'utw')) {
		$stem = substr($text, 3);
		$suggestions[] = 'ab'.$stem;
		$suggestions[] = 'imy'.$stem;
		$suggestions[] = 'iby'.$stem;
	}	
	
	// Strip letters from beginning to create other suggestions
	while (strlen($text) > 3) {
		if ($text[0] != '-')
			$text = '-'.$text;				// Append dash
		elseif ($text[2] != ' ')
			$text = '-'.substr($text, 2);  	// Replace next starting letter with a dash
		else
			$text = substr($text, 3);		// Skip over word space
			
		$suggestions[] = $text;
	}
		
	return $suggestions;
}

/**
 * Returns form tag strings for the given definition
 * @param Definition the definition
 * @return array the array of tag strings, e.g. ['gukora', '-kora', '-koze']
 */
function kumva_rw_autotag_form($definition) {
	$forms = array();
	$forms[] = $definition->getPrefix().$definition->getLemma();		// Always prefix+lemma for all word classes
	
	if ($definition->getWordClass() == 'v') {
		$forms[] = '-'.$definition->getLemma();								// Verb present tense / imperative
		$forms[] = rw_verbpasttense($definition);						// Verb past tense
	} elseif ($definition->getWordClass() == 'n') {
		$forms[] = rw_plural($definition);								// Noun plural
	} 
	return $forms;
}

/**
 * Creates the plural form of a noun from the stem and the plural prefix
 */
function rw_plural($definition) {
    $modifier = $definition->getModifier();
    if (!aka_endswith($modifier, '-'))
		return $modifier;
    
    return str_replace('-', $definition->getLemma(), $modifier);
}

function rw_verbpasttense($definition) {
	$modifier = $definition->getModifier();
	if (!aka_startswith($modifier, '-'))
		return $modifier;

	// Verb may have auxillary words
	$words = explode(' ', $definition->getLemma());
	$verb = $words[0];
	array_shift($words);
	$extra = implode(' ', $words);
	$stem = rw_verbstem($verb);

	if ($stem) {
		$past = str_replace('-', $stem, $modifier);
		return '-'.$past.($extra ? ' '.$extra : '');
	}

	return $definition->getModifier();
}

function rw_verbstem($verb) {
	global $kumva_rw_suffixes;
	
	$verb = Lexical::removeSuffixes($verb, $kumva_rw_suffixes);
    
    for ($c = strlen($verb) - 1; $c >= 0; $c--) {
    	if (!Lexical::isVowel($verb[$c])) {
    		if ($c == 0)
    			return substr($verb, 0, $c);
    		if (Lexical::isVowel($verb[$c - 1]))
    			return substr($verb, 0, $c);
    	}
    }
    return NULL;
}

?>
