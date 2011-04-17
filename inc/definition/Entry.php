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
 * Purpose: Entry class
 */

/**
 * Dictionary entry class
 */
class Entry extends Entity {
	private $deleteChangeId;
	
	// Lazy loaded properties
	private $head;
	private $deleteChange;
	private $revisions;
	
	/**
	 * Constructs an entry
	 * @param int id the id
	 */
	public function __construct($id = 0, $deleteChangeId = NULL) {
		$this->id = (int)$id;
		$this->deleteChangeId = $deleteChangeId;
	}
	
	/**
	 * Creates an entry from the given row of database columns
	 * @param array the associative array
	 * @return Entry the entry
	 */
	public static function fromRow(&$row) {
		return new Entry($row['entry_id'], $row['delete_change_id']);
	}
	
	/**
	 * Gets the head revision () using lazy loading
	 * @return Revision the head revision
	 */
	public function getHead() {
		if (!$this->head)
			$this->head = Dictionary::getDefinitionService()->getEntryRevision($this, Revision::HEAD);
		
		return $this->head;
	}
	
	/**
	 * Gets the delete change using lazy loading
	 * @return Change the delete change
	 */
	public function getDeleteChange() {
		if (!$this->deleteChange && $this->deleteChangeId)
			$this->deleteChange = Dictionary::getChangeService()->getChange($this->deleteChangeId);
		
		return $this->deleteChange;
	}
	
	/**
	 * Sets the delete change
	 * @param Change change the delete change
	 */
	public function setDeleteChange($change) {
		$this->deleteChange = $change;
		$this->deleteChangeId = $change ? $change->getId() : NULL;
	}
	
	/**
	 * Gets all the revisions using lazy loading
	 * @return array the revisions
	 */
	public function getRevisions() {
		if ($this->revisions === NULL)
			$this->revisions = Dictionary::getDefinitionService()->getEntryDefinitions($this);
		
		return $this->revisions;
	}
	
	/**
	 * Gets whether this entry has been deleted - i.e. it's headless
	 * @return bool TRUE if entry has been deleted
	 */
	public function isDeleted() {
		return !$this->getHead();
	}
}

?>
