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
 * Purpose: WordClass class
 */
 
// Word class abbreviations
$KUMVA_WORDCLASS = array(
	'abbr' => KU_STR_ABBREVIATION,
	'adj' => KU_STR_ADJECTIVE,
	'adv' => KU_STR_ADVERB,
	'conj' => KU_STR_CONJUNCTION,
	'dem' => KU_STR_DEMONSTRATIVE,
	'int' => KU_STR_INTERJECTION,
	'n' => KU_STR_NOUN,
	'nm' => KU_STR_NOUNMODIFIER,
	'phr' => KU_STR_PHRASE,
	'pn' => KU_STR_PROPERNOUN,
	'prep' => KU_STR_PREPOSITION,
	'pro' => KU_STR_PRONOUN,
	'v' => KU_STR_VERB,
	'vm' => KU_STR_VERBMODIFIER
);

/**
 * Word class class
 */
class WordClass {
	/**
	 * Gets the name of a word class from its abbreviation
	 * @param string code the word class abbreviation
	 * @return string the name
	 */
	public static function getNameFromCode($code) {
		global $KUMVA_WORDCLASS;
		return isset($KUMVA_WORDCLASS[$code]) ? $KUMVA_WORDCLASS[$code] : 'Unknown';
	}
}

?>
