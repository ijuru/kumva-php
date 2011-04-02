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
 * Purpose: Report results class
 */

/**
 * Holds the results of a report
 */
class ReportResults {
	private $fields;
	private $fieldsRaw;
	private $rows;
	private $time;
	
	/**
	 * Constructs a results object
	 * @param array fields the field names
	 * @param rows the rows
	 * @param int time the time taken
	 */
	public function __construct($fields, $rows, $time = NULL) {
		$this->fieldsRaw = $fields;
		$this->rows = $rows;
		$this->time = $time;
		
		// Create array of clean field names (i.e. remove prefixes)
		$this->fields = array();
		foreach ($this->fieldsRaw as $field) {
			if ($field[0] != '_') {
				if ($field[0] == '?' || $field[0] == '#')
					$field = substr($field, 1);
				$this->fields[] = $field;	
			}
		}	
	}
	
	/**
	 * Gets the field names
	 * @param bool raw TRUE to return raw field names with prefixes
	 * @return array the field names
	 */
	public function getFields($raw = FALSE) {
		return $raw ? $this->fieldsRaw : $this->fields;
	}
	
	/**
	 * Gets the rows
	 * @return array the rows
	 */
	public function getRows() {
		return $this->rows;	
	}
	
	/**
	 * Gets the number of rows
	 * @return int the number of rows
	 */
	public function getRowCount() {
		return count($this->rows);	
	}
	
	/**
	 * Gets the time taken in milliseconds
	 * @return int the time taken
	 */
	public function getTime() {
		return $this->time;	
	}
	
	/**
	 * Gets the CSV representation of these results
	 * @return string the CSV string
	 */
	public function toCSVString() {
		$csv = implode(',', $this->getFields())."\n";
		
		foreach ($this->rows as $row) {
			foreach ($this->fieldsRaw as $field) {
				if ($field[0] != '_') {
					$csv .= $row[$field];
					$csv .= ",";
				}
			}
			$csv .= "\n";	
		}
		
		return $csv;	
	}
}

?>