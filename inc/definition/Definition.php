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
 * Information flags which can be set on definitions
 */
class Flags extends Enum {
	const OLD = 0;
	const RARE = 1;
	const SLANG = 2;
	const RUDE = 3;
	
	protected static $strings = array('old', 'rare', 'slang', 'rude');
	protected static $localized = array(KU_STR_OLD, KU_STR_RARE, KU_STR_SLANG, KU_STR_RUDE);
}

/**
 * Word definition class
 */
class Definition extends Entity {
	private $entryId;
	private $revision;
	private $changeId;
	private $wordClass;
	private $prefix;
	private $lemma;
	private $modifier;
	private $meaning;
	private $comment;
	private $flags;
	private $verified;
	private $proposal;		#TBR
	
	// Lazy loaded properties
	private $entry;
	private $change;
	private $nounClasses;
	private $examples;
	private $tags = array();
	
	/**
	 * Constructs a definition
	 * @param int id the definition id
	 * @param int entryId the entry id
	 * @param int revision the revision number
	 * @param int changeId the change id
	 * @param string wordClass the word class, e,g, 'n'
	 * @param string prefix the prefix, e.g 'umu'
	 * @param string lemma the lemma, e.g. 'gabo'
	 * @param string modifier the modifier, e.g. 'aba-'
	 * @param string meaning the meaning, e.g. 'man, husband'
	 * @param string comment the comment
	 * @param int flags the flags
	 * @param bool verified TRUE if definition has been verified
	 */
	public function __construct(
			$id = 0, $entryId = 0, $revision = 0, $changeId = 0,
			$wordClass = '', $prefix = '', $lemma = '', $modifier = '', $meaning = '', $comment = '', $flags = 0, 
			$verified = FALSE, 
			$proposal = FALSE, $voided = FALSE   #TBR
			) 
	{
		$this->id = (int)$id;
		$this->entryId = $entryId;
		$this->revision = (int)$revision;
		$this->changeId = $changeId;
		$this->wordClass = $wordClass;
		$this->prefix = $prefix;
		$this->lemma = $lemma;
		$this->modifier = $modifier;
		$this->meaning = $meaning;
		$this->comment = $comment;
		$this->flags = (int)$flags;
		$this->verified = (bool)$verified;
		$this->proposal = (bool)$proposal;  #TBR
		$this->voided = (bool)$voided;		#TBR
	}
	
	/**
	 * Creates a definition from the given row of database columns
	 * @param array the associative array
	 * @return Definition the definition
	 */
	public static function fromRow(&$row) {
		return new Definition($row['definition_id'], $row['entry_id'], $row['revision'], $row['change_id'], $row['wordclass'], $row['prefix'], $row['lemma'], $row['modifier'], $row['meaning'], $row['comment'], $row['flags'], $row['verified'], $row['proposal'], $row['voided']);
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
	 * Gets the meaning
	 * @return string the meaning
	 */
	public function getMeaning() {
		return $this->meaning;
	}
	
	/**
	 * Sets the meaning
	 * @param string meaning the meaning
	 */
	public function setMeaning($meaning) {
		$this->meaning = $meaning;
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
	 * Gets the state of the given flag
	 * @param int flag the flag
	 * @return bool TRUE if flag is set, else FALSE
	 */
	public function getFlag($flag) {
		$mask = pow(2, $flag);
		return $this->flags & $mask;
	}
	
	/**
	 * Gets the flags
	 * @return int the flags
	 */
	public function getFlags() {
		return $this->flags;
	}
	
	/**
	 * Sets the state of the given flag
	 * @param int flag the flag
	 * @param bool state TRUE to set flag, else FALSE
	 */
	public function setFlag($flag, $state) {
		$mask = pow(2, $flag);
		if ($state)
			$this->flags |= $mask;
		else
			$this->flags &= ~$mask;
	}
	
	/**
	 * Sets the flags
	 * @param mixed flags the flags (can be an bit field int or CSV string)
	 */
	public function setFlags($flags) {
		if (!is_int($flags))
			$flags = Flags::toBits(Flags::parseCSVString($flags));
		
		$this->flags = $flags;
	}
	
	/**
	 * Gets if this has been verified
	 * @return bool TRUE if has been verified
	 */
	public function isVerified() {
		return $this->verified;
	}
	
	/**
	 * Sets if this has been verified
	 * @param bool proposal TRUE if has been verified, else FALSE
	 */
	public function setVerified($verified) {
		$this->verified = (bool)$verified;
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
	 * Gets a string representation (i.e. prefix+lemma[function])
	 * @return string the string representation
	 */
	public function toString() {
		return $this->prefix.$this->lemma.'['.$this->wordClass.']';
	}
	
	/**
	 * #TBR
	 */
	public function getPermissions($user = NULL) {
		$user = $user ? $user : Session::getCurrent()->getUser();
		$permissions = array();	

		if ($this->isVoided()) {
			// Voided definitions are locked to everyone
			$permissions['propose'] = FALSE;
			$permissions['update'] = FALSE;
		}
		elseif ($this->isProposal()) {
			// No-one can propose changes to proposals
			$permissions['propose'] = FALSE;
			
			// Editors and submitters can update proposals
			$change = Dictionary::getChangeService()->getChangeForProposal($this);
			$permissions['update'] = $user->hasRole(Role::EDITOR) || $change->getSubmitter()->equals($user);
		}
		else {
			// Non proposals with pending changes are locked to everyone
			$changes = Dictionary::getChangeService()->getChangesForDefinition($this);
			$changePending = count($changes) > 0 ? ($changes[0]->getStatus() == Status::PENDING) : FALSE;
			
			$permissions['propose'] = !$changePending && $user->hasRole(Role::CONTRIBUTOR);
			$permissions['update'] = !$changePending && $user->hasRole(Role::ADMINISTRATOR);
		}
		
		return $permissions;
	}
	
	/**
	 * #TBR
	 */
	public function isProposal() {
		return $this->proposal;
	}
	
	/**
	 * #TBR
	 */
	public function setProposal($proposal) {
		$this->proposal = (bool)$proposal;
	}
}

?>
