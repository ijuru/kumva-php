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
 * Purpose: User form class
 */

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

?>
