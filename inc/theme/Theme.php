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
 * Purpose: Theme class 
 */	
 
/**
 * Utility class for themed components
 */
class Theme {
	private static $pages = array();
	
	/**
	 * Outputs the page header
	 */
	public static function header() {
		include_once KUMVA_DIR_THEME.'/header.php';
	}
	
	/**
	 * Outputs the page footer
	 */
	public static function footer() {
		include_once KUMVA_DIR_THEME.'/footer.php';
	}
	
	/**
	 * Gets the name of the noun class
	 * @param string the noun class identifier
	 * @return string the noun class name
	 */
	public static function getNounClassName($cls) {
		if (function_exists('kumva_theme_getnounclassname'))
			return kumva_theme_getnounclassname($cls);
		else
			return NULL;
	}
	
	/**
	 * Creates a new theme page
	 * @param string name the page name
	 * @param string title the title
	 * @param string file the file	
	 * @param string parentName the parent page name 
	 */
	public static function createPage($name, $title, $file, $parentName) {
		$parent = ($parentName != NULL) ?  self::$pages[$parentName] : NULL;
		$page = new Page($name, $title, $file, $parent);
		self::$pages[$page->getName()] = $page;
		return $page;
	}
	
	/**
	 * Gets the theme page with the given name
	 * @param name the page name, e.g. 'reference'
	 * @return Page the page
	 */
	public static function getPage($name) {
		return isset(self::$pages[$name]) ? self::$pages[$name] : NULL;
	}
	
	/**
	 * Get all pages for the current theme
	 * @return array the pages
	 */
	public static function getPages() {
		return self::$pages;
	}
	
	/**
	 * Gets the name of the page to link the given word class to. For example,
	 * word class 'adj' which could be mapped to a page called 'adjectives'
	 * @param string the word class
	 * @return string the page name
	 */
	public static function getPageForWordClass($cls) {
		if (function_exists('kumva_theme_getpageforwordclass'))
			return kumva_theme_getpageforwordclass($cls);
		else
			return NULL;
	}
	
	/**
	 * Gets the children of the given page
	 * @param Page page the page
	 * @return array the child pages
	 */
	public static function getPageChildren($page) {
		$children = array();
		foreach (self::$pages as $p) {
			if ($p->getParent() == $page)
				$children[] = $p;
		}
		return $children;
	}
}

?>
