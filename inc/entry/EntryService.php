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
 * Purpose: Entry service class
 */

/**
 * Entry, revision and example functions
 */
class EntryService extends Service {
	/**
	 * Gets the entry with the given id
	 * @param int id the entry id
	 * @return Entry the entry
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
	 * Gets all the revisions for the given entry
	 * @return array the revisions
	 */
	public function getEntryRevisions($entry) {
		$sql = 'SELECT * FROM `'.KUMVA_DB_PREFIX.'revision` 
				WHERE entry_id = '.$entry->getId(). '
				ORDER BY number DESC';
		return Revision::fromQuery($this->database->query($sql));
	}
	
	/**
	 * Gets the revision with the given id
	 * @param int id the revision id
	 * @return Revision the revision
	 */
	public function getRevision($id) {
		$row = $this->database->row('SELECT * FROM `'.KUMVA_DB_PREFIX.'revision` WHERE definition_id = '.$id);
		return ($row != NULL) ? Revision::fromRow($row) : NULL;
	}
	
	/**
	 * Gets all the accepted revisions
	 * @return array the revisions
	 */
	public function getAcceptedRevisions() {
		$sql = 'SELECT * FROM `'.KUMVA_DB_PREFIX.'revision`
				WHERE status = 1
				ORDER BY entry_id ASC';
		return Revision::fromQuery($this->database->query($sql));
	}
	
	/**
	 * Gets the specified revision for the given entry
	 * @param Entry entry the entry
	 * @param int number the revision number
	 * @return Revision the revision
	 */
	public function getEntryRevision($entry, $number) {
		switch ($number) {
		case RevisionPreset::ACCEPTED:
			$where = 'entry_id = '.$entry->getId().' AND status = 1';
			break;
		case RevisionPreset::PROPOSED:
			$where = 'entry_id = '.$entry->getId().' AND status = 2';
			break;
		case RevisionPreset::HEAD:
			$where = 'entry_id = '.$entry->getId().' AND (status = 1 OR status = 2) ORDER BY number DESC LIMIT 1';
			break;
		case RevisionPreset::LAST:
			$where = 'entry_id = '.$entry->getId().' ORDER BY number DESC LIMIT 1';
			break;
		default:
			$where = 'entry_id = '.$entry->getId().' AND number = '.(int)$number;
		}
	
		$row = $this->database->row('SELECT * FROM `'.KUMVA_DB_PREFIX.'revision` WHERE '.$where);
		return ($row != NULL) ? Revision::fromRow($row) : NULL;
	}
	
	/**
	 * Gets the noun classes for the given revision
	 * @param Revision revision the revision
	 * @return array the noun classes (integers)
	 */
	public function getRevisionNounClasses($revision) {
		$sql = 'SELECT `nounclass` FROM `'.KUMVA_DB_PREFIX.'revision_nounclass` 
		        WHERE `definition_id` = '.$revision->getId().'
		        ORDER BY `order` ASC';
		$res = $this->database->query($sql);
		$classes = array();
		if ($res) {
			while ($row = mysql_fetch_assoc($res))
				$classes[] = (int)$row['nounclass'];
		}
		return $classes;
	}
	
	/**
	 * Gets the meanings for the given revision
	 * @param Revision revision the revision
	 * @return array the meanings
	 */
	public function getRevisionMeanings($revision) {
		$sql = 'SELECT * FROM `'.KUMVA_DB_PREFIX.'meaning` 
				WHERE definition_id = '.$revision->getId().' 
				ORDER BY `order` ASC';
		return Meaning::fromQuery($this->database->query($sql));
	}
	
	/**
	 * Gets the tags for the given revision
	 * @param Revision revision the revision
	 * @param int relationshipId the relationship id
	 * @return array the tags
	 */
	public function getRevisionTags($revision, $relationshipId) {
		$sql = 'SELECT t.* FROM `'.KUMVA_DB_PREFIX.'tag` t 
				INNER JOIN `'.KUMVA_DB_PREFIX.'revision_tag` dt ON dt.tag_id = t.tag_id
				WHERE dt.definition_id = '.$revision->getId().' AND dt.relationship_id = '.$relationshipId.' 
				ORDER BY dt.order ASC';		
		return Tag::fromQuery($this->database->query($sql));
	}
	
	/**
	 * Gets the usage examples for the given revision
	 * @param Revision revision the revision
	 * @return array the examples
	 */
	public function getRevisionExamples($revision) {
		$sql = 'SELECT * FROM `'.KUMVA_DB_PREFIX.'example` WHERE definition_id = '.$revision->getId();
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
				.aka_prepsqlval($entry->getMedia()).')';
			
			$res = $this->database->insert($sql);
			if ($res === FALSE) 
				return FALSE;
			$entry->setId($res);
		}
		else {
			$sql = 'UPDATE `'.KUMVA_DB_PREFIX.'entry` SET '
				.'media = '.aka_prepsqlval($entry->getMedia()).' '
				.'WHERE entry_id = '.$entry->getId();
			
			if ($this->database->query($sql) === FALSE)
				return FALSE;
		}
		return TRUE;
	}
	
	/**
	 * Saves the specified revision
	 * @param Revision revision the revision
	 * @return bool TRUE if successful, else FALSE
	 */
	public function saveRevision($revision) {
		if ($revision->isNew()) {
			$sql = 'INSERT INTO `'.KUMVA_DB_PREFIX.'revision` VALUES('
				.'NULL,'
				.aka_prepsqlval($revision->getEntry()).','
				.aka_prepsqlval($revision->getNumber()).','
				.aka_prepsqlval($revision->getStatus()).','
				.aka_prepsqlval($revision->getChange()).','
				.aka_prepsqlval($revision->getWordClass()).','
				.aka_prepsqlval($revision->getPrefix()).','
				.aka_prepsqlval($revision->getLemma()).','
				.aka_prepsqlval($revision->getModifier()).','
				.aka_prepsqlval($revision->getPronunciation()).','
				.aka_prepsqlval($revision->getComment()).','
				.aka_prepsqlval($revision->isUnverified()).')';
			
			$res = $this->database->insert($sql);
			if ($res === FALSE) 
				return FALSE;
			$revision->setId($res);
		}
		else {
			$sql = 'UPDATE `'.KUMVA_DB_PREFIX.'revision` SET '
				.'entry_id = '.aka_prepsqlval($revision->getEntry()).','
				.'number = '.aka_prepsqlval($revision->getNumber()).','
				.'status = '.aka_prepsqlval($revision->getStatus()).','
				.'change_id = '.aka_prepsqlval($revision->getChange()).','
				.'wordclass = '.aka_prepsqlval($revision->getWordClass()).','
				.'prefix = '.aka_prepsqlval($revision->getPrefix()).','
				.'lemma = '.aka_prepsqlval($revision->getLemma()).','
				.'modifier = '.aka_prepsqlval($revision->getModifier()).','
				.'pronunciation = '.aka_prepsqlval($revision->getPronunciation()).','
				.'comment = '.aka_prepsqlval($revision->getComment()).','
				.'unverified = '.aka_prepsqlval($revision->isUnverified()).' '
				.'WHERE definition_id = '.$revision->getId();
			
			if ($this->database->query($sql) === FALSE)
				return FALSE;
		}

		// Save noun classes, meanings, tags and examples		
		$this->saveRevisionNounClasses($revision);
		$this->saveRevisionMeanings($revision);
		$this->saveRevisionTags($revision);
		$this->saveRevisionExamples($revision);
		
		return TRUE;
	}
	
	/**
	 * Saves noun class associations for the given revision
	 * @param Revision revision the revision
	 * @param array nounClasses the noun classes
	 * @return bool TRUE if noun classes were saved, else FALSE
	 */
	private function saveRevisionNounClasses($revision) {
		$nounClasses = $revision->getNounClasses();
	
		// Delete any existing class assocations for this revision
		$this->database->query('DELETE FROM `'.KUMVA_DB_PREFIX.'revision_nounclass` WHERE `definition_id` = '.$revision->getId());
		
		$order = 0;
		foreach ($nounClasses as $cls) {
			$this->database->insert('INSERT INTO `'.KUMVA_DB_PREFIX.'revision_nounclass` VALUES('.$revision->getId().','.$order.','.(int)$cls.')');
			$order++;
		}
		
		return TRUE;
	}
	
	/**
	 * Saves meanings for the given revision
	 * @param Revision revision the revision
	 * @return bool TRUE if examples were saved, else FALSE
	 */
	private function saveRevisionMeanings($revision) {
		$meanings = $revision->getMeanings();
	
		// Delete any existing class assocations for this revision
		$this->database->query('DELETE FROM `'.KUMVA_DB_PREFIX.'meaning` WHERE `definition_id` = '.$revision->getId());
		
		$order = 0;
		foreach ($meanings as $meaning) {
			$sql = 'INSERT INTO `'.KUMVA_DB_PREFIX.'meaning` VALUES('
				.'NULL,'
				.aka_prepsqlval($revision->getId()).','
				.$order.','
				.aka_prepsqlval($meaning->getMeaning()).','
				.aka_prepsqlval($meaning->getFlags()).')';
			
			$res = $this->database->insert($sql);
			if ($res === FALSE)
				return FALSE;
			$meaning->setId($res);
			$order++;
		}
		return TRUE;
	}
	
	/**
	 * Saves noun class associations for the given revision
	 * @param Revision revision the revision
	 * @return bool TRUE if tags were saved, else FALSE
	 */
	private function saveRevisionTags($revision) {
		$relationships = Dictionary::getTagService()->getRelationships();
		foreach ($relationships as $relationship)
			$revision->getTags($relationship->getId());
	
		// Remove any existing tags for this revision
		if ($this->database->query('DELETE FROM `'.KUMVA_DB_PREFIX.'revision_tag` WHERE `definition_id` = '.$revision->getId()) === FALSE)
			return FALSE;
		
		foreach ($relationships as $relationship) {
			$tags = $revision->getTags($relationship->getId());
			$tagCount = count($tags);
			if ($tagCount > 0) {
				$order = 0;
				
				$weight_count = 1.0 / $tagCount;
				
				foreach ($tags as $tag) {
					$weight_order = 1.0 - $order / $tagCount;
					$weight_order *= $weight_order;
					$weight = (int)(($weight_count + $weight_order) * 100);
					
					Dictionary::getTagService()->addTag($revision, $relationship, $order, $weight, $tag);
					$order++;
				}
			}
		}
		return TRUE;
	}
	
	/**
	 * Saves examples for the given revision
	 * @param Revision revision the revision
	 * @return bool TRUE if examples were saved, else FALSE
	 */
	private function saveRevisionExamples($revision) {
		$examples = $revision->getExamples();
	
		// Delete any existing examples for this revision
		$this->database->query('DELETE FROM `'.KUMVA_DB_PREFIX.'example` WHERE `definition_id` = '.$revision->getId());
		
		foreach ($examples as $example) {
			$sql = 'INSERT INTO `'.KUMVA_DB_PREFIX.'example` VALUES('
				.'NULL,'
				.aka_prepsqlval($revision->getId()).','
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
	 * Generates weights for all taggings
	 */
	public function generateTagWeights() {
		$revisions = $this->getRevisions(TRUE, TRUE);
		foreach ($revisions as $revision)
			$this->saveRevisionTags($revision);
	}
	
	/**
	 * Gets statistics about the contents of this dictionary
	 * @return array the content statistics
	 */
	public function getContentStatistics() {
		$stats = array();
		$stats['entries'] = $this->database->scalar('SELECT COUNT(*) FROM `'.KUMVA_DB_PREFIX.'revision` WHERE status = 1');
		$stats['entries_unverified'] = $this->database->scalar(
			'SELECT COUNT(*) FROM `'.KUMVA_DB_PREFIX.'revision` WHERE status = 1 AND unverified = 1');
		$stats['media'] = $this->database->row(
			'SELECT 
				COUNT(CASE WHEN media & 1 THEN 1 ELSE NULL END) AS `audio`,
				COUNT(CASE WHEN media & 2 THEN 1 ELSE NULL END) AS `image` 
			FROM `'.KUMVA_DB_PREFIX.'entry`');
		return $stats;
	}
	
	/**
	 * Gets counts of entries with each media type
	 * @return array the media type counts
	 */
	public function getMediaCounts() {
		return $this->database->row('SELECT 
				COUNT(CASE WHEN media & 1 THEN 1 ELSE NULL END) AS `audio`,
				COUNT(CASE WHEN media & 2 THEN 1 ELSE NULL END) AS `image` 
			FROM `'.KUMVA_DB_PREFIX.'entry`');
	}
	
	/**
	 * Gets counts of each word class
	 * @return array the word class counts
	 */
	public function getWordClassCounts() {
		$sql = 'SELECT `wordclass`, COUNT(*) AS `count` FROM `'.KUMVA_DB_PREFIX.'revision`
				WHERE status = 1
				GROUP BY `wordclass` ORDER BY `wordclass` ASC';
		return $this->database->rows($sql, 'wordclass');
	}
}

?>
