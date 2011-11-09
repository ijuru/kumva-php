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
 * Purpose: Diff class
 */
 
/**
 * Class for displaying a diff of two revisions
 */
class Diff {
	/**
	 * Creates a side-by-side comparison of two revisions
	 * @param Revision revision1 the first revision
	 * @param string label1 the label for first revision
	 * @param Revision revision2 the second revision
	 * @param string label1 the label for second revision
	 */
	public static function revisions($revision1, $label1, $revision2, $label2) {
		$fields1 = $revision1 ? self::getDiffFields($revision1) : NULL;
		$fields2 = $revision2 ? self::getDiffFields($revision2) : NULL;
		
		$fieldLabels = array();
		$fieldLabels[] = KU_STR_WORDCLASS;
		$fieldLabels[] = KU_STR_NOUNCLASSES;
		$fieldLabels[] = KU_STR_PREFIX.' | '.KU_STR_LEMMA;
		$fieldLabels[] = KU_STR_MODIFIER;
		$fieldLabels[] = KU_STR_PRONUNCIATION;
		$fieldLabels[] = KU_STR_MEANINGS;
		$fieldLabels[] = KU_STR_COMMENT;

		foreach (Dictionary::getTagService()->getRelationships() as $relationship)
			$fieldLabels[] = KU_STR_TAGS.': '.$relationship->getTitle();
		for ($e = 1; $e <= KUMVA_MAX_EXAMPLES; $e++)
			$fieldLabels[] = KU_STR_EXAMPLE.': '.$e;
		$fieldLabels[] = KU_STR_UNVERIFIED;
		
		?>
		<table width="100%" class="diff">
			<tr>
				<td width="300">
				<span id="diffhide"><?php Templates::button('bullet_collapse', "$('tr.diffrow').hide(); $('#diffhide').hide(); $('#diffshow').show()", KU_STR_HIDE); ?></span>
				<span id="diffshow" style="display:none"><?php Templates::button('bullet_expand', "$('tr.diffrow').show(); $('#diffshow').hide(); $('#diffhide').show()", KU_STR_SHOW); ?></span>
				</td>
				<?php if ($revision1) { ?>
					<td style="vertical-align: middle">
						<b><?php echo KU_STR_REVISION.': '.$revision1->getNumber().' ('.$label1.')'; ?></b>
					</td>
				<?php } if ($revision2) { ?>
					<td style="vertical-align: middle">
						<b><?php echo KU_STR_REVISION.': '.$revision2->getNumber().' ('.$label2.')'; ?></b>
					</td>
				<?php } ?>
			</tr>
		
			<?php 
			for ($f = 0; $f < count($fieldLabels); $f++) { 
				$difference = $revision1 && $revision2 && ($fields1[$f] != $fields2[$f]);	
			?>
				<tr class="diffrow<?php echo $difference ? ' difference' : ''; ?>">
					<td><b><?php echo $fieldLabels[$f]; ?></b></td>
					
					<?php if ($revision1) { ?>
						<td><?php echo $fields1[$f]; ?></td>
					<?php } if ($revision2) { ?>
						<td><?php echo $fields2[$f]; ?></td>
					<?php } ?>
				</tr>
			<?php } ?>
		</table>
		<?php
	}
	
	/**
	 * Utility method to get fields for diffing
	 * @param Revision the revision
	 * @return array the field values
	 */
	private static function getDiffFields($revision) {
		$fields = array();
		$fields[] = $revision->getWordClass();
		$fields[] = aka_makecsv($revision->getNounClasses());
		$fields[] = $revision->getPrefix().'|'.$revision->getLemma();
		$fields[] = $revision->getModifier();
		$fields[] = $revision->getPronunciation();
		
		$meaningStrs = array();
		foreach ($revision->getMeanings() as $meaning) {
			$meaningStr = aka_prephtml($meaning->getMeaning());
			
			if ($meaning->getFlags() > 0) {
				$flagNames = array();
				foreach (Flags::values() as $flag) {
					if ($meaning->getFlag($flag))
						$flagNames[] = Flags::toLocalizedString($flag);
				}
				
				$meaningStr .= ' ['.implode(', ', $flagNames).']'; 
			}
			$meaningStrs[] = $meaningStr;
		}
		
		$fields[] = implode('<br/>', $meaningStrs);
		$fields[] = $revision->getComment();
		
		foreach (Dictionary::getTagService()->getRelationships() as $relationship)
			$fields[] = aka_makecsv($relationship->makeTagStrings($revision->getTags($relationship->getId())));
		
		$examples = $revision->getExamples();
		for ($e = 0; $e < KUMVA_MAX_EXAMPLES; $e++)
			$fields[] = isset($examples[$e]) ? ($examples[$e]->getForm().' - '.$examples[$e]->getMeaning()) : '';
			
		$fields[] = $revision->isUnverified() ? 'Yes' : 'No';
			
		return $fields;
	}
}

?>
