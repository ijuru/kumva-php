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
 * Purpose: Subscription class
 */
 
/**
 * A subscription given to a user
 */
class Subscription extends Entity {
	private $name;
	private $description;
	
	// Built-in subscriptions
	const NEW_CHANGE = 1;
	
	/**
	 * Constructs a subscription based on an id
	 * @param int the id
	 */
	public function __construct($id, $name, $description) {
		$this->id = (int)$id;	
		$this->name = $name;
		$this->description = $description;
	}
	
	/**
	 * Creates a subscription from the given row of database columns
	 * @param array the associative array
	 * @return Subscription the subscription
	 */
	public static function fromRow(&$row) {
		return new Subscription($row['subscription_id'], $row['name'], $row['description']);
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
}

?>
