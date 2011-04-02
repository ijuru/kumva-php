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
 * Purpose: Definition edit page
 */

include_once '../inc/kumva.php';

Session::requireUser();

// Process delete request
$action = Request::getPostParam('action');
if ($action == 'delete') {
	$delId = Request::getPostParam('targetId');
	$change = Change::createDelete($delId);
	if (Dictionary::getChangeService()->saveChange($change)) {
		$change->watch();
		Notifications::newChange($change);
		Request::redirect('change.php?id='.$change->getId().'&ref='.urlencode(KUMVA_URL_CURRENT));
	}
}

$defId = (int)Request::getGetParam('id', 0);
$definition = ($defId > 0) ? Dictionary::getDefinitionService()->getDefinition($defId) : new Definition();
	
if ($definition->isProposal())
	$change = Dictionary::getChangeService()->getChangeForProposal($definition);
else {
	$changes = Dictionary::getChangeService()->getChangesForDefinition($definition);
	$changePending = count($changes) > 0 ? ($changes[0]->getStatus() == Status::PENDING) : FALSE;
}
$permissions = $definition->getPermissions();

include_once 'tpl/header.php';

?>
<script type="text/javascript">
/* <![CDATA[ */
function deleteDefinition(id) {
	if (confirm('<?php echo KU_MSG_CONFIRMDELETEDEFINITION; ?>')) {
		$('#action').val('delete');
		$('#targetId').val(id);
		$('#definitionForm').submit();
	}
}
/* ]]> */
</script>
<h3><?php echo KU_STR_VIEWDEFINITION; ?></h3>

<div class="listcontrols">
	<div style="float: left">
		<?php Templates::buttonLink('back', Request::getGetParam('ref', 'definitions.php'), KU_STR_BACK); ?>
	</div>
	<div style="float: right">
		<form id="definitionForm" method="post">
			<input type="hidden" id="action" name="action" />
			<input type="hidden" id="targetId" name="targetId" />
			<?php 
			if ($permissions['propose'] || $permissions['update'])
				Templates::buttonLink('edit', 'definition.php?id='.$definition->getId().'&amp;ref='.urlencode(KUMVA_URL_CURRENT), KU_STR_EDIT);
			if ($permissions['propose'])
				Templates::button('delete', 'deleteDefinition('.$definition->getId().')', KU_STR_DELETE);
			?>
		</form>
	</div>
</div>
<?php 
if ($definition->isVoided())
	echo '<div class="info">'.KU_MSG_DEFINITIONVOIDED.'</div>'; 
else if ($definition->isProposal())
	printf('<div class="info">'.KU_MSG_DEFINITIONPROPOSAL.'</div>', 'change.php?id='.$change->getId().'&amp;ref='.urlencode(KUMVA_URL_CURRENT)); 
elseif ($changePending)
	printf('<div class="info">'.KU_MSG_DEFINITIONCHANGEPENDING.'</div>', 'change.php?id='.$changes[0]->getId().'&amp;ref='.urlencode(KUMVA_URL_CURRENT));
	
if (!$definition->isVerified() && !$definition->isProposal())
	echo '<div class="warning">'.KU_MSG_DEFINITIONNOTVERIFIED.'</div>'; 

?>	
<table class="form">
	<tr>
		<th><?php echo KU_STR_WORDCLASS.'/'.KU_STR_NOUNCLASSES; ?></th>
		<td><?php echo $definition->getWordClass(); ?> <?php echo aka_makecsv($definition->getNounClasses()); ?></td>
	</tr>
	<tr>
		<th><?php echo KU_STR_PREFIX.'/'.KU_STR_LEMMA; ?></th>
		<td><?php Templates::definition($definition, FALSE); ?></td>
	</tr>
	<tr>
		<th><?php echo KU_STR_MODIFIER; ?></th>
		<td><?php echo $definition->getModifier(); ?></td>
	</tr>
	<tr>
		<th><?php echo KU_STR_MEANING; ?></th>
		<td><?php echo $definition->getMeaning(); ?></td>
	</tr>
	<tr>
		<th><?php echo KU_STR_COMMENT; ?></th>
		<td><?php echo $definition->getComment(); ?></td>
	</tr>
	<tr>
		<th><?php echo KU_STR_FLAGS; ?></th>
		<td>
		<?php
			$flagNames = array();
			foreach (Flags::values() as $flag) {
				if ($definition->getFlag($flag))
					$flagNames[] = Flags::toLocalizedString($flag);
			}
			echo implode(', ', $flagNames);
		?>
		</td>
	</tr>
	<tr>
		<td colspan="2" class="sectionheader"><?php echo KU_STR_TAGS; ?></td>
	</tr>
	<?php foreach (Dictionary::getTagService()->getRelationships() as $relationship) { 
		$tags = $definition->getTags($relationship->getId());
		$tagStrings = $relationship->makeTagStrings($tags);
	?>
		<tr>
			<td>
				<b><?php echo htmlspecialchars($relationship->getTitle()); ?></b> (defaults to <b><?php echo $relationship->getDefaultLang(TRUE); ?></b>)<br />
				<?php echo htmlspecialchars($relationship->getDescription()); ?>
			</td>
			<td><?php echo aka_makecsv($tagStrings); ?></td>
		</tr>
	<?php } ?>
	<tr>
		<td colspan="2" class="sectionheader"><?php echo KU_STR_EXAMPLES; ?></td>
	</tr>
	<tr>
		<td colspan="2">
			<?php Templates::exampleList($definition->getExamples()); ?>
		</td>
	</tr>
</table>
<?php 

if (!$definition->isNew() && !$definition->isProposal() && isset($changes)) {
	?>
	<h3><?php echo KU_STR_CHANGEHISTORY; ?></h3>
	<?php 
	Templates::changesTable($changes, TRUE, FALSE, TRUE);
}

include_once 'tpl/footer.php'; 
?>
