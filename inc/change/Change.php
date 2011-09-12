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
 * Purpose: Change class
 */
 
/**
 * Possible actions for changes
 */
class Action extends Enum {
	const CREATE = 0;
	const MODIFY = 1;
	const DELETE = 2;
	
	protected static $strings = array('create', 'modify', 'delete');
	protected static $localized = array(KU_STR_CREATE, KU_STR_MODIFY, KU_STR_DELETE);
}
 
/**
 * Possible states for changes
 */
class Status extends Enum {
	const PENDING = 0;
	const ACCEPTED = 1;
	const REJECTED = 2;
	
	protected static $strings = array('pending', 'accepted', 'rejected');
	protected static $localized = array(KU_STR_PENDING, KU_STR_ACCEPTED, KU_STR_REJECTED);
}
 
/**
 * Change being made to the dictionary
 */
class Change extends Entity {
	private $entryId;
	private $action;
	private $submitterId;
	private $submitted;
	private $status;
	private $resolverId;
	private $resolved;
	
	// Lazy loaded properties
	private $entry;
	private $submitter;
	private $resolver;
	private $comments;
	private $watchers;
	
	/**
	 * Constructs a new change
	 * @param int id the id
	 * @param int entryId the entry id
	 * @param int action the action type
	 * @param int submitterId the submitter user id
	 * @param int submitted the timestamp of submission
	 * @param int status the status
	 * @param int resolverId the resolver user id
	 * @param int resolved the timestamp of acceptance/rejection
	 */
	public function __construct($id, $entryId, $action, $submitterId, $submitted, $status, $resolverId = NULL, $resolved = NULL) {
		$this->id = (int)$id;
		$this->entryId = (int)$entryId;
		$this->action = (int)$action;
		$this->submitterId = (int)$submitterId;
		$this->submitted = (int)$submitted;
		$this->status = (int)$status;
		$this->resolverId = (int)$resolverId;
		$this->resolved = (int)$resolved;
	}
	
	/**
	 * Creates a change from the given row of database columns
	 * @param array the associative array
	 * @return Change the change
	 */
	public static function fromRow(&$row) {
		return new Change($row['change_id'], $row['entry_id'], $row['action'], $row['submitter_id'], 
			aka_timefromsql($row['submitted']), $row['status'], $row['resolver_id'], aka_timefromsql($row['resolved']));
	}
	
	/**
	 * Creates a new pending change
	 * @param Entry entry the entry
	 * @param int action the action type
	 * @return Change the change
	 */
	public static function create($entry, $action) {
		return new Change(0, $entry->getId(), $action, Session::getCurrent()->getUser()->getId(), time(), Status::PENDING);
	}
	
	/**
	 * Gets the entry using lazy loading
	 * @return Entry the entry
	 */
	public function getEntry() {
		if (!$this->entry && $this->entryId)
			$this->entry = Dictionary::getEntryService()->getEntry($this->entryId);
			
		return $this->entry;
	}
	
	/**
	 * Sets the entry
	 * @param entry Entry the entry
	 */
	public function setEntry($entry) {
		$this->entryId = $entry ? $entry->getId() : 0;
		$this->entry = $entry;
	}
	
	/**
	 * Gets the action
	 * @return int the action
	 */
	public function getAction() {
		return $this->action;
	}
	
	/**
	 * Sets the action
	 * @param int action the status
	 */
	public function setAction($action) {
		$this->action = (int)$action;
	}
	
	/**
	 * Gets the submitter using lazy loading
	 * @return User the submitter
	 */
	public function getSubmitter() {
		if (!$this->submitter && $this->submitterId)
			$this->submitter = Dictionary::getUserService()->getUser($this->submitterId);
			
		return $this->submitter;
	}
	
	/**
	 * Gets the submitted timestamp
	 * @return int the timestamp
	 */
	public function getSubmitted() {
		return $this->submitted;
	}
	
	/**
	 * Gets the status
	 * @return int the status
	 */
	public function getStatus() {
		return $this->status;
	}
	
	/**
	 * Gets if the change is pending
	 * @return bool TRUE if change is pending, else FALSE
	 */
	public function isPending() {
		return $this->status == Status::PENDING;
	}
	
	/**
	 * Sets the status
	 * @param int status the status
	 */
	public function setStatus($status) {
		$this->status = (int)$status;
	}
	
	/**
	 * Gets the resolver using lazy loading
	 * @return User the resolver
	 */
	public function getResolver() {
		if (!$this->resolver && $this->resolverId)
			$this->resolver = Dictionary::getUserService()->getUser($this->resolverId);
			
		return $this->resolver;
	}
	
	/**
	 * Sets the resolver
	 * @param User resolver the resolver
	 */
	public function setResolver($resolver) {
		$this->resolver = $resolver;
	}
	
	/**
	 * Gets the resolved timestamp
	 * @return int the timestamp
	 */
	public function getResolved() {
		return $this->resolved;
	}
	
	/**
	 * Sets the resolved timestamp
	 * @param int resolved the resolved timestamp
	 */
	public function setResolved($resolved) {
		$this->resolved = (int)$resolved;
	}
	
	/**
	 * Gets the comments using lazy loading
	 * @return array the comments
	 */
	public function getComments() {
		if ($this->comments === NULL)
			$this->comments = Dictionary::getChangeService()->getChangeComments($this);
		
		return $this->comments;
	}
	
	/**
	 * Gets the watchers using lazy loading
	 * @return array the watchers
	 */
	public function getWatchers() {
		if ($this->watchers === NULL)
			$this->watchers = Dictionary::getChangeService()->getChangeWatchers($this);
		
		return $this->watchers;
	}
	
	/**
	 * Checks if a user is watching this change
	 * @param User user the user to check (NULL for the current user)
	 */
	public function isWatcher($user = NULL) {
		$user = $user ? $user : Session::getCurrent()->getUser();
		return $user ? $user->inArray($this->getWatchers()) : FALSE;
	}
	
	/**
	 * Adds the current user as a watcher
	 * @return bool TRUE if successful, else FALSE
	 */
	public function watch() {
		$user = Session::getCurrent()->getUser();
		if ($user && !$this->isWatcher($user)) {
			$this->watchers = NULL;
			return Dictionary::getChangeService()->addWatcher($this, $user);	
		}
		return FALSE;
	}
	
	/**
	 * Removes the current user as a watcher
	 * @return bool TRUE if successful, else FALSE
	 */
	public function unwatch() {
		$user = Session::getCurrent()->getUser();
		if ($user && $this->isWatcher($user)) {
			$this->watchers = NULL;
			return Dictionary::getChangeService()->removeWatcher($this, $user);	
		}
		return FALSE;
	}
	
	/**
	 * Gets a string representation of this change
	 * @return string the string representation
	 */
	public function toString() {
		if ($this->getAction() == Action::DELETE) {
			$entry = $this->getEntry();
			$revision = Dictionary::getEntryService()->getEntryRevision($entry, RevisionPreset::LAST);
		}
		else
			$revision = Dictionary::getChangeService()->getChangeRevision($this);

		return '#'.$this->id.' '.strtolower(Action::toString($this->action)).'('.$revision->getPrefix().$revision->getLemma().')';
	}	
}

?>
