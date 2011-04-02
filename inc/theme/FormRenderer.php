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
 * Purpose: FormRenderer class
 */
 
/**
 * Class for rendering form controls
 */
class FormRenderer extends Renderer {	
	/**
	 * Creates a save button
	 */
	public function saveButton($class) {
		Templates::button('save', "$('#_action').val('save'); aka_submit(this);", KU_STR_SAVE);
	}
	
	/**
	 * Creates a cancel button
	 */
	public function cancelButton($returnUrl, $class) {
		Templates::buttonLink('cancel', $returnUrl, KU_STR_CANCEL);
	}
}

?>
