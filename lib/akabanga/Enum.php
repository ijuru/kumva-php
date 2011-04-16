<?php
/**
 * This file is part of Akabanga.
 *
 * Akabanga is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Akabanga is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Akabanga.  If not, see <http://www.gnu.org/licenses/>.
 *
 * Copyright Rowan Seymour 2010
 * 
 * Purpose: Enum class
 */
 
/**
 * Base class for enumeration classes. Such classes should contain a static array
 * called $strings which maps ordinal values to strings and/or a static array called
 * $localized which maps ordinal values to localized strings
 */
class Enum {
	/**
	 * Gets the array of all ordinal values
	 * @return array the values
	 */
	public static function values() {
		return array_keys(static::$strings);
	}
	
	/**
	 * Gets a string from the ordinal value
	 * @param int value the value
	 * @return string the string value or FALSE if ordinal wasn't valid
	 */
	public static function toString($value) {
		return isset(static::$strings[$value]) ? static::$strings[$value] : FALSE;
	}
	
	/**
	 * Gets a localized string from the ordinal value
	 * @param int value the value
	 * @return string the localized string value or FALSE if ordinal wasn't valid
	 */
	public static function toLocalizedString($value) {
		return isset(static::$localized[$value]) ? static::$localized[$value] : FALSE;
	}
	
	/**
	 * Parses an ordinal value from a string
	 * @param string the string to parse
	 * @return int the ordinal value, or FALSE if string couldn't be parsed
	 */
	public static function parseString($string) {
		return array_search($string, static::$strings);
	}
	
	/**
	 * Parses an array of ordinal values from a CSV string
	 * @param csv the CSV string to parse
	 * @return array the ordinal values
	 */
	public static function parseCSVString($csv) {
		$ordinals = array();
		$strings = aka_parsecsv($csv);
		foreach ($strings as $string) {
			$ordinal = self::parseString($string);
			if ($ordinal !== FALSE)
				$ordinals[] = $ordinal;
		}
		return $ordinals;
	}
	
	/**
	 * Creates a CSV string from the array of ordinals
	 * @param array ordinals the ordinals
	 * @return string the CSV string
	 */
	public static function makeCSVString($ordinals) {
		$strings = array();
		foreach ($ordinals as $ordinal) 
			$strings[] = self::toString($ordinal);
		return aka_makecsv($strings);
	}
	
	/**
	 * Parses the given bitfield to get an array of ordinals
	 * @param int value the bit field
	 * @return array the ordinals
	 */
	public static function fromBits($value) {
		$ordinals = array();
		$ordinal = 0;
		while ($value) {
			if ($value & 1)
				$ordinals[] = $ordinal;
			$value = $value >> 1;
			$ordinal++;	
		}
		return $ordinals;
	}
	
	/**
	 * Converts the array of ordinal values to a bitfield
	 * @param array the ordinals
	 * @return int the bit field
	 */
	public static function toBits($ordinals) {
		$value = 0;	
		foreach ($ordinals as $ordinal) {
			$value |= (1 << $ordinal);	
		}
		return $value;
	}
}

?>
