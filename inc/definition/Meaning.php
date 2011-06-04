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
 * Purpose: Meaning class
 */
 
/**
 * Information flags which apply to meanings
 */
class Flags extends Enum {
	const OLD = 0;
	const RARE = 1;
	const SLANG = 2;
	const RUDE = 3;
	
	protected static $strings = array('old', 'rare', 'slang', 'rude');
	protected static $localized = array(KU_STR_OLD, KU_STR_RARE, KU_STR_SLANG, KU_STR_RUDE);
}

/**
 * Class for a meaning of a definition
 */
class Meaning extends Entity {
	private $meaning;
	private $flags;
	
	/**
	 * Constructs an example
	 * @param int id the id
	 * @param string form the form
	 * @param string meaning the meaning
	 */
	public function __construct($id = 0, $meaning = '', $flags = 0) {
		$this->id = (int)$id;
		$this->meaning = $meaning;
		$this->flags = (int)$flags;
	}
	
	/**
	 * Creates an meaning from the given row of database columns
	 * @param array the associative array
	 * @return Meaning the meaning
	 */
	public static function fromRow(&$row) {
		return new Meaning($row['meaning_id'], $row['meaning'], $row['flags']);
	}
	
	/**
	 * Gets the meaning
	 * @return string the meaning
	 */
	public function getMeaning() {
		return $this->meaning;
	}
	
	/**
	 * Gets the state of the given flag
	 * @param int flag the flag
	 * @return bool TRUE if flag is set, else FALSE
	 */
	public function getFlag($flag) {
		$mask = pow(2, $flag);
		return $this->flags & $mask;
	}
	
	/**
	 * Gets the flags
	 * @return int the flags
	 */
	public function getFlags() {
		return $this->flags;
	}
	
	/**
	 * Sets the state of the given flag
	 * @param int flag the flag
	 * @param bool state TRUE to set flag, else FALSE
	 */
	public function setFlag($flag, $state) {
		$mask = pow(2, $flag);
		if ($state)
			$this->flags |= $mask;
		else
			$this->flags &= ~$mask;
	}
	
	/**
	 * Sets the flags
	 * @param mixed flags the flags (can be an bit field int or CSV string)
	 */
	public function setFlags($flags) {
		if (!is_int($flags))
			$flags = Flags::toBits(Flags::parseCSVString($flags));
		
		$this->flags = $flags;
	}
}

?>
