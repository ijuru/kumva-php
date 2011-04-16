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
 * Purpose: Language class
 */
 
/**
 * Language used for site messages or lexical processing
 */ 
class Language extends Entity {
	private $code;
	private $name;
	private $localName;
	private $queryUrl;
	private $hasTranslation;
	private $hasLexical;
	
	/**
	 * Constructs a language
	 * @param int id the id
	 * @param string code the language code
	 * @param string name the name
	 * @param string name the local name
	 * @param string queryUrl the URL for lookups of this language
	 * @param bool hasTranslation has a site translation file
	 * @param bool hasLexical has a lexical script file
	 */
	public function __construct($id, $code, $name, $localName, $queryUrl, $hasTranslation, $hasLexical) {
		$this->id = (int)$id;
		$this->code = $code;
		$this->name = $name;
		$this->localName = $localName;
		$this->queryUrl = $queryUrl;
		$this->hasTranslation = (bool)$hasTranslation;
		$this->hasLexical = (bool)$hasLexical;
	}
	
	/**
	 * Creates a tag from the given row of database columns
	 * @param array the associative array
	 * @return Tag the tag
	 */
	public static function fromRow(&$row) {
		return new Language($row['language_id'], $row['code'], $row['name'], $row['localname'], $row['queryurl'], $row['hastranslation'], $row['haslexical']);
	}
	
	/**
	 * Gets the code, e.g. 'en'
	 * @return string the code
	 */
	public function getCode() {
		return $this->code;
	}
	
	/**
	 * Gets the name, e.g. 'French'
	 * @return string the name
	 */
	public function getName() {
		return $this->name;
	}
	
	/**
	 * Gets the local name, e.g. 'Français'
	 * @return string the local name
	 */
	public function getLocalName() {
		return $this->localName;
	}
	
	/**
	 * Gets the query URL
	 * @return string the URL
	 */
	public function getQueryUrl() {
		return $this->queryUrl;
	}
	
	/**
	 * Gets whether language has a site translation
	 * @return bool TRUE if language has a site translation
	 */
	public function hasTranslation() {
		return $this->hasTranslation;
	}
	
	/**
	 * Gets whether language has a lexical script
	 * @return bool TRUE if language has a lexical script
	 */
	public function hasLexical() {
		return $this->hasLexical;
	}
}

?>
