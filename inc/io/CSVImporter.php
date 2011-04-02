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
 * Purpose: CSVImporter class
 *
 */
 
/**
 * Class to describe a column in an CSV file
 */
class CSVColumn {
	const PROPERTY = 1;	// Property of definition object
	const TAGS = 2;		// Set of tags for a relationship
	const EXAMPLE = 3;	// Usage example
	
	private $type;
	private $descriptor;
	
	/**
	 * Constructor
	 * @param int type the column type
	 * @param mixed descriptor depends on column type
	 */
	public function __construct($type, $descriptor) {
		$this->type = $type;
		$this->descriptor = $descriptor;
	}
	
	/**
	 * Gets the type
	 * @return int the type
	 */
	public function getType() {
		return $this->type;
	}
	
	/**
	 * Gets the descriptor
	 * @return mixed the descriptor
	 */
	public function getDescriptor() {
		return $this->descriptor;
	}
}

/**
 * CSV importer
 */
class CSVImporter extends Importer {
	/**
	 * Loads definitions from a CSV file
	 * @param string path path of the CSV file
	 * @param bool TRUE if definitions are verified
	 * @return bool TRUE if successful, else FALSE
	 */
	public function load($path, $verified) {	
		$handle = @fopen($path, "r");
		if (!$handle)
			return FALSE;
	
		// Parse header row to get column descriptors
		$header = fgetcsv($handle, 0, ",");
		$columns = self::parseHeaderRow($header);
	
		// Load each row as a definition
		while (($row = fgetcsv($handle, 0, ",")) !== FALSE) {
			if ($this->loadRow($row, $columns, $verified) !== FALSE)
				$this->defCount++;
		}
	
		fclose($handle);
		return TRUE;
	}
	
	/**
	 * Parses the header row to get column descriptors for each column
	 * @param array row an associative array
	 * @return array the column descriptors
	 */
	private static function parseHeaderRow(&$row) {
		$columns = array();
		
		foreach ($row as $header) {
			$tokens = explode(':', $header);
			$type = count($tokens) > 1 ? trim($tokens[0]) : NULL;
			$name = count($tokens) > 1 ? trim($tokens[1]) : trim($tokens[0]);
			if (strtolower($type) == 'tags') {
				$type = CSVColumn::TAGS;
				$descriptor = Dictionary::getTagService()->getRelationshipByName($name);
			}
			elseif (strtolower($type) == 'example') {
				$type = CSVColumn::EXAMPLE;
				$descriptor = (int)$name;
			}
			else {
				$type = CSVColumn::PROPERTY;
				$descriptor = $name;
			}
			
			$columns[] = new CSVColumn($type, $descriptor);
		}
		
		return $columns;
	}
	
	/**
	 * Loads a definition from a row
	 * @param array row array of row values
	 * @param array column array of column descriptors
	 * @param bool TRUE if definitions default to verified
	 * @return Definition the definition or FALSE if an error occured
	 */
	private function loadRow(&$row, &$columns, $verified) {
		$definition = new Definition();
		$examples = array();
		
		// Default verified value
		$definition->setVerified($verified);
		
		for ($col = 0; $col < count($row); $col++) {
			$value = $row[$col];
			$column = $columns[$col];
			switch ($column->getType()) {
			case CSVColumn::PROPERTY:
				$prop = lcfirst($column->getDescriptor());
				BeanUtils::setProperty($definition, $prop, $value);
				break;
			case CSVColumn::TAGS:
				$relationship = $column->getDescriptor();
				$definition->setTagsFromStrings($relationship, aka_parsecsv($value));
				break;
			case CSVColumn::EXAMPLE:
				if ($value) {
					$exParts = explode('/', $value);
					$examples[] = new Example(0, $exParts[0], $exParts[1]);
					$this->exampleCount++;
				}
				break;
			}
		}
		
		// Add usage examples
		$definition->setExamples($examples);
	
		// Save definition to database
		if (!Dictionary::getDefinitionService()->saveDefinition($definition)) {
			$this->log('Unable to save definition "'.$definition->toString().'"');
			return FALSE;
		}	
		
		return $definition;
	}
}

?>
