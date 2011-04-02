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
 * Purpose: Service class
 */
 
/**
 * Base class for services
 */
class Service {
	protected $database;

	/**
	 * Constructs the service
	 * @param Database database the database connection
	 */
	public function __construct($database) {
		$this->database = $database;
	}
}

?>
