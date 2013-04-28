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
 * Purpose: Example class
 */
 
define('KUMVA_MAX_EXAMPLES', 10);

/**
 * Usage example class
 */
class Example extends Entity {
	private $form;
	private $meaning;
	
	/**
	 * Constructs an example
	 * @param int id the id
	 * @param string form the form
	 * @param string meaning the meaning
	 */
	public function __construct($id, $form, $meaning) {
		$this->id = (int)$id;
		$this->form = $form;
		$this->meaning = $meaning;
	}
	
	/**
	 * Creates an example from the given row of database columns
	 * @param array the associative array
	 * @return Example the example
	 */
	public static function fromRow(&$row) {
		return new Example($row['example_id'], $row['form'], $row['meaning']);
	}
	
	/**
	 * Gets the form
	 * @return string the form
	 */
	public function getForm() {
		return $this->form;
	}
	
	/**
	 * Gets the meaning
	 * @return string the meaning
	 */
	public function getMeaning() {
		return $this->meaning;
	}
}

?>
