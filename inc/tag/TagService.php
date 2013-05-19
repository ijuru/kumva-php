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
 * Purpose: Tag service class
 */

/**
 * Tag functions
 */
class TagService extends Service {
	/**
	 * Finds tags by text or relationship
	 * @param mixed relationshipId a single relationship id or an array of relationship ids (can be NULL)
	 * @param string textLike text to match against tag text (can be NULL)
	 * @param int limit the maximum number of tags to return
	 * @return array the tags
	 */
	public function getTags($relationshipId = NULL, $textLike = NULL, $limit = 0) {
		$sql = 'SELECT DISTINCT t.* FROM `'.KUMVA_DB_PREFIX.'tag` t 
				INNER JOIN `'.KUMVA_DB_PREFIX.'revision_tag` dt ON dt.tag_id = t.tag_id
				WHERE dt.active = 1 ';
		
		if ($relationshipId != NULL && is_array($relationshipId))
			$sql .= 'AND dt.relationship_id IN ('.implode(',', $relationshipId).') ';
		elseif ($relationshipId != NULL)
			$sql .= 'AND dt.relationship_id = '.$relationshipId.' ';
			
		if ($textLike != NULL)
			$sql .= "AND t.text LIKE '$textLike' ";
			
		if ($limit > 0)
			$sql .= 'LIMIT '.$limit;
	
		return Tag::fromQuery($this->database->query($sql));
	}
	
	/**
	 * Gets tags that aren't related to any revisions
	 * @return array the tags
	 */
	public function getOrphanTags() {
		$sql = 'SELECT * FROM `'.KUMVA_DB_PREFIX.'tag` WHERE tag_id NOT IN (SELECT DISTINCT tag_id FROM `'.KUMVA_DB_PREFIX.'revision_tag`)';
		return Tag::fromQuery($this->database->query($sql));
	}
	
	/**
	 * Deletes tags that aren't related to any revisions
	 * @return bool TRUE if successful, else FALSE
	 */
	public function deleteOrphanTags() {
		$sql = 'DELETE FROM `'.KUMVA_DB_PREFIX.'tag` WHERE tag_id NOT IN (SELECT DISTINCT tag_id FROM `'.KUMVA_DB_PREFIX.'revision_tag`)';
		return $this->database->query($sql);
	}
	
	/**
	 * Gets the relationship with the given id
	 * @param int id the relationship id
	 * @return Relationship the relationship
	 */
	public function getRelationship($id) {
		$row = $this->database->row('SELECT * FROM `'.KUMVA_DB_PREFIX.'relationship` WHERE relationship_id = '.$id);	
		return ($row != NULL) ? Relationship::fromRow($row) : NULL;
	}
	
	/**
	 * Gets the relationship with the given name
	 * @param string name the relationship name
	 * @return Relationship the relationship
	 */
	public function getRelationshipByName($name) {
		$row = $this->database->row('SELECT * FROM `'.KUMVA_DB_PREFIX.'relationship` WHERE `name` = '.aka_prepsqlval($name));	
		return ($row != NULL) ? Relationship::fromRow($row) : NULL;
	}
	
	/**
	 * Gets all the relationships
	 * @param bool matchDefault TRUE to include only those relationships which are matched by default during searching
	 * @return array the relationships
	 */
	public function getRelationships($matchDefault = FALSE) {
		$sql = 'SELECT * FROM `'.KUMVA_DB_PREFIX.'relationship` ';
		if ($matchDefault)
			$sql .= 'WHERE matchdefault = 1 ';
			
		$sql .= 'ORDER BY `relationship_id`';
	
		return Relationship::fromQuery($this->database->query($sql));
	}

	/**
	 * Inserts a tag into the database
	 * @param Tag tag the tag
	 * @return bool TRUE if operation was successful, else FALSE
	 */
	public function saveTag($tag) {
		if ($tag->isNew()) {
			$sql = 'INSERT INTO `'.KUMVA_DB_PREFIX.'tag` VALUES('
				.'NULL,'
				.aka_prepsqlval($tag->getLang()).','
				.aka_prepsqlval($tag->getText()).','
				.aka_prepsqlval($tag->getStem()).','
				.aka_prepsqlval($tag->getSound()).')';	
		
			$res = $this->database->insert($sql);
			if ($res === FALSE)
				return FALSE;
			$tag->setId($res);
		}
		else {
			$sql = 'UPDATE `'.KUMVA_DB_PREFIX.'tag` SET '
				.'lang = '.aka_prepsqlval($tag->getLang()).','
				.'text = '.aka_prepsqlval($tag->getText()).','
				.'stem = '.aka_prepsqlval($tag->getStem()).','
				.'sound = '.aka_prepsqlval($tag->getSound()).' '
				.'WHERE tag_id = '.$tag->getId();
			
			if ($this->database->query($sql) === FALSE)
				return FALSE;
		}
		return TRUE;
	}
	
	/**
	 * Adds a tag to the given revision
	 * @param Revision revision the revision
	 * @param Relationship the relationship
	 * @param int order the order
	 * @param int weight the weighting
	 * @param Tag tag the tag
	 * @return bool TRUE if operation was successful, else FALSE
	 */
	public function addTag($revision, $relationship, $order, $weight, $tag) {
		// Query tags to see if an exact match already exists
		$sql = "SELECT `tag_id` FROM `".KUMVA_DB_PREFIX."tag`
				WHERE `lang` = '".$tag->getLang()."' AND `text` = ".aka_prepsqlval($tag->getText()).' COLLATE utf8_bin';
		$tagId = $this->database->scalar($sql);
		if ($tagId === FALSE) {
			if ($this->saveTag($tag) == FALSE)
				return FALSE;
			$tagId = $tag->getId();
		}
		
		$active = $revision->isAccepted();
			
		$sql = 'INSERT INTO `'.KUMVA_DB_PREFIX.'revision_tag` VALUES('
			.aka_prepsqlval($revision->getId()).','
			.aka_prepsqlval($relationship->getId()).','
			.aka_prepsqlval($order).','
			.aka_prepsqlval($tagId).','
			.aka_prepsqlval($weight).','
			.aka_prepsqlval($active).')';
		
		return $this->database->query($sql) !== FALSE;
	}
	
	/**
	 * Gets tag suggestions for a partial search string
	 * @param string string the partial search string
	 * @param int max the maximum number of results to return (0 means no limit)
	 * @return array the array of suggested search strings
	 */
	public function getTagSuggestions($string, $max = 0) {
		$match = $this->database->escape($string).'%';
		
		$relationshipIds = array(Relationship::FORM, Relationship::VARIANT, Relationship::MEANING);

		return self::getTags($relationshipIds, $match, $max);
	}
	
	/**
	 * Gets statistics about the tags used in this dictionary
	 * @return array the tag statistics
	 */
	public function getTagStatistics() {
		$relCriteria = array();
		foreach ($this->getRelationships() as $relationship)
			$relCriteria[] = 'COUNT(CASE WHEN `relationship_id` = '.$relationship->getId().' THEN 1 END) as `'.$relationship->getName().'`'; 
	
		$sql = 'SELECT lang, '.implode(', ', $relCriteria).
		'FROM (
			SELECT DISTINCT t.tag_id, t.lang, dt.relationship_id FROM `'.KUMVA_DB_PREFIX.'revision_tag` dt 
			INNER JOIN `'.KUMVA_DB_PREFIX.'tag` t ON t.tag_id = dt.tag_id
			WHERE dt.active = 1
		) tt GROUP BY lang ORDER BY `form` DESC, `variant` DESC, `meaning` DESC, `root` DESC';
		
		return $this->database->rows($sql, 'lang');
	}
	
	/**
	 * Get all the languages in use by tags
	 * @return array the language codes
	 */
	public function getTagLanguages() {
		$langs = $this->database->rows('SELECT DISTINCT lang FROM `'.KUMVA_DB_PREFIX.'tag`', 'lang');
		return array_keys($langs);
	}

	/**
	 * Gets counts of all category tags
	 */
	public function getCategoryCounts() {
		$sql = 'SELECT t.`text` as `category`, COUNT(*) as `count`
			FROM `rw_tag` t
			INNER JOIN `rw_revision_tag` rt ON rt.`tag_id` = t.`tag_id` AND rt.`active` = 1 AND rt.`relationship_id` = '.Relationship::CATEGORY.'
			GROUP BY t.`text`
			ORDER BY t.`text` ASC';

		return $this->database->rows($sql);
	}
	
	/**
	 * Generates all tag stems and sounds
	 * @return bool TRUE if successful, else FALSE
	 */
	public function generateLexical() {
		$tags = $this->getTags();
		foreach ($tags as $tag) {
			$tag->generateLexical();
			$this->saveTag($tag);
		}
	}
}

?>
