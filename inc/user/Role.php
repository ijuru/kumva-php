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
 * Purpose: Role class
 */
 
/**
 * A role given to a user
 */
class Role extends Entity {
	private $name;
	private $description;
	
	// Built-in roles
	const ADMINISTRATOR = 1;
	const EDITOR = 2;
	const CONTRIBUTOR = 3;
	
	/**
	 * Constructs a role based on an id
	 * @param int the id
	 */
	public function __construct($id, $name, $description) {
		$this->id = (int)$id;	
		$this->name = $name;
		$this->description = $description;
	}
	
	/**
	 * Creates a role from the given row of database columns
	 * @param array the associative array
	 * @return Role the role
	 */
	public static function fromRow(&$row) {
		return new Role($row['role_id'], $row['name'], $row['description']);
	}
	
	/**
	 * Gets the name
	 * @return string the name
	 */
	public function getName() {
		return $this->name;
	}
	
	/**
	 * Gets the description
	 * @return string the description
	 */
	public function getDescription() {
		return $this->description;
	}
	
	/**
	 * Gets if this is the super role which contains all other roles
	 * @return bool TRUE if role is super role, else FALSE
	 */
	public function isSuperRole() {
		return $this->id === 1;
	}
}

?>
