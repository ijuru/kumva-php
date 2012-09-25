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
 * Purpose: Dictionary export script
 */
 
include_once '../inc/kumva.php';
	
$entries = Dictionary::getEntryService()->getEntries(true);

header('Content-type: text/csv');
header('Content-Disposition: attachment; filename=paldo-export-'.date('Y-m-d').'.csv');

// Output header row
echo "source_id, source_rev, word_class, noun_attrs, lemma, alt_form, meaning_en, meaning_flags\n";

foreach ($entries as $entry) {
	$revision = Dictionary::getEntryService()->getEntryRevision($entry, RevisionPreset::ACCEPTED);
	
	if (is_null($revision))
		continue;

	foreach ($revision->getMeanings() as $meaning) {

		$flags = get_meaning_flags_csv($meaning);
		$splitMeanings = aka_parsecsv($meaning->getMeaning());

		foreach ($splitMeanings as $splitMeaning) {

			// Trim the 'to ' prefix from verb meanings
			if (aka_startswith($splitMeaning, 'to ') && $revision->getWordClass() == 'v')
				$splitMeaning = substr($splitMeaning, 3);

			echo $entry->getId().',';
			echo $revision->getNumber().',';
			echo aka_prepcsvval($revision->getWordClass()).',';
			echo aka_prepcsvval(implode(',', $revision->getNounClasses())).',';
			echo aka_prepcsvval($revision->getPrefix().$revision->getLemma()).',';

			if ($revision->getWordClass() == 'n') {
				echo aka_prepcsvval(rw_plural($revision));
			}
			else if ($revision->getWordClass() == 'v') {
				echo aka_prepcsvval(rw_verbpasttense($revision));
			}
			echo ',';

			echo aka_prepcsvval($splitMeaning).',';
			echo aka_prepcsvval($flags);
			echo "\n";
		}
	}
}

/**
 * Gets the flags for the given meaning as a CSV list of flag names
 */
function get_meaning_flags_csv($meaning) {
	if ($meaning->getFlags() == 0)
		return '';

	$flagNames = array();
	foreach (Flags::values() as $flag) {
		if ($meaning->getFlag($flag))
			$flagNames[] = Flags::toLocalizedString($flag);
	}
				
	return implode(', ', $flagNames); 
}

?>
