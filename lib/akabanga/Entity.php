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
 * Purpose: Entity class
 */

/**
 * Class to represent an database entity
 */
abstract class Entity {
	protected $id;
	protected $voided;
	
	/**
	 * Constructs a new entity
	 * @param int the id
	 */
	public function __construct($id) {
		$this->id = (int)$id;
	}
	
	/**
	 * Creates an array of entities from a database query
	 * @param resource result the database query result
	 * @return array the entities
	 */
	public static function fromQuery($result) {
		$entities = array();
		if ($result) {
			while ($row = mysql_fetch_assoc($result))
				$entities[] = static::fromRow($row);
		}
		return $entities;
	}
	
	/**
	 * Creates an array of entities from an array of associative arrays
	 * @param array rows the array of rows
	 * @return array the entities
	 */
	public static function fromRows(&$rows) {
		$entities = array();
		foreach ($rows as $row)
			$entities[] = static::fromRow($row);
		return $entities;
	}
	
	/**
	 * Overridden by subclasses to construct an instance from an associative array
	 * @param array row the associative array
	 * @return Entity the instance
	 */
	public abstract static function fromRow(&$row);
	
	/**
	 * Gets the id
	 * @return int the id
	 */
	public function getId() {
		return $this->id;
	}
	
	/**
	 * Sets the id
	 * @param int id the id
	 */
	public function setId($id) {
		$this->id = $id;
	}
	
	/**
	 * Gets whether entity is voided
	 * @return bool TRUE if voided
	 */
	public function isVoided() {
		return $this->voided;
	}
	
	/**
	 * Sets whether entity is voided
	 * @param bool voided TRUE if voided
	 */
	public function setVoided($voided) {
		$this->voided = (bool)$voided;
	}
	
	/**
	 * Gets if this is a new entity, i.e. doesn't exist as a row in the database
	 * @return bool TRUE if entity is new, else FALSE
	 */
	public function isNew() {
		return ($this->id == 0);
	}
	
	/**
	 * Checks for equality, i.e. equal ids
	 * @param Entity entity the entity
	 * @return bool TRUE if equal, else FALSE
	 */
	public function equals($entity) {
		return $this->id == $entity->id;
	}
	
	/**
	 * Checks if this entity occurs in the given array of entities
	 * @param array entities the array
	 * @return bool TRUE if entity occurs, else FALSE
	 */
	public function inArray(&$entities) {
		foreach ($entities as $entity) {
			if ($this->equals($entity))
				return TRUE;
		}
		return FALSE;
	}
}

?>
