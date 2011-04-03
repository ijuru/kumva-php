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
/*$action = Request::getPostParam('action');
if ($action == 'delete') {
	$delId = Request::getPostParam('targetId');
	$change = Change::createDelete($delId);
	if (Dictionary::getChangeService()->saveChange($change)) {
		$change->watch();
		Notifications::newChange($change);
		Request::redirect('change.php?id='.$change->getId().'&ref='.urlencode(KUMVA_URL_CURRENT));
	}
}*/

$entryId = (int)Request::getGetParam('id', 0);
$entry = ($entryId > 0) ? Dictionary::getDefinitionService()->getEntry($entryId) : new Entry();
$definitions = Dictionary::getDefinitionService()->getEntryDefinitions($entry);
$viewRev = (int)Request::getGetParam('rev', 0);
if ($viewRev) {
	// Find requested revision
	foreach ($definitions as $def) {
		if ($def->getRevision() == $viewRev) {
			$definition = $def;
			break;
		}		
	}
}
else
	$definition = $definitions[0]; // Default to latest revision
	
/*if ($definition->isProposal())
	$change = Dictionary::getChangeService()->getChangeForProposal($definition);
else {
	$changes = Dictionary::getChangeService()->getChangesForDefinition($definition);
	$changePending = count($changes) > 0 ? ($changes[0]->getStatus() == Status::PENDING) : FALSE;
}
$permissions = $definition->getPermissions();*/

include_once 'tpl/header.php';

?>
<script type="text/javascript">
/* <![CDATA[ */
function deleteDefinition(id) {
	if (confirm('<?php echo KU_MSG_CONFIRMDELETEDEFINITION; ?>')) {
		$('#action').val('delete');
		$('#definitionForm').submit();
	}
}
/* ]]> */
</script>
<h3><?php echo KU_STR_VIEWENTRY; ?></h3>

<div class="listcontrols">
	<div style="float: left">
		<form method="get">
			<input type="hidden" name="id" value="<?php echo $entry->getId(); ?>" />
			
			<?php Templates::buttonLink('back', Request::getGetParam('ref', 'definitions.php'), KU_STR_BACK); ?>
			
			<?php echo KU_STR_REVISION; ?>
			<select name="rev">
				<?php foreach ($definitions as $def) { 
					$isCurrent = $definition->getRevision() == $def->getRevision();
					if ($def->getRevision() == $entry->getAcceptedRevision())
						$label = $def->getRevision().' ('.KU_STR_ACCEPTED.')';
					elseif ($def->getRevision() == $entry->getProposedRevision())
						$label = $def->getRevision().' ('.KU_STR_PROPOSED.')';
					else
						$label = $def->getRevision()
					?>
					<option value="<?php echo $def->getRevision(); ?>" <?php echo $isCurrent ? 'selected="selected"' : ''; ?>><?php echo $label; ?></option>
				<?php } ?>
			</select>
			<input type="hidden" name="ref" value="<?php echo Request::getGetParam('ref'); ?>" />
			<?php Templates::button('refresh', "aka_submit(this);", KU_STR_REFRESH); ?>
		</form>
	</div>
	<div style="float: right">
		<form id="definitionForm" method="post">
			<input type="hidden" id="action" name="action" />
			<input type="hidden" name="targetId" value="<?php echo $entry->getId(); ?>" />
			<?php 
			/*if ($permissions['propose'] || $permissions['update'])
				Templates::buttonLink('edit', 'definition.php?id='.$definition->getId().'&amp;ref='.urlencode(KUMVA_URL_CURRENT), KU_STR_EDIT);
			if ($permissions['propose'])
				Templates::button('delete', 'deleteDefinition('.$definition->getId().')', KU_STR_DELETE);*/
			?>
		</form>
	</div>
</div>
<?php 
/*if ($definition->isProposal())
	printf('<div class="info">'.KU_MSG_DEFINITIONPROPOSAL.'</div>', 'change.php?id='.$change->getId().'&amp;ref='.urlencode(KUMVA_URL_CURRENT)); 
elseif ($changePending)
	printf('<div class="info">'.KU_MSG_DEFINITIONCHANGEPENDING.'</div>', 'change.php?id='.$changes[0]->getId().'&amp;ref='.urlencode(KUMVA_URL_CURRENT));*/

if ($entry->isDeleted())
	echo '<div class="info">'.KU_MSG_DEFINITIONVOIDED.'</div>';
elseif (!$definition->isVerified())
	echo '<div class="info">'.KU_MSG_DEFINITIONNOTVERIFIED.'</div>'; 

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
<h3><?php echo KU_STR_REVISIONS; ?></h3>
<table class="list" cellspacing="0" border="0">
	<tr>
		<th style="width: 30px">&nbsp;</th>
		<th style="width: 20px">&nbsp;</th>
		<th><?php echo KU_STR_REVISION; ?></th>
		<th><?php echo KU_STR_SUBMITTED; ?></th>
		<th><?php echo KU_STR_SUBMITTER; ?></th>
		<th style="width: 30px">&nbsp;</th>
	</tr>
	<?php 
	foreach ($definitions as $def) { 
		$itemUrl = 'entry.php?id='.$entry->getId().'&amp;rev='.$def->getRevision().'&amp;ref='.urlencode(Request::getGetParam('ref'));
		$change = Dictionary::getDefinitionService()->getDefinitionChange($def);
		?>
		<tr class="rowlink" onclick="aka_goto('<?php echo $itemUrl; ?>')">
			<td>&nbsp;</td>
			<td><?php Templates::icon('change'); ?></td>
			<td style="text-align:center"><?php echo $def->getRevision(); ?></td>
			<td><?php echo $change ? Templates::dateTime($change->getSubmitted()) : ''; ?></td>
			<td><?php echo $change ? Templates::userLink($change->getSubmitter()) : ''; ?></td>
			<td>&nbsp;</td>
		</tr>
	<?php } ?>
</table>
	
<?php include_once 'tpl/footer.php'; ?>
