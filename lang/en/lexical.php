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
 * Purpose: Lexical functions for English
 */

include_once KUMVA_DIR_ROOT.'/lib/porter2stemmer.php';
 
global $kumva_porter2, $kumva_en_soundmaps;

$kumva_porter2 = new Porter2Stemmer();
 
$kumva_en_soundmaps = array('ISE' => 'IZE', 'OUR' => 'OR', 'TRE\B' => 'TER', 			// UK > US word endings
							'PH' => 'F', 
							'BB' => 'B', 'DD' => 'D', 'GG' => 'G', 'LL' => 'L', 'MM' => 'M', 'NN' => 'N', 'PP' => 'P', 'SS' => 'S', 'TT' => 'T',
							'SCH' => 'SK', 
							'SC' => 'SK', 
							'CA' => 'KA', 'CO' => 'KO', 'CU' => 'KU',					// C / K equivalence
							'X' => 'KS');

/**
 * Gets the stem of the given English form using the PORTER2 algorithm
 * @param string form the form to stem
 * @return string the stem
 */
function kumva_en_stem($text) {
	global $kumva_porter2;
	
	$text = strtolower($text);
    return $kumva_porter2->Stem($text);
}

/**
 * Gets a sounding representation of the given English form
 * @param string form the form
 * @return string the sounding
 */
function kumva_en_sound($text) {
	global $kumva_en_soundmaps;
	
	$text = strtoupper($text);
 
   	// Remove punctuation and do sound map
   	$text = Lexical::stripPunctuation($text);
   	$text = Lexical::applySoundmap($text, $kumva_en_soundmaps);
	
	return $text;
}

/**
 * Returns meaning tag strings for the given definition
 * @param Definition the definition
 * @return array the array of tag strings
 */
function kumva_en_autotag_meaning($definition) {
	$tags = array();
	foreach ($definition->getMeanings() as $meaning)
		$tags = array_merge($tags, aka_parsecsv($meaning->getMeaning()));
	
	if ($definition->getWordClass() == 'v') {
		// Strip infinitive prepositions
		for ($t = 0; $t < count($tags); $t++) {
			if (aka_startswith($tags[$t], 'to '))
				$tags[$t] = substr($tags[$t], 3);
			if (aka_startswith($tags[$t], 'be '))
				$tags[$t] = substr($tags[$t], 3);
		}
	}
	else if ($definition->getWordClass() == 'n') {
		// Strip articles
		for ($t = 0; $t < count($tags); $t++) {
			if (aka_startswith($tags[$t], 'a '))
				$tags[$t] = substr($tags[$t], 2);
			if (aka_startswith($tags[$t], 'the '))
				$tags[$t] = substr($tags[$t], 4);
		}
	}
	
	return $tags;
}

?>
