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
 * Class for displaying a diff of two definitions
 */
class Diff {
	/**
	 * Creates a side-by-side comparison of two definitions
	 * @param Definition definition1 the first definition
	 * @param string label1 the label for first definition
	 * @param Definition definition2 the second definition
	 * @param string label1 the label for second definition
	 */
	public static function definitions($definition1, $label1, $definition2, $label2) {
		$fields1 = $definition1 ? self::getDiffFields($definition1) : NULL;
		$fields2 = $definition2 ? self::getDiffFields($definition2) : NULL;
		
		$fieldLabels = array();
		$fieldLabels[] = KU_STR_WORDCLASS;
		$fieldLabels[] = KU_STR_NOUNCLASSES;
		$fieldLabels[] = KU_STR_PREFIX.' | '.KU_STR_LEMMA;
		$fieldLabels[] = KU_STR_MODIFIER;
		$fieldLabels[] = KU_STR_MEANING;
		$fieldLabels[] = KU_STR_COMMENT;
		$fieldLabels[] = KU_STR_FLAGS;
		foreach (Dictionary::getTagService()->getRelationships() as $relationship)
			$fieldLabels[] = KU_STR_TAGS.': '.$relationship->getTitle();
		for ($e = 1; $e <= KUMVA_MAX_EXAMPLES; $e++)
			$fieldLabels[] = KU_STR_EXAMPLE.': '.$e;
		$fieldLabels[] = KU_STR_UNVERIFIED;
		
		?>
		<table width="100%" class="diff">
			<tr>
				<td width="300">
				<span id="diffhide" style="display:none"><?php Templates::button('bullet_collapse', "$('tr.diffrow').hide(); $('#diffhide').hide(); $('#diffshow').show()", KU_STR_HIDE); ?></span>
				<span id="diffshow"><?php Templates::button('bullet_expand', "$('tr.diffrow').show(); $('#diffshow').hide(); $('#diffhide').show()", KU_STR_SHOW); ?></span>
				</td>
				<?php if ($definition1) { ?>
					<td style="vertical-align: middle">
						<b><?php echo KU_STR_REVISION.': '.$definition1->getRevision().' ('.$label1.')'; ?></b>
					</td>
				<?php } if ($definition2) { ?>
					<td style="vertical-align: middle">
						<b><?php echo KU_STR_REVISION.': '.$definition2->getRevision().' ('.$label2.')'; ?></b>
					</td>
				<?php } ?>
			</tr>
		
			<?php 
			for ($f = 0; $f < count($fieldLabels); $f++) { 
				$difference = $definition1 && $definition2 && ($fields1[$f] != $fields2[$f]);	
			?>
				<tr class="diffrow<?php echo $difference ? ' difference' : ''; ?>" style="display:none">
					<td><b><?php echo $fieldLabels[$f]; ?></b></td>
					
					<?php if ($definition1) { ?>
						<td><?php echo $fields1[$f]; ?></td>
					<?php } if ($definition2) { ?>
						<td><?php echo $fields2[$f]; ?></td>
					<?php } ?>
				</tr>
			<?php } ?>
		</table>
		<?php
	}
	
	/**
	 * Utility method to get fields for diffing
	 * @param Definition the definition
	 * @return array the field values
	 */
	private static function getDiffFields($definition) {
		$fields = array();
		$fields[] = $definition->getWordClass();
		$fields[] = aka_makecsv($definition->getNounClasses());
		$fields[] = $definition->getPrefix().'|'.$definition->getLemma();
		$fields[] = $definition->getModifier();
		$fields[] = $definition->getMeaning();
		$fields[] = $definition->getComment();
		
		$flagNames = array();
		foreach (Flags::values() as $flag) {
			if ($definition->getFlag($flag))
				$flagNames[] = Flags::toString($flag);
		}
		$fields[] = implode(', ', $flagNames);
		
		foreach (Dictionary::getTagService()->getRelationships() as $relationship)
			$fields[] = aka_makecsv($relationship->makeTagStrings($definition->getTags($relationship->getId())));
		
		$examples = $definition->getExamples();
		for ($e = 0; $e < KUMVA_MAX_EXAMPLES; $e++)
			$fields[] = isset($examples[$e]) ? ($examples[$e]->getForm().' - '.$examples[$e]->getMeaning()) : '';
			
		$fields[] = $definition->isUnverified() ? 'Yes' : 'No';
			
		return $fields;
	}
}

?>
