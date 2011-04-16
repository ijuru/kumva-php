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
 * Purpose: Unit tests for Enum class
 */
 
require_once "../akabanga.php";

/**
 * Test enum class
 */
class TestEnum extends Enum {
	const FIRST = 0;
	const SECOND = 1;
	const THIRD = 2;

	protected static $strings = array('first', 'second', 'third');
	protected static $localized = array('premier', 'deuxième', 'troisième');
}

/**
 * Test case for Enum class
 */
class EnumTest extends PHPUnit_Framework_TestCase {
	
	public function test_values() {
		$values = TestEnum::values();
		$this->assertEquals(3, count($values));
		$this->assertEquals(TestEnum::FIRST, $values[0]);
		$this->assertEquals(TestEnum::SECOND, $values[1]);
		$this->assertEquals(TestEnum::THIRD, $values[2]);
	}
	
	public function test_toString() {
		$this->assertEquals('first', TestEnum::toString(TestEnum::FIRST));
		$this->assertEquals('second', TestEnum::toString(TestEnum::SECOND));
		$this->assertEquals('third', TestEnum::toString(TestEnum::THIRD));
	}
	
	public function test_toLocalizedString() {
		$this->assertEquals('premier', TestEnum::toLocalizedString(TestEnum::FIRST));
		$this->assertEquals('deuxième', TestEnum::toLocalizedString(TestEnum::SECOND));
		$this->assertEquals('troisième', TestEnum::toLocalizedString(TestEnum::THIRD));
	}
	
	public function test_parseString() {
		$this->assertEquals(TestEnum::FIRST, TestEnum::parseString('first'));
		$this->assertEquals(TestEnum::SECOND, TestEnum::parseString('second'));
		$this->assertEquals(TestEnum::THIRD, TestEnum::parseString('third'));
	}
	
	public function test_parseCSVString() {
		$values = TestEnum::parseCSVString('third,first, second,');
		$this->assertEquals(3, count($values));
		$this->assertEquals(TestEnum::THIRD, $values[0]);
		$this->assertEquals(TestEnum::FIRST, $values[1]);
		$this->assertEquals(TestEnum::SECOND, $values[2]);
	}
	
	public function test_makeCSVString() {
		$csv = TestEnum::makeCSVString(array(TestEnum::THIRD, TestEnum::FIRST, TestEnum::SECOND));
		$this->assertEquals('third, first, second', $csv);
	}
	
	public function test_fromBits() {
		$values = TestEnum::fromBits(0);  // 00000000
		$this->assertEquals(0, count($values));

		$values = TestEnum::fromBits(5);  // 00000101
		$this->assertEquals(2, count($values));
		$this->assertEquals(TestEnum::FIRST, $values[0]);
		$this->assertEquals(TestEnum::THIRD, $values[1]);
	}
	
	public function test_toBits() {
		$bits = TestEnum::toBits(array());
		$this->assertEquals(0, $bits);	// 00000000
		
		$bits = TestEnum::toBits(array(TestEnum::FIRST, TestEnum::THIRD));
		$this->assertEquals(5, $bits);	// 00000101

		$bits = TestEnum::toBits(array(TestEnum::THIRD, TestEnum::FIRST, TestEnum::SECOND));
		$this->assertEquals(7, $bits);	// 00000111
	}
}

?>