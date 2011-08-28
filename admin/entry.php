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

Session::requireUser();

// Get entry
$entryId = (int)Request::getGetParam('id', 0);
$entry = ($entryId > 0) ? Dictionary::getEntryService()->getEntry($entryId) : new Entry();

// Get pending change if there is one
$pendingChanges = Dictionary::getChangeService()->getChangesByEntry($entry, Status::PENDING);
$pendingChange = count($pendingChanges) ? $pendingChanges[0] : NULL;
$pendingChangeUrl = $pendingChange ? 'change.php?id='.$pendingChange->getId().'&amp;ref='.urlencode(KUMVA_URL_CURRENT) : NULL;

// Process delete request
$action = Request::getPostParam('action');
if ($action == 'delete' && !$pendingChange) {
	$change = Change::create($entry, ACTION::DELETE);
	if (Dictionary::getChangeService()->saveChange($change)) {
		$change->watch();
		Notifications::newChange($change);
		Request::redirect('change.php?id='.$change->getId().'&ref='.urlencode(KUMVA_URL_CURRENT));
	}
}

// Get revision to view
$viewRev = (int)Request::getGetParam('rev', RevisionPreset::HEAD);
$revision = Dictionary::getEntryService()->getEntryRevision($entry, $viewRev);
if (!$revision)
	$revision = Dictionary::getEntryService()->getEntryRevision($entry, RevisionPreset::LAST);

$canEdit = !$entry->isDeleted() && !$pendingChange && Session::getCurrent()->hasRole(Role::CONTRIBUTOR);
$canDelete = !$entry->isDeleted() && !$pendingChange && Session::getCurrent()->hasRole(Role::CONTRIBUTOR);

$revisions = Dictionary::getEntryService()->getEntryRevisions($entry);

include_once 'tpl/header.php';

?>
<script type="text/javascript">
/* <![CDATA[ */
function deleteEntry(id) {
	if (confirm('<?php echo KU_MSG_CONFIRMDELETEENTRY; ?>')) {
		$('#action').val('delete');
		$('#revisionForm').submit();
	}
}
/* ]]> */
</script>
<h3><?php echo KU_STR_VIEWENTRY; ?></h3>

<div class="listcontrols">
	<div style="float: left">
		<?php Templates::buttonLink('back', Request::getGetParam('ref', 'entries.php'), KU_STR_BACK); ?>
	</div>
	<div style="float: right">
		<form id="revisionForm" method="post">
			<input type="hidden" id="action" name="action" />
			<?php 
			if ($canEdit)
				Templates::buttonLink('edit', 'entryedit.php?id='.$entry->getId().'&amp;ref='.urlencode(KUMVA_URL_CURRENT), KU_STR_EDIT);
			if ($canDelete)
				Templates::button('delete', 'deleteEntry()', KU_STR_DELETE);
			?>
		</form>
	</div>
</div>

<?php 
if ($pendingChange && $pendingChange->getAction() == Action::DELETE)
	$message = sprintf(KU_MSG_ENTRYDELETECHANGEPENDING, $pendingChangeUrl);
elseif ($pendingChange)
	$message = sprintf(KU_MSG_ENTRYCHANGEPENDING, $pendingChangeUrl);
elseif ($entry->isDeleted())
	$message = KU_MSG_ENTRYDELETED;
elseif ($revision->isUnverified())
	$message = KU_MSG_ENTRYNOTVERIFIED;
	
if (isset($message))
	echo '<div class="info">'.$message.'</div>'; 
?>	
<table class="form">
	<tr>
		<th><?php echo KU_STR_WORDCLASS.'/'.KU_STR_NOUNCLASSES; ?></th>
		<td><?php echo $revision->getWordClass(); ?> <?php echo aka_makecsv($revision->getNounClasses()); ?></td>
	</tr>
	<tr>
		<th><?php echo KU_STR_PREFIX.'/'.KU_STR_LEMMA; ?></th>
		<td><?php Templates::word($revision, FALSE); ?></td>
	</tr>
	<tr>
		<th><?php echo KU_STR_MODIFIER; ?></th>
		<td><?php echo $revision->getModifier(); ?></td>
	</tr>
	<tr>
		<th><?php echo KU_STR_PRONUNCIATION; ?></th>
		<td>/<?php echo $revision->getPronunciation(); ?>/</td>
	</tr>
	<tr>
		<th><?php echo KU_STR_MEANINGS; ?></th>
		<td>
		<?php 
		foreach ($revision->getMeanings() as $meaning) {
			echo aka_prephtml($meaning->getMeaning());
			
			if ($meaning->getFlags() > 0) {
				$flagNames = array();
				foreach (Flags::values() as $flag) {
					if ($meaning->getFlag($flag))
						$flagNames[] = Flags::toLocalizedString($flag);
				}
				
				echo ' ['.implode(', ', $flagNames).']'; 
			}
			echo '<br/>';
		}	
		?>
		</td>
	</tr>
	<tr>
		<th><?php echo KU_STR_COMMENT; ?></th>
		<td><?php echo $revision->getComment(); ?></td>
	</tr>
	<tr>
		<td colspan="2" class="sectionheader"><?php echo KU_STR_TAGS; ?></td>
	</tr>
	<?php foreach (Dictionary::getTagService()->getRelationships() as $relationship) { 
		$tags = $revision->getTags($relationship->getId());
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
			<?php Templates::exampleList($revision->getExamples()); ?>
		</td>
	</tr>
</table>

<h3><?php echo KU_STR_HISTORY; ?></h3>

<table class="list" cellspacing="0" border="0">
	<tr>
		<th style="width: 30px">&nbsp;</th>
		<th style="width: 20px">&nbsp;</th>
		<th><?php echo KU_STR_REVISION; ?></th>
		<th><?php echo KU_STR_CHANGE; ?></th>
		<th><?php echo KU_STR_SUBMITTED; ?></th>
		<th><?php echo KU_STR_SUBMITTER; ?></th>
		<th><?php echo KU_STR_STATUS; ?></th>
		<th style="width: 30px">&nbsp;</th>
	</tr>
	<?php 
	foreach ($revisions as $rev) { 
		$change = $rev->getChange();
		$itemUrl = 'entry.php?id='.$entry->getId().'&amp;rev='.$rev->getNumber().'&amp;ref='.urlencode(Request::getGetParam('ref'));
		?>
		<tr class="rowlink" onclick="aka_goto('<?php echo $itemUrl; ?>')">
			<td>&nbsp;</td>
			<td><?php Templates::icon('change'); ?></td>
			<td class="primarycol" style="text-align:center"><?php echo $rev->getNumber(); ?></td>
			<?php if ($change) { ?>
				<td style="text-align:center"><a href="change.php?id=<?php echo $change->getId(); ?>"><?php echo $change->getId(); ?></a></td>
				<td style="text-align:center"><?php echo Templates::dateTime($change->getSubmitted()); ?></td>
				<td style="text-align:center"><?php echo Templates::userLink($change->getSubmitter()); ?></td>
				<td style="text-align: center" class="status-<?php echo $change->getStatus(); ?>">
					<?php echo Status::toLocalizedString($change->getStatus()); ?>
				</td>
			<?php } else { ?>
				<td style="text-align:center" colspan="4"><i>No change information</i></td>
			<?php } ?>
			<td>&nbsp;</td>
		</tr>
	<?php } ?>
</table>
<div id="pager"></div>
	
<?php include_once 'tpl/footer.php'; ?>
