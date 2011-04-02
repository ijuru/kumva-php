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
 * Purpose: RAnk class
 */
 
/**
 * A rank given to a user
 */
class Rank extends Entity {
	private $name;
	private $threshold;
	
	/**
	 * Constructs a rank
	 * @param int id the id
	 * @param string name the name
	 * @param int threshold the effort threshold
	 */
	public function __construct($id, $name, $threshold) {
		$this->id = (int)$id;	
		$this->name = $name;
		$this->threshold = (int)$threshold;
	}
	
	/**
	 * Creates a rank from the given row of database columns
	 * @param array the associative array
	 * @return Rank the rank
	 */
	public static function fromRow(&$row) {
		return new Rank($row['rank_id'], $row['name'], $row['threshold']);
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
	 * Gets the threshold
	 * @return int the threshold
	 */
	public function getThreshold() {
		return $this->threshold;
	}
}

?>
