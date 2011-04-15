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
 * Purpose: Unit tests for Entity class
 */
 
require_once "lib/akabanga/akabanga.php";

/**
 * Dummy entity class for testing
 */
class DummyEntity extends Entity {
	public function __construct($id) {
		$this->id = (int)$id;
	}
	
	public static function fromRow(&$row) {}
}

/**
 * Test case for Entity class
 */
class EntityTest extends PHPUnit_Framework_TestCase {
	
	public function test_getId() {
		$entity1 = new DummyEntity(12);
		$this->assertEquals(12, $entity1->getId());
	}
	
	public function test_setId() {
		$entity1 = new DummyEntity(12);
		$entity1->setId(56);
		$this->assertEquals(56, $entity1->getId());
	}
	
	public function test_isNew() {
		$entity1 = new DummyEntity(0);
		$entity2 = new DummyEntity(12);
		$this->assertTrue($entity1->isNew());
		$this->assertFalse($entity2->isNew());
	}
	
	public function test_equals() {
		$entity1 = new DummyEntity(12);
		$entity2 = new DummyEntity(34);
		$entity3 = new DummyEntity(12);
		$entity4 = NULL;
		
		$this->assertFalse($entity1->equals($entity2));
		$this->assertTrue($entity1->equals($entity3));
		$this->assertFalse($entity2->equals($entity3));
		$this->assertFalse($entity1->equals($entity4));
	}
	
	public function test_inArray() {
		$entity1 = new DummyEntity(12);
		$entity2 = new DummyEntity(34);
		$entity3 = new DummyEntity(56);
		$entities = array($entity1, $entity2);
		$this->assertTrue($entity1->inArray($entities));
		$this->assertFalse($entity3->inArray($entities));	
	}
	
	public function test_arrayToSet() {
		$entity1 = new DummyEntity(12);
		$entity2 = new DummyEntity(34);
		$array = array($entity1, $entity2, $entity1);
		$set = Entity::arrayToSet($array);
		$this->assertEquals(2, count($set));
	}
	
	public function test_union() {
		$entity1 = new DummyEntity(12);
		$entity2 = new DummyEntity(34);
		$entity3 = new DummyEntity(56);
		$entities1 = array($entity1, $entity2);
		$entities2 = array($entity2, $entity3);
		$union = Entity::union($entities1, $entities2);
		$this->assertEquals(3, count($union));
	}
}

?>