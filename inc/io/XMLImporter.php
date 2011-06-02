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
 * Purpose: XMLImporter class
 *
 */

/**
 * XML importer
 */
class XMLImporter extends Importer {
	private $idMap = array();

	/**
	 * Loads definitions from an XML file
	 * @param string path path of the XML file
	 * @param bool TRUE if definitions are verified
	 * @return bool TRUE if successful, else FALSE
	 */
	public function load($path, $unverified) {	
		if (($xml = simplexml_load_file($path)) === FALSE)
			return FALSE;
		
		// Iterate through each definition element
		foreach ($xml->definitions->definition as $definitionXml) {
			if ($this->loadDefinition($definitionXml) !== FALSE)
				$this->defCount++;
		}
		
		return TRUE;	
	}
	
	/**
	 * Loads a definition from an XML node
	 * @param SimpleXmlElement xml the XML node
	 * @return Definition the definition
	 */
	private function loadDefinition($xml) {
		$attributes = $xml->attributes();
		
		$id = (int)$attributes['id'];
		$wordClass = (string)$attributes['wordclass'];
		$nounClasses = aka_parsecsv((string)$attributes['nounclasses'], TRUE);
		$prefix = (string)$xml->prefix;
		$lemma = (string)$xml->lemma;
		$modifier = (string)$xml->modifier;
		$meaning = (string)$xml->meaning;
		$comment = (string)$xml->comment;
		$confidence = (int)$attributes['confidence'];
		$proposal = (bool)$attributes['proposal'];
		$voided = (bool)$attributes['voided'];
		
		$definition = new Definition(0, $wordClass, $prefix, $lemma, $modifier, $meaning, $comment, $confidence, $proposal, $voided);
		$definition->setNounClasses($nounClasses);
		
		// TODO Add tags
		
		// Add usage examples
		$examples = array();
		foreach ($xml->examples->example as $exampleXml)
			$examples[] = new Example(0, (string)$exampleXml->usage, (string)$exampleXml->meaning);
			
		$definition->setExamples($examples);
	
		// Save definition to database
		if (!Dictionary::getDefinitionService()->saveDefinition($definition)) {
			$this->log('Unable to save definition "'.$definition->toString().'"');
			return FALSE;
		}
		
		$this->idMap[$id] = $definition->getId();
		
		// Load attached changes
		foreach ($xml->changes->change as $changeXml) {
			$change = $this->loadChange($changeXml);
			
			// TODO save change
		}
		
		//echo $definition->toString();
		var_dump($definition);
		echo '<hr/>';
		
		return $definition;
	}
	
	/**
	 * Loads a change from an XML node
	 * @param SimpleXmlElement xml the XML node
	 * @return Change the change
	 */
	private function loadChange($xml) {
		$attributes = $xml->attributes();
		
		$id = (int)$attributes['id'];
		$originalId = isset($attributes['original']) ? (int)$attributes['original'] : NULL;
		$proposalId = isset($attributes['proposal']) ? (int)$attributes['proposal'] : NULL;
		$action = (int)$attributes['action'];
		$submitterId = (int)$attributes['submitter'];
		$submitted = (int)$attributes['submitted'];
		$status = (int)$attributes['status'];
		$resolverId = isset($attributes['resolver']) ? (int)$attributes['resolver'] : NULL;
		$resolved = isset($attributes['resolved']) ? strtotime($attributes['resolved']) : NULL;
		
		return new Change(0, $originalId, $proposalId, $action, $submitterId, $submitted, $status, $resolverId, $resolved);
	}
}

?>
