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
 * Purpose: Report service class
 */

$reports = array();

/**
 * Registers a function based report
 * @param string name the report name
 * @param string title the report title
 * @param string function the function name
 */
function kumva_registerreport($name, $title, $function) {
	global $reports;
	$reports[] = new Report($name, $title, $function); 	
}

kumva_registerreport('no-wordclass', 'Entries with no wordclass', 'kumva_report_nowordclass');
kumva_registerreport('no-pronunciation', 'Entries with no pronunciation', 'kumva_report_nopronunciation');
kumva_registerreport('no-tags', 'Entries with no tags (i.e. never searchable)', 'kumva_report_notags');
kumva_registerreport('no-examples', 'Entries with no examples', 'kumva_report_noexamples');
kumva_registerreport('duplicate-entries', 'Possible duplicate entries', 'kumva_report_duplicateentries');
kumva_registerreport('top-searches', 'Most common search terms (last month)', 'kumva_report_topsearches');

/**
 * Gets entries without a wordclass
 * @param Paging paging the paging object
 */
function kumva_report_nowordclass($paging) {
	$sql = "SELECT SQL_CALC_FOUND_ROWS d.definition_id as `#Definition`
			FROM `".KUMVA_DB_PREFIX."definition` d 
			WHERE d.revisionstatus = 1 AND (d.wordclass IS NULL OR d.wordclass = '')";
	
	return Dictionary::getReportService()->getResultFromSQL($sql, $paging);
}

/**
 * Gets entries without a pronunciation
 * @param Paging paging the paging object
 */
function kumva_report_nopronunciation($paging) {
	$sql = "SELECT SQL_CALC_FOUND_ROWS d.definition_id as `#Definition`
			FROM `".KUMVA_DB_PREFIX."definition` d 
			WHERE d.revisionstatus = 1 AND (d.pronunciation IS NULL OR d.pronunciation = '')";
	
	return Dictionary::getReportService()->getResultFromSQL($sql, $paging);
}

/**
 * Gets entries without examples
 * @param Paging paging the paging object
 */
function kumva_report_noexamples($paging) {
	$sql = "SELECT SQL_CALC_FOUND_ROWS d.definition_id as `#Definition`
			FROM `".KUMVA_DB_PREFIX."definition` d
			WHERE d.revisionstatus = 1 AND d.definition_id NOT IN (
				SELECT DISTINCT definition_id FROM `".KUMVA_DB_PREFIX."example`
			)";
	
	return Dictionary::getReportService()->getResultFromSQL($sql, $paging);
}

/**
 * Gets entries without tags
 * @param Paging paging the paging object
 */
function kumva_report_notags($paging) {
	$sql = "SELECT SQL_CALC_FOUND_ROWS d.definition_id as `#Definition`
			FROM `".KUMVA_DB_PREFIX."definition` d
			WHERE d.revisionstatus = 1 AND d.definition_id NOT IN (
				SELECT DISTINCT definition_id FROM `".KUMVA_DB_PREFIX."definition_tag`
			)";
	
	return Dictionary::getReportService()->getResultFromSQL($sql, $paging);
}

/**
 * Gets possible duplicate entries
 * @param Paging paging the paging object
 */
function kumva_report_duplicateentries($paging) {
	$sql = "SELECT SQL_CALC_FOUND_ROWS
				CONCAT(COALESCE(d.prefix, ''), d.lemma) as `?Query`, 
				COUNT(*) as `Count`,
				CONCAT(COALESCE(d.prefix, ''), d.lemma, '|', COALESCE(d.wordclass, '')) as `_entry` 
			FROM `".KUMVA_DB_PREFIX."definition` d
			WHERE d.revisionstatus = 1 
			GROUP BY `_entry`
			HAVING `Count` > 1";
	
	return Dictionary::getReportService()->getResultFromSQL($sql, $paging);
}

/**
 * Gets possible duplicate entries
 * @param Paging paging the paging object
 */
function kumva_report_topsearches($paging) {
	$since = time() - 60 * 60 * 24 * 30;
	$sql = 'SELECT SQL_CALC_FOUND_ROWS
				`query` as `?Query`, 
				COUNT(`search_id`) as `Count` 
			FROM `'.KUMVA_DB_PREFIX.'searchrecord` 
			WHERE `timestamp` > '.$since.' AND `results` > 0 AND `suggest` IS NULL		
			GROUP BY `?Query` ORDER BY `Count` DESC ';
	
	return Dictionary::getReportService()->getResultFromSQL($sql, $paging);
}

/**
 * Report functions
 */
class ReportService extends Service {
	/**
	 * Gets all the reports
	 * @return array the reports
	 */
	public function getReports() {
		global $reports;
		return $reports;
	}
	
	/**
	 * Gets the named report
	 * @param string the report name
	 * @return Report the report
	 */
	public function getReportByName($name) {
		global $reports;
		foreach ($reports as $report) {
			if ($report->getName() == $name)
				return $report;
		}
		return NULL;
	}
	
	/**
	 * Generates a report result from the given SQL
	 * @param string sql the SQL
	 * @param Paging paging the paging object
	 */
	public function getResultFromSQL($sql, $paging) {
		$start = microtime(TRUE);
		$res = $this->database->query($sql, $paging);
		$fields = $this->database->fields($res);
		$rows = $this->database->rows($res);
		$time = microtime(TRUE) - $start;
		
		return new ReportResults($fields, $rows, $time); 
	}
}

?>
