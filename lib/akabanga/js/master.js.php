<?php
/**
 * This file is part of Akabanga.
 *
 * Akabanga is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Akabanga is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Akabanga.  If not, see <http://www.gnu.org/licenses/>.
 *
 * Copyright Rowan Seymour 2010
 * 
 * Purpose: Master javascript include file
 */

include_once '../akabanga.php';

header('Content-type: text/javascript');

echo "/* Akabanga Master Javascript file */\n";

foreach($AKABANGA_JSINCLUDES as $jsInclude)
	echo file_get_contents($jsInclude);

?>

/**
 * MD5 encrypts the given string value
 */
function aka_md5(value) {
	return hex_md5(value);
}

/**
 * Submits the form that contains the given element
 */
function aka_submit(element) {
	$(element).closest('form').submit();
}

/**
 * Redirects client to the given URL
 */
function aka_goto(url) {
	window.location.href = url;
}

