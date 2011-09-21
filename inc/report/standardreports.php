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
 * Purpose: Standard reports
 *
 * A report is a SQL result set that can be displayed in an HTML table or
 * exported to a CSV file. Special characters are prefixed onto column
 * names of the result set to indicate how that column should be displayed.
 * These special characters are as follows:
 *   _  Column should not be displayed or exported
 *   ?  Column should be displayed as a query link (in HTML)
 *   #  Column contains entry ids and should be displayed as links to entries
 *   >  Column should be displayed as a query link where URL is found in column
 *      with same name but _ prefix
 */

kumva_registerreport('no-wordclass', 'Entries with no wordclass', 'kumva_report_nowordclass');
kumva_registerreport('no-pronunciation', 'Entries with no pronunciation', 'kumva_report_nopronunciation');
kumva_registerreport('no-tags', 'Entries with no tags (i.e. never searchable)', 'kumva_report_notags');
kumva_registerreport('no-examples', 'Entries with no examples', 'kumva_report_noexamples');
kumva_registerreport('duplicate-entries', 'Possible duplicate entries', 'kumva_report_duplicateentries');
kumva_registerreport('top-searches', 'Most common search terms (last month)', 'kumva_report_topsearches');
kumva_registerreport('top-searchmisses', 'Most common missed search terms (last month)', 'kumva_report_topsearchmisses');
kumva_registerreport('tags-categories', 'Used category tags', 'kumva_report_tagscategories');

/**
 * Gets entries without a wordclass
 * @param Paging paging the paging object
 */
function kumva_report_nowordclass($paging) {
	$sql = "SELECT SQL_CALC_FOUND_ROWS r.revision_id as `#Definition`
			FROM `".KUMVA_DB_PREFIX."revision` r 
			WHERE r.status = 1 AND (r.wordclass IS NULL OR r.wordclass = '')";
	
	return Dictionary::getReportService()->getResultFromSQL($sql, $paging);
}

/**
 * Gets entries without a pronunciation
 * @param Paging paging the paging object
 */
function kumva_report_nopronunciation($paging) {
	$sql = "SELECT SQL_CALC_FOUND_ROWS r.revision_id as `#Definition`
			FROM `".KUMVA_DB_PREFIX."revision` r 
			WHERE r.status = 1 AND (r.pronunciation IS NULL OR r.pronunciation = '')";
	
	return Dictionary::getReportService()->getResultFromSQL($sql, $paging);
}

/**
 * Gets entries without examples
 * @param Paging paging the paging object
 */
function kumva_report_noexamples($paging) {
	$sql = "SELECT SQL_CALC_FOUND_ROWS r.revision_id as `#Definition`
			FROM `".KUMVA_DB_PREFIX."revision` r
			WHERE r.status = 1 AND r.revision_id NOT IN (
				SELECT DISTINCT revision_id FROM `".KUMVA_DB_PREFIX."example`
			)";
	
	return Dictionary::getReportService()->getResultFromSQL($sql, $paging);
}

/**
 * Gets entries without tags
 * @param Paging paging the paging object
 */
function kumva_report_notags($paging) {
	$sql = "SELECT SQL_CALC_FOUND_ROWS r.revision_id as `#Definition`
			FROM `".KUMVA_DB_PREFIX."revision` r
			WHERE r.status = 1 AND r.revision_id NOT IN (
				SELECT DISTINCT revision_id FROM `".KUMVA_DB_PREFIX."revision_tag`
			)";
	
	return Dictionary::getReportService()->getResultFromSQL($sql, $paging);
}

/**
 * Gets possible duplicate entries
 * @param Paging paging the paging object
 */
function kumva_report_duplicateentries($paging) {
	$sql = "SELECT SQL_CALC_FOUND_ROWS
				CONCAT(COALESCE(r.prefix, ''), r.lemma) as `?Query`, 
				COUNT(*) as `Count`,
				CONCAT(COALESCE(r.prefix, ''), r.lemma, '|', COALESCE(r.wordclass, ''), '|', COALESCE(r.pronunciation, '')) as `_entry` 
			FROM `".KUMVA_DB_PREFIX."revision` r
			WHERE r.status = 1 
			GROUP BY `_entry` COLLATE utf8_bin
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
			WHERE UNIX_TIMESTAMP(`timestamp`) > '.$since.' AND `results` > 0 AND `suggest` IS NULL		
			GROUP BY `?Query` ORDER BY `Count` DESC';
	
	return Dictionary::getReportService()->getResultFromSQL($sql, $paging);
}

/**
 * Gets possible duplicate entries
 * @param Paging paging the paging object
 */
function kumva_report_topsearchmisses($paging) {
	$since = time() - 60 * 60 * 24 * 30;
	$sql = 'SELECT SQL_CALC_FOUND_ROWS
				`query` as `?Query`, 
				COUNT(`search_id`) as `Count` 
			FROM `'.KUMVA_DB_PREFIX.'searchrecord`
			WHERE UNIX_TIMESTAMP(`timestamp`) > '.$since.' AND `results` = 0		
			GROUP BY `?Query` ORDER BY `Count` DESC';
	
	return Dictionary::getReportService()->getResultFromSQL($sql, $paging);
}

/**
 * Gets possible duplicate entries
 * @param Paging paging the paging object
 */
function kumva_report_tagscategories($paging) {
	$sql = 'SELECT 
				t.text as `>Tag`, 
				CONCAT(\'match:category \', t.text) as `_Tag`,
				COUNT(*) as `Entries`
			FROM `'.KUMVA_DB_PREFIX.'tag` t 
			INNER JOIN `'.KUMVA_DB_PREFIX.'revision_tag` rt ON rt.tag_id = t.tag_id
			INNER JOIN `'.KUMVA_DB_PREFIX.'revision` r ON r.revision_id = rt.revision_id
			WHERE rt.relationship_id = '.Relationship::CATEGORY.' AND r.status = 1
			GROUP BY t.tag_id';
	
	return Dictionary::getReportService()->getResultFromSQL($sql, $paging);
}

?>
