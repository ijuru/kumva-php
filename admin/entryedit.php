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

Session::requireRole(Role::CONTRIBUTOR);

/**
 * Validator for definition objects
 */
class DefinitionValidator extends Validator {
	public function validate($definition, $errors) {
		if (strlen($definition->getLemma()) == 0)
			$errors->addForProperty('lemma', KU_MSG_ERROREMPTY);
		if (strlen($definition->getMeaning()) == 0)
			$errors->addForProperty('meaning', KU_MSG_ERROREMPTY);
		
		foreach ($definition->getNounClasses() as $nounClass) {
			if ($nounClass < 1) {
				$errors->addForProperty('nounClasses', KU_MSG_ERRORNOUNCLASS);
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

/**
 * Form controller for add/edit definition
 */
class DefinitionForm extends Form {
	/**
	 * @see Form::createEntity()
	 */
	protected function createEntity() {
		$entryId = (int)Request::getGetParam('id', 0);
		if ($entryId) {
			$entry = Dictionary::getDefinitionService()->getEntry($entryId);
			return $entry->getProposed() ? $entry->getProposed() : $entry->getAccepted();
		}
		
		return new Definition();
	}
	
	/**
	 * @see Form::onBind()
	 */
	protected function onBind($definition) {
		// Bind noun classes
		$nounClasses = aka_parsecsv(Request::getPostParam('nounclasses'), TRUE);
		$definition->setNounClasses($nounClasses);
		
		// Bind flags
		$definition->setFlags(0);
		$flags = Request::getPostParams('flags_');
		foreach ($flags as $flag => $state)
			$definition->setFlag($flag, TRUE);
		
		// Bind tags
		$tagParams = Request::getPostParams('tags_');
		foreach ($tagParams as $relationshipId => $tagSet) {
			$relationship = Dictionary::getTagService()->getRelationship($relationshipId);
			$tagStrings = aka_parsecsv($tagSet);
			$definition->setTagsFromStrings($relationship, $tagStrings);
		}
		
		// Handle any autotag requests
		$autotagRelId = Request::getPostParam('autotag', 0);
		if ($autotagRelId > 0) {
			$relationship = Dictionary::getTagService()->getRelationship($autotagRelId);
			$tagStrings = Lexical::autoTag($definition, $relationship);
			$definition->setTagsFromStrings($relationship, $tagStrings);
		}
		
		// Bind examples
		$exFormParams = Request::getPostParams('exampleform');
		$examples = array();
		foreach ($exFormParams as $param => $form) {
			$meaning = Request::getPostParam('examplemeaning'.$param);
			$examples[] = new Example(0, $form, $meaning);
		}	
		$definition->setExamples($examples);
	}
	
	/**
	 * @see Form::saveEntity()
	 */
	protected function saveEntity($definition) {	
		$saveType = Request::getPostParam('saveType');
	
		if ($saveType == 'propose') {
			if ($definition->isProposal() || $definition->isVoided())
				return FALSE;
		
			$definition->setProposal(TRUE);
		
			if ($definition->isNew()) {
				// Save as new proposal definition
				if (!Dictionary::getDefinitionService()->saveDefinition($definition))
					return FALSE;
		
				$change = Change::createCreate($definition->getId());
			}
			else {
				// Save as new proposal definition
				$originalId = $definition->getId();
				$definition->setId(0);
				if (!Dictionary::getDefinitionService()->saveDefinition($definition))
					return FALSE;
				
				$change = Change::createModify($originalId, $definition->getId());
			}
		
			// Save the change
			if (!Dictionary::getChangeService()->saveChange($change))
				return FALSE;
				
			// Notify subscribed users
			Notifications::newChange($change);
				
			// Add current user as a watcher of the change
			if (!$change->watch())
				return FALSE;
			
			// Update successurl to take us straight to the new change
			$this->setSuccessUrl('change.php?id='.$change->getId());
			return TRUE;
		}
		elseif ($saveType == 'update') {
			
		
			return Dictionary::getDefinitionService()->saveDefinition($definition);
		}
		
		return FALSE;	
	}
}
 
$returnUrl = Request::getGetParam('ref', 'entries.php');
$form = new DefinitionForm($returnUrl, new DefinitionValidator(), new FormRenderer());
$definition = $form->getEntity();
$change = $definition->getChange();
$entry = $definition->getEntry();
$curUser = Session::getCurrent()->getUser();
$canUpdate = $curUser->hasRole(Role::EDITOR) || ($change && $curUser->equals($change->getSubmitter()));
$canPropose = !$entry || !$entry->getProposed();

include_once 'tpl/header.php';

?>
<script type="text/javascript">
/* <![CDATA[ */
function autoTag(relationshipId) {
	$('#autotag').val(relationshipId);
	$('#definitionform').submit();
}

function addNewExample() {
	if ($('div.examplefield').size() < <?php echo KUMVA_MAX_EXAMPLES; ?>) {
		$('#examples').append('<div id="example_' + exampleId + '" class="examplefield">'
			+ '<input name="exampleform_' + exampleId + '" class="text" /> '
			+ '<input name="examplemeaning_' + exampleId + '" class="text" /> '
			+ '<a href="javascript:deleteExample(' + exampleId +')"><?php Templates::icon('delete', KU_STR_REMOVEEXAMPLE); ?></a>'
			+ '</div>'
		);
		exampleId++;
	} else {
		alert("<?php printf(KU_MSG_MAXEXAMPLES, KUMVA_MAX_EXAMPLES); ?>");
	}
}

function deleteExample(index) {
	if (confirm('<?php echo KU_MSG_CONFIRMREMOVEEXAMPLE; ?>'))
		$('#' + 'example_' + index).remove();
}

var exampleId = 1000000;
/* ]]> */
</script>

<h3><?php echo $form->getEntity()->isNew() ? KU_STR_ADDENTRY : KU_STR_EDITENTRY; ?></h3>

<?php
if ($entry && $entry->isDeleted())
	echo '<div class="info">'.KU_MSG_ENTRYDELETED.'</div>'; 
else if ($change)
	printf('<div class="info">'.KU_MSG_DEFINITIONPROPOSAL.'</div>', 'change.php?id='.$change->getId().'&amp;ref='.urlencode(KUMVA_URL_CURRENT)); 
	
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
		<th><?php echo KU_STR_MEANING; ?></th>
		<td><?php $form->textField('meaning'); ?> <?php $form->errors('meaning'); ?></td>
	</tr>
	<tr>
		<th><?php echo KU_STR_COMMENT; ?></th>
		<td><?php $form->textArea('comment'); ?> <?php $form->errors('comment'); ?></td>
	</tr>
	<tr>
		<th><?php echo KU_STR_FLAGS; ?></th>
		<td>
		<?php
			foreach (Flags::values() as $flag) {
				$flagState = $definition->getFlag($flag);
				$flagName = Flags::toLocalizedString($flag);
				echo '<input type="checkbox" name="flags_'.$flag.'" '.($flagState ? 'checked="checked"' : '').' /> '.$flagName.'&nbsp;&nbsp;';
			}
		?>
		</td>
	</tr>
	<tr>
		<td colspan="2" class="sectionheader"><?php echo KU_STR_TAGS; ?></td>
	</tr>
	<?php foreach (Dictionary::getTagService()->getRelationships() as $relationship) { 
		$tags = $form->getEntity()->getTags($relationship->getId());
		$tagStrings = $relationship->makeTagStrings($tags);
		$tagsText = htmlspecialchars(aka_makecsv($tagStrings));
	?>
	<tr>
		<td><b><?php echo htmlspecialchars($relationship->getTitle()); ?></b> (defaults to <b><?php echo $relationship->getDefaultLang(TRUE); ?></b>)<br />
			<?php echo htmlspecialchars($relationship->getDescription()); ?>
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
					<input name="exampleform_<?php echo $exCount; ?>" value="<?php echo $example->getForm(); ?>" class="text" />
					<input name="examplemeaning_<?php echo $exCount; ?>" value="<?php echo $example->getMeaning(); ?>" class="text" />
					<?php Templates::iconLink('delete', "javascript:deleteExample($exCount)", KU_STR_REMOVEEXAMPLE); ?>
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
		<th><?php echo KU_STR_VERIFIED; ?></th>
		<td><?php $form->checkbox('verified'); ?> <?php $form->errors('verified'); ?></td>
	</tr>
	<tr>
		<td colspan="2"><hr />
			<?php 
			if ($canUpdate)
				Templates::button('save', "$('#saveType').val('update'); $('#_action').val('save'); aka_submit(this)", KU_STR_SAVE);
			if ($canPropose)
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
