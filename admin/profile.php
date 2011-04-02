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
 * Purpose: User profile page
 */

include_once '../inc/kumva.php';

Session::requireUser();

$userId = (int)Request::getGetParam('id', 0);
$user = Dictionary::getUserService()->getUser($userId);
$activity = Dictionary::getUserService()->getUserActivity($user);
$rank = Dictionary::getUserService()->getRankForScore($activity['score']);

$curUser = Session::getCurrent()->getUser();
$isViewingSelf = ($curUser != NULL && $curUser->getId() == $userId);

$whichChanges = Request::getGetParam('changes', 'proposed');
$paging = new Paging('start', 10);
if ($whichChanges == 'proposed')
	$changes = Dictionary::getChangeService()->getChangesBySubmitter($user, NULL, $paging);
else
	$changes = Dictionary::getChangeService()->getChangesByResolver($user, NULL, $paging);

include_once 'tpl/header.php';

?>
<script type="text/javascript">
/* <![CDATA[ */
function voidUser(id) {
	if (confirm('<?php echo KU_MSG_CONFIRMVOIDUSER; ?>'))
		aka_goto('users.php?voidId=' + id);
}
/* ]]> */
</script>

<h3><?php echo KU_STR_VIEWPROFILE; ?></h3>

<div class="listcontrols">
	<div style="float: left">
		<?php Templates::buttonLink('back', Request::getGetParam('ref', 'users.php'), KU_STR_BACK); ?>
	</div>
	<div style="float: right">
		<?php
		if (Session::getCurrent()->hasRole(Role::ADMINISTRATOR) || $isViewingSelf) 
			Templates::buttonLink('edit', 'user.php?id='.$user->getId().'&amp;ref='.urlencode(KUMVA_URL_CURRENT), KU_STR_EDIT); 
		if (Session::getCurrent()->hasRole(Role::ADMINISTRATOR) && !$isViewingSelf) 
			Templates::button('delete', 'javascript:voidUser('.$user->getId().')', KU_STR_VOID);
		?>
	</div>
</div>
<?php 
if ($user->isVoided())
	echo '<div class="info">'.KU_MSG_USERVOIDED.'</div>'; 
?>

<table class="form">
	<tr>
		<th><?php Templates::icon('user'); ?> <?php echo KU_STR_NAME; ?></th>
		<td><?php echo $user->getName().' ('.$user->getLogin().')'; ?></td>
	</tr>
	<tr>
		<th><?php Templates::icon('date'); ?> <?php echo KU_STR_LASTLOGIN; ?></th>
		<td><?php Templates::dateTime($user->getLastLogin()); ?></td>
	</tr>
	<tr>
		<th><?php Templates::icon('email'); ?> <?php echo KU_STR_EMAIL; ?></th>
		<td><a href="mailto:<?php echo $user->getEmail(); ?>"><?php echo $user->getEmail(); ?></a></td>
	</tr>
	<tr>
		<th><?php Templates::icon('website'); ?> <?php echo KU_STR_WEBSITE; ?></th>
		<td><a href="<?php echo $user->getWebsite(); ?>"><?php echo $user->getWebsite(); ?></a></td>
	</tr>
	<tr>
		<th><?php Templates::icon('roles'); ?> <?php echo KU_STR_ROLES; ?></th>
		<td>
		<?php 
		$roles = array();
		foreach ($user->getRoles() as $role)
			$roles[] = $role->getName();
		echo aka_makecsv($roles);
		?>
		</td>
	</tr>
	<tr>
		<th><?php Templates::icon('activity'); ?> <?php echo KU_STR_ACTIVITY; ?></th>
		<td>
			<?php 
			echo $activity['proposals'].' ';
			Templates::icon('definition_proposal', KU_STR_PROPOSALS);
			echo ' '.$activity['comments'].' ';
			Templates::icon('comments', KU_STR_COMMENTS);
			?>
		</td>
	</tr>
	<tr>
		<th><?php Templates::icon('rank'); ?> <?php echo KU_STR_RANK; ?></th>
		<td><?php Templates::rank($rank); ?></td>
	</tr>
</table>

<div class="listcontrols">
	<form method="get">
		<input name="id" type="hidden" value="<?php echo $user->getId(); ?>" />
		<?php echo KU_STR_CHANGES; ?>
		<select name="changes">
			<option value="proposed" <?php echo $whichChanges == 'proposed' ? 'selected="selected"' : ''; ?>><?php echo KU_STR_PROPOSED; ?></option>
			<option value="resolved" <?php echo $whichChanges == 'resolved' ? 'selected="selected"' : ''; ?>><?php echo KU_STR_RESOLVED; ?></option>
		</select>
		<?php Templates::button('refresh', "aka_submit(this)", KU_STR_REFRESH); ?>
	</form>
</div>

<?php Templates::changesTable($changes, TRUE, TRUE, FALSE, ($whichChanges != 'proposed')); ?>
	
<div id="pager">
	<?php
	if ($paging->getTotalPages() > 1) {
		Templates::pagerButtons($paging);
		echo '&nbsp;&nbsp;';
	}
	if (count($changes))
		printf(KU_MSG_PAGER, $paging->getStart() + 1, $paging->getStart() + count($changes), $paging->getTotal());
	?>			
</div>

<?php include_once 'tpl/footer.php'; ?>
