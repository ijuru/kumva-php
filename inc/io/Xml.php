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
	
	/**
	 * Outputs the header of a kumva XML document
	 */
	public static function header() {
		echo '<?xml version="1.0" encoding="utf-8" ?>';
		echo '<kumva xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="'.self::NAME_SPACE.'">';
	}
	
	/**
	 * Outputs the footer of a kumva XML document
	 */
	public static function footer() {
		echo '</kumva>';
	}
	
	/**
	 * Outputs the given entry
	 * @param Entry entry the entry
	 * @param bool incChanges whether proposed and historical revisions should be included, and change records
	 */
	public static function entry($entry, $incChanges = true) {
		echo '<entry id="'.$entry->getId().'">';
		
		if ($incChanges) {
			$revisions = Dictionary::getEntryService()->getEntryRevisions($entry);
			foreach ($revisions as $revision)
				Xml::revision($revision, true);
		} else {
			$revision = Dictionary::getEntryService()->getEntryRevision($entry, RevisionPreset::ACCEPTED);
			if ($revision)
				Xml::revision($revision, false);
		}
		
		if ($entry->hasMedia(Media::AUDIO))
			echo '<media type="audio" url="'.KUMVA_URL_MEDIA.'/audio/'.$entry->getId().'.mp3" />';
		if ($entry->hasMedia(Media::IMAGE))
			echo '<media type="audio" url="'.KUMVA_URL_MEDIA.'/image/'.$entry->getId().'.jpg" />';
		
		echo '</entry>';
	}

	/**
 	 * Outputs the given revision
 	 * @param Revision revision the revision
 	 * @param bool incChange TRUE to include this revision's change information
 	 * @param bool asDefinition outputs the revision as an unrevisioned definition
	 */
	public static function revision($revision, $incChange = false, $asDefinition = false) {
		
		if ($asDefinition) {
			echo '<definition ';
		} else {
			echo '<revision ';
			echo 'number="'.aka_prepxmlval($revision->getNumber()).'" ';
			echo 'status="'.strtolower(RevisionStatus::toString($revision->getStatus())).'" ';
		}
		
		echo 'wordclass="'.$revision->getWordClass().'" ';
		echo 'nounclasses="'.implode(',', $revision->getNounClasses()).'" ';
		echo 'unverified="'.aka_prepxmlval($revision->isUnverified()).'">';

		echo '<prefix>'.aka_prepxmlval($revision->getPrefix()).'</prefix>';
		echo '<lemma>'.aka_prepxmlval($revision->getLemma()).'</lemma>';
		echo '<modifier>'.aka_prepxmlval($revision->getModifier()).'</modifier>';
		echo '<pronunciation>'.aka_prepxmlval($revision->getPronunciation()).'</pronunciation>';
		
		echo '<meanings>';
		foreach ($revision->getMeanings() as $meaning) {
			$flags = Flags::makeCSVString(Flags::fromBits($meaning->getFlags()));
			
			echo '<meaning flags="'.$flags.'">'.aka_prepxmlval($meaning->getMeaning()).'</meaning>';
		}
		echo '</meanings>';
		
		echo '<comment>'.aka_prepxmlval($revision->getComment()).'</comment>';	
		
		// Tags
		echo '<tags>';
		foreach (Dictionary::getTagService()->getRelationships() as $relationship) {
			echo '<relationship name="'.$relationship->getName().'">';
			foreach ($revision->getTags($relationship->getId()) as $tag)
				echo '<tag lang="'.$tag->getLang().'" text="'.aka_prepxmlval($tag->getText()).'" />';
			echo '</relationship>';
		}
		echo '</tags>';
	
		// Examples
		echo '<examples>';
		foreach ($revision->getExamples() as $ex)
			echo '<example><usage>'.aka_prepxmlval($ex->getForm()).'</usage><meaning>'.aka_prepxmlval($ex->getMeaning()).'</meaning></example>';
		echo '</examples>';
		
		if ($incChange && $revision->getChange()) 
			self::change($revision->getChange());
		
		if ($asDefinition)
			echo '</definition>';
		else
			echo '</revision>';
	}
	
	/**
 	 * Outputs the given change
 	 * @param Change change the change
	 */
	public static function change($change) {	
		echo '<change ';
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
		echo '<comment ';
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
