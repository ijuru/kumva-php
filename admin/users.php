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
 * Purpose: Search statistics page
 */

include_once '../inc/kumva.php';

Session::requireUser();

// Process void request
$voidId = Request::getGetParam('voidId', NULL);
if ($voidId && Session::getCurrent()->hasRole(Role::ADMINISTRATOR)) {
	$user = Dictionary::getUserService()->getUser($voidId);
	// Don't let users void themselves
	if ($user && $user->getId() != Session::getCurrent()->getUser()->getId())
		Dictionary::getUserService()->voidUser($user);
}

$showVoided = (bool)Request::getGetParam('showVoided', FALSE);

include_once 'tpl/header.php';
?>
<h3><?php echo KU_STR_USERS; ?></h3>

<div class="listcontrols">
	<div style="float: left">
		<form method="get" id="listform" action="">
			<input type="checkbox" name="showVoided" value="1" <?php echo $showVoided ? 'checked="checked"' : '' ?> /> <?php echo KU_STR_SHOWVOIDED; ?>
			<?php Templates::button('refresh', "$('#listform').submit()", KU_STR_REFRESH); ?>
		</form>
	</div>
	<?php if (Session::getCurrent()->hasRole(Role::ADMINISTRATOR)) { ?>
	<div style="float: right">
		<?php Templates::buttonLink('add', 'useredit.php', KU_STR_ADD); ?>
	</div>
	<?php } ?>
</div> 

<table class="list" cellspacing="0" border="0">
	<tr>
		<th style="width: 30px">&nbsp;</th>
		<th style="width: 20px">&nbsp;</th>
		<th><?php echo KU_STR_NAME; ?></th>
		<th><?php echo KU_STR_LOGIN; ?></th>
		<th><?php echo KU_STR_EMAIL; ?></th>
		<th><?php echo KU_STR_ROLES; ?></th>
		<th><?php Templates::icon('proposal', KU_STR_PROPOSALS); ?></th>
		<th><?php Templates::icon('proposal_accepted', KU_STR_ACCEPTED); ?></th>
		<th><?php Templates::icon('comments', KU_STR_COMMENTS); ?></th>
		<th><?php echo KU_STR_LASTLOGIN; ?></th>
		<th style="width: 30px">&nbsp;</th>
	</tr>
	<?php 
	$users = Dictionary::getUserService()->getUsers($showVoided);
	foreach($users as $user) { 
		$itemUrl = 'user.php?id='.$user->getId();
		$userStats = Dictionary::getUserService()->getUserActivity($user);
		?>
		<tr class="rowlink" onclick="aka_goto('<?php echo $itemUrl; ?>')">
			<td>&nbsp;</td>
			<td>
			<?php 
			if ($user->isVoided())
				Templates::icon('user_voided', KU_STR_VOIDED); 
			elseif ($user->hasRole(Role::EDITOR))
				Templates::icon('user_editor', 'Editor'); 
			else
				Templates::icon('user', KU_STR_USER); 
			?>
			</td>
			<td class="primarycol"><?php echo $user->getName(); ?></td>
			<td><?php echo $user->getLogin(); ?></td>
			<td><?php echo $user->getEmail(); ?></td>
			<td><?php 
			$roles = array();
			foreach ($user->getRoles() as $role)
				$roles[] = $role->getName();
			echo aka_makecsv($roles);
			?>
			</td>
			<td style="text-align: center"><?php echo $userStats['proposals']; ?></td>
			<td style="text-align: center"><?php echo $userStats['accepted']; ?></td>
			<td style="text-align: center"><?php echo $userStats['comments']; ?></td>
			<td style="text-align: center"><?php Templates::dateTime($user->getLastLogin()); ?></td>
			<td>&nbsp;</td>
		</tr>
	<?php } ?>
</table>

<?php include_once 'tpl/footer.php'; ?>
