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
 * Purpose: Paging utility class and functions
 */
 
class Paging {
	private $param;
	private $size;
	private $start;
	private $total = 0;
	
	/**
	 * Constructs a paging object
	 * @param string param the parameter name used for offset
	 * @param int size the results per page
	 */
	public function __construct($param, $size) {
		$this->param = $param;
		$this->size = $size;
		$this->start = Request::getGetParam($param, 0);
	}
	
	/**
	 * Gets the page size
	 * @return int the page size
	 */
	public function getSize() {
		return $this->size;
	}
	
	/**
	 * Gets the page start offset
	 * @return int the total results
	 */
	public function getStart() {
		return $this->start;
	}
	
	/**
	 * Gets the total results available
	 * @return int the total results
	 */
	public function getTotal() {
		return $this->total;
	}
	
	/**
	 * Sets the total results available
	 * @param int total the total results
	 */
	public function setTotal($total) {
		$this->total = $total;
	}
	
	/**
	 * Gets the total number of pages available
	 * @return int the total pages
	 */
	public function getTotalPages() {
		return (int)ceil($this->total / (float)$this->size);
	}
	
	/**
	 * Gets the URL of the first page based on the current URL
	 * @return string the URL
	 */
	public function getUrlFirst() {
		$params = $_GET;
		$params[$this->param] = 0;
		return aka_buildurl('', $params);
	}
	
	/**
	 * Gets the URL of the previous page based on the current URL
	 * @return string the URL
	 */
	public function getUrlPrevious() {
		$params = $_GET;
		$params[$this->param] = max($this->start - $this->size, 0);
		return aka_buildurl('', $params);
	}
	
	/**
	 * Gets the URL of the next page based on the current URL
	 * @return string the URL
	 */
	public function getUrlNext() {
		$params = $_GET;
		$params[$this->param] = min($this->start + $this->size, $this->total);
		return aka_buildurl('', $params);
	}
	
	/**
	 * Gets the URL of the last page based on the current URL
	 * @return string the URL
	 */
	public function getUrlLast() {
		$params = $_GET;
		$params[$this->param] = $this->total - $this->size;
		return aka_buildurl('', $params);
	}
}

?>
