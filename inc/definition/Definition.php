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
 * Purpose: Definition class
 */
 
/**
 * Revision number presets
 */
class Revision {
	const FIRST = 	  1;
	const ACCEPTED = -1001;
	const PROPOSED = -1002;
	const HEAD = 	 -1003; // Latest of accepted or proposal
	const LAST =	 -1004; // Absolute last
}

/**
 * Revision status enum
 */
class RevisionStatus extends Enum {
	const ARCHIVED = 0;
	const ACCEPTED = 1;
	const PROPOSED = 2;
	
	protected static $strings = array('archived', 'accepted', 'proposed');
}

/**
 * Word definition class
 */
class Definition extends Entity {
	private $entryId;
	private $revision;
	private $revisionStatus;
	private $changeId;
	private $wordClass;
	private $prefix;
	private $lemma;
	private $modifier;
	private $pronunciation;
	private $comment;
	private $unverified;
	
	// Lazy loaded properties
	private $entry;
	private $change;
	private $nounClasses;
	private $meanings;
	private $tags = array();
	private $examples;
	
	/**
	 * Constructs a definition
	 * @param int id the definition id
	 * @param int entryId the entry id
	 * @param int revision the revision number
	 * @param int revisionStatus the revision status (archived, accepted etc)
	 * @param int changeId the change id
	 * @param string wordClass the word class, e,g, 'n'
	 * @param string prefix the prefix, e.g 'umu'
	 * @param string lemma the lemma, e.g. 'gabo'
	 * @param string modifier the modifier, e.g. 'aba-'
	 * @param string comment the comment
	 * @param bool unverified TRUE if definition is unverified
	 */
	public function __construct(
			$id = 0, $entryId = 0, $revision = 0, $revisionStatus = 0, $changeId = 0,
			$wordClass = '', $prefix = '', $lemma = '', $modifier = '', $pronunciation = '', $comment = '', $unverified = FALSE) 
	{
		$this->id = (int)$id;
		$this->entryId = (int)$entryId;
		$this->revision = (int)$revision;
		$this->revisionStatus = (int)$revisionStatus;
		$this->changeId = $changeId;
		$this->wordClass = $wordClass;
		$this->prefix = $prefix;
		$this->lemma = $lemma;
		$this->modifier = $modifier;
		$this->pronunciation = $pronunciation;
		$this->comment = $comment;
		$this->unverified = (bool)$unverified;
	}
	
	/**
	 * Creates a definition from the given row of database columns
	 * @param array the associative array
	 * @return Definition the definition
	 */
	public static function fromRow(&$row) {
		return new Definition($row['definition_id'], $row['entry_id'], $row['revision'], $row['revisionstatus'], $row['change_id'], $row['wordclass'], $row['prefix'], $row['lemma'], $row['modifier'], $row['pronunciation'], $row['comment'], $row['unverified']);
	}
	
	/**
	 * Gets the entry using lazy loading
	 * @return Entry the entry
	 */
	public function getEntry() {
		if (!$this->entry && $this->entryId)
			$this->entry = Dictionary::getDefinitionService()->getEntry($this->entryId);
			
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
	 * Gets the revision number
	 * @return int the revision number
	 */
	public function getRevision() {
		return $this->revision;
	}
	
	/**
	 * Sets the revision number
	 * @param int revision the revision number
	 */
	public function setRevision($revision) {
		$this->revision = $revision;
	}
	
	/**
	 * Gets the revision number
	 * @return int the revision number
	 */
	public function getRevisionStatus() {
		return $this->revisionStatus;
	}
	
	/**
	 * Sets the revision status
	 * @param int revision the revision status
	 */
	public function setRevisionStatus($revisionStatus) {
		$this->revisionStatus = $revisionStatus;
	}
	
	/**
	 * Gets the change using lazy loading
	 * @return Change the change (if there is one)
	 */
	public function getChange() {
		if ($this->change === NULL && $this->changeId)
			$this->change = Dictionary::getChangeService()->getChange($this->changeId);
		
		return $this->change;
	}
	
	/**
	 * Sets the change
	 * @param Change change the change
	 */
	public function setChange($change) {
		$this->change = $change;
		$this->changeId = $change ? $change->getId() : NULL;
	}
	
	/**
	 * Gets the word class
	 * @return string the word class
	 */
	public function getWordClass() {
		return $this->wordClass;
	}
	
	/**
	 * Sets the word class
	 * @param string wordClass the word class
	 */
	public function setWordClass($wordClass) {
		$this->wordClass = $wordClass;
	}
	
	/**
	 * Gets the prefix
	 * @return string the prefix
	 */
	public function getPrefix() {
		return $this->prefix;
	}
	
	/**
	 * Sets the prefix
	 * @param string prefix the prefix
	 */
	public function setPrefix($prefix) {
		$this->prefix = $prefix;
	}
	
	/**
	 * Gets the lemma
	 * @return string the lemma
	 */
	public function getLemma() {
		return $this->lemma;
	}
	
	/**
	 * Sets the lemma
	 * @param string lemma the lemma
	 */
	public function setLemma($lemma) {
		$this->lemma = $lemma;
	}
	
	/**
	 * Gets the modifier
	 * @return string the modifier
	 */
	public function getModifier() {
		return $this->modifier;
	}
	
	/**
	 * Sets the modifier
	 * @param string modifier the modifier
	 */
	public function setModifier($modifier) {
		$this->modifier = $modifier;
	}
	
	/**
	 * Gets the pronunciation
	 * @return string the pronunciation
	 */
	public function getPronunciation() {
		return $this->pronunciation;
	}
	
	/**
	 * Sets the comment
	 * @param string comment the comment
	 */
	public function setPronunciation($pronunciation) {
		$this->pronunciation = $pronunciation;
	}
	
	/**
	 * Gets the comment
	 * @return string the comment
	 */
	public function getComment() {
		return $this->comment;
	}
	
	/**
	 * Sets the comment
	 * @param string comment the comment
	 */
	public function setComment($comment) {
		$this->comment = $comment;
	}
	
	/**
	 * Gets noun classes using lazy loading
	 * @return array the classes (integers)
	 */
	public function getNounClasses() {
		if ($this->nounClasses === NULL)
			$this->nounClasses = Dictionary::getDefinitionService()->getDefinitionNounClasses($this);
		
		return $this->nounClasses;
	}
	
	/**
	 * Sets noun classes
	 * @param mixed the classes as array of integers or csv string
	 */
	public function setNounClasses($classes) {
		$this->nounClasses = is_array($classes) ? $classes : aka_parsecsv($classes, TRUE);
	}
	
	/**
	 * Gets if this is unverified
	 * @return bool TRUE if unverified
	 */
	public function isUnverified() {
		return $this->unverified;
	}
	
	/**
	 * Sets if this is unverified
	 * @param bool unverified TRUE if unverified, else FALSE
	 */
	public function setUnverified($unverified) {
		$this->unverified = (bool)$unverified;
	}
	
	/**
	 * Gets meanings using lazy loading
	 * @return array the meanings
	 */
	public function getMeanings() {
		if ($this->meanings === NULL)
			$this->meanings = Dictionary::getDefinitionService()->getDefinitionMeanings($this);
		
		return $this->meanings;
	}
	
	/**
	 * Sets meanings
	 * @param array meanings the meanings
	 */
	public function setMeanings($meanings) {
		$this->meanings = $meanings;
	}
	
	/**
	 * Gets tags with the given relationship
	 * @param int relationshipId the relationship id
	 * @return array the tags
	 */
	public function getTags($relationshipId) {
		if (!isset($this->tags[$relationshipId]))
			$this->tags[$relationshipId] = Dictionary::getDefinitionService()->getDefinitionTags($this, $relationshipId);
	
		return $this->tags[$relationshipId];
	}
	
	/**
	 * Sets tags with the given relationship
	 * @param int relationshipId the relationship id
	 * @param array tags the tags
	 */
	public function setTags($relationshipId, $tags) {
		$this->tags[$relationshipId] = $tags;
	}
	
	/**
	 * Sets tags from an array of tag strings
	 * @param Relationship the relationship
	 * @param array the tag strings
	 */
	public function setTagsFromStrings($relationship, $tagStrings) {
		$tags = array();
		foreach ($tagStrings as $tagString)
			$tags[] = $relationship->parseTagString($tagString);
			
		$this->setTags($relationship->getId(), $tags);
	}
	
	/**
	 * Gets usage examples using lazy loading
	 * @return array the examples
	 */
	public function getExamples() {
		if ($this->examples === NULL)
			$this->examples = Dictionary::getDefinitionService()->getDefinitionExamples($this);
		
		return $this->examples;
	}
	
	/**
	 * Sets usage examples
	 * @param array examples the examples
	 */
	public function setExamples($examples) {
		$this->examples = $examples;
	}
	
	/**
	 * Gets if definition is the accepted revision
	 * @return bool TRUE if definition is accepted
	 */
	public function isAcceptedRevision() {
		return $this->revisionStatus == RevisionStatus::ACCEPTED;
	}
	
	/**
	 * Gets if definition is the proposed revision
	 * @return bool TRUE if definition is proposed
	 */
	public function isProposedRevision() {
		return $this->revisionStatus == RevisionStatus::PROPOSED;
	}
	
	/**
	 * Gets a string representation (i.e. prefix+lemma[function])
	 * @return string the string representation
	 */
	public function toString() {
		return $this->prefix.$this->lemma.'['.$this->wordClass.']';
	}
}

?>
