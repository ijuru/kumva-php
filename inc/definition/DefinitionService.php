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
	 * Gets the definition with the given id
	 * @param int id the definition id
	 * @return Definition the definition
	 */
	public function getDefinition($id) {
		$row = $this->database->row('SELECT * FROM `'.KUMVA_DB_PREFIX.'definition` WHERE definition_id = '.$id);
		return ($row != NULL) ? Definition::fromRow($row) : NULL;
	}
	
	/**
	 * Gets all the definitions
	 * @param bool incProposals TRUE to include proposal definitions
	 * @param bool incVoided TRUE to include voided definitions
	 * @return array the definitions
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
			
		$where = 'WHERE proposal = 0 AND verified = 1 AND flags = 0 AND voided = 0';
		
		// Geta total number of suitable definitions
		$total = $this->database->scalar("SELECT COUNT(*) FROM `".KUMVA_DB_PREFIX."definition` $where");
		if ($total > 0) {
			// Select a row with random offset
			$offset = mt_rand(0, $total - 1);
			$row = $this->database->row("SELECT * FROM `".KUMVA_DB_PREFIX."definition` $where LIMIT $offset, 1");
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
	 * Saves the specified definition
	 * @param Definition definition the definition
	 * @return bool true if definition was inserted
	 */
	public function saveDefinition($definition) {
		if ($definition->isNew()) {
			$sql = 'INSERT INTO `'.KUMVA_DB_PREFIX.'definition` VALUES('
				.'NULL,'
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
	 * Voids the specified definition
	 * @param Definition definition the definition
	 * @return bool TRUE if definition was voided
	 */
	public function voidDefinition($definition) {
		$definition->setVoided(TRUE);
		return $this->saveDefinition($definition);
	}
	
	/**
	 * Deletes the specified definition
	 * @param Definition definition the definition
	 * @return bool TRUE if definition was deleted
	 */
	public function deleteDefinition($definition) {
		if ($this->database->query('DELETE FROM `'.KUMVA_DB_PREFIX.'definition` WHERE `definition_id` = '.$definition->getId()) === FALSE)
			return FALSE;
		
		return Dictionary::getTagService()->deleteOrphanTags();
	}
	
	/**
	 * Clears the dictionary, i.e. removes all definitions, examples and tags
	 * @return bool true if successful, else false
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
		
		return TRUE;
	}
	
	/**
	 * Gets statistics about the contents of this dictionary
	 * @return array the content statistics
	 */
	public function getContentStatistics() {
		$stats = array();
		$stats['definitions'] = $this->database->scalar('SELECT COUNT(*) FROM `'.KUMVA_DB_PREFIX.'definition` WHERE proposal = 0 AND voided = 0');
		$stats['definitions_unverified'] = $this->database->scalar('SELECT COUNT(*) FROM `'.KUMVA_DB_PREFIX.'definition` WHERE proposal = 0 AND voided = 0 AND verified = 0');
		$stats['examples'] = $this->database->scalar('SELECT COUNT(*) FROM `'.KUMVA_DB_PREFIX.'example`');
		
		return $stats;
	}
	
	/**
	 * Gets counts of each word class
	 * @return array the word class counts
	 */
	public function getWordClassCounts() {
		$sql = 'SELECT `wordclass`, COUNT(*) AS `count` FROM `'.KUMVA_DB_PREFIX.'definition` WHERE proposal = 0 AND voided = 0 GROUP BY `wordclass` ORDER BY `wordclass` ASC';
		return $this->database->rows($sql, 'wordclass');
	}
}

?>
