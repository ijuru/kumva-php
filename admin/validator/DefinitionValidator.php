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
 * Purpose: Definition validator class
 */

/**
 * Validator for definition objects
 */
class DefinitionValidator extends Validator {
	public function validate($definition, $errors) {
		if (strlen($definition->getLemma()) == 0)
			$errors->addForProperty('lemma', KU_MSG_ERROREMPTY);
		if (count($definition->getMeanings()) == 0)
			$errors->addForProperty('meanings', KU_MSG_ERROREMPTY);
		
		foreach ($definition->getNounClasses() as $nounClass) {
			if ($nounClass < 1) {
				$errors->addForProperty('nounClasses', KU_MSG_ERRORNOUNCLASS);
				break;
			}
		}
		
		foreach ($definition->getMeanings() as $meaning) {
			if (strlen($meaning->getMeaning()) == 0) {
				$errors->addForProperty('meanings', KU_MSG_ERROREMPTY);
				break;
			}
		}
		
		foreach ($definition->getExamples() as $example) {
			if (strlen($example->getForm()) == 0 || strlen($example->getMeaning()) == 0) {
				$errors->addForProperty('examples', KU_MSG_ERROREMPTY);
				break;
			}
		}
	}
}

?>