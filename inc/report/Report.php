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
 * Purpose: Report class
 */
 
/**
 * Report which returns results from a function
 */
class Report {
	protected $name;
	protected $title;
	protected $function;
	
	/**
	 * Constructs a report
	 * @param string name the name
	 * @param string title the title
	 * @param string function the function name
	 */
	public function __construct($name, $title, $function) {
		$this->name = $name;
		$this->title = $title;
		$this->function = $function;	
	}
	
	/**
	 * Gets the name
	 * @return string the name
	 */
	public function getName() {
		return $this->name;	
	}
	
	/**
	 * Gets the title (as displayed)
	 * @return string the title
	 */
	public function getTitle() {
		return $this->title;	
	}
	
	/**
	 * Gets the function name
	 * @return string the function name
	 */
	public function getFunction() {
		return $this->function;	
	}
	
	/**
	 * Runs the report
	 * @param array the report parameters
	 * @param Paging the paging object
	 * @return ReportResults the results
	 */
	public function run($paging = NULL) {
		$fn = $this->function;
		return $fn($paging);
	}
}

?>