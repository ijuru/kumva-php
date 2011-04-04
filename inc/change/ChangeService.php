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
 * Purpose: Change service class
 */

/**
 * Change functions
 */
class ChangeService extends Service {	
	/**
	 * Get the change with the given id
	 * @param id int the id
	 * @return Change the change
	 */
	public function getChange($id) {
		$row = $this->database->row('SELECT * FROM `'.KUMVA_DB_PREFIX.'change` WHERE change_id = '.$id);
		return ($row != NULL) ? Change::fromRow($row) : NULL;
	}
	
	/**
	 * Gets all changes
	 * @param Definition definition the definition
	 * @param User submitter the submitter
	 * @param int status the status (NULL means any status)
	 * @param bool orderByResolved TRUE to order changes by resolved date
	 * @param Paging paging the paging object
	 * @return array the changes
	 */
	public function getChanges($definition = NULL, $submitter = NULL, $status = NULL, $resolver = NULL, $orderByResolved = FALSE, $paging = NULL) {
		$sql = 'SELECT SQL_CALC_FOUND_ROWS c.* FROM `'.KUMVA_DB_PREFIX.'change` c WHERE 1=1 ';	
		if ($definition)
			$sql .= 'AND (c.`original_id` = '.$definition->getId().' OR c.`proposal_id` = '.$definition->getId().')';
		if ($submitter)
			$sql .= 'AND c.`submitter_id` = '.$submitter->getId().' ';
		if ($status !== NULL)
			$sql .= 'AND c.`status` = '.(int)$status.' ';
		if ($resolver)
			$sql .= 'AND c.`resolver_id` = '.$resolver->getId().' ';
				
		$sql .= 'ORDER BY '.($orderByResolved ? 'c.`resolved`' : 'c.`submitted`').' DESC';
		
		return Change::fromQuery($this->database->query($sql, $paging));
	}
	
	/**
	 * Gets all changes for the given definition
	 * @param Definition definition the definition
	 * @param int status the status (NULL means any status)
	 * @return array the changes
	 */
	public function getChangesForDefinition($definition, $status = NULL) {
		return $definition->isProposal() ? array() : $this->getChanges($definition, NULL, $status, NULL, FALSE, NULL);
	}
	
	/**
	 * Gets all changes submitted by the given user
	 * @param User submitter the submitter
	 * @param int status the status (NULL means any status)
	 * @return array the changes
	 */
	public function getChangesBySubmitter($submitter, $status, $paging) {
		return $this->getChanges(NULL, $submitter, $status, NULL, FALSE, $paging);
	}
	
	/**
	 * Gets all changes resolved by the given resolver
	 * @param User resolver the resolver
	 * @param int status the status (NULL means any status)
	 * @return array the changes
	 */
	public function getChangesByResolver($resolver, $status, $paging) {
		return $this->getChanges(NULL, NULL, $status, $resolver, TRUE, $paging);
	}
	
	/**
	 * Gets the change for the given proposal definition
	 * @param Definition definition the definition
	 * @return Change the change
	 */
	public function getChangeForProposal($definition) {
		$row = $this->database->row('SELECT * FROM `'.KUMVA_DB_PREFIX.'change` WHERE proposal_id = '.$definition->getId());
		return ($row != NULL) ? Change::fromRow($row) : NULL;
	}
	
	/**
	 * Gets all comments for the given change
	 * @param Change the change
	 * @param bool incVoided TRUE to include voided comments
	 * @return array the comments
	 */
	public function getChangeComments($change, $incVoided = FALSE) {
		$sql = 'SELECT * FROM `'.KUMVA_DB_PREFIX.'comment` WHERE `change_id` = '.$change->getId().' ';
		if (!$incVoided)
			$sql .= 'AND voided = 0 ';
			
		$sql .= 'ORDER BY `created` ASC';
			
		return Comment::fromQuery($this->database->query($sql));
	}
	
	/**
	 * Gets just counts of comments and approvals for the given change
	 * @param Change the change
	 * @return array the counts (keys: 'comments', 'approvals')
	 */
	public function getChangeCommentCounts($change) {
		$sql = 'SELECT COUNT(*) as `comments`, COUNT(CASE WHEN `approval` = 1 THEN 1 END) as `approvals` 
		        FROM `'.KUMVA_DB_PREFIX.'comment`
		        WHERE `change_id` = '.$change->getId().' AND voided = 0 ';
		return $this->database->row($sql);
	}
	
	/**
	 * Gets all watchers for the given change
	 * @param Change the change
	 * @return array the watchers (users)
	 */
	public function getChangeWatchers($change) {
		$sql = 'SELECT u.* FROM `'.KUMVA_DB_PREFIX.'change_watch` cw  
		        INNER JOIN `'.KUMVA_DB_PREFIX.'user` u ON u.user_id = cw.user_id
		        WHERE cw.`change_id` = '.$change->getId().'
		        ORDER BY u.`name` ASC';
		return User::fromQuery($this->database->query($sql));
	}
	
	/**
	 * Accepts the given change
	 * @param Change the change
	 * @return bool TRUE if successful, else FALSE
	 */
	public function acceptChange($change) {
		if ($change->getStatus() != Status::PENDING)
			return FALSE;
		
		// For creates and modifies, clone proposal and save as definition
		if ($change->getAction() == Action::CREATE || $change->getAction() == Action::MODIFY) {
			$proposal = $change->getProposal();
			
			// TODO find a better way to load lazy properties (by Definition::copy(...) ?)
			if (!Dictionary::getDefinitionService()->saveDefinition($proposal))
				return FALSE;
			
			// Clone proposal
			$definition = clone $proposal;
			$definition->setProposal(FALSE);
			
			// If modifying existing definition, use its id so it will be overwritten
			if ($change->getAction() == Action::MODIFY)
				$definition->setId($change->getDefinition()->getId());
			else 
				$definition->setId(0);
			
			// Save new definition / update existing definition
			if (!Dictionary::getDefinitionService()->saveDefinition($definition))
				return FALSE;
				
			$change->setDefinition($definition);
				
			// Void proposal
			if (!Dictionary::getDefinitionService()->voidDefinition($proposal))
				return FALSE;
		}
		elseif ($change->getAction() == Action::DELETE) {
			// Void definition
			if (!Dictionary::getDefinitionService()->voidDefinition($change->getDefinition()))
				return FALSE;
		}
	
		$change->setStatus(Status::ACCEPTED);
		$change->setResolver(Session::getCurrent()->getUser());
		$change->setResolved(time());
		
		return $this->saveChange($change);
	}
	
	/**
	 * Rejects the given change
	 * @param Change the change
	 * @return bool TRUE if successful, else FALSE
	 */
	public function rejectChange($change) {
		if ($change->getStatus() != Status::PENDING)
			return FALSE;
		
		// Void proposal definition	
		if ($change->getProposal()) {
			if (!Dictionary::getDefinitionService()->voidDefinition($change->getProposal()))
				return FALSE;
		}
	
		$change->setStatus(Status::REJECTED);
		$change->setResolver(Session::getCurrent()->getUser());
		$change->setResolved(time());
		
		return $this->saveChange($change);
	}
	
	/**
	 * Saves a change to the database
	 * @param Change the change
	 * @return bool TRUE if successful, else FALSE
	 */
	public function saveChange($change) {
		if ($change->isNew()) {
			$sql = 'INSERT INTO `'.KUMVA_DB_PREFIX.'change` VALUES('
				.'NULL,'
				.aka_prepsqlval($change->getDefinition()).','  #TBR
				.aka_prepsqlval($change->getProposal()).','
				.aka_prepsqlval($change->getAction()).','
				.aka_prepsqlval($change->getSubmitter()).','
				.aka_timetosql($change->getSubmitted()).','
				.aka_prepsqlval($change->getStatus()).','
				.aka_prepsqlval($change->getResolver()).','
				.'FROM_UNIXTIME('.aka_prepsqlval($change->getResolved()).'))';
			
			$res = $this->database->insert($sql);
			if ($res === FALSE)
				return FALSE;
			$change->setId($res);
		}
		else {
			$sql = 'UPDATE `'.KUMVA_DB_PREFIX.'change` SET '
				.'original_id = '.aka_prepsqlval($change->getDefinition()).',' #TBR
				.'proposal_id = '.aka_prepsqlval($change->getProposal()).','
				.'action = '.aka_prepsqlval($change->getAction()).','
				.'submitter_id = '.aka_prepsqlval($change->getSubmitter()).','
				.'submitted = '.aka_timetosql($change->getSubmitted()).','
				.'status = '.aka_prepsqlval($change->getStatus()).','
				.'resolver_id = '.aka_prepsqlval($change->getResolver()).','
				.'resolved = '.aka_timetosql($change->getResolved()).' '
				.'WHERE change_id = '.$change->getId();

			if ($this->database->query($sql) === FALSE)
				return FALSE;
		}
		return TRUE;
	}
	
	/**
	 * Saves a comment to the database
	 * @param Change the change
	 * @param Comment the comment
	 * @return bool TRUE if successful, else FALSE
	 */
	public function saveComment($change, $comment) {
		if ($comment->isNew()) {
			$sql = 'INSERT INTO `'.KUMVA_DB_PREFIX.'comment` VALUES('
				.'NULL,'
				.aka_prepsqlval($change).','
				.aka_prepsqlval($comment->getUser()).','
				.'FROM_UNIXTIME('.aka_prepsqlval($comment->getCreated()).'),'
				.aka_prepsqlval($comment->isApproval()).','
				.aka_prepsqlval($comment->getText()).','
				.aka_prepsqlval($comment->isVoided()).')';
			
			$res = $this->database->insert($sql);
			if ($res === FALSE)
				return FALSE;
			$comment->setId($res);
		}
		return TRUE;
	}
	
	/**
	 * Voids a comment 
	 * @param Comment the comment
	 * @return bool TRUE if successful, else FALSE
	 */
	public function voidComment($comment) {
		$sql = 'UPDATE `'.KUMVA_DB_PREFIX.'comment` SET voided = 1 WHERE comment_id = '.aka_prepsqlval($comment);
		return $this->database->query($sql) !== FALSE;
	}
	
	/**
	 * Adds a user as a watcher of a change
	 * @param Change the change to watch
	 * @param User the watcher
	 * @return bool TRUE if successful, else FALSE
	 */
	public function addWatcher($change, $user) {
		$sql = 'INSERT INTO `'.KUMVA_DB_PREFIX.'change_watch` VALUES('.aka_prepsqlval($change).','.aka_prepsqlval($user).')';		
		return $this->database->query($sql) !== FALSE;
	}
	
	/**
	 * Removes a user as a watcher of a change
	 * @param Change the change to watch
	 * @param User the watcher
	 * @return bool TRUE if successful, else FALSE
	 */
	public function removeWatcher($change, $user) {
		$sql = 'DELETE FROM `'.KUMVA_DB_PREFIX.'change_watch` WHERE change_id = '.aka_prepsqlval($change).' AND user_id = '.aka_prepsqlval($user);		
		return $this->database->query($sql) !== FALSE;
	}
	
	/**
	 * Gets statistics about the changes
	 * @return array the change statistics
	 */
	public function getChangeStatistics($submitter = NULL) {
		$sql = 'SELECT status, COUNT(*) as `count` FROM `'.KUMVA_DB_PREFIX.'change` ';
		if ($submitter)
			$sql .= 'WHERE submitter_id = '.$submitter->getId().' ';
		$sql .= 'GROUP BY status';
		
		return $this->database->rows($sql, 'status');
	}
}

?>
