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
	private $acceptedRevision;
	private $proposedRevision;
	
	/**
	 * Constructs an entry
	 * @param int id the id
	 * @param int acceptedRevision the accepted revision number
	 * @param int proposedRevision the proposed revision number
	 */
	public function __construct($id = 0, $acceptedRevision = 0, $proposedRevision = 0) {
		$this->id = (int)$id;
		$this->acceptedRevision = (int)$acceptedRevision;
		$this->proposedRevision = (int)$proposedRevision;
	}
	
	/**
	 * Creates an entry from the given row of database columns
	 * @param array the associative array
	 * @return Entry the entry
	 */
	public static function fromRow(&$row) {
		return new Entry($row['entry_id'], $row['accepted_revision'], $row['proposed_revision']);
	}
	
	/**
	 * Gets the accepted revision number
	 * @return int the accepted revision number
	 */
	public function getAcceptedRevision() {
		return $this->acceptedRevision;
	}
	
	/**
	 * Sets the accepted revision number
	 * @param int the accepted revision number
	 */
	public function setAcceptedRevision($acceptedRevision) {
		$this->acceptedRevision = $acceptedRevision;
	}
	
	/**
	 * Gets the proposed revision number
	 * @return int the proposed revision number
	 */
	public function getProposedRevision() {
		return $this->proposedRevision;
	}
	
	/**
	 * Sets the proposed revision number
	 * @param int the proposed revision number
	 */
	public function setProposedRevision($proposedRevision) {
		$this->proposedRevision = $proposedRevision;
	}
	
	/**
	 * Gets whether this entry has been deleted
	 * @return bool TRUE if entry has been deleted
	 */
	public function isDeleted() {
		return !($this->acceptedRevision || $this->proposedRevision);
	}
}

?>
