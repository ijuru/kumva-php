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
 * Purpose: Miscellaneous utility functions
 */
 
$AKABANGA_JSINCLUDES = array();
 
/**
 * Checks if a string ends with another string
 */
function aka_endswith($haystack, $needle){
    return strrpos($haystack, $needle) === strlen($haystack) - strlen($needle);
}

/**
 * Checks if a string starts with another string
 */
function aka_startswith($haystack, $needle) {
	return substr($haystack, 0, strlen($needle)) == $needle;
}

/**
 * Builds a url from a script name an array of parameters
 * @param string script the name of the script, e.g. index.php
 * @param array params the query string parameters, e.g. array('q' => 'x', 's' => 0);
 * @return the URL, e.g. 'index.php?q=x&s=0'
 */
function aka_buildurl($script, $params) {
	$pairs = array();
	foreach ($params as $param => $value)
		$pairs[] = $param.'='.urlencode($value);
	
	return $script.'?'.implode('&amp;', $pairs);
}

/**
 * Parses a string into a boolean value ('0', 'no' and 'false' all equate to FALSE, '1', 'yes' and 'true' all equate to TRUE)
 * @param string string the CSV string
 * @param bool default the default value
 * @return bool the boolean value
 */
function aka_parsebool($string, $default = NULL) {
	if ($string === NULL)
		return $default;
	$string = strtolower(trim($string));
	if (in_array($string, array('1', 'yes', 'true')))
		return TRUE;
	if (in_array($string, array('0', 'no', 'false')))
		return FALSE;
	return $default;
}

/**
 * Parses a CSV string into an array of values (strings are trimmed, empty strings are discarded)
 * @param string csv the CSV string
 * @param bool asInts TRUE if ecah value should be cast to an int
 * @return array the array of values
 */
function aka_parsecsv($csv, $asInts = FALSE) {
	$values = array();
	foreach (explode(',', $csv) as $value) {
		$value = trim($value);
		if (strlen($value) > 0)
			$values[] = $asInts ? (int)$value : $value;
	}
	return $values;
}

/**
 * Creates a CSV string from an array of values
 * @param array the array of values
 * @return string the CSV string
 */
function aka_makecsv($values) {
	return implode(', ', $values);
}

/**
 * Prepares a value for inclusion in a SQL statement
 * @param mixed val the value to prepare
 */
function aka_prepsqlval($val) {
	if (is_null($val) || $val === '')
		return 'NULL';
	elseif (is_int($val))
		return $val;
	elseif ($val instanceof Entity)
		return $val->getId();
	
	// Clean pattern string to prevent SQL injection attack and escape quotes etc
	return "'".Database::getCurrent()->escape($val)."'";
}

/**
 * Converts a GMT SQL timestamp to a UNIX timestamp
 * @param string sqlDate the GMT SQL timestamp string
 * @return int the UNIX timestamp
 */
function aka_timefromsql($sqlDate) {
	return $sqlDate ? strtotime($sqlDate.' GMT') : NULL;
}

/**
 * Converts a UNIX timestamp to SQL date value
 * @param int timestamp the UNIX timestamp
 * @return string the SQL
 */
function aka_timetosql($timestamp) {
	return $timestamp ? 'FROM_UNIXTIME('.$timestamp.')' : 'NULL';
}

/**
 * Prepares a value for inclusion in XML
 * @param mixed val the value to prepare
 */
function aka_prepxmlval($val) {
	if (is_bool($val))
		return $val ? 'true' : 'false';
		
	return htmlspecialchars($val);
}

/**
 * Prepares a value for inclusion in a CSV file 
 * @param mixed val the value to prepare
 */
function aka_prepcsvval($val) {
	if (is_string($val))
		return '"'.str_replace('"', '""', $val).'"';
	
	return $val;
}

/**
 * Prepares a value for inclusion in HTML 
 * @param mixed val the value to prepare
 * @param bool newLines TRUE if newlines should be preserved as <br/> tags
 */
function aka_prephtml($val, $newLines = FALSE) {
	$html = htmlspecialchars($val);
	
	return $newLines ? str_replace("\n", '<br/>', $val) : $html;
}

/**
 * Registers a javascript file to be included in js/master.js.php
 * @param string path the path of the javascript file
 */
function aka_includejs($path) {
	global $AKABANGA_JSINCLUDES;
	$AKABANGA_JSINCLUDES[] = $path;
}

/**
 * Gets the specified bit of a value
 * @param int val the value
 * @param int bit the bit number
 * @return bool the bit state
 */
function aka_getbit($val, $bit) {
	$mask = pow(2, $bit);
	return (bool)($val & $mask);
}

/**
 * Sets the specified bit of a value to true or false
 * @param int val the value to modify
 * @param int bit the bit number
 * @param bool state the bit state (defaults to TRUE)
 */
function aka_setbit($val, $bit, $state = true) {
	$mask = pow(2, $bit);
	if ($state)
		return $val | $mask;
	else
		return $val & ~$mask;
}
 
?>
