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
	$revision = Dictionary::getEntryService()->getEntryRevision($entry, RevisionPreset::LAST);
}
else {
	$revision = Dictionary::getChangeService()->getChangeRevision($change);
	$entry = $revision->getEntry();
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

	// Process watch/unwatch request
	$watch = Request::getPostParam('watch', NULL);
	if ($watch == 'watch')
		$change->watch();
	elseif ($watch == 'unwatch')
		$change->unwatch();

	// Process comment/resolve
	$action = Request::getPostParam('action', NULL);
	$commentText = Request::getPostParam('comment');

	// If action succeeds, reload URL to prevent form resubmission
	if (processAction($change, $action, $commentText))
		Request::redirect(KUMVA_URL_CURRENT);
}

$comments = $change->getComments();
$watchers = $change->getWatchers();

// Does the current user watch this change?
$curUserWatches = $change->isWatcher();

include_once 'tpl/header.php';

?>
<script type="text/javascript">
/* <![CDATA[ */
function deleteComment(commentId) {
	if (confirm('<?php echo ku_message('msg_confirmdeletecomment'); ?>')) {
		var row = $('#comment-' + commentId);
		row.animate({ backgroundColor: '#FB6C6C' }, 300);
		$.post('_ajax.php', { action:'delete-comment', targetId:commentId }, function(data) { row.slideUp(300, function() { row.remove(); }); }, 'json');
	}
}
function onActionSubmit() {
	var action = $('#action').val();
	var comment = $('#comment').val();
	if ((action == 'post' || action == 'reject') && comment == '') {
		alert('<?php echo ku_message('msg_errorcomment'); ?>');
		return false;
	}
	if (action == 'reject')
		return confirm('<?php echo ku_message('msg_confirmrejectchange'); ?>');
	return true;
}
/* ]]> */
</script>

<h3><?php echo KU_STR_REVIEWCHANGE.' #'.$change->getId(); ?></h3>

<div class="listcontrols">
	<div style="float: left">
		<?php Templates::buttonLink('back', Request::getGetParam('ref', 'changes.php'), ku_message('str_back')); ?>
	</div>
	<div style="float: right">
		<form action="" method="post">
			<input type="hidden" name="watch" id="watch" />
			<?php 
			if ($curUserWatches)
				Templates::button('unwatch', "$('#watch').val('unwatch'); aka_submit(this);", ku_message('str_unwatch')); 
			else
				Templates::button('watch', "$('#watch').val('watch'); aka_submit(this);", ku_message('str_watch')); 
			?>
		</form>
	</div>
</div>

<input type="hidden" name="verdict" id="verdict" />
<table class="form">
	<tr>
		<th><?php Templates::icon('entry'); ?> <?php echo ku_message('str_entry'); ?></th>
		<td><?php Templates::wordLink($revision, $canEditDef); ?></td>
	</tr>
	<tr>
		<th><?php Templates::icon('user'); ?> <?php echo ku_message('str_submitted'); ?></th>
		<td><?php Templates::dateTime($change->getSubmitted()); ?> by <?php Templates::userLink($change->getSubmitter()); ?></td>
	</tr>
	<tr>
		<th><?php Templates::icon('status'); ?> <?php echo ku_message('str_status'); ?></th>
		<td><span class="status status-<?php echo $change->getStatus(); ?>"><?php echo Status::toLocalizedString($change->getStatus()); ?></span></td>
	</tr>
	<?php if ($change->getResolver()) { ?>
	<tr>
		<th><?php Templates::icon('user_editor'); ?> <?php echo ku_message('str_resolved'); ?></th>
		<td><?php Templates::dateTime($change->getResolved()); ?> by <?php Templates::userLink($change->getResolver()); ?></td>
	</tr>
	<?php } ?>
	<tr>
		<th><?php Templates::icon('watchers'); ?> <?php echo ku_message('str_watchers'); ?></th>
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
		<th><?php Templates::icon('auto'); ?> <?php echo ku_message('str_action'); ?></th>
		<td><?php echo Action::toLocalizedString($change->getAction()); ?></td>
	</tr>
	<tr>
		<td colspan="2" style="padding: 0">
			<div id="difftable">
				<?php 
				$accepted = Dictionary::getEntryService()->getEntryRevision($entry, RevisionPreset::ACCEPTED);
				
				if ($change->getStatus() == Status::PENDING) {
					if ($change->getAction() == Action::CREATE)
						Diff::revisions($revision, ku_message('str_proposed'), NULL, NULL);
					elseif ($change->getAction() == Action::MODIFY)
						Diff::revisions($accepted, ku_message('str_current'), $revision, ku_message('str_proposed'));
					elseif ($change->getAction() == Action::DELETE)
						Diff::revisions($accepted, ku_message('str_current'), NULL, NULL);
				}
				else {
					if ($change->getAction() == Action::CREATE)
						Diff::revisions($revision, ku_message('str_proposed'), NULL, NULL);
					elseif ($change->getAction() == Action::MODIFY)
						Diff::revisions($revision, ku_message('str_proposed'), $accepted, ku_message('str_current'));
					elseif ($change->getAction() == Action::DELETE)
						Diff::revisions($revision, ku_message('str_last'), NULL, NULL);
				}
				?>
			</div>
		</td>
	</tr>
</table>
	
<table class="form" cellspacing="0" border="0">
	<tr>
		<td colspan="3" class="sectionheader"><?php echo ku_message('str_comments'); ?></td>
	</tr>
	<?php 
	foreach($comments as $comment) {
		$text = aka_prephtml($comment->getText(), TRUE);
		$text = Markup::urlsToLinks($text);
		$text = Templates::parseReferences($text, 'entries.php');
	?>
	<tr id="comment-<?php echo $comment->getId(); ?>">
		<td width="300" style="border-bottom: solid 1px #EEE">
			<?php Templates::icon($comment->isApproval() ? 'approve' : 'comment'); ?>
			
			<?php Templates::dateTime($comment->getCreated()); ?>
			
			<?php Templates::userLink($comment->getUser()); ?>
		</td>
		<td style="border-bottom: solid 1px #EEE"><?php echo $text; ?></td>
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
					Templates::button('post', "$('#action').val('post'); aka_submit(this)", ku_message('str_post')); 
				if ($canApprove && $change->isPending())
					Templates::button('approve', "$('#action').val('approve'); aka_submit(this)", ku_message('str_approve'));
				if ($canResolve && $change->isPending()) {
					Templates::button('accept', "$('#action').val('accept'); aka_submit(this);", ku_message('str_accept'));
					Templates::button('reject', "$('#action').val('reject'); aka_submit(this);", ku_message('str_reject'));
				}
				
				if (!$curUserWatches) {
					?>
					<input type="checkbox" name="watch" value="watch" checked="checked" /> <?php echo ku_message('str_watchthischange'); ?>
				<?php } ?>
				<br />
				<span style="font-size: 9px"><?php echo ku_message('msg_commentinstructions'); ?></span>
			</form>
		</td>
	</tr>
</table>

<?php include_once 'tpl/footer.php'; ?>
