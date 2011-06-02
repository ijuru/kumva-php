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
 * Purpose: Importer class
 *
 */
 
/**
 * Base class of importers
 */
abstract class Importer {
	protected $defCount = 0;
	protected $exampleCount = 0;
	protected $messages = array();
	
	/**
	 *
	 */
	public function run($clearExisting, $path, $unverified) {
		if ($clearExisting) {
			if (!Dictionary::getDefinitionService()->clear()) {
				$this->log('Unable to clear existing definitions');
				return FALSE;
			}
			else
				$this->log('Cleared existing definitions');
		}
		
		if ($this->load($path, $unverified)) {
			$this->log('Loaded '.$this->getDefinitionCount().' definitions and '.$this->getExampleCount().' examples');
			return TRUE;
		}
		else {
			$this->log('Unable to load '.$path);
			return FALSE;	
		}
	}
	
	/**
	 * Loads definitions from a file
	 * @param string path path of the file
	 * @param bool TRUE if definitions are unverified
	 * @return bool TRUE if successful, else FALSE
	 */
	public abstract function load($path, $unverified);
	
	/**
	 * Logs a status message
	 * @param string message the message
	 */
	protected function log($message) {
		$this->messages[] = $message;
	}
	
	/**
	 * Gets the number of definitions imported so far
	 * @return int the number of definitions
	 */
	public function getDefinitionCount() {
		return $this->defCount;
	}
	
	/**
	 * Gets the number of examples imported so far
	 * @return int the number of examples
	 */
	public function getExampleCount() {
		return $this->exampleCount;
	}
	
	/**
	 * Gets the status messages
	 * @return array the status messages
	 */
	public function getMessages() {
		return $this->messages;
	}
}

?>
