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
 * Purpose: About page
 */
 
$gutanya_vowels = array('a','e','i','o','u');

function gutanya_isvowel($char) {
	global $gutanya_vowels;
	return in_array($char, $gutanya_vowels);
}

function gutunya_match($haystack, $needle, $position) {
	return (substr($haystack, $position, strlen($needle)) == $needle);
}

/**
 * The input text, e.g. ndagukunda
 */
class ParserInput {
	private $text;
	private $position = 0;
	
	public function __construct($text) {
		$this->text = $text;
	}
	
	public function getRemaining() {
		return substr($this->text, $this->position);
	}
	
	public function matches($unit) {
		echo 'ParserInput::matches('.$unit->getText().')[Remaining: '.$this->getRemaining().']<br/>';
	
		$unitText = $unit->getText();
		$unitLen = strlen($unitText);
	
		// Apply spelling rule from previous letter
		if ($this->position > 0) {
			$prevChar = $this->text[$this->position - 1];
			$rule = $unit->getRuleForCharBefore($prevChar);
			if ($rule != NULL) 
				$unitText = $rule;
		}
		
		// Apply spelling rule from next letter
		if ($this->position + $unitLen < strlen($this->text)) {
			$nextChar = $this->text[$this->position + $unitLen];
			$rule = $unit->getRuleForCharAfter($nextChar);
			if ($rule != NULL) {
				echo 'Found rule: '.$rule;
				$unitText = $rule;
			}
		}
		
		return gutunya_match($this->text, $unitText, $this->position) ? strlen($unitText) : FALSE;
	}
	
	/**
	 * Advances the input position by the given number of chars
	 * @param int the number of chars
	 */
	public function advance($chars) {
		$this->position += $chars;
	}
}

/**
 * Represents a recognizable value of a component
 */
class Unit {
	private $text;
	private $beforeRules;
	private $afterRules;
	private $labels;
	
	/**
	 * Constructs a unit
	 * @param string the unit text
	 * @param mixed a single string label, or an array of labels
	 */
	public function __construct($text, $beforeRules, $afterRules, $labels) {
		$this->text = $text;
		$this->beforeRules = $beforeRules;
		$this->afterRules = $afterRules;
		$this->labels = is_array($labels) ? $labels : array($labels);
	}
	
	/**
	 * Gets the unit text, e.g. ki, bi or mu
	 * @return string the unit text
	 */
	public function getText() {
		return $this->text;
	}
	
	public function getRuleForCharBefore($char) {
		return self::findRuleForChar($this->beforeRules, $char);
	}
	
	public function getRuleForCharAfter($char) {
		return self::findRuleForChar($this->afterRules, $char);
	}
	
	private static function findRuleForChar($set, $char) {
		if ($set != NULL) {
			foreach (array_keys($set) as $rule) {
				if (preg_match('/'.$rule.'/', $char) > 0)
					return $set[$rule];
			}
		}
		return NULL;
	}
	
	/**
	 * Gets the meaning labels
	 * @return array the meaning labels
	 */
	public function getLabels() {
		return $this->labels;
	}
}

/**
 * A component of a conjugation, e.g. the subject or object
 */
class Component {
	private $name;
	private $units;
	
	public function __construct($name, $units) {
		$this->name = $name;
		$this->units = $units;
	}
	
	public static function fixedUnit($name, $unit) {
		return new Component($name, array($unit));
	}
	
	public function match($input) {
		foreach ($this->units as $unit) {
			$chars = $input->matches($unit);
			if ($chars !== FALSE) {
				$input->advance($chars);
				return $unit;
			}
		}
		return NULL;
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function getUnits() {
		return $this->units;
	}
}

class UnitMatch {
	private $componentName;
	private $unit;
	
	public function __construct($componentName, $unit) {
		$this->componentName = $componentName;
		$this->unit = $unit;
	}
	
	public function getComponentName() {
		return $this->componentName;
	}
	
	public function getUnit() {
		return $this->unit;
	}
}

class Sequence {
	private $components;
	
	public function __construct($components) {
		$this->components = $components;
	}
	
	public function match($input, &$matches) {
		foreach ($this->components as $component) {
			$unit = $component->match($input);
			if ($unit != NULL) {
				$matches[] = new UnitMatch($component->getName(), $unit);	
			}
			else
				return FALSE;
		}
		return TRUE;
	}
}

class Model {
	private $sequences;
	
	public function __construct($sequences) {
		$this->sequences = $sequences;
	}
	
	public function getSequences() {
		return $this->sequences;
	}
}

define('GUTANYA_SUBJ_1PS', 'I');		// n
define('GUTANYA_SUBJ_2PS', 'you');		// u/w
define('GUTANYA_SUBJ_3PSA', 'he/she');	// a/y
define('GUTANYA_SUBJ_3PSI', 'it');		// u/w i/y ki/cy ri/ry ru/rw ka/k bu/bw ku/kw ha/h
define('GUTANYA_SUBJ_1PP', 'we'); 		// tu/tw/du
define('GUTANYA_SUBJ_2PP', 'you');		// mu/mw
define('GUTANYA_SUBJ_3PPA', 'they');	// ba/b
define('GUTANYA_SUBJ_3PPI', 'they');	// i/y zi/z bi/by a/y tu/tw/du

define('GUTANYA_OBJ_1PS', 'me');		// n
define('GUTANYA_OBJ_2PS', 'you');		// ku/gu
define('GUTANYA_OBJ_3PSA', 'him/her');	// mu/mw
define('GUTANYA_OBJ_3PSI', 'it');		// mu/mw yi/y ki/cy ri/ry ru/rw ka/k bu/bw ku/kw ha/h
define('GUTANYA_OBJ_1PP', 'us'); 		// tu/tw/du
define('GUTANYA_OBJ_2PP', 'you');		// ba/b
define('GUTANYA_OBJ_3PPA', 'them');		// ba/b
define('GUTANYA_OBJ_3PPI', 'them');		// yi/y zi/z bi/by ya/y tu/tw/du

define('GUTANYA_PREFIX_INFINITIVE', 'infinitive');
define('GUTANYA_TENSE_PRESENT', 'present');
define('GUTANYA_TENSE_NEARPAST', 'near-past');
define('GUTANYA_TENSE_FARPAST', 'far-past');

$subject = new Component('subject', array(
	new Unit('n', NULL, NULL, array(GUTANYA_SUBJ_1PS)),
	new Unit('u', NULL, NULL, array(GUTANYA_SUBJ_2PS, GUTANYA_SUBJ_3PSI)), 
	new Unit('a', NULL, NULL, array(GUTANYA_SUBJ_3PSA, GUTANYA_SUBJ_3PPI)), 
	new Unit('tu', NULL, NULL, array(GUTANYA_SUBJ_1PP, GUTANYA_SUBJ_3PPI)),
	new Unit('mu', NULL, NULL, array(GUTANYA_SUBJ_2PP)),
	new Unit('ba', NULL, NULL, array(GUTANYA_SUBJ_3PPA)),
	new Unit('i', NULL, NULL, array(GUTANYA_SUBJ_3PSI, GUTANYA_SUBJ_3PPI)),
	new Unit('zi', NULL, NULL, array(GUTANYA_SUBJ_3PPI)),
	new Unit('ki', NULL, NULL, array(GUTANYA_SUBJ_3PSI)),
	new Unit('bi', NULL, NULL, array(GUTANYA_SUBJ_3PPI)),
	new Unit('ri', NULL, NULL, array(GUTANYA_SUBJ_3PSI)),
	new Unit('ru', NULL, NULL, array(GUTANYA_SUBJ_3PSI)),
	new Unit('ka', NULL, NULL, array(GUTANYA_SUBJ_3PSI)),
	new Unit('bu', NULL, NULL, array(GUTANYA_SUBJ_3PSI)),
	new Unit('ku', NULL, NULL, array(GUTANYA_SUBJ_3PSI)),
	new Unit('ha', NULL, NULL, array(GUTANYA_SUBJ_3PSI))
));

$object = new Component('object', array(
	new Unit('n', NULL, NULL, array(GUTANYA_SUBJ_1PS)),
	new Unit('ku', NULL, NULL, array(GUTANYA_SUBJ_2PS, GUTANYA_SUBJ_3PSI)), 
	new Unit('mu', NULL, NULL, array(GUTANYA_SUBJ_3PSA, GUTANYA_SUBJ_3PSI)), 
	new Unit('tu', NULL, NULL, array(GUTANYA_SUBJ_1PP, GUTANYA_SUBJ_3PPI)),
	new Unit('ba', NULL, NULL, array(GUTANYA_SUBJ_2PP, GUTANYA_SUBJ_3PPA)),
	new Unit('yi', NULL, NULL, array(GUTANYA_SUBJ_3PSI, GUTANYA_SUBJ_3PPI)),
	new Unit('zi', NULL, NULL, array(GUTANYA_SUBJ_3PPI)),
	new Unit('ki', NULL, NULL, array(GUTANYA_SUBJ_3PSI)),
	new Unit('bi', NULL, NULL, array(GUTANYA_SUBJ_3PPI)),
	new Unit('ri', NULL, NULL, array(GUTANYA_SUBJ_3PSI)),
	new Unit('ru', NULL, NULL, array(GUTANYA_SUBJ_3PSI)),
	new Unit('ka', NULL, NULL, array(GUTANYA_SUBJ_3PSI)),
	new Unit('bu', NULL, NULL, array(GUTANYA_SUBJ_3PSI)),
	new Unit('ku', NULL, NULL, array(GUTANYA_SUBJ_3PSI)),
	new Unit('ha', NULL, NULL, array(GUTANYA_SUBJ_3PSI))
));

$inf_prefix = Component::fixedUnit('prefix', new Unit('ku', NULL, array('[aeiou]' => 'kw'), GUTANYA_PREFIX_INFINITIVE));
$tense_ra = Component::fixedUnit('tense', new Unit('ra', array('n' => 'da'), array('[eiou]' => 'r'), GUTANYA_TENSE_PRESENT)); // TODO: ra+a
$tense_a = Component::fixedUnit('tense', new Unit('a', NULL, NULL, GUTANYA_TENSE_NEARPAST));
$tense_ara = Component::fixedUnit('tense', new Unit('ara', NULL, NULL, GUTANYA_TENSE_FARPAST));

$kinyarwandaModel = new Model(array(
	new Sequence(array($inf_prefix)),						// Infinitive, e.g. ku-bona
	new Sequence(array($subject)),							// Habitual present, e.g. n-bona 
	new Sequence(array($subject, $tense_ra)),				// Immediate present, e.g. nda-bona
	new Sequence(array($subject, $tense_ra, $object)),		// Immediate present with object, e.g. ndamu-bona
	new Sequence(array($subject, $tense_a)),				// Near past, e.g. na-bonye
	new Sequence(array($subject, $tense_a, $object)),		// Near past with object, e.g. namu-bonye
	new Sequence(array($subject, $tense_ara)),				// Far past, e.g. nara-bonye
	new Sequence(array($subject, $tense_ara, $object))		// Far past with object, e.g. naramu-bonye
));

class SequenceMatch {
	private $unitMatches;
	private $remainder;
	
	public function __construct($unitMatches, $remainder) {
		$this->unitMatches = $unitMatches;
		$this->remainder = $remainder;
	}
	
	public function getUnitMatches() {
		return $this->unitMatches;
	}
	
	public function getRemainder() {
		return $this->remainder;
	}
}

class Parser {
	private $model;
	
	public function __construct($model) {
		$this->model = $model;
	}
	
	public static function getKinyarwanda() {
		global $kinyarwandaModel;
		return new Parser($kinyarwandaModel);
	} 

	public function parse($text) {
		$matches = array();
		
		foreach ($this->model->getSequences() as $sequence) {
			$input = new ParserInput($text);
			$unitMatches = array();
			if ($sequence->match($input, $unitMatches)) 
				$matches[] = new SequenceMatch($unitMatches, $input->getRemaining());
		}
		
		return $matches;
	}
}

?>
