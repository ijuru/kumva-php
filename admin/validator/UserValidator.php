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
 * Purpose: User validator class
 */

/**
 * Validator for user objects
 */
class UserValidator extends Validator {
	public function validate($user, $errors) {
		if (!$user->getName())
			$errors->addForProperty('name', KU_MSG_ERROREMPTY);
			
		if (!$user->getLogin())
			$errors->addForProperty('login', KU_MSG_ERROREMPTY);
		elseif (!preg_match("/^[A-Za-z0-9]+$/", $user->getLogin()))
			$errors->addForProperty('login', KU_MSG_ERRORALPHANUMERIC);
			
		if (!$user->getEmail())
			$errors->addForProperty('email', KU_MSG_ERROREMPTY);
		elseif (!preg_match('/^[^@]+@[a-zA-Z0-9._-]+\.[a-zA-Z]+$/', $user->getEmail()))
			$errors->addForProperty('email', KU_MSG_ERROREMAILFORMAT);
	}
}

?>