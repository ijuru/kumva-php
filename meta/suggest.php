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
 * Purpose: AJAX auto suggest handler
 */
 
include_once '../inc/kumva.php';

header("Content-type: text/javascript");

$term = Request::getGetParam('term', '');
$format = Request::getGetParam('format', 'jquery');
$max = (int)Request::getGetParam('max', 10);
	
if (strlen($term) < 3)
	return;
	
$suggestions = Dictionary::getTagService()->getTagSuggestions($term, $max);

echo '[';

if ($format == 'jquery') {
	for ($s = 0; $s < count($suggestions); $s++) {
		if ($s > 0) echo ',';
		echo '{"lang":"'.$suggestions[$s]->getLang().'","label":"'.$suggestions[$s]->getText().'","value":"'.$suggestions[$s]->getText().'"}';
	}
}
elseif ($format == 'opensearch') {
	echo '"'.$term.'", [';
	for ($s = 0; $s < count($suggestions); $s++) {
		if ($s > 0) echo ',';
		echo '"'.$suggestions[$s]->getText().'"';
	}
	echo "]";
}

echo ']';

?>
