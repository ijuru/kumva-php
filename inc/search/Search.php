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
 * Purpose: Search class
 */
 
/**
 * Enumeration of search types
 */
class SearchType extends Enum {
	const FORM = 0;
	const STEM = 1;
	const SOUND = 2;
	
	protected static $strings = array('form', 'stem', 'sound');
}
 
/**
 * Class to represent a full dictionary search
 */
class Search {
	private $dictionary;
	private $query;
	private $paging;
	private $incProposals;
	private $defaultOrderBy = OrderBy::ENTRY;
	private $results = NULL;
	private $iteration;
	private $suggestion = NULL;
	private $time = 0;
	
	// Minimum length of query patterns on which to perform a smart search
	const MIN_SMART_QUERY_LEN = 4;
	
	/**
	 * Creates a search object
	 * @param string string the search string
	 * @param bool incProposals TRUE if proposal definitions should be included, else FALSE
	 * @param Paging paging the paging object
	 */
	public function __construct($string, $incProposals, $paging) {
		$this->query = Query::parse($string);
		$this->incProposals = (bool)$incProposals;
		$this->paging = $paging;
		
		$this->defaultOrderBy = $this->query->getPattern() ? OrderBy::RELEVANCE : OrderBy::ENTRY;
	}
	
	/**
	 * Runs the search
	 * @param string source the source of this query, e.g. 'os' for opensearch plugin
	 * @return array the array of definitions found
	 */
	public function run($source = NULL) {
		$start = microtime(TRUE);
		
		$initSearchType = $this->query->isPartialMatch() ? SearchType::FORM : SearchType::STEM;
		$orderBy = ($this->query->getOrderBy() !== NULL) ? $this->query->getOrderBy() : $this->defaultOrderBy;
		
		$this->results = Dictionary::getSearchService()->search($this->query, $initSearchType, $this->incProposals, $orderBy, $this->paging);
		$this->iteration = 1;
		
		// Only do smart search if this is not a partial match, we didn't find anything yet, and the pattern is long enough
		$doSmartSearch = !$this->query->isPartialMatch() && ($this->results !== FALSE) && (strlen($this->query->getPattern()) >= self::MIN_SMART_QUERY_LEN);
		
		if ($doSmartSearch && !$this->hasResults()) {
			// Do sounds-like search
			$this->results = Dictionary::getSearchService()->search($this->query, SearchType::SOUND, $this->incProposals, $orderBy, $this->paging);
			$this->iteration = 2;
			
			// If that fails to find results then perform suggestions search
			if (!$this->hasResults()) {
			
				// Clone query object to create suggestion query
				$this->suggestion = clone $this->query;
				
				// Create suggestions based on query language
				$suggestionsLang = $this->query->getLang() ? $this->query->getLang() : KUMVA_LANG_DEFS; 
				$suggestions = Lexical::suggestions($suggestionsLang, $this->query->getPattern());

				foreach ($suggestions as $suggestion) {
					$this->suggestion->setPattern($suggestion);
						
					$this->results = Dictionary::getSearchService()->search($this->suggestion, SearchType::STEM, $this->incProposals, $orderBy, $this->paging);
					$this->iteration = 3;
					
					// If results found quit searching
					if ($this->hasResults())
						break;
				}
			}
		}
		
		$this->time = (int)((microtime(true) - $start) * 1000);
		
		if (!Request::isRobot() && $source != NULL)
			Dictionary::getSearchService()->logSearch($this->query->getRawQuery(), $this->getSuggestionPattern(), $this->iteration, $this->getTotalCount(), $this->time, $source);
	}
	
	/**
	 * Gets whether search returned any results
	 */
	public function hasResults() {
		return $this->results != null && count($this->results) > 0;
	}
	
	/**
	 * Gets the results as array of definitions
	 */
	public function getResults() {
		return $this->results;
	}
	
	/**
	 * Gets the number of results
	 * @return int the total
	 */
	public function getResultCount() {
		return count($this->results);
	}
	
	/**
	 * Gets the total number of available results
	  * @return int the total
	 */
	public function getTotalCount() {
		return ($this->paging != NULL) ? $this->paging->getTotal() : count($this->results);
	}
	
	/**
	 * Gets the query
	 */
	public function getQuery() {
		return $this->query;
	}
	
	/**
	 * Sets the default order by
	 * @param int orderBy the default order by
	 */
	public function setDefaultOrderBy($orderBy) {
		return $this->defaultOrderBy = $orderBy;
	}
	
	/**
	 * Gets if search used a suggestion
	 */
	public function isBySuggestion() {
		return $this->iteration > 1;
	}
	
	/**
	 * Gets if search used a "sounds-like" suggestion
	 */
	public function isBySoundSuggestion() {
		return $this->iteration == 2;
	}
	
	/**
	 * Gets the suggested query pattern from a smart search
	 */
	public function getSuggestionPattern() {
		return ($this->suggestion != NULL) ? $this->suggestion->getPattern() : NULL;
	} 
	
	/**
	 * Gets the time taken for the search
	 */
	public function getTime() {
		return $this->time;
	}
}

?>
