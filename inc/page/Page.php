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
 * Purpose: Page class 
 */
 
class Page {
	private $name;
	private $title;
	private $file;
	private $parent;
	
	public function __construct($name, $title, $file, $parent) {
		$this->name = $name;
		$this->title = $title;
		$this->file = $file;
		$this->parent = $parent;
	}
	
	/**
	 * Gets the name
	 * @return string the name
	 */
	public function getName() {
		return $this->name;
	}
	
	/**
	 * Gets the title
	 * @return string the title
	 */
	public function getTitle() {
		return $this->title;
	}
	
	/**
	 * Gets the file
	 * @return string the file
	 */
	public function getFile() {
		return $this->file;
	}
	
	/**
	 * Gets the parent page
	 * @return Page the parent page
	 */
	public function getParent() {
		return $this->parent;
	}
	
	/**
	 * Gets the hierarchy
	 * @return array the hierarch of pages
	 */
	public function getHierarchy() {
		$pages = array();
		$current = $this->parent;
		while ($current != NULL) {
			$pages[] = $current;
			$current = $current->getParent();
		}
		return $pages;
	}
	
	/**
	 * Gets if the specified page is an ancestor 
	 * @param Page page the page to check
	 * @return bool TRUE if page is an ancestor
	 */
	public function isAncestor($page) {
		$ancestors = $this->getHierarchy();
		foreach ($ancestors as $ancestor) {
			if ($ancestor->getName() == $page->getName())
				return TRUE;
		}
		return FALSE;
	}
	
	/**
	 * Gets the page's children
	 * @return array the child pages
	 */
	public function getChildren() {
		return Theme::getPageChildren($this);
	}
	
	/**
	 * Does an include of the page HTML
	 */
	public function doInclude() {
		$path = KUMVA_DIR_THEME.'/pages/'.$this->file;
		if (file_exists($path)) {
			require_once $path;
			return TRUE;
		}
		return FALSE;
	}
}

?>
