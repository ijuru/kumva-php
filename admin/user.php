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

// Allow user's to edit their own profiles
$userId = (int)Request::getGetParam('id', 0);
$curUser = Session::getCurrent()->getUser();
$isEditingSelf = ($curUser != NULL && $curUser->getId() == $userId);
if (!$isEditingSelf)
	Session::requireRole(Role::ADMINISTRATOR);

/**
 * Validator for user objects
 */
class UserValidator extends Validator {
	public function validate($user, $errors) {
		if (!$user->getName())
			$errors->addForProperty('name', KU_MSG_ERROREMPTY);
			
		if (!$user->getLogin())
			$errors->addForProperty('login', KU_MSG_ERROREMPTY);
		elseif (!preg_match("/^[A-Za-z0-9]+$/", $user->getLogin()))
			$errors->addForProperty('login', KU_MSG_ERRORALPHANUMERIC);
			
		if (!$user->getEmail())
			$errors->addForProperty('email', KU_MSG_ERROREMPTY);
		elseif (!preg_match('/^[^@]+@[a-zA-Z0-9._-]+\.[a-zA-Z]+$/', $user->getEmail()))
			$errors->addForProperty('email', KU_MSG_ERROREMAILFORMAT);
	}
}

/**
 * Form controller for add/edit user
 */
class UserForm extends Form {
	/**
	 * @see Form::createEntity()
	 */
	protected function createEntity() {
		global $userId;	
		$user = Dictionary::getUserService()->getUser($userId);
		return ($user != NULL) ? $user : new User();
	}
	
	/**
	 * @see Form::saveEntity()
	 */
	protected function saveEntity($user) {
		global $isEditingSelf;
		
		$password = Request::getPostParam('password');
		if ($password == '')
			$password = NULL;
			
		// Update user subscriptions
		$subscriptionIds = Request::getPostParam('subscriptions', array());
		$subscriptions = array();
		foreach ($subscriptionIds as $subscriptionId)
			$subscriptions[] = Dictionary::getUserService()->getSubscription($subscriptionId);
		$user->setSubscriptions($subscriptions);
		
		// Update user roles
		if (Session::getCurrent()->hasRole(Role::ADMINISTRATOR)) {
			$roleIds = Request::getPostParam('roles', array());
			$roles = array();
			foreach ($roleIds as $roleId)
				$roles[] = Dictionary::getUserService()->getRole($roleId);
			$user->setRoles($roles);
		}
		
		$res = Dictionary::getUserService()->saveUser($user, $password, TRUE);
		
		// Update session user if we are editing our own user account
		if ($isEditingSelf)
			Session::getCurrent()->reloadUser();
		
		return $res;
	}
}
 
$returnUrl = Request::getGetParam('ref', 'users.php');
$form = new UserForm($returnUrl, new UserValidator(), new FormRenderer());
$user = $form->getEntity();

include_once 'tpl/header.php';

?>
<script type="text/javascript">
/* <![CDATA[ */
$(document).ready(function() {
	$('#userform').submit(function() {
		var password = $('#dummy_p1').val();
		var confirm = $('#dummy_p2').val();
		var newUser = <?php echo $form->getEntity()->isNew() ? 'true' : 'false' ?>;
	
		if (newUser || password.length > 0) {
			if (password.length < 6) {
				$('#error_password').text('Password must be at least 6 characters');
				return false;
			}
			if (password != confirm) {
				$('#error_password').text('Password mismatch');
				return false;
			}
			$('#password').val(aka_md5(password));
		}
		return true;
	});
});
/* ]]> */
</script>

<h3><?php echo $form->getEntity()->isNew() ? KU_STR_ADDUSER : ($isEditingSelf ? KU_STR_EDITPROFILE : KU_STR_EDITUSER) ?></h3>

<?php 
	$form->start('userform'); 
	if (count($form->getErrors()->get()) > 0)
		echo '<div class="error">'.implode('<br />', $form->getErrors()->get()).'</div>';
?>
	<input type="hidden" name="password" id="password" />
	<table class="form">
		<tr>
			<th><?php echo KU_STR_NAME; ?></th>
			<td><?php $form->textField('name'); ?> <?php $form->errors('name'); ?></td>
		</tr>
		<tr>
			<th><?php echo KU_STR_LOGIN; ?></th>
			<td><?php $form->textField('login'); ?> <?php $form->errors('login'); ?></td>
		</tr>
		<tr>
			<th><?php echo KU_STR_EMAIL; ?></th>
			<td><?php $form->textField('email'); ?> <?php $form->errors('email'); ?></td>
		</tr>
		<tr>
			<th><?php echo KU_STR_WEBSITE; ?></th>
			<td><?php $form->textField('website'); ?> <?php $form->errors('website'); ?></td>
		</tr>
		<tr>
			<th><?php echo KU_STR_TIMEZONE; ?></th>
			<td><?php $form->dropdown('timezone', timezone_identifiers_list(), FALSE); ?> <?php $form->errors('timezone'); ?></td>
		</tr>
		<?php if (Session::getCurrent()->hasRole(Role::ADMINISTRATOR)) { ?>
			<tr>
				<td colspan="2" class="sectionheader"><?php echo KU_STR_ROLES; ?></td>
			</tr>
			<?php
			$allRoles = Dictionary::getUserService()->getRoles();
			$userRoles = $user->getRoles();
			foreach ($allRoles as $role) {
				$hasRole = $role->inArray($userRoles);
				?>
				<tr>
					<th><?php echo $role->getName(); ?></th>
					<td>		
						<input type="checkbox" name="roles[]" value="<?php echo $role->getId(); ?>" <?php echo $hasRole ? 'checked="checked"' : ''?> /> 
						<?php echo $role->getDescription(); ?>
					</td>
				</tr>	
				<?php 	
				}
			} 
		?>
		<tr>
			<td colspan="2" class="sectionheader"><?php echo KU_STR_NOTIFICATIONS; ?></td>
		</tr>
		<tr>
			<?php
			$allSubscriptions = Dictionary::getUserService()->getSubscriptions();
			$userSubscriptions = $user->getSubscriptions();
			foreach ($allSubscriptions as $subscription) {
				$hasSubscription = $subscription->inArray($userSubscriptions);
				?>
				<tr>
					<th><?php echo $subscription->getName(); ?></th>
					<td>		
						<input type="checkbox" name="subscriptions[]" value="<?php echo $subscription->getId(); ?>" <?php echo $hasSubscription ? 'checked="checked"' : ''?> /> 
						<?php echo $subscription->getDescription(); ?>
					</td>
				</tr>	
				<?php 	
			}
			?>
		</tr>
		<tr>
			<td colspan="2" class="sectionheader"><?php echo KU_STR_ACCOUNT; ?></td>
		</tr>
		<tr>
			<th><?php echo KU_STR_PASSWORD; ?></th>
			<td>
				<input type="password" id="dummy_p1" /> <span id="error_password" class="error"></span><br />
				<input type="password" id="dummy_p2" /> (confirm)
			</td>
		</tr>
		<?php if (Session::getCurrent()->hasRole(Role::ADMINISTRATOR)) { ?>
			<tr>
				<th><?php echo KU_STR_VOIDED; ?></th>
				<td><?php $form->checkbox('voided'); ?> <?php $form->errors('voided'); ?></td>
			</tr>	
		<?php } ?>
		<tr>
			<td colspan="2"><hr /><?php $form->saveButton(); $form->cancelButton(); ?></td>
		</tr>
	</table>
<?php $form->end();

include_once 'tpl/footer.php';
?>
