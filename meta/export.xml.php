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

Session::requireUser();

// The export type
$incChanges = (bool)Request::getGetParam('changes', FALSE);

if ($incChanges)
	Session::requireRole(Role::ADMINISTRATOR);

header('Content-type: text/xml');
header('Content-Disposition: attachment; filename='.($incChanges ? 'entries' : 'definitions').'-'.date('Y-m-d').'.xml');

Xml::header();

if ($incChanges) {
	$entries = Dictionary::getEntryService()->getEntries();
	
	echo '<entries>';
	foreach ($entries as $entry)
		Xml::entry($entry);
	echo '</entries>';	
}
else {	
	$definitions = Dictionary::getEntryService()->getAcceptedRevisions();
	
	echo '<definitions>';
	foreach ($revisions as $revision)
		Xml::revision($revision, FALSE);
	echo '</definitions>';
}

Xml::footer();

?>
