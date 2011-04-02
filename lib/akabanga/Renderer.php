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
 * Purpose: Renderer class
 */
 
/**
 * Class for rendering HTML controls
 */
class Renderer {
	/**
	 * Creates a text-field
	 * @param string name the name and id of the control
	 * @param string value the initial value the control
	 * @param string class the CSS class
	 */
	public function textField($name, $value, $class) {
		echo '<input type="text" id="'.$name.'" name="'.$name.'" value="'.htmlspecialchars($value).'" class="'.$class.'" />';
	}
	
	/**
	 * Creates a text-area
	 * @param string name the name and id of the control
	 * @param string value the initial value the control
	 * @param string class the CSS class
	 */
	public function textArea($name, $value, $class) {
		echo '<textarea rows="3" cols="40" id="'.$name.'" name="'.$name.'" class="'.$class.'">'.htmlspecialchars($value).'</textarea>';
	}
	
	/**
	 * Creates a checkbox
	 * @param string name the name and id of the control
	 * @param bool checked the initial checked state the control
	 * @param string class the CSS class
	 */
	public function checkbox($name, $checked, $class) {
		echo '<input type="checkbox" id="'.$name.'" name="'.$name.'" value="1" '.($checked ? 'checked="checked"' : '').' class="'.$class.'" />';
	}
	
	/**
	 * Creates a dropdown list
	 * @param string name the name and id of the control
	 * @param array options the list options
	 * @param bool keyAsValue TRUE if option array keys are used as the option value
	 * @param string value the initial value the control
	 * @param string class the CSS class
	 */
	public function dropdown($name, $value, $options, $keyAsValue, $class) {
		echo '<select id="'.$name.'" name="'.$name.'" class="'.$class.'">';
		foreach ($options as $optKey => $optLabel) {
			$ctlValue = $keyAsValue ? $optKey : $optLabel;
			echo '<option value="'.$ctlValue.'" '.($ctlValue == $value ? 'selected="selected"' : '').'>'.$optLabel.'</option>';
		}
		echo '</select>';
	}
	
	/**
	 * Creates a bound hidden field
	 * @param string name the name and id of the control
	 * @param string value the initial value the control
	 */
	public function hidden($name, $value) {
		echo '<input type="hidden" id="'.$name.'" name="'.$name.'" value="'.htmlspecialchars($value).'" />';
	}
	
	/**
	 * Creates a list of field errors
	 * @param array messages the error messages
	 * @param string class the CSS class
	 */
	public function errors($messages, $class) {
		echo '<span class="'.$class.'">'.implode('<br />', $messages).'</span>';
	}
	
	/**
	 * Creates a save button
	 * @param string class the CSS class
	 */
	public function saveButton($class) {
		echo '<input type="button" value="Save" onclick="$(\'#_action\').val(\'save\'); aka_submit(this)" class="'.$class.'" />';
	}
	
	/**
	 * Creates a cancel button
	 * @param string class the CSS class
	 */
	public function cancelButton($returnUrl, $class) {
		echo '<input type="button" value="Cancel" onclick="aka_goto(\''.$returnUrl.'\')" class="'.$class.'" />';
	}
}

?>
