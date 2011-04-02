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
 * Purpose: Errors class
 */
 
/**
 * Class for recording validation errors of entities
 */
class Errors {
	private $errors = array();
	
	/**
	 * Adds an error message not specific to a property
	 * @param string message the error message
	 */
	public function add($message) {
		if (!isset($this->errors['*']))
			$this->errors['*'] = array();
			
		$this->errors['*'][] = $message;
	}
	
	/**
	 * Gets all the errors not specific to a property
	 * @return array the error messages
	 */
	public function get() {
		return isset($this->errors['*']) ? $this->errors['*'] : array();
	}
	
	/**
	 * Adds an error message for the given property
	 * @param string property the name of the property
	 * @param string message the error message
	 */
	public function addForProperty($property, $message) {
		if (!isset($this->errors[$property]))
			$this->errors[$property] = array();
		
		$this->errors[$property][] = $message;
	}
	
	/**
	 * Gets all the errors for the given property
	 * @return array the error messages
	 */
	public function getForProperty($property) {
		return isset($this->errors[$property]) ? $this->errors[$property] : array();
	}
	
	/**
	 * Gets if there are any error messages
	 * @return bool TRUE if there are errors, else FALSE
	 */
	public function isEmpty() {
		return count($this->errors) == 0;
	}
}

?>
