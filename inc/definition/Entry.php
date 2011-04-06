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
	private $acceptedId;
	private $proposedId;
	private $deleteChangeId;
	
	// Lazy loaded properties
	private $accepted;
	private $proposed;
	private $head;
	private $deleteChange;
	private $revisions;
	
	/**
	 * Constructs an entry
	 * @param int id the id
	 * @param int acceptedId the accepted revision id
	 * @param int proposedId the proposed revision id
	 */
	public function __construct($id = 0, $acceptedId = NULL, $proposedId = NULL, $deleteChangeId = NULL) {
		$this->id = (int)$id;
		$this->acceptedId = $acceptedId;
		$this->proposedId = $proposedId;
		$this->deleteChangeId = $deleteChangeId;
	}
	
	/**
	 * Creates an entry from the given row of database columns
	 * @param array the associative array
	 * @return Entry the entry
	 */
	public static function fromRow(&$row) {
		return new Entry($row['entry_id'], $row['accepted_id'], $row['proposed_id'], $row['delete_change_id']);
	}
	
	/**
	 * Gets the accepted definition using lazy loading
	 * @return Definition the accepted definition
	 */
	public function getAccepted() {
		if (!$this->accepted && $this->acceptedId)
			$this->accepted = Dictionary::getDefinitionService()->getDefinition($this->acceptedId);
		
		return $this->accepted;
	}
	
	/**
	 * Sets the accepted revision
	 * @param Definition accepted the accepted definition
	 */
	public function setAccepted($accepted) {
		$this->accepted = $accepted;
		$this->acceptedId = $accepted ? $accepted->getId() : NULL;
	}
	
	/**
	 * Gets the proposed revision using lazy loading
	 * @return Revision the approved revision
	 */
	public function getProposed() {
		if (!$this->proposed && $this->proposedId)
			$this->proposed = Dictionary::getDefinitionService()->getDefinition($this->proposedId);
		
		return $this->proposed;
	}
	
	/**
	 * Sets the proposed revision
	 * @param Revision proposed the revision
	 */
	public function setProposed($proposed) {
		$this->proposed = $proposed;
		$this->proposedId = $proposed ? $proposed->getId() : NULL;
	}
	
	/**
	 * Gets the head revision () using lazy loading
	 * @return Revision the head revision
	 */
	public function getHead() {
		if (!$this->head)
			$this->head = Dictionary::getDefinitionService()->getEntryHeadDefinition($this);
		
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
	 * Gets whether this entry has been deleted
	 * @return bool TRUE if entry has been deleted
	 */
	public function isDeleted() {
		return !($this->acceptedId || $this->proposedId);
	}
}

?>
