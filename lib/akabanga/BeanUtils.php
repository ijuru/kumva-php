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
 * Purpose: BeanUtils class
 */
 
/**
 * Utility class for working with bean properties
 */
class BeanUtils {
	/**
	 * Gets the specified bean property on the given object
	 * @param object object the object
	 * @param string name the name of the bean property
	 * @param bool asBool whether to get property with an "is_" method
	 * @return object the property value
	 */
	public static function getProperty($object, $name, $asBool = FALSE) {
		$method = ($asBool ? 'is' : 'get').ucfirst($name);
		return $object->{$method}();
	} 
	
	/**
	 * Sets the specified bean property on the given object
	 * @param object object the object
	 * @param string name the name of the bean property
	 * @param object value the property value
	 */
	public static function setProperty($object, $name, $value) {
		$method = 'set'.ucfirst($name);
		$object->{$method}($value);
	}
}

?>
