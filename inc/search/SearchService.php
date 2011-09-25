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
 * Purpose: Search service class
 */

/**
 * Search functions
 */
class SearchService extends Service {
	/**
	 * Searches for all entries that match the given search criteria
	 * @param Query the query
	 * @param int type the search type
	 * @param Paging paging the paging object
	 * @return array the matching revisions
	 */
	public function search($query, $type, $orderBy = OrderBy::ENTRY, $paging = null) {
		$proposals = Session::getCurrent()->isAuthenticated();
		$pattern = $query->getPattern();
		
		// Search specific relationships or default to [meaning, form, or variant]
		$relationships = $query->getRelationship() ? array($query->getRelationship()) : Dictionary::getTagService()->getRelationships(true);
		
		// Search specific tag language or all configured tag languages?
		$langs = $query->getLang() ? array($query->getLang()) : Dictionary::getLanguageService()->getLexicalLanguages(true);
		
		$sql = "SELECT SQL_CALC_FOUND_ROWS e.*, CONCAT(COALESCE(r.prefix, ''), r.lemma) as `entry`
				FROM `".KUMVA_DB_PREFIX."entry` e 
				INNER JOIN `".KUMVA_DB_PREFIX."revision` r ON r.entry_id = e.entry_id AND ";
		
		// Check revision status		
		if ($proposals)
			$sql .= '(r.status = 1 OR r.status = 2) ';
		else
			$sql .= 'r.status = 1 ';
		
		/////////// Tag based criteria /////////////
		
		$sql .= "INNER JOIN (
				 SELECT rt.revision_id, MAX(rt.weight) as `maxtagweight` FROM `".KUMVA_DB_PREFIX."revision_tag` rt
				 INNER JOIN `".KUMVA_DB_PREFIX."tag` t ON rt.tag_id = t.tag_id ";
			
		$tagCriteria = array();
		foreach ($langs as $lang) {
			$lang = $this->database->escape($lang);
			if ($pattern != '') {
				switch ($type) {
				case SearchType::FORM:
					$text = str_replace('*', '%', $this->database->escape($pattern));
					$patternOp = $query->isPartialMatch() ? 'LIKE' : '=';
					$tagCriteria[] = "(t.lang = '$lang' AND t.text $patternOp '$text')";
					break;
				case SearchType::STEM:
					$stem = $this->database->escape(Lexical::stem($lang, $pattern));
					$tagCriteria[] = "(t.lang = '$lang' AND t.stem = '$stem')";
					break;
				case SearchType::SOUND:
					$sound = $this->database->escape(Lexical::sound($lang, $pattern));
					$tagCriteria[] = "(t.lang = '$lang' AND t.sound = '$sound')";
					break;
				}
			} 
			else
				$tagCriteria[] = "(t.lang = '$lang')";
		}
		
		// Add a language agnostic match for stem searches with no explicit language
		if ($type == SearchType::STEM && !$query->getLang()) {
			$text = $this->database->escape($pattern);
			$tagCriteria[] = "t.text = '$text'";
		}
	
		$tagDefCriteria = array();
		foreach ($relationships as $relationship)
			$tagDefCriteria[] = 'rt.relationship_id = '.$relationship->getId();
	
		$sql .= "  WHERE (".implode(' OR ', $tagCriteria).") AND (".implode(' OR ', $tagDefCriteria).") ";
		
		// If not including proposals, then only use active taggings
		if (!$proposals)
			$sql .= 'AND rt.active = 1 ';
		
		$sql .= "  GROUP BY rt.revision_id ";			
		$sql .= ") m ON m.revision_id = r.revision_id ";
		
		/////////////////// Revision/entry criteria //////////////////
		
		$defCriteria = array();
			
		// Filter by wordclass
		if ($query->getWordClass())
			$defCriteria[] = 'r.wordclass = '.aka_prepsqlval($query->getWordClass());
			
		// Filter by verified state
		$verified = $query->getVerified();
		if ($verified !== null)
			$defCriteria[] = 'r.unverified = '.(int)(!$verified);
			
		// Filter by media
		$hasMedia = $query->getHasMedia();
		if ($hasMedia !== null)
			$defCriteria[] = 'e.media & '.pow(2, $hasMedia);
			
		if (count($defCriteria) > 0)
			$sql .= 'WHERE '.implode(' AND ', $defCriteria).' ';
			
		// If we matched against proposals then we might have 2 copies of one entry
		if ($proposals)
			$sql .= 'GROUP BY e.entry_id ';
		
		//////////////////// Order by //////////////////////////////
		
		switch ($orderBy) {
		case OrderBy::ENTRY:
			$sql .= "ORDER BY `entry` ASC ";
			break;
		case OrderBy::STEM:
			$sql .= "ORDER BY r.lemma ASC, r.prefix ASC ";
			break;
		case OrderBy::RELEVANCE:
			$sql .= "ORDER BY `maxtagweight` DESC, `entry` ASC ";
			break;
		}
		
		// Execute query
		$result = $this->database->query($sql, $paging);
		
		return Entry::fromQuery($result);
	}
	
	/**
	 * Gets searches from the history
	 * @param string remoteAddr the remote address (may be NULL)
	 * @apram string source the search source (may be NULL)
	 * @param bool showOnlyMisses whether to only return searches that gave no results
	 * @return array the searches
	 */
	public function getSearchHistory($remoteAddr, $source, $showOnlyMisses, $paging) {
		$sql = 'SELECT SQL_CALC_FOUND_ROWS h.* FROM `'.KUMVA_DB_PREFIX.'searchrecord` h
				WHERE 1=1 ';
				
		if ($remoteAddr)
			$sql .= 'AND h.remoteaddr = '.aka_prepsqlval($remoteAddr).' ';
		if ($source)
			$sql .= 'AND h.source = '.aka_prepsqlval($source).' ';		
		if ($showOnlyMisses)
			$sql .= 'AND h.results = 0 ';
				
		$sql .= 'ORDER BY h.timestamp DESC LIMIT '.$paging->getStart().', '.$paging->getSize();
				
		$rows = $this->database->rows($sql);	
		$total = $this->database->scalar('SELECT FOUND_ROWS()');
		$paging->setTotal($total);
											  
		return $rows;
	}
	
	/**
	 * Gets search statistics 
	 * @return array the statistics
	 */
	public function getSearchStatistics($since) {
		$stats = array();
		$stats['total'] = $this->database->scalar('SELECT COUNT(*) FROM `'.KUMVA_DB_PREFIX.'searchrecord` WHERE `timestamp` > FROM_UNIXTIME('.$since.')');
		$stats['misses'] = $this->database->scalar('SELECT COUNT(*) FROM `'.KUMVA_DB_PREFIX.'searchrecord` WHERE `results` = 0 AND `timestamp` > FROM_UNIXTIME('.$since.')');
		$stats['suggestions'] = $this->database->scalar('SELECT COUNT(*) FROM `'.KUMVA_DB_PREFIX.'searchrecord` WHERE `results` > 0 AND `suggest` IS NOT NULL AND `timestamp` > FROM_UNIXTIME('.$since.')');
		$stats['sources'] = $this->getSearchSourcesByCount($since);
		return $stats;
	}
	
	/**
	 * Gets the most popular search sources
	 * @param int since the timestamp to start at
	 * @param int max the max number of terms to return
	 * @return array the terms and their counts
	 */
	public function getSearchSourcesByCount($since, $max = 5) {
		$sql = 'SELECT `source`, COUNT(`search_id`) as `count` FROM `'.KUMVA_DB_PREFIX.'searchrecord` 
				WHERE `timestamp` > FROM_UNIXTIME('.$since.')
				GROUP BY `source` ORDER BY `count` DESC LIMIT 0, '.$max;
			
		return $this->database->rows($sql);
	}
	
	/**
	 * Logs the given search in the dictionary history
	 * @param string pattern the search query pattern
	 * @param string suggest the smart search suggestion
	 * @param int resultCount the number of results the search returned
	 * @param int timeTaken the time taken for searching in ms
	 * @param string ref the coded referal source of this query
	 * @return bool TRUE if operation was successful, else FALSE
	 */
	public function logSearch($pattern, $suggest, $iterations, $resultCount, $timeTaken, $source = null) {		
		
		$remoteAddr = $_SERVER['REMOTE_ADDR'];
		
		$sql = 'INSERT INTO `'.KUMVA_DB_PREFIX.'searchrecord` VALUES(
			NULL,'
			.aka_prepsqlval($pattern).','
			.aka_prepsqlval($suggest).','
			.aka_prepsqlval($iterations).','
			.aka_prepsqlval($resultCount).',
			NOW(),'
			.aka_prepsqlval($timeTaken).','
			.aka_prepsqlval($remoteAddr).','
			.aka_prepsqlval($source).')';

				
		return $this->database->query($sql) !== false;
	}
}

?>
