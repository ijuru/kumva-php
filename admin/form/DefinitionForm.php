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
 * Purpose: Definition form class
 */

/**
 * Form controller for add/edit definition
 */
class DefinitionForm extends Form {
	var $entry;
	var $change;

	/**
	 * @see Form::createEntity()
	 */
	protected function createEntity() {
		$entryId = (int)Request::getGetParam('id', 0);
		if ($entryId) {
			$this->entry = Dictionary::getDefinitionService()->getEntry($entryId);
			$definition = $this->entry->getHead();
			$this->change = $definition->getChange();
			return $definition;
		}
		
		return new Definition();
	}
	
	/**
	 * @see Form::onBind()
	 */
	protected function onBind($definition) {
		// Bind noun classes
		$nounClasses = aka_parsecsv(Request::getPostParam('nounclasses'), TRUE);
		$definition->setNounClasses($nounClasses);
		
		// Bind flags
		$definition->setFlags(0);
		$flags = Request::getPostParams('flags_');
		foreach ($flags as $flag => $state)
			$definition->setFlag($flag, TRUE);
		
		// Bind tags
		$tagParams = Request::getPostParams('tags_');
		foreach ($tagParams as $relationshipId => $tagSet) {
			$relationship = Dictionary::getTagService()->getRelationship($relationshipId);
			$tagStrings = aka_parsecsv($tagSet);
			$definition->setTagsFromStrings($relationship, $tagStrings);
		}
		
		// Handle any autotag requests
		$autotagRelId = Request::getPostParam('autotag', 0);
		if ($autotagRelId > 0) {
			$relationship = Dictionary::getTagService()->getRelationship($autotagRelId);
			$tagStrings = Lexical::autoTag($definition, $relationship);
			$definition->setTagsFromStrings($relationship, $tagStrings);
		}
		
		// Bind examples
		$exFormParams = Request::getPostParams('exampleform');
		$examples = array();
		foreach ($exFormParams as $param => $form) {
			$meaning = Request::getPostParam('examplemeaning'.$param);
			$examples[] = new Example(0, $form, $meaning);
		}	
		$definition->setExamples($examples);
	}
	
	/**
	 * @see Form::saveEntity()
	 */
	protected function saveEntity($definition) {	
		$saveType = Request::getPostParam('saveType');
		
		// New definition will need a container entry
		if ($definition->isNew()) {
			$entry = new Entry();
			if (!Dictionary::getDefinitionService()->saveEntry($entry))
				return FALSE;
				
			$definition->setEntry($entry);
		}
		else
			$entry = $definition->getEntry();
	
		if ($saveType == 'propose' && $this->canPropose()) {
			// Create the new change and revision number
			if ($definition->isNew()) {
				$revision = 1;
				$change = Change::create($entry, Action::CREATE);
			}
			else {
				$last = Dictionary::getDefinitionService()->getEntryRevision($entry, Revision::LAST);
				$revision = $last->getRevision() + 1;
				$change = Change::create($entry, Action::MODIFY);
			}
				
			// Save the change
			if (!Dictionary::getChangeService()->saveChange($change))
				return FALSE;
				
			// Save as new proposal definition
			$definition->setId(0);
			$definition->setRevision($revision);
			$definition->setRevisionStatus(RevisionStatus::PROPOSED);
			$definition->setChange($change);
			if (!Dictionary::getDefinitionService()->saveDefinition($definition))
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
			return Dictionary::getDefinitionService()->saveDefinition($definition);
		}
		
		return FALSE;	
	}
	
	/**
	 * Gets whether the current user can update the definition
	 * @return bool TRUE if user can update
	 */
	public function canUpdate() {
		if ($this->entry && $this->entry->isDeleted())
			return FALSE;
			
		$curUser = Session::getCurrent()->getUser();
		
		if ($this->getEntity()->isProposedRevision()) {
			if ($curUser->hasRole(Role::EDITOR))
				return TRUE;
			elseif ($this->change->getSubmitter()->equals($curUser))
				return TRUE;
		}
		else
			return Session::getCurrent()->hasRole(Role::ADMINISTRATOR);
	}
	
	/**
	 * Gets whether the current user can propose a change to the definition
	 * @return bool TRUE if user can propose a change
	 */
	public function canPropose() {
		if ($this->entry && $this->entry->isDeleted() || $this->getEntity()->isProposedRevision())
			return FALSE;
			
		return Session::getCurrent()->hasRole(Role::CONTRIBUTOR);
	}
}

?>