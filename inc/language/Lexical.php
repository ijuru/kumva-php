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
 * Purpose: Lexical utility class
 */

/**
 * Lexical utility class
 */
class Lexical {
	private static $punctuation = array('-', ',', "'", ' ', '"', '!', '.', '?', '`');
	
	/**
	 * Initialize languages
	 */
	public static function initializeLanguages() {
		$languages = Dictionary::getLanguageService()->getLexicalLanguages();
		foreach ($languages as $language)
			include_once KUMVA_DIR_ROOT.'/lang/'.$language->getCode().'/lexical.php';
	}
	
	/**
	 * Checks if the give language specific function exists
	 * @param string lang the language code
	 * @param string func the name of the function
	 * @return bool TRUE if function exists, else FALSE
	 */
	public static function hasLangFunction($lang, $func) {
		return function_exists('kumva_'.$lang.'_'.$func);
	}

	/**
	 * Calls a language specific function
	 * @param string lang the language code
	 * @param string func the name of the function
	 * @param array params the params to pass to the function
	 * @return string the result of the language specific function
	 */
	public static function callLangFunction($lang, $func, $params) {
		$func = 'kumva_'.$lang.'_'.$func;
		return function_exists($func) ? call_user_func_array($func, $params) : FALSE;
	}
	
	/**
	 * Gets stem of the given text, in the given language. Defaults to stripping punctuation for unknown languages
	 * @param string lang the language code
	 * @param string text the text to stem
	 * @return string the stemmed text
	 */
	public static function stem($lang, $text) {
		if (self::hasLangFunction($lang, 'stem'))
			return self::callLangFunction($lang, 'stem', array($text));
		
		return strtolower(self::stripPunctuation($text));
	}

	/**
	 * Gets sound of the given text. Defaults to stripping punctuation and uppercasing for unknown languages
	 * @param string lang the language code
	 * @param string text the text
	 * @return string the stemmed text
	 */
	public static function sound($lang, $text) {
		if (self::hasLangFunction($lang, 'sound'))
			return self::callLangFunction($lang, 'sound', array($text));
		
		return strtoupper(self::stripPunctuation($text));
	}

	/**
	 * Gets search suggestions for the given text. Defaults to none (empty array)
	 * @param string lang the language code
	 * @param string text the text
	 * @return array the array of suggestions
	 */
	public static function suggestions($lang, $text) {
		if (self::hasLangFunction($lang, 'suggestions'))
			return self::callLangFunction($lang, 'suggestions', array($text));
	
		return array();
	}
	
	/**
	 * Returns tag strings for the given definition. Defaults to none (empty array)
	 * @param Definition the definition
	 * @param Relationship the relationship
	 * @return array the array of form tag strings, e.g. ['gukora', '-kora', '-koze']
	 */
	function autoTag($definition, $relationship) {
		$lang = $relationship->getDefaultLang(TRUE);
		$func = 'autotag_'.$relationship->getName();
		if (self::hasLangFunction($lang, $func))
			return self::callLangFunction($lang, $func, array($definition));
		
		return array();
	}

	/**
	 * Strips punctuation characters from a string
	 * @param string text the text
	 * @return string the text without punctuation
	 */
	public static function stripPunctuation($text) {
		foreach (self::$punctuation as $char)
			$text = str_replace($char, '', $text);
		return $text;
	}

	/**
	 * Sound maps a string
	 * @param string text the text
	 * @return string the sound mapped text
	 */
	public static function applySoundmap($text, &$maps) {
		foreach ($maps as $pattern => $replace)
			$text = preg_replace('/'.$pattern.'/', $replace, $text);
		return $text;
	}
	
	/**
	 * Removes the given suffixes from a word if they are found
	 * @param string text the text
	 * @param array the suffixes
	 * @retutn string the new text
	 */
	public static function removeSuffixes($text, &$suffixes) {
		foreach ($suffixes as $suffix) {
			if (aka_endswith($text, $suffix))
				return substr($text, 0, strlen($text) - strlen($suffix));
		}
		return $text;
	}

	/**
	 * Removes the initial vowel of a string if its sufficently long
	 * @param string text the text
	 * @param int minLen the minimum length of text that will be modified
	 * @retutn string the new text
	 */
	public static function removeInitialVowel($text, $minLen = 3) {
		if (strlen($text) >= $minLen && self::isVowel($text[0]))
			return substr($text, 1);
		return $text;
	}
	
	/**
	 * Removes the final vowel of a string if its sufficently long
	 * @param string text the text
	 * @param int minLen the minimum length of text that will be modified
	 * @retutn string the new text
	 */
	public static function removeFinalVowel($text, $minLen = 3) {
		if (strlen($text) >= $minLen && self::isVowel($text[strlen($text) - 1]))
			return substr($text, 0, strlen($text) - 1);
		return $text;
	}

	/**
	 * Gets whether the given character is a vowel
	 * @param string char the character to check
	 * @return bool TRUE if character is a vowel
	 */
	public static function isVowel($char) {
		$char = strtoupper($char);
		return $char == 'A' || $char == 'E' || $char == 'I' || $char == 'O' || $char == 'U';
	}
}

?>
