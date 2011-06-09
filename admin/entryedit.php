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
 * Purpose: Entry edit page
 */

include_once '../inc/kumva.php';
include_once 'form/DefinitionForm.php';
include_once 'validator/DefinitionValidator.php';

Session::requireRole(Role::CONTRIBUTOR);

$pronunChars = defined('KUMVA_PRONUNCIATION_CHARS') ? aka_strsplit(KUMVA_PRONUNCIATION_CHARS) : array();
 
$returnUrl = Request::getGetParam('ref', 'entries.php');
$form = new DefinitionForm($returnUrl, new DefinitionValidator(), new FormRenderer());
$definition = $form->getEntity();

include_once 'tpl/header.php';

?>
<script type="text/javascript">
/* <![CDATA[ */
function autoTag(relationshipId) {
	$('#autotag').val(relationshipId);
	$('#definitionform').submit();
}

function addNewMeaning() {
	$('#meanings').append('<div id="meaning_' + meaningId + '" class="meaningfield">'
			+ '<input name="meaningtext_' + meaningId + '" class="text" /> '
			
			<?php
			foreach (Flags::values() as $flag) {
				$flagName = Flags::toLocalizedString($flag);
				echo "+ '<input type=\"checkbox\" name=\"meaningflag_' + meaningId + '_$flag\" /> $flagName&nbsp;&nbsp;'";
			}
			?>
			
			+ '<a href="javascript:deleteMeaning(' + meaningId +')"><?php Templates::icon('delete', KU_STR_REMOVE); ?></a>'
			+ '</div>'
	);
	meaningId++;
}

function addNewExample() {
	if ($('div.examplefield').size() < <?php echo KUMVA_MAX_EXAMPLES; ?>) {
		$('#examples').append('<div id="example_' + exampleId + '" class="examplefield">'
			+ '<input name="exampleform_' + exampleId + '" class="text" /> '
			+ '<input name="examplemeaning_' + exampleId + '" class="text" /> '
			+ '<a href="javascript:deleteExample(' + exampleId +')"><?php Templates::icon('delete', KU_STR_REMOVE); ?></a>'
			+ '</div>'
		);
		exampleId++;
	} else {
		alert("<?php printf(KU_MSG_MAXEXAMPLES, KUMVA_MAX_EXAMPLES); ?>");
	}
}

function insertPronunciation(str) {
	$('#_ctrl_pronunciation').insertAtCaret(str);
}

function deleteMeaning(index) {
	if (confirm('<?php echo KU_MSG_CONFIRMREMOVEMEANING; ?>'))
		$('#' + 'meaning_' + index).remove();
}

function deleteExample(index) {
	if (confirm('<?php echo KU_MSG_CONFIRMREMOVEEXAMPLE; ?>'))
		$('#' + 'example_' + index).remove();
}

var meaningId = 1000000;
var exampleId = 1000000;
/* ]]> */
</script>

<h3><?php echo $form->isNewEntity() ? KU_STR_ADDENTRY : KU_STR_EDITENTRY; ?></h3>

<?php
if ($form->entry && $form->entry->isDeleted())
	echo '<div class="info">'.KU_MSG_ENTRYDELETED.'</div>'; 
elseif ($definition->isProposedRevision())
	printf('<div class="info">'.KU_MSG_DEFINITIONPROPOSAL.'</div>', 'change.php?id='.$form->change->getId().'&amp;ref='.urlencode(KUMVA_URL_CURRENT)); 
	
if (count($form->getErrors()->get()) > 0)
	echo '<div class="error">'.implode('<br />', $form->getErrors()->get()).'</div>';
	
$form->start('definitionform');
?>	
<input type="hidden" id="autotag" name="autotag" value="" />
<input type="hidden" id="saveType" name="saveType" value="propose" />
<table class="form">
	<tr>
		<th><?php echo KU_STR_WORDCLASS.'/'.KU_STR_NOUNCLASSES; ?></th>
		<td>
			<?php $form->textField('wordClass', 'short'); ?> <?php $form->errors('wordClass'); ?>
			<input name="nounclasses" class="short" value="<?php echo aka_makecsv($form->getEntity()->getNounClasses()); ?>" />
			<?php $form->errors('nounClasses'); ?>
		</td>
	</tr>
	<tr>
		<th><?php echo KU_STR_PREFIX.'/'.KU_STR_LEMMA; ?></th>
		<td><?php $form->textField('prefix', 'prefix'); $form->textField('lemma', 'lemma'); ?> 
			<?php $form->errors('prefix'); ?> <?php $form->errors('lemma'); ?>
		</td>
	</tr>
	<tr>
		<th><?php echo KU_STR_MODIFIER; ?></th>
		<td><?php $form->textField('modifier'); ?> <?php $form->errors('modifier'); ?></td>
	</tr>
	<tr>
		<th><?php echo KU_STR_PRONUNCIATION; ?></th>
		<td>
			<?php 
			$form->textField('pronunciation');
			echo ' <span>'.KU_STR_INSERT.': ';
			foreach ($pronunChars as $char) { 
				?>
				<a href="javascript:insertPronunciation('<?php echo $char; ?>')"><?php echo $char; ?></a>
				<?php 
			} 
			echo '</span> ';
			$form->errors('pronunciation'); 
			?>
		</td>
	</tr>
	<tr>
		<th><?php echo KU_STR_MEANINGS; ?></th>
		<td id="meanings">
			<?php 
			$form->errors('meanings');
			$meanCount = 0;
			foreach ($form->getEntity()->getMeanings() as $meaning) { 
				?>
				<div id="meaning_<?php echo $meanCount; ?>" class="meaningfield">
					<input name="meaningtext_<?php echo $meanCount; ?>" value="<?php echo aka_prephtml($meaning->getMeaning()); ?>" class="text" />
					<?php
					foreach (Flags::values() as $flag) {
						$flagState = $meaning->getFlag($flag);
						$flagName = Flags::toLocalizedString($flag);
						echo '<input type="checkbox" name="meaningflag_'.$meanCount.'_'.$flag.'" ';
						echo ($flagState ? 'checked="checked"' : '').' /> '.$flagName.'&nbsp;&nbsp;';
					}	
					if ($meanCount > 0)
						Templates::iconLink('delete', "javascript:deleteMeaning($meanCount)", KU_STR_REMOVE); 
					?>
				</div>
				<?php 
				$meanCount++; 
			} 
			?>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><?php Templates::button('add', "addNewMeaning()", KU_STR_ADD); ?></td>
	</tr>
	<tr>
		<th><?php echo KU_STR_COMMENT; ?></th>
		<td><?php $form->textArea('comment'); ?> <?php $form->errors('comment'); ?></td>
	</tr>
	<tr>
		<td colspan="2" class="sectionheader"><?php echo KU_STR_TAGS; ?></td>
	</tr>
	<?php foreach (Dictionary::getTagService()->getRelationships() as $relationship) { 
		$tags = $form->getEntity()->getTags($relationship->getId());
		$tagStrings = $relationship->makeTagStrings($tags);
		$tagsText = aka_prephtml(aka_makecsv($tagStrings));
	?>
	<tr>
		<td><b><?php echo aka_prephtml($relationship->getTitle()); ?></b> (defaults to <b><?php echo $relationship->getDefaultLang(TRUE); ?></b>)<br />
			<?php echo aka_prephtml($relationship->getDescription()); ?>
		</td>
		<td>
			<textarea rows="3" cols="40" name="tags_<?php echo $relationship->getId(); ?>" class="text"><?php echo $tagsText; ?></textarea>
			<?php
			if (Lexical::hasLangFunction($relationship->getDefaultLang(TRUE), 'autotag_'.$relationship->getName()))
				Templates::button('auto', "autoTag({$relationship->getId()})", KU_STR_AUTO);
			
			$form->errors('tags_'.$relationship->getId()); 
			?>
		</td>
	</tr>
	<?php } ?>
	<tr>
		<td colspan="2" class="sectionheader"><?php echo KU_STR_EXAMPLES; ?></td>
	</tr>
	<tr>
		<td colspan="2" id="examples">
			<?php 
			$form->errors('examples');
			$exCount = 0;
			foreach ($form->getEntity()->getExamples() as $example) { ?>
				<div id="example_<?php echo $exCount; ?>" class="examplefield">
					<input name="exampleform_<?php echo $exCount; ?>" value="<?php echo aka_prephtml($example->getForm()); ?>" class="text" />
					<input name="examplemeaning_<?php echo $exCount; ?>" value="<?php echo aka_prephtml($example->getMeaning()); ?>" class="text" />
					<?php Templates::iconLink('delete', "javascript:deleteExample($exCount)", KU_STR_REMOVE); ?>
				</div>
				<?php 
				$exCount++; 
			} 
			?>
		</td>
	</tr>
	<tr>
		<td colspan="2"><?php Templates::button('add', "addNewExample()", KU_STR_ADD); ?></td>
	</tr>
	<tr>
		<td colspan="2" class="sectionheader">Meta</td>
	</tr>
	<tr>
		<th><?php echo KU_STR_UNVERIFIED; ?></th>
		<td><?php $form->checkbox('unverified'); ?> <?php $form->errors('unverified'); ?></td>
	</tr>
	<tr>
		<td colspan="2"><hr />
			<?php 
			if ($form->canUpdate())
				Templates::button('save', "$('#saveType').val('update'); $('#_action').val('save'); aka_submit(this)", KU_STR_SAVE);
			if ($form->canPropose())
				Templates::button('propose', "$('#saveType').val('propose'); $('#_action').val('save'); aka_submit(this)", KU_STR_PROPOSE);
			
			$form->cancelButton(); 
			?>
		</td>
	</tr>
</table>
<?php 
$form->end(); 

include_once 'tpl/footer.php'; 
?>
