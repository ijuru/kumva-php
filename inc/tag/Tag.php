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
 * Purpose: Tag class
 */

/**
 * Tag class
 */
class Tag extends Entity implements JsonSerializable {
	private $lang;
	private $text;
	private $stem;
	private $sound;
	
	/**
	 * Constructs a tag
	 * @param int id the tag id
	 * @param string lang the language code
	 * @param string text the text
	 * @apram string stem the stem
	 * @param string sound the sound
	 */
	public function __construct($id, $lang, $text, $stem, $sound) {
		$this->id = (int)$id;
		$this->lang = $lang;
		$this->text = $text;
		$this->stem = $stem;
		$this->sound = $sound;
		
		if ($this->stem == NULL || $this->sound == NULL)
			$this->generateLexical();
	}
	
	/**
	 * Creates a tag from the given row of database columns
	 * @param array the associative array
	 * @return Tag the tag
	 */
	public static function fromRow(&$row) {
		return new Tag($row['tag_id'], $row['lang'], $row['text'], $row['stem'], $row['sound']);
	}
	
	/**
	 * Gets the language code, e.g. 'rw'
	 * @return string the language code
	 */
	public function getLang() {
		return $this->lang;
	}
	
	/**
	 * Gets the text
	 * @return string the text
	 */
	public function getText() {
		return $this->text;
	}
	
	/**
	 * Gets the stem
	 * @return string the stem
	 */
	public function getStem() {
		return $this->stem;
	}
	
	/**
	 * Gets the sound string 
	 * @return string the sound string
	 */
	public function getSound() {
		return $this->sound;
	}
	
	/**
	 * Generates stem and sound based on tag language
	 * @return bool TRUE if successful, else FALSE
	 */
	public function generateLexical() {
		$this->stem = Lexical::stem($this->lang, $this->text);
		$this->sound = Lexical::sound($this->lang, $this->text);
		return ($this->stem !== FALSE && $this->sound !== FALSE);
	}
	
	/**
	 * Gets a string representation (i.e. lang:text, or text if lang is NULL)
	 * @return string the string representation
	 */
	public function toString() {
		return ($this->lang != NULL ? $this->lang.':' : '').$this->text;
	}

	/**
	 * @see JsonSerializable::jsonSerialize()
	 */
	public function jsonSerialize() {
		return [ 
			'lang' => $this->lang,
			'text' => $this->text
		];
	}
}

?>
