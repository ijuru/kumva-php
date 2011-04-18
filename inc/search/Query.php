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
 * Purpose: Query class
 */
 
/**
 * Enumeration of ordering types
 */
class OrderBy extends Enum {
	const ENTRY = 0;
	const STEM = 1;
	const RELEVANCE = 2;
	
	protected static $strings = array('entry', 'stem', 'relevance');
}
 
/**
 * Class to represent a dictionary query
 */
class Query {
	private $pattern;
	private $lang;
	private $relationship;
	private $partialMatch;
	private $wordClass;
	private $verified;
	private $orderBy;
	private $rawQuery;
	
	private static $parsedParams = array();
	
	/**
	 * Creates a query based on match criteria and search pattern
	 * @param int orderBy how to order the results
	 */
	private function __construct($pattern, $lang, $relationship, $partialMatch, $wordClass, $verified, $orderBy, $rawQuery) {
		$this->pattern = $pattern;
		$this->lang = $lang;
		$this->relationship = $relationship;
		$this->partialMatch = $partialMatch;
		$this->wordClass = $wordClass;
		$this->verified = $verified;
		$this->orderBy = $orderBy;
		$this->rawQuery = $rawQuery;
	}
	
	/**
	 * Creates a query by parsing a string
	 * @param string string the search string
	 */
	public static function parse($string) {
		// Capture and remove all query parameters, pattern is what is left
		self::$parsedParams = array();
		$pattern = strtolower(trim(preg_replace_callback("/(\w+):\s*(\w+)/", 'Query::parseParamMatchCallback', $string)));
		
		// If pattern starts or ends with an * then we should do a partial match
		$partialMatch = aka_startswith($pattern, '*') || aka_endswith($pattern, '*');
	
		// Get named query parameters
		$lang = self::readParameter('lang');
		
		$wordClass = self::readParameter('wclass');
		$verified = aka_parsebool(self::readParameter('verified'));
		
		$order = self::readParameter('order');
		$orderBy = $order ? OrderBy::parseString($order) : NULL;
		if ($orderBy === FALSE)
			$orderBy = NULL;
		
		$match = self::readParameter('match');
		$relationship = $match ? Dictionary::getTagService()->getRelationshipByName($match) : NULL;
		
		return new Query($pattern, $lang, $relationship, $partialMatch, $wordClass, $verified, $orderBy, $string);		
	}
	
	/**
	 * Callback from parse method as it matches params in the query string
	 */
	public static function parseParamMatchCallback($matches) {
		self::$parsedParams[$matches[1]] = $matches[2];
		return '';
	}
	
	/**
	 * Reads a parameter from the query
	 * @param string name the name of the parameter
	 * @param string default the default value
	 * @return string the parameter value
	 */
	private static function readParameter($name, $default = NULL) {
		return isset(self::$parsedParams[$name]) ? self::$parsedParams[$name] : $default; 
	}
	
	/**
	 * Gets the pattern
	 */
	public function getPattern() {
		return $this->pattern;
	}
	
	/**
	 * Sets the pattern
	 */
	public function setPattern($pattern) {
		$this->pattern = $pattern;
	}
	
	/**
	 * Gets the language code
	 * @return string the language code
	 */
	public function getLang() {
		return $this->lang;
	}
	
	/**
	 * Gets the relationship
	 * @return Relationship the relationship
	 */
	public function getRelationship() {
		return $this->relationship;
	}
	
	/**
	 * Gets if query is for a partial match
	 * @return bool TRUE if is partial match, else FALSE
	 */
	public function isPartialMatch() {
		return $this->partialMatch;
	}
	
	/**
	 * Gets the word class to match
	 * @return string the word class
	 */
	public function getWordClass() {
		return $this->wordClass;
	}
	
	/**
	 * Gets the verified requirement
	 * @return bool the verified requirement
	 */
	public function getVerified() {
		return $this->verified;
	}
	
	/**
	 * Gets the order by
	 * @return int the order by
	 */
	public function getOrderBy() {
		return $this->orderBy;
	}
	
	/**
	 * Gets the raw query, e.g. "match:root lang:sw"
	 * @return string the raw query string
	 */
	public function getRawQuery() {
		return $this->rawQuery;
	}
}

?>
