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
 * Purpose: Definition service class
 */

/**
 * Definition and example functions
 */
class DefinitionService extends Service {
	/**
	 * #TBR
	 */
	public function getDefinitions($incProposals = FALSE, $incVoided = FALSE) {
		$sql = 'SELECT * FROM `'.KUMVA_DB_PREFIX.'definition` WHERE 1=1 ';
		if (!$incProposals)
			$sql .= 'AND proposal = 0 ';
		if (!$incVoided)
			$sql .= 'AND voided = 0 ';
	
		return Definition::fromQuery($this->database->query($sql));
	}
	
	/**
	 * #TBR
	 */
	public function deleteDefinition($definition) {
		if ($this->database->query('DELETE FROM `'.KUMVA_DB_PREFIX.'definition` WHERE `definition_id` = '.$definition->getId()) === FALSE)
			return FALSE;
		
		return Dictionary::getTagService()->deleteOrphanTags();
	}
	
	/**
	 * #TBR
	 */
	public function voidDefinition($definition) {
		$definition->setVoided(TRUE);
		return $this->saveDefinition($definition);
	}
	
	/**
	 * #TBR
	 */
	public function clear() {
		if ($this->database->query('TRUNCATE `'.KUMVA_DB_PREFIX.'definition_nounclass`') === FALSE)
			return FALSE;
		if ($this->database->query('TRUNCATE `'.KUMVA_DB_PREFIX.'definition_tag`') === FALSE)
			return FALSE;
		if ($this->database->query('TRUNCATE `'.KUMVA_DB_PREFIX.'tag`') === FALSE)
			return FALSE;
		if ($this->database->query('TRUNCATE `'.KUMVA_DB_PREFIX.'example`') === FALSE)
			return FALSE;
		if ($this->database->query('TRUNCATE `'.KUMVA_DB_PREFIX.'comment`') === FALSE)
			return FALSE;
		if ($this->database->query('TRUNCATE `'.KUMVA_DB_PREFIX.'change`') === FALSE)
			return FALSE;
		if ($this->database->query('TRUNCATE `'.KUMVA_DB_PREFIX.'definition`') === FALSE)
			return FALSE;
		if ($this->database->query('TRUNCATE `'.KUMVA_DB_PREFIX.'entry`') === FALSE)
			return FALSE;
		
		return TRUE;
	}
	
	/**
	 * Gets the entry with the given id
	 * @param int id the definition id
	 * @return Definition the definition
	 */
	public function getEntry($id) {
		$row = $this->database->row('SELECT * FROM `'.KUMVA_DB_PREFIX.'entry` WHERE entry_id = '.$id);
		return ($row != NULL) ? Entry::fromRow($row) : NULL;
	}
	
	/**
	 * Gets all the entries
	 * @return array the entries
	 */
	public function getEntries() {
		$sql = 'SELECT * FROM `'.KUMVA_DB_PREFIX.'entry`';
		return Entry::fromQuery($this->database->query($sql));
	}
	
	/**
	 * Gets all the definitions for the given entry
	 * @return array the definitions
	 */
	public function getEntryDefinitions($entry) {
		$sql = 'SELECT * FROM `'.KUMVA_DB_PREFIX.'definition` 
				WHERE entry_id = '.$entry->getId(). '
				ORDER BY revision DESC';
		return Definition::fromQuery($this->database->query($sql));
	}
	
	/**
	 * Gets the definition with the given id
	 * @param int id the definition id
	 * @return Definition the definition
	 */
	public function getDefinition($id) {
		$row = $this->database->row('SELECT * FROM `'.KUMVA_DB_PREFIX.'definition` WHERE definition_id = '.$id);
		return ($row != NULL) ? Definition::fromRow($row) : NULL;
	}
	
	/**
	 * Gets the definition with the given entry and revision
	 * @param int entryId the entry id
	 * @param int revision the revision number
	 * @return Definition the definition
	 */
	public function getDefinitionByRevision($entryId, $revision) {
		$row = $this->database->row('SELECT * FROM `'.KUMVA_DB_PREFIX.'definition` 
									 WHERE entry_id = '.(int)$entryId.' AND revision = '.(int)$revision);
		return ($row != NULL) ? Definition::fromRow($row) : NULL;
	}
	
	/**
	 * Gets the change for the given definition if there is one
	 * @param Definition definition the definition
	 * @return Change the change
	 */
	public function getDefinitionChange($definition) {
		$row = $this->database->row('SELECT * FROM `'.KUMVA_DB_PREFIX.'change` WHERE proposal_id = '.$definition->getId());
		return ($row != NULL) ? Change::fromRow($row) : NULL;
	}
	
	/**
	 * Gets a random definition (ignores proposals, voided, unverified, flagged definitions)
	 * @param bool wotd true for 'word of the day' mode
	 * @return Definition the random definition
	 */
	public function getRandomDefinition($wotd = FALSE) {
		// For word of the day mode, seed the random number generator based on the day
		if ($wotd) {
			$date = getdate(time()); 	// Get GMT date
			$date['seconds'] = 0;		// Clear time fields
			$date['minutes'] = 0;		
			$date['hours'] = 0;		
			$time = mktime($date['hours'], $date['minutes'], $date['seconds'], $date['mon'], $date['mday'], $date['year']);
			mt_srand($time);
		}
		else
			mt_srand();	
		
		$join = 'INNER JOIN `'.KUMVA_DB_PREFIX.'entry` e ON e.accepted_id = d.definition_id';	
		$where = 'WHERE d.verified = 1 AND d.flags = 0';
		
		// Geta total number of suitable entries
		$total = $this->database->scalar("SELECT COUNT(*) FROM `".KUMVA_DB_PREFIX."definition` d $join $where");
		if ($total > 0) {
			// Select a row with random offset
			$offset = mt_rand(0, $total - 1);
			$row = $this->database->row("SELECT * FROM `".KUMVA_DB_PREFIX."definition` d $join $where LIMIT $offset, 1");
			return ($row != NULL) ? Definition::fromRow($row) : NULL;
		}
		return NULL;
	}
	
	/**
	 * Gets the tags for the given definition
	 * @param Definition definition the definition
	 * @param int relationshipId the relationship id
	 * @return array the tags
	 */
	public function getDefinitionTags($definition, $relationshipId) {
		$sql = 'SELECT t.* FROM `'.KUMVA_DB_PREFIX.'tag` t 
				INNER JOIN `'.KUMVA_DB_PREFIX.'definition_tag` dt ON dt.tag_id = t.tag_id
				WHERE dt.definition_id = '.$definition->getId().' AND dt.relationship_id = '.$relationshipId.' 
				ORDER BY dt.order ASC';		
		return Tag::fromQuery($this->database->query($sql));
	}
	
	/**
	 * Gets the noun classes for the given definition
	 * @param Definition definition the definition
	 * @return array the noun classes (integers)
	 */
	public function getDefinitionNounClasses($definition) {
		$sql = 'SELECT `nounclass` FROM `'.KUMVA_DB_PREFIX.'definition_nounclass` 
		        WHERE `definition_id` = '.$definition->getId().'
		        ORDER BY `order`';
		$res = $this->database->query($sql);
		$classes = array();
		if ($res) {
			while ($row = mysql_fetch_assoc($res))
				$classes[] = (int)$row['nounclass'];
		}
		return $classes;
	}
	
	/**
	 * Gets the usage examples for the given definition
	 * @param Definition definition the definition
	 * @return array the examples
	 */
	public function getDefinitionExamples($definition) {
		$sql = 'SELECT * FROM `'.KUMVA_DB_PREFIX.'example` WHERE definition_id = '.$definition->getId();
		return Example::fromQuery($this->database->query($sql));
	}
	
	/**
	 * Saves the specified entry
	 * @param Entry entry the entry
	 * @return bool TRUE if successful, else FALSE
	 */
	public function saveEntry($entry) {
		if ($entry->isNew()) {
			$sql = 'INSERT INTO `'.KUMVA_DB_PREFIX.'entry` VALUES('
				.'NULL,'
				.aka_prepsqlval($entry->getAccepted()).','
				.aka_prepsqlval($entry->getProposed()).')';
			
			$res = $this->database->insert($sql);
			if ($res === FALSE) 
				return FALSE;
			$entry->setId($res);
		}
		else {
			$sql = 'UPDATE `'.KUMVA_DB_PREFIX.'entry` SET '
				.'accepted_id = '.aka_prepsqlval($entry->getAccepted()).','
				.'proposed_id = '.aka_prepsqlval($entry->getProposed()).' '
				.'WHERE entry_id = '.$entry->getId();
			
			if ($this->database->query($sql) === FALSE)
				return FALSE;
		}
		return TRUE;
	}
	
	/**
	 * Saves the specified definition
	 * @param Definition definition the definition
	 * @return bool TRUE if successful, else FALSE
	 */
	public function saveDefinition($definition) {
		if ($definition->isNew()) {
			$sql = 'INSERT INTO `'.KUMVA_DB_PREFIX.'definition` VALUES('
				.'NULL,'
				.aka_prepsqlval($definition->getEntry()).','
				.aka_prepsqlval($definition->getRevision()).','
				.aka_prepsqlval($definition->getWordClass()).','
				.aka_prepsqlval($definition->getPrefix()).','
				.aka_prepsqlval($definition->getLemma()).','
				.aka_prepsqlval($definition->getModifier()).','
				.aka_prepsqlval($definition->getMeaning()).','
				.aka_prepsqlval($definition->getComment()).','
				.aka_prepsqlval($definition->getFlags()).','
				.aka_prepsqlval($definition->isVerified()).','
				.aka_prepsqlval($definition->isProposal()).','
				.aka_prepsqlval($definition->isVoided()).')';
			
			$res = $this->database->insert($sql);
			if ($res === FALSE) 
				return FALSE;
			$definition->setId($res);
		}
		else {
			$sql = 'UPDATE `'.KUMVA_DB_PREFIX.'definition` SET '
				.'entry_id = '.aka_prepsqlval($definition->getEntry()).','
				.'revision = '.aka_prepsqlval($definition->getRevision()).','
				.'wordclass = '.aka_prepsqlval($definition->getWordClass()).','
				.'prefix = '.aka_prepsqlval($definition->getPrefix()).','
				.'lemma = '.aka_prepsqlval($definition->getLemma()).','
				.'modifier = '.aka_prepsqlval($definition->getModifier()).','
				.'meaning = '.aka_prepsqlval($definition->getMeaning()).','
				.'comment = '.aka_prepsqlval($definition->getComment()).','
				.'flags = '.aka_prepsqlval($definition->getFlags()).','
				.'verified = '.aka_prepsqlval($definition->isVerified()).','
				.'proposal = '.aka_prepsqlval($definition->isProposal()).','
				.'voided = '.aka_prepsqlval($definition->isVoided()).' '
				.'WHERE definition_id = '.$definition->getId();
			
			if ($this->database->query($sql) === FALSE)
				return FALSE;
		}

		// Save noun classes, tags and examples		
		$this->saveDefinitionNounClasses($definition);
		$this->saveDefinitionTags($definition);
		$this->saveDefinitionExamples($definition);
		
		return TRUE;
	}
	
	/**
	 * Saves examples for the given definition
	 * @param Definition definition the definition
	 * @return bool TRUE if examples were saved, else FALSE
	 */
	private function saveDefinitionExamples($definition) {
		$examples = $definition->getExamples();
	
		// Delete any existing class assocations for this definition
		$this->database->query('DELETE FROM `'.KUMVA_DB_PREFIX.'example` WHERE `definition_id` = '.$definition->getId());
		
		foreach ($examples as $example) {
			$sql = 'INSERT INTO `'.KUMVA_DB_PREFIX.'example` VALUES('
				.'NULL,'
				.aka_prepsqlval($definition->getId()).','
				.aka_prepsqlval($example->getForm()).','
				.aka_prepsqlval($example->getMeaning()).')';
			
			$res = $this->database->insert($sql);
			if ($res === FALSE)
				return FALSE;
			$example->setId($res);
		}
		return TRUE;
	}
	
	/**
	 * Saves noun class associations for the given definition
	 * @param Definition definition the definition
	 * @param array nounClasses the noun classes
	 * @return bool TRUE if noun classes were saved, else FALSE
	 */
	private function saveDefinitionNounClasses($definition) {
		$nounClasses = $definition->getNounClasses();
	
		// Delete any existing class assocations for this definition
		$this->database->query('DELETE FROM `'.KUMVA_DB_PREFIX.'definition_nounclass` WHERE `definition_id` = '.$definition->getId());
		
		$order = 0;
		foreach ($nounClasses as $cls) {
			$this->database->insert('INSERT INTO `'.KUMVA_DB_PREFIX.'definition_nounclass` VALUES('.$definition->getId().','.$order.','.(int)$cls.')');
			$order++;
		}
		
		return TRUE;
	}
	
	/**
	 * Saves noun class associations for the given definition
	 * @param Definition definition the definition
	 * @param array nounClasses the noun classes
	 * @return bool TRUE if noun classes were saved, else FALSE
	 */
	private function saveDefinitionTags($definition) {
		$relationships = Dictionary::getTagService()->getRelationships();
		foreach ($relationships as $relationship)
			$definition->getTags($relationship->getId());
	
		// Remove any existing tags for this definition
		if ($this->database->query('DELETE FROM `'.KUMVA_DB_PREFIX.'definition_tag` WHERE `definition_id` = '.$definition->getId()) === FALSE)
			return FALSE;
		
		foreach ($relationships as $relationship) {
			$tags = $definition->getTags($relationship->getId());
			$tagCount = count($tags);
			if ($tagCount > 0) {
				$order = 0;
				
				$weight_count = 1.0 / $tagCount;
				
				foreach ($tags as $tag) {
					$weight_order = 1.0 - $order / $tagCount;
					$weight_order *= $weight_order;
					$weight = (int)(($weight_count + $weight_order) * 100);
					
					Dictionary::getTagService()->addTag($definition, $relationship, $order, $weight, $tag);
					$order++;
				}
			}
		}
		return TRUE;
	}
	
	/**
	 * Generates weights for all taggings
	 */
	public function generateTagWeights() {
		$definitions = $this->getDefinitions(TRUE, TRUE);
		foreach ($definitions as $definition)
			$this->saveDefinitionTags($definition);
	}
	
	/**
	 * Gets statistics about the contents of this dictionary
	 * @return array the content statistics
	 */
	public function getContentStatistics() {
		$stats = array();
		$stats['entries'] = $this->database->scalar('SELECT COUNT(*) FROM `'.KUMVA_DB_PREFIX.'entry` WHERE accepted_id IS NOT NULL');
		return $stats;
	}
	
	/**
	 * Gets counts of each word class
	 * @return array the word class counts
	 */
	public function getWordClassCounts() {
		$sql = 'SELECT `wordclass`, COUNT(*) AS `count` FROM `'.KUMVA_DB_PREFIX.'definition` d
				INNER JOIN `'.KUMVA_DB_PREFIX.'entry` e ON e.accepted_id = d.definition_id 
				GROUP BY `wordclass` ORDER BY `wordclass` ASC';
		return $this->database->rows($sql, 'wordclass');
	}
}

?>
