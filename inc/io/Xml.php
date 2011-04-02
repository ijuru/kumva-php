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
 * Purpose: Xml class
 */
 
/**
 * Class for XML templates
 */
class Xml { 
	const NAME_SPACE = 'http://kumva.ijuru.com';
	const SCHEMA_URL = 'http://kumva.ijuru.com/kumva.xsd';
	
	/**
	 * Outputs the header of a kumva XML document
	 */
	public static function header() {
		echo '<?xml version="1.0" encoding="utf-8"?>';
		echo '<kumva xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="'.self::NAME_SPACE.'" xsi:schemaLocation="'.self::SCHEMA_URL.'">';
	}
	
	/**
	 * Outputs the footer of a kumva XML document
	 */
	public static function footer() {
		echo '</kumva>';
	}

	/**
 	 * Outputs the given definition
 	 * @param Definition definition the definition
 	 * @param bool incChanges TRUE to include changes, else FALSE
	 */
	public static function definition($definition, $incChanges = FALSE) {
		$flags = Flags::makeCSVString(Flags::fromBits($definition->getFlags()));
		
		echo '<definition id="'.$definition->getId().'" ';
		echo 'wordclass="'.$definition->getWordClass().'" ';
		echo 'nounclasses="'.implode(',', $definition->getNounClasses()).'" ';
		echo 'flags="'.$flags.'" ';
		echo 'verified="'.aka_prepxmlval($definition->isVerified()).'" ';
		echo 'proposal="'.aka_prepxmlval($definition->isProposal()).'" ';
		echo 'voided="'.aka_prepxmlval($definition->isVoided()).'">';
		echo '<prefix>'.aka_prepxmlval($definition->getPrefix()).'</prefix>';
		echo '<lemma>'.aka_prepxmlval($definition->getLemma()).'</lemma>';
		echo '<modifier>'.aka_prepxmlval($definition->getModifier()).'</modifier>';
		echo '<meaning>'.aka_prepxmlval($definition->getMeaning()).'</meaning>';
		echo '<comment>'.aka_prepxmlval($definition->getComment()).'</comment>';	
		
		// Tags
		echo '<tags>';
		foreach (Dictionary::getTagService()->getRelationships() as $relationship) {
			echo '<relationship name="'.$relationship->getName().'">';
			foreach ($definition->getTags($relationship->getId()) as $tag)
				echo '<tag lang="'.$tag->getLang().'" text="'.aka_prepxmlval($tag->getText()).'" />';
			echo '</relationship>';
		}
		echo '</tags>';
	
		// Examples
		echo '<examples>';
		foreach ($definition->getExamples() as $ex)
			echo '<example><usage>'.aka_prepxmlval($ex->getForm()).'</usage><meaning>'.aka_prepxmlval($ex->getMeaning()).'</meaning></example>';
		echo '</examples>';
		
		// Changes
		if ($incChanges) {
			echo '<changes>';
			foreach (Dictionary::getChangeService()->getChangesForDefinition($definition) as $change)
				self::change($change);
			echo '</changes>';
		}
	
		echo '</definition>';
	}
	
	/**
 	 * Outputs the given change
 	 * @param Change change the change
	 */
	public static function change($change) {	
		echo '<change id="'.$change->getId().'" ';
		
		if ($change->getAction() != Action::CREATE)
			echo 'proposal="'.$change->getProposal()->getId().'" ';
			
		echo 'action="'.strtolower(Action::toString($change->getAction())).'" ';
		echo 'submitter="'.$change->getSubmitter()->getId().'" ';
		echo 'submitted="'.date('c', $change->getSubmitted()).'" ';
		echo 'status="'.strtolower(Status::toString($change->getStatus())).'" ';
		
		if ($change->getStatus() != Status::PENDING) {
			echo 'resolver="'.$change->getResolver()->getId().'" ';
			echo 'resolved="'.date('c', $change->getResolved()).'" ';
		}
		
		echo '>';
		
		foreach ($change->getComments() as $comment)
			self::comment($comment);
		echo '</change>';
	}
	
	/**
 	 * Outputs the given comment
 	 * @param Comment comment the comment
	 */
	public static function comment($comment) {
		echo '<comment id="'.$comment->getId().'" ';
		echo 'user="'.$comment->getUser()->getId().'" ';
		echo 'created="'.date('c', $comment->getCreated()).'" ';
		echo 'approval="'.aka_prepxmlval($comment->isApproval()).'" ';
		echo 'voided="'.aka_prepxmlval($comment->isVoided()).'" ';
		echo '>';
		echo aka_prepxmlval($comment->getText());
		echo '</comment>';
	}
}
 
?>
