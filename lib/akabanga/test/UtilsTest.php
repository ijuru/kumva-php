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
 * Purpose: Unit tests for utils functions
 */
 
require_once "../akabanga.php";

/**
 * Test case for utils functions
 */
class UtilsTest extends PHPUnit_Framework_TestCase {
	
	public function test_getbit() {
		$val = 10; // 1010
		
		$this->assertEquals(false, aka_getbit($val, 0));
		$this->assertEquals(true, aka_getbit($val, 1));
		$this->assertEquals(false, aka_getbit($val, 2));
		$this->assertEquals(true, aka_getbit($val, 3));
	}
	
	public function test_setbit() {
		$val1 = 2; // 0010
		$val2 = 15; // 1111
		
		$this->assertEquals(3, aka_setbit($val1, 0, true));
		$this->assertEquals(11, aka_setbit($val2, 2, false));
	}
	
	public function test_strsplit() {
		$val1 = 'abcdef';
		$val2 = 'âbcdêf';
		
		$this->assertEquals(array('a', 'b', 'c', 'd', 'e', 'f'), aka_strsplit($val1, 1));
		$this->assertEquals(array('ab', 'cd', 'ef'), aka_strsplit($val1, 2));
		$this->assertEquals(array('â', 'b', 'c', 'd', 'ê', 'f'), aka_strsplit($val2, 1));
		$this->assertEquals(array('âb', 'cd', 'êf'), aka_strsplit($val2, 2));
	}
}

?>