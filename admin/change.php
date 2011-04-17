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
 * Purpose: Change review page
 */

include_once '../inc/kumva.php';

Session::requireUser();

$changeId = (int)Request::getGetParam('id', 0);
$change = Dictionary::getChangeService()->getChange($changeId);
if ($change->getAction() == Action::DELETE) {
	$entry = $change->getEntry();
	$definition = Dictionary::getDefinitionService()->getEntryRevision($entry, Revision::LAST);
}
else {
	$definition = Dictionary::getChangeService()->getChangeDefinition($change);
	$entry = $definition->getEntry();
}
$curUser = Session::getCurrent()->getUser();
$commentText = '';

// User can resolve a pending change if they are an editor, and they are not the submitter
$canEditDef = $change->getAction() != Action::DELETE;
$canComment = $curUser->hasRole(Role::CONTRIBUTOR);
$canApprove = $curUser->hasRole(Role::CONTRIBUTOR) && !$curUser->equals($change->getSubmitter());
$canResolve = (($curUser->hasRole(Role::EDITOR) && !$curUser->equals($change->getSubmitter())) || $curUser->hasRole(Role::ADMINISTRATOR));

/**
 * Process the change with the given action and comment
 */
function processAction($change, $action, $commentText) {
	global $canComment, $canApprove, $canResolve;

	$commentApproval = ($action == 'approve');
	if ($commentText || $commentApproval) {
		$comment = new Comment(0, Session::getCurrent()->getUser()->getId(), time(), $commentApproval, $commentText);
		if (!Dictionary::getChangeService()->saveComment($change, $comment))
			return FALSE;	
	}
	
	if ($action =='post' && $commentText && $canComment) {
		Notifications::newComment($change, $comment);
		return TRUE;
	}
	elseif ($action == 'approve' && $canApprove && $change->isPending()) {
		Notifications::newComment($change, $comment);
		return TRUE;	
	}
	elseif ($action == 'accept' && $canResolve && $change->isPending()) {
		if (Dictionary::getChangeService()->acceptChange($change)) {
			Notifications::changeAccepted($change);
			return TRUE;
		}
	}
	elseif ($action == 'reject' && $commentText && $canResolve && $change->isPending()) {
		if (Dictionary::getChangeService()->rejectChange($change)) {
			Notifications::changeRejected($change, $comment);
			return TRUE;
		}
	}
	
	return FALSE;
}

// Process action
if (Request::isPost()) {
	$action = Request::getPostParam('action', NULL);
	$commentText = Request::getPostParam('comment');

	if (processAction($change, $action, $commentText))
		$commentText = '';
}

// Process watch/unwatch request
$watch = Request::getPostParam('watch', NULL);
if ($watch == 'watch')
	$change->watch();
elseif ($watch == 'unwatch')
	$change->unwatch();

$comments = $change->getComments();
$watchers = $change->getWatchers();

// Does the current user watch this change?
$curUserWatches = $change->isWatcher();

include_once 'tpl/header.php';

?>
<script type="text/javascript">
/* <![CDATA[ */
function deleteComment(commentId) {
	if (confirm('<?php echo KU_MSG_CONFIRMDELETECOMMENT; ?>')) {
		var row = $('#comment-' + commentId);
		row.animate({ backgroundColor: '#FB6C6C' }, 300);
		$.post('_ajax.php', { action:'delete-comment', targetId:commentId }, function(data) { row.slideUp(300, function() { row.remove(); }); }, 'json');
	}
}
function onActionSubmit() {
	var action = $('#action').val();
	var comment = $('#comment').val();
	if ((action == 'post' || action == 'reject') && comment == '') {
		alert('<?php echo KU_MSG_ERRORCOMMENT; ?>');
		return false;
	}
	if (action == 'reject')
		return confirm('<?php echo KU_MSG_CONFIRMREJECTCHANGE; ?>');
	return true;
}
/* ]]> */
</script>

<h3><?php echo KU_STR_REVIEWCHANGE.' #'.$change->getId(); ?></h3>

<div class="listcontrols">
	<div style="float: left">
		<?php Templates::buttonLink('back', Request::getGetParam('ref', 'changes.php'), KU_STR_BACK); ?>
	</div>
	<div style="float: right">
		<form action="" method="post">
			<input type="hidden" name="watch" id="watch" />
			<?php 
			if ($curUserWatches)
				Templates::button('unwatch', "$('#watch').val('unwatch'); aka_submit(this);", KU_STR_UNWATCH); 
			else
				Templates::button('watch', "$('#watch').val('watch'); aka_submit(this);", KU_STR_WATCH); 
			?>
		</form>
	</div>
</div>

<input type="hidden" name="verdict" id="verdict" />
<table class="form">
	<tr>
		<th><?php Templates::icon('entry'); ?> <?php echo KU_STR_ENTRY; ?></th>
		<td><?php Templates::definitionLink($definition, $canEditDef); ?></td>
	</tr>
	<tr>
		<th><?php Templates::icon('user'); ?> <?php echo KU_STR_SUBMITTED; ?></th>
		<td><?php Templates::dateTime($change->getSubmitted()); ?> by <?php Templates::userLink($change->getSubmitter()); ?></td>
	</tr>
	<tr>
		<th><?php Templates::icon('status'); ?> <?php echo KU_STR_STATUS; ?></th>
		<td><span class="status status-<?php echo $change->getStatus(); ?>"><?php echo Status::toLocalizedString($change->getStatus()); ?></span></td>
	</tr>
	<?php if ($change->getResolver()) { ?>
	<tr>
		<th><?php Templates::icon('user_editor'); ?> <?php echo KU_STR_RESOLVED; ?></th>
		<td><?php Templates::dateTime($change->getResolved()); ?> by <?php Templates::userLink($change->getResolver()); ?></td>
	</tr>
	<?php } ?>
	<tr>
		<th><?php Templates::icon('watchers'); ?> <?php echo KU_STR_WATCHERS; ?></th>
		<td>
		<?php  
		for ($w = 0; $w < count($watchers); $w++) {
			if ($w > 0)
				echo ', ';
			Templates::userLink($watchers[$w]);
		}
		?>
		</td>
	</tr>
	<tr>
		<th><?php Templates::icon('auto'); ?> <?php echo KU_STR_ACTION; ?></th>
		<td><?php echo Action::toLocalizedString($change->getAction()); ?></td>
	</tr>
	<tr>
		<td colspan="2" style="padding: 0">
			<div id="difftable">
				<?php 
				$accepted = Dictionary::getDefinitionService()->getEntryRevision($entry, Revision::ACCEPTED);
				
				if ($change->getStatus() == Status::PENDING) {
					if ($change->getAction() == Action::CREATE)
						Diff::definitions($definition, KU_STR_PROPOSED, NULL, NULL);
					elseif ($change->getAction() == Action::MODIFY)
						Diff::definitions($accepted, KU_STR_CURRENT, $definition, KU_STR_PROPOSED);
					elseif ($change->getAction() == Action::DELETE)
						Diff::definitions($accepted, KU_STR_CURRENT, NULL, NULL);
				}
				else {
					if ($change->getAction() == Action::CREATE)
						Diff::definitions($definition, KU_STR_PROPOSED, NULL, NULL);
					elseif ($change->getAction() == Action::MODIFY)
						Diff::definitions($definition, KU_STR_PROPOSED, $accepted, KU_STR_CURRENT);
					elseif ($change->getAction() == Action::DELETE)
						Diff::definitions($definition, KU_STR_LAST, NULL, NULL);
				}
				?>
			</div>
		</td>
	</tr>
</table>
	
<table class="form" cellspacing="0" border="0">
	<tr>
		<td colspan="3" class="sectionheader"><?php echo KU_STR_COMMENTS; ?></td>
	</tr>
	<?php foreach($comments as $comment) { ?>
	<tr id="comment-<?php echo $comment->getId(); ?>">
		<td width="300" style="border-bottom: solid 1px #EEE">
			<?php Templates::icon($comment->isApproval() ? 'approve' : 'comment'); ?>
			
			<?php Templates::dateTime($comment->getCreated()); ?>
			
			<?php Templates::userLink($comment->getUser()); ?>
		</td>
		<td style="border-bottom: solid 1px #EEE"><?php echo Templates::parseReferences(htmlspecialchars($comment->getText()), 'entries.php'); ?></td>
		<td style="border-bottom: solid 1px #EEE" align="right">
			<?php 
			if (Session::getCurrent()->hasRole(Role::ADMINISTRATOR)) 
				Templates::iconLink('delete', 'javascript:deleteComment('.$comment->getId().')'); 
			?>
		</td>
	</tr>
	<?php } ?>
	<tr>
		<td colspan="3">
			<form method="post" onsubmit="return onActionSubmit();">
				<input type="hidden" name="action" id="action" />
				<textarea id="comment" name="comment" style="width: 400px; height: 60px; vertical-align: top"><?php echo $commentText; ?></textarea>
				<?php
				if ($canComment)
					Templates::button('post', "$('#action').val('post'); aka_submit(this)", KU_STR_POST); 
				if ($canApprove && $change->isPending())
					Templates::button('approve', "$('#action').val('approve'); aka_submit(this)", KU_STR_APPROVE);
				if ($canResolve && $change->isPending()) {
					Templates::button('accept', "$('#action').val('accept'); aka_submit(this);", KU_STR_ACCEPT);
					Templates::button('reject', "$('#action').val('reject'); aka_submit(this);", KU_STR_REJECT);
				}
				
				if (!$curUserWatches) {
					?>
					<input type="checkbox" name="watch" value="watch" checked="checked" /> <?php echo KU_STR_WATCHTHISCHANGE; ?>
				<?php } ?>
				<br />
				<span style="font-size: 9px"><?php echo KU_MSG_COMMENTINSTRUCTIONS; ?></span>
			</form>
		</td>
	</tr>
</table>

<?php include_once 'tpl/footer.php'; ?>
