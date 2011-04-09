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
 * Purpose: Dictionary export script
 */
 
include_once '../inc/kumva.php';

Session::requireUser();

// The export type
$format = Request::getGetParam('format', NULL);
$incChanges = (bool)Request::getGetParam('changes', FALSE);

/**
 * Gets an array of tag strings from an array of tag objects
 */
function tagvalues($rel, &$tags) {
	$vals = array();
	foreach ($tags as $tag)
		$vals[] = $rel->makeTagString($tag);
	return $vals;
}

if ($format == 'xml' && !$incChanges) {
	header('Content-type: text/xml');
	header('Content-Disposition: attachment; filename=definitions-'.date('Y-m-d').'.xml');
	
	$definitions = Dictionary::getDefinitionService()->getAcceptedDefinitions();
	
	Xml::header();
	
	echo '<definitions>';
	foreach ($definitions as $definition)
		Xml::definition($definition, FALSE);
	echo '</definitions>';
	
	Xml::footer();
}
elseif ($format == 'xml' && $incChanges) {
	header('Content-type: text/xml');
	header('Content-Disposition: attachment; filename=entries-'.date('Y-m-d').'.xml');
	
	$entries = Dictionary::getDefinitionService()->getEntries();
	
	Xml::header();
	
	echo '<entries>';
	foreach ($entries as $entry)
		Xml::entry($entry);
	echo '</entries>';
	
	Xml::footer();
}
elseif ($format == 'csv') {
	header('Content-type: text/csv');
	header('Content-Disposition: attachment; filename=definitions-'.date('Y-m-d').'.csv');

	$definitions = Dictionary::getDefinitionService()->getAcceptedDefinitions();
	$relationships = Dictionary::getTagService()->getRelationships();
	
	// Output header
	echo "WordClass,NounClasses,Prefix,Lemma,Modifier,Meaning,Comment,Flags,Verified";
	
	foreach ($relationships as $relationship)
		echo ',Tags:'.$relationship->getName();
		
	echo ",Example:1,Example:2,Example:3\n";

	foreach ($definitions as $definition) {
		$flags = Flags::makeCSVString(Flags::fromBits($definition->getFlags()));
		
		// Output definition fields
		echo aka_prepcsvval($definition->getWordClass()).',';
		echo aka_prepcsvval(implode(',', $definition->getNounClasses())).',';
		echo aka_prepcsvval($definition->getPrefix()).',';
		echo aka_prepcsvval($definition->getLemma()).',';
		echo aka_prepcsvval($definition->getModifier()).',';
		echo aka_prepcsvval($definition->getMeaning()).',';
		echo aka_prepcsvval($definition->getComment()).',';
		echo aka_prepcsvval($flags).',';
		echo aka_prepcsvval($definition->isVerified());
		
		// Output tags
		foreach ($relationships as $relationship) {
			$tags = $definition->getTags($relationship->getId());
			echo ','.aka_prepcsvval(implode(',', tagvalues($relationship, $tags)));
		}
		
		// Output examples
		$examples = $definition->getExamples();
		for ($e = 0; $e < KUMVA_MAX_EXAMPLES; $e++) {
			$example = isset($examples[$e]) ? $examples[$e] : NULL;
			echo ',';
			if ($example != NULL)
				echo aka_prepcsvval($example->getForm().'/'.$example->getMeaning());
		}
		
		echo "\n";
	}
}

?>
