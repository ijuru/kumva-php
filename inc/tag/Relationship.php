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
 * Purpose: Relationship class
 */
 
/**
 * Tag relationship type
 */ 
class Relationship extends Entity {
	// System relationship types
	const FORM = 1;
	const VARIANT = 2;
	const MEANING = 3;
	const ROOT = 4;
	const CATEGORY = 5;

	private $name;
	private $title;
	private $description;
	private $system;
	private $matchDefault;
	private $defaultLang;
	
	/**
	 * Constructs a relationship
	 * @param int id the id
	 * @param string name the name, e.g. 'form'
	 * @param string title the title, e.g. 'Form'
	 * @param string description the description
	 * @param bool system TRUE if this is a system type
	 * @param bool matchDefault TRUE if tags of this relationship are matched by default
	 * @param string defaultLang the default language code, e.g. 'rw'
	 */
	public function __construct($id, $name, $title, $description, $system, $matchDefault, $defaultLang) {
		$this->id = (int)$id;
		$this->name = $name;
		$this->title = $title;
		$this->description = $description;;
		$this->system = (bool)$system;
		$this->matchDefault = (bool)$matchDefault;
		$this->defaultLang = $defaultLang;
	}
	
	/**
	 * Creates a relationship from the given row of database columns
	 * @param array the associative array
	 * @return Relationship the relationship
	 */
	public static function fromRow(&$row) {
		return new Relationship($row['relationship_id'], $row['name'], $row['title'], $row['description'], $row['system'], $row['matchdefault'], $row['defaultlang']);
	}
	
	/**
	 * Gets the name
	 * @return string the name
	 */
	public function getName() {
		return $this->name;
	}
	
	/**
	 * Sets the name
	 * @param string name the name
	 */
	public function setName($name) {
		$this->name = $name;
	}
	
	/**
	 * Gets the title
	 * @return string the title
	 */
	public function getTitle() {
		return $this->title;
	}
	
	/**
	 * Sets the title
	 * @param string title the title
	 */
	public function setTitle($title) {
		$this->title = $title;
	}
	
	/**
	 * Gets the description
	 * @return string the description
	 */
	public function getDescription() {
		return $this->description;
	}
	
	/**
	 * Sets the description
	 * @param string description the description
	 */
	public function setDescription($description) {
		$this->description = $description;
	}
	
	/**
	 * Gets if this is a system relationship (can't be edited)
	 * @return bool TRUE if this is a system relationship
	 */
	public function isSystem() {
		return $this->system;
	}
	
	/**
	 * Gets if this relationship is matched by default during searching
	 * @return bool TRUE if is matched by default
	 */
	public function isMatchDefault() {
		return $this->matchDefault;
	}
	
	/**
	 * Gets the default tag language
	 * @param bool actual TRUE if the language references @D and @T should be converted to the actual languages
	 * @return string the language code or language reference, e.g. 'en' or '@D'
	 */
	public function getDefaultLang($actual = FALSE) {
		if ($actual) {
			if ($this->defaultLang == '@D')
				return KUMVA_LANG_DEFS;
			elseif ($this->defaultLang == '@M')
				return KUMVA_LANG_MEANING;
		}
	
		return $this->defaultLang;
	}
	
	/**
	 * Parses a tag string, e.g. en:thing or rw:ikintu
	 * @param string tagString the tag string
	 * @return Tag the tag
	 */
	public function parseTagString($tagString) {
		$tokens = explode(':', $tagString);
		$lang = count($tokens) > 1 ? trim($tokens[0]) : $this->getDefaultLang(TRUE);
		$text = count($tokens) > 1 ? trim($tokens[1]) : trim($tokens[0]);
		return new Tag(0, strtolower($lang), $text, NULL, NULL);
	}
	
	/**
	 * Creates a tag string from a tag, e.g. en:thing or rw:ikintu
	 * @param Tag tag the tag
	 * @return string the tag string
	 */
	public function makeTagString($tag) {
		return (($tag->getLang() != $this->getDefaultLang(TRUE)) ? $tag->getLang().':' : '').$tag->getText();
	}
	
	/**
	 * Creates an array of tag strings from an array of tags
	 * @param array tags the array of tags
	 * @return string the tag string
	 */
	public function makeTagStrings(&$tags) {
		$tagStrings = array();
		foreach ($tags as $tag)
			$tagStrings[] = $this->makeTagString($tag);
		return $tagStrings;
	}
}

?>
