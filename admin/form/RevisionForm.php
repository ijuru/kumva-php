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
 * Purpose: Revision form class
 */

/**
 * Form controller for add/edit entry
 */
class RevisionForm extends Form {
	var $entry;
	var $change;

	/**
	 * @see Form::createEntity()
	 */
	protected function createEntity() {
		$entryId = (int)Request::getGetParam('id', 0);
		if ($entryId) {
			$this->entry = Dictionary::getEntryService()->getEntry($entryId);
			$revision = $this->entry->getHead();
			$this->change = $revision->getChange();
			return $revision;
		}
		
		// Create new revision
		$initialLemma = Request::getGetParam('new', '');
		$revision = new Revision();
		$revision->setMeanings(array(new Meaning()));
		$revision->setLemma($initialLemma);
		return $revision;
	}
	
	/**
	 * @see Form::onBind()
	 */
	protected function onBind($revision) {
		// Bind noun classes
		$nounClasses = aka_parsecsv(Request::getPostParam('nounclasses'), TRUE);
		$revision->setNounClasses($nounClasses);
		
		// Bind meanings
		$meanTextParams = Request::getPostParams('meaningtext_');
		$meanings = array();
		foreach ($meanTextParams as $param => $text) {
			$meaning = new Meaning(0, $text, 0);
			
			$meanFlagParams = Request::getPostParams('meaningflag_'.$param.'_');
			foreach ($meanFlagParams as $flag => $state)
				$meaning->setFlag($flag, TRUE);
				
			$meanings[] = $meaning;
		}	
		$revision->setMeanings($meanings);
		
		// Bind tags
		$tagParams = Request::getPostParams('tags_');
		foreach ($tagParams as $relationshipId => $tagSet) {
			$relationship = Dictionary::getTagService()->getRelationship($relationshipId);
			$tagStrings = aka_parsecsv($tagSet);
			$revision->setTagsFromStrings($relationship, $tagStrings);
		}
		
		// Bind examples
		$exFormParams = Request::getPostParams('exampleform_');
		$examples = array();
		foreach ($exFormParams as $param => $form) {
			$meaning = Request::getPostParam('examplemeaning_'.$param);
			$examples[] = new Example(0, $form, $meaning);
		}	
		$revision->setExamples($examples);
		
		// Handle any autotag requests
		$autotagRelId = Request::getPostParam('autotag', 0);
		if ($autotagRelId > 0) {
			$relationship = Dictionary::getTagService()->getRelationship($autotagRelId);
			$tagStrings = Lexical::autoTag($revision, $relationship);
			$revision->setTagsFromStrings($relationship, $tagStrings);
		}
	}
	
	/**
	 * @see Form::saveEntity()
	 */
	protected function saveEntity($revision) {	
		$saveType = Request::getPostParam('saveType');
		
		// New revision will need a container entry
		if ($revision->isNew()) {
			$entry = new Entry();
			if (!Dictionary::getEntryService()->saveEntry($entry))
				return FALSE;
				
			$revision->setEntry($entry);
		}
		else
			$entry = $revision->getEntry();
	
		if ($saveType == 'propose' && $this->canPropose()) {
			// Create the new change and revision number
			if ($revision->isNew()) {
				$number = 1;
				$change = Change::create($entry, Action::CREATE);
			}
			else {
				$last = Dictionary::getEntryService()->getEntryRevision($entry, RevisionPreset::LAST);
				$number = $last->getNumber() + 1;
				$change = Change::create($entry, Action::MODIFY);
			}
				
			// Save the change
			if (!Dictionary::getChangeService()->saveChange($change))
				return FALSE;
				
			// Save as new proposal revision
			$revision->setId(0);
			$revision->setNumber($number);
			$revision->setStatus(RevisionStatus::PROPOSED);
			$revision->setChange($change);
			if (!Dictionary::getEntryService()->saveRevision($revision))
				return FALSE;
				
			// Notify subscribed users
			Notifications::newChange($change);
				
			// Add current user as a watcher of the change
			if (!$change->watch())
				return FALSE;
			
			// Update successurl to take us straight to the new change
			$this->setSuccessUrl('change.php?id='.$change->getId());
			return TRUE;
		}
		elseif ($saveType == 'update') {
			// If its new, then maked it the accepted revision
			if ($revision->isNew()) {
				$revision->setNumber(1);
				$revision->setStatus(RevisionStatus::ACCEPTED);
			}
			
			return Dictionary::getEntryService()->saveRevision($revision);
		}
		
		return FALSE;	
	}
	
	/**
	 * Gets whether the current user can update the entry
	 * @return bool TRUE if user can update
	 */
	public function canUpdate() {
		if ($this->entry && $this->entry->isDeleted())
			return FALSE;
			
		$curUser = Session::getCurrent()->getUser();
		
		if ($this->getEntity()->isProposed()) {
			if ($curUser->hasRole(Role::EDITOR))
				return TRUE;
			elseif ($this->change->getSubmitter()->equals($curUser))
				return TRUE;
		}
		else
			return Session::getCurrent()->hasRole(Role::ADMINISTRATOR);
	}
	
	/**
	 * Gets whether the current user can propose a change to the entry
	 * @return bool TRUE if user can propose a change
	 */
	public function canPropose() {
		if ($this->entry && $this->entry->isDeleted() || $this->getEntity()->isProposed())
			return FALSE;
			
		return Session::getCurrent()->hasRole(Role::CONTRIBUTOR);
	}
}

?>