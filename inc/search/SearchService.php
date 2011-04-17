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
	 * Searches for all definitions that match the given search criteria
	 * @param Query the query
	 * @param int type the search type
	 * @param Paging paging the paging object
	 * @param bool incProposals TRUE if proposal definitions should be included, else FALSE
	 * @return array the matching definitions
	 */
	public function search($query, $type, $incProposals = FALSE, $orderBy = OrderBy::ENTRY, $paging = NULL) {
		$pattern = $query->getPattern();
		
		// Search specific relationships or default to [meaning, form, or variant]
		$relationships = $query->getRelationship() ? array($query->getRelationship()) : Dictionary::getTagService()->getRelationships(TRUE);
		
		// Search specific tag language or all configured tag languages?
		$langs = $query->getLang() ? array($query->getLang()) : Dictionary::getLanguageService()->getLexicalLanguages(TRUE);
		
		$sql = "SELECT SQL_CALC_FOUND_ROWS d.*, CONCAT(COALESCE(d.prefix, ''), d.lemma) as `entry`
				FROM `".KUMVA_DB_PREFIX."definition` d ";
		
		/////////// Tag based criteria /////////////
		
		$sql .= "INNER JOIN (
				 SELECT dt.definition_id, MAX(dt.weight) as `maxtagweight` FROM `".KUMVA_DB_PREFIX."definition_tag` dt
				 INNER JOIN `".KUMVA_DB_PREFIX."tag` t ON dt.tag_id = t.tag_id ";
			
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
			$tagDefCriteria[] = 'dt.relationship_id = '.$relationship->getId();
	
		$sql .= "  WHERE (".implode(' OR ', $tagCriteria).") AND (".implode(' OR ', $tagDefCriteria).") ";
		
		// If not including proposals, then only use active taggings
		if (!$incProposals)
			$sql .= 'AND dt.active = 1 ';
		
		$sql .= "  GROUP BY dt.definition_id ";			
		$sql .= ") m ON m.definition_id = d.definition_id ";
		
		/////////////////// Definition criteria //////////////////
		
		$defCriteria = array();
		
		// Return proposed definitions as well?
		if ($incProposals)
			$defCriteria[] = "(d.revisionstatus = 1 OR d.revisionstatus = 2)";
		else
			$defCriteria[] = "d.revisionstatus = 1";
			
		// Filter by wordclass
		if ($query->getWordClass())
			$defCriteria[] = 'd.wordclass = '.aka_prepsqlval($query->getWordClass());
			
		// Filter by verified state
		$verified = $query->getVerified();
		if ($verified !== NULL)
			$defCriteria[] = 'd.verified = '.(int)$verified;
			
		$sql .= ' WHERE '.implode(' AND ', $defCriteria).' ';
		
		//////////////////// Order by //////////////////////////////
		
		switch ($orderBy) {
		case OrderBy::ENTRY:
			$sql .= "ORDER BY `entry` ASC ";
			break;
		case OrderBy::STEM:
			$sql .= "ORDER BY d.lemma ASC, d.prefix ASC ";
			break;
		case OrderBy::RELEVANCE:
			$sql .= "ORDER BY `maxtagweight` DESC ";
			break;
		}
		
		// Execute query
		$result = $this->database->query($sql, $paging);
		
		return Definition::fromQuery($result);
	}
	
	/**
	 * Gets searches from the history
	 * @param string remoteAddr the remote address (may be NULL)
	 * @apram string source the search source (may be NULL)
	 * @param bool showCurrentUser whether to include searches by the current user
	 * @param bool showOnlyMisses whether to only return searches that gave no results
	 * @return array the searches
	 */
	public function getSearchHistory($remoteAddr, $source, $showCurrentUser, $showOnlyMisses, $paging) {
		$sql = 'SELECT SQL_CALC_FOUND_ROWS h.*, u.login FROM `'.KUMVA_DB_PREFIX.'searchrecord` h
				LEFT OUTER JOIN `'.KUMVA_DB_PREFIX.'user` u ON u.user_id = h.user_id 
				WHERE 1=1 ';
				
		if ($remoteAddr)
			$sql .= 'AND h.remoteaddr = '.aka_prepsqlval($remoteAddr).' ';
		if ($source)
			$sql .= 'AND h.source = '.aka_prepsqlval($source).' ';		
		if (!$showCurrentUser) {
			$user = Session::getCurrent()->getUser();
			$userId = $user != NULL ? $user->getId() : NULL;
			$sql .= 'AND (h.user_id IS NULL OR h.user_id != '.$userId.') ';	
		}
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
		$stats['terms'] = $this->getSearchTermsByCount($since, FALSE, FALSE, 10);
		$stats['sources'] = $this->getSearchSourcesByCount($since, 10);
		return $stats;
	}
	
	/**
	 * Gets the most popular search terms
	 * @param int since the timestamp to start at
	 * @param bool incSuggested TRUE to include searches which used suggestions
	 * @param bool incUsers TRUE to include searches by users
	 * @param int max the max number of terms to return
	 * @return array the terms and their counts
	 */
	public function getSearchTermsByCount($since, $incSuggested = FALSE, $incUsers = FALSE, $max = 10) {
		$sql = 'SELECT `query`, COUNT(`search_id`) as `count` FROM `'.KUMVA_DB_PREFIX.'searchrecord` 
				WHERE `timestamp` > '.$since.' AND `results` > 0 ';
		if (!$incSuggested)
			$sql .= 'AND `suggest` IS NULL ';
		if (!$incUsers)
			$sql .= 'AND `user_id` IS NULL ';		
				
		$sql .=	'GROUP BY `query` ORDER BY `count` DESC LIMIT 0, '.$max;
			
		return $this->database->rows($sql);
	}
	
	/**
	 * Gets the most popular search sources
	 * @param int since the timestamp to start at
	 * @param int max the max number of terms to return
	 * @return array the terms and their counts
	 */
	public function getSearchSourcesByCount($since, $max = 10) {
		$sql = 'SELECT `source`, COUNT(`search_id`) as `count` FROM `'.KUMVA_DB_PREFIX.'searchrecord` 
				WHERE `timestamp` > '.$since.'
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
	public function logSearch($pattern, $suggest, $iterations, $resultCount, $timeTaken, $source = NULL) {		
		
		$remoteAddr = $_SERVER['REMOTE_ADDR'];
		$user = Session::getCurrent()->getUser();
		$user_id = $user != NULL ? $user->getId() : NULL;
		
		$sql = 'INSERT INTO `'.KUMVA_DB_PREFIX.'searchrecord` VALUES(
			NULL,'
			.aka_prepsqlval($pattern).','
			.aka_prepsqlval($suggest).','
			.aka_prepsqlval($iterations).','
			.aka_prepsqlval($resultCount).',
			NOW(),'
			.aka_prepsqlval($timeTaken).','
			.aka_prepsqlval($remoteAddr).','
			.aka_prepsqlval($source).','
			.aka_prepsqlval($user_id).')';

				
		return $this->database->query($sql) !== FALSE;
	}
}

?>
