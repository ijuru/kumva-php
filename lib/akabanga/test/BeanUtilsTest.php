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
 * Purpose: Unit tests for BeanUtils class
 */
 
require_once "../akabanga.php";

/**
 * Test bean class
 */
class TestBean  {
	private $name;
	private $value;
	
	public function __construct($name, $value) {
		$this->name = $name;
		$this->value = (int)$value;
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function setName($name) {
		$this->name = $name;
	}
	
	public function getValue() {
		return $this->value;
	}
	
	public function setValue($value) {
		$this->value = (int)$value;
	}
}

/**
 * Test case for BeanUtils class
 */
class BeanUtilsTest extends PHPUnit_Framework_TestCase {
	
	public function test_getProperty() {
		$bean = new TestBean('first', 12);
		$this->assertEquals('first', BeanUtils::getProperty($bean, 'name'));
		$this->assertEquals(12, BeanUtils::getProperty($bean, 'value'));
	}
	
	public function test_setProperty() {
		$bean = new TestBean('first', 12);
		BeanUtils::setProperty($bean, 'name', 'second');
		BeanUtils::setProperty($bean, 'value', 34);
		
		$this->assertEquals('second', $bean->getName());
		$this->assertEquals(34, $bean->getValue());
	}
}

?>