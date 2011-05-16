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
 * Purpose: Markup class
 */
 
/**
 * Utility class for marking up comments and pages
 */
class Markup {
	/**
	 * Replaces all URLs in the given text with HTML hyperlinks
	 * @param string text the text to markup
	 * @param string the marked up HTML
	 */
	public static function urlsToLinks($text) {
		$regex = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";

		return preg_replace($regex, "<a href=\"\\0\" target=\"_blank\">\\0</a>", $text);
	}
}
?>
