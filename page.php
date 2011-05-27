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
 * Purpose: Generic page holder
 */
 
include_once 'inc/kumva.php';

$name = Request::getGetParam('name');

/**
 * Displays a link to the given query
 * @param string query the query
 * @param string text the text of the link (optional)
 */
function ku_query($query, $text = NULL) {
	$text = $text != NULL ? $text : $query;
	$query = str_replace(' ', '+', $query);
	echo '<a class="query link" href="index.php?q='.$query.'">'.$text.'</a>';
}

/**
 * Displays a link to the given page
 * @param string name the name of the page
 * @param string text the text of the link (optional)
 * @param string section the name of an section anchor
 */
function ku_page($name, $text = NULL, $section = NULL) {
	$text = $text != NULL ? $text : $name;
	$name = str_replace(' ', '_', $name);
	echo '<a class="link" href="page.php?name='.$name.($section != NULL ? ('#'.$section) : '').'">'.$text.'</a>';
}

Theme::header();

$page = Theme::getPage($name);

// Output page title and content
if ($page != NULL) {
	echo '<div class="info">';
	// Output breadcrumb trail for page hierarchy
	if ($page->getParent() != NULL)
		Templates::pageHierarchy($page);
		
	echo '<h2>'.$page->getTitle().'</h2>';
	echo '</div>';

	echo '<div id="page">';
	$page->doInclude();
	echo '</div>';
}
else
	echo '<div class="info">No such page!</div>';
	
Theme::footer();
?>
