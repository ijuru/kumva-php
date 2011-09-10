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
 * Purpose: User service class
 */

/**
 * User, role and session functions
 */
class UserService extends Service {
	/**
	 * Gets the user with the given id
	 * @param int id the user id
	 * @return User the user
	 */
	public function getUser($id) {
		$row = $this->database->row('SELECT * FROM `'.KUMVA_DB_PREFIX.'user` WHERE user_id = '.$id);	
		return ($row != NULL) ? User::fromRow($row) : NULL;
	}
	
	/**
	 * Gets the user with the given login
	 * @param string login the login
	 * @return User the user
	 */
	public function getUserByLogin($login) {
		$row = $this->database->row('SELECT * FROM `'.KUMVA_DB_PREFIX.'user` WHERE login = '.aka_prepsqlval($login));	
		return ($row != NULL) ? User::fromRow($row) : NULL;
	}
	
	/**
	 * Gets all the users
	 * @param bool incVoided TRUE to included voided users
	 * @return array the users
	 */
	public function getUsers($incVoided = FALSE) {
		$sql = 'SELECT * FROM `'.KUMVA_DB_PREFIX.'user` u ';
		if (!$incVoided)
			$sql .= 'WHERE u.voided = 0 ';
		$sql .= 'ORDER BY u.`name` ASC';
		
		return User::fromQuery($this->database->query($sql));
	}
	
	/**
	 * Gets all the users with the specified role
	 * @param Role role the role
	 * @return array the users
	 */
	public function getUsersWithRole($role) {
		$sql = 'SELECT u.* FROM `'.KUMVA_DB_PREFIX.'user` u
				INNER JOIN `'.KUMVA_DB_PREFIX.'user_role` ur ON ur.user_id = u.user_id
				WHERE ur.role_id = '.$role->getId();							   
		return User::fromQuery($this->database->query($sql));
	}
	
	/**
	 * Gets all the users with the specified subscription
	 * @param Subscription the subscription
	 * @return array the users
	 */
	public function getUsersWithSubscription($subscription) {
		$sql = 'SELECT u.* FROM `'.KUMVA_DB_PREFIX.'user` u
				INNER JOIN `'.KUMVA_DB_PREFIX.'user_subscription` us ON us.user_id = u.user_id
				WHERE us.subscription_id = '.$subscription->getId();							   
		return User::fromQuery($this->database->query($sql));
	}
	
	/**
	 * Checks the given users password
	 * @param User user the user
	 * @param string password the password
	 * @return bool true is password matches password in the database
	 */
	public function checkUserPassword($user, $password) {
		$sql = 'SELECT password, salt FROM `'.KUMVA_DB_PREFIX.'user` WHERE user_id = '.$user->getId();
		$row = $this->database->row($sql);
		$dbpass = $row['password'];
		$dbsalt = $row['salt'];
		
		return $this->hashPassword($dbsalt, $password) == $dbpass;
	}
	
	/**
	 * Gets the role with the given id
	 * @param int id the role id
	 * @return Role the role
	 */
	public function getRole($id) {
		$row = $this->database->row('SELECT * FROM `'.KUMVA_DB_PREFIX.'role` WHERE role_id = '.$id);	
		return ($row != NULL) ? Role::fromRow($row) : NULL;
	}
	
	/**
	 * Gets all roles
	 * @return array the roles
	 */
	public function getRoles() {
		return Role::fromQuery($this->database->query('SELECT * FROM `'.KUMVA_DB_PREFIX.'role`'));
	}
	
	/**
	 * Gets the roles for the given user
	 * @param User user the user
	 * @return array the roles
	 */
	public function getUserRoles($user) {
		$sql = 'SELECT * FROM `'.KUMVA_DB_PREFIX.'role` r 
				INNER JOIN `'.KUMVA_DB_PREFIX.'user_role` ur ON ur.role_id = r.role_id
				WHERE ur.user_id = '.$user->getId();
		return Role::fromQuery($this->database->query($sql));
	}
	
	/**
	 * Gets all ranks
	 * @return array the ranks
	 */
	public function getRanks() {
		return Rank::fromQuery($this->database->query('SELECT * FROM `'.KUMVA_DB_PREFIX.'rank`'));
	}
	
	/**
	 * Gets the subscription with the given id
	 * @param int id the subscription id
	 * @return Subscription the subscription
	 */
	public function getSubscription($id) {
		$row = $this->database->row('SELECT * FROM `'.KUMVA_DB_PREFIX.'subscription` WHERE subscription_id = '.$id);	
		return ($row != NULL) ? Subscription::fromRow($row) : NULL;
	}
	
	/**
	 * Gets all subscriptions
	 * @return array the subscriptions
	 */
	public function getSubscriptions() {
		return Subscription::fromQuery($this->database->query('SELECT * FROM `'.KUMVA_DB_PREFIX.'subscription`'));
	}
	
	/**
	 * Gets the subscriptions for the given user
	 * @param User user the user
	 * @return array the subscriptions
	 */
	public function getUserSubscriptions($user) {
		$sql = 'SELECT * FROM `'.KUMVA_DB_PREFIX.'subscription` s 
				INNER JOIN `'.KUMVA_DB_PREFIX.'user_subscription` us ON us.subscription_id = s.subscription_id
				WHERE us.user_id = '.$user->getId();
		return Subscription::fromQuery($this->database->query($sql));
	}
	
	/**
	 * Gets counts of comments and proposals for the given user
	 * @param User user the user
	 * @return array the counts (keys: 'proposals', 'pending', 'accepted', 'rejected', 'comments')
	 */
	public function getUserActivity($user) {
		$stats = Dictionary::getChangeService()->getChangeStatistics($user);
		$pending = isset($stats[Status::PENDING]) ? (int)$stats[Status::PENDING]['count'] : 0;
		$accepted = isset($stats[Status::ACCEPTED]) ? (int)$stats[Status::ACCEPTED]['count'] : 0;
		$rejected = isset($stats[Status::REJECTED]) ? (int)$stats[Status::REJECTED]['count'] : 0;
		$comments = $this->database->scalar('SELECT COUNT(*) FROM `'.KUMVA_DB_PREFIX.'comment` WHERE voided = 0 AND user_id = '.$user->getId());
		$score = $accepted * 3 + $comments;
		
		return array('proposals' => ($pending + $accepted + $rejected), 
					 'pending' => $pending, 
					 'accepted' => $accepted, 
					 'rejected' => $rejected, 
					 'comments' => $comments,
					 'score' => $score);
	}
	
	/**
	 * Gets the rank for the given score
	 * @param int score the score
	 * @return int the rank
	 */
	public function getRankForScore($score) {
		$sql = 'SELECT * FROM `'.KUMVA_DB_PREFIX.'rank` WHERE threshold <= '.(int)$score.' ORDER BY rank_id DESC LIMIT 1';
		$row = $this->database->row($sql);
		return ($row != NULL) ? Rank::fromRow($row) : NULL;
	}
	
	/**
	 * Saves a user to the database
	 * @param User user the user to save
	 * @param string password the user's MD5 encrypted password
	 * @param bool updateExtras TRUE if user roles and subscriptions should be updated
	 * @return bool TRUE if save was successful, else FALSE
	 */
	public function saveUser($user, $password = NULL, $updateExtras = TRUE) {
		if ($user->isNew()) {
			$salt = sha1(uniqid()); // Random salt
			$encPassword = $this->hashPassword($salt, $password);
			
			$sql = 'INSERT INTO `'.KUMVA_DB_PREFIX.'user` VALUES('
				.'NULL,'
				.aka_prepsqlval($user->getLogin()).','
				.aka_prepsqlval($encPassword).','
				.aka_prepsqlval($salt).','
				.aka_prepsqlval($user->getName()).','
				.aka_prepsqlval($user->getEmail()).','
				.aka_prepsqlval($user->getWebsite()).','
				.aka_prepsqlval($user->getTimezone()).','
				.'NULL,'
				.'NULL,'
				.aka_prepsqlval($user->getFailedLoginAttempts()).','
				.aka_prepsqlval($user->getRememberToken()).','
				.aka_prepsqlval($user->isVoided()).')';
			
			$res = $this->database->insert($sql);
			if ($res === FALSE)
				return FALSE;			
			$user->setId($res);
		}
		else {
			$sql = 'UPDATE `'.KUMVA_DB_PREFIX.'user` SET '
				.'login = '.aka_prepsqlval($user->getLogin()).',';
			
			if ($password != NULL) {
				$salt = $this->database->scalar('SELECT salt FROM `'.KUMVA_DB_PREFIX.'user` WHERE user_id = '.$user->getId());
				$encPassword = $this->hashPassword($salt, $password);
				$sql .= 'password = '.aka_prepsqlval($encPassword).',';
			}
			
			$sql .= 'name = '.aka_prepsqlval($user->getName()).','
				.'email = '.aka_prepsqlval($user->getEmail()).','
				.'website = '.aka_prepsqlval($user->getWebsite()).','
				.'timezone = '.aka_prepsqlval($user->getTimezone()).','
				.'lastlogin = '.aka_timetosql($user->getLastLogin()).', '
				.'lastloginattempt = '.aka_timetosql($user->getLastLoginAttempt()).', '
				.'failedloginattempts = '.aka_prepsqlval($user->getFailedLoginAttempts()).', '
				.'remembertoken = '.aka_timetosql($user->getRememberToken()).', '
				.'voided = '.aka_prepsqlval($user->isVoided()).' '
				.'WHERE user_id = '.$user->getId();

			$res = $this->database->query($sql);
			if ($res === FALSE)
				return FALSE;
		}
		
		// Save user roles and subscriptions (optional to allow quick simple updates to user objects)
		if ($updateExtras) {
			$roles = $user->getRoles();
			$subscriptions = $user->getSubscriptions();
			
			// Delete all existing roles and subscriptions
			$this->database->query('DELETE FROM `'.KUMVA_DB_PREFIX.'user_role` WHERE user_id = '.$user->getId());
			$this->database->query('DELETE FROM `'.KUMVA_DB_PREFIX.'user_subscription` WHERE user_id = '.$user->getId());
		
			foreach ($roles as $role)
				$this->database->query('INSERT INTO `'.KUMVA_DB_PREFIX.'user_role` VALUES('.$user->getId().', '.$role->getId().')');
				
			foreach ($subscriptions as $subscription)
				$this->database->query('INSERT INTO `'.KUMVA_DB_PREFIX.'user_subscription` VALUES('.$user->getId().', '.$subscription->getId().')');

		}
		
		return TRUE;
	}
	
	/**
	 * Hashes the given password with a salt
	 * @param string salt the salt
	 * @param string password the password
	 * @return string the hashed password
	 */
	private function hashPassword($salt, $password) {
		return sha1($salt.$password);
	}
	
	/**
	 * Voids the specified user
	 * @param User user the user
	 * @return bool TRUE if user was voided
	 */
	public function voidUser($user) {
		$user->setVoided(TRUE);
		return $this->database->query('UPDATE `'.KUMVA_DB_PREFIX.'user` SET voided = 1 WHERE `user_id` = '.$user->getId());
	}
}

?>
