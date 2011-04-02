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
 * Purpose: User class
 */

/**
 * Class to represent a user of the system
 */
class User extends Entity {
	private $login;
	private $name;
	private $email;
	private $website;
	private $timezone;
	private $lastLogin;
	
	// Lazy loaded properties
	private $roles;
	private $subscriptions;
	
	/**
	 * Constructs a new user
	 * @param int the user id
	 * @param string login the login/username
	 * @param string name the user's name
	 * @param string email the user's email address
	 * @param string website the user's website
	 * @param string timezone the user's timezone identifier
	 * @param int lastLogin the last login timestamp
	 * @param bool voided TRUE if user is voided
	 */
	public function __construct($id = 0, $login = '', $name = '', $email = NULL, $website = NULL, $timezone = NULL, $lastLogin = NULL, $voided = FALSE) {
		$this->id = (int)$id;
		$this->login = $login;
		$this->name = $name;
		$this->email = $email;
		$this->website = $website;
		$this->timezone = $timezone;
		$this->lastLogin = (int)$lastLogin;
		$this->voided = (bool)$voided;
	}
	
	/**
	 * Creates a user from the given row of database columns
	 * @param array the associative array
	 * @return User the user
	 */
	public static function fromRow(&$row) {
		return new User($row['user_id'], $row['login'], $row['name'], $row['email'], $row['website'], $row['timezone'], aka_timefromsql($row['lastlogin']), $row['voided']);
	}
	
	/**
	 * Gets the login
	 * @return string the login
	 */
	public function getLogin() {
		return $this->login;
	}
	
	/**
	 * Sets the login
	 * @param string login the login
	 */
	public function setLogin($login) {
		$this->login = $login;
	}
	
	/**
	 * Gets the name
	 * @return string the name
	 */
	public function getName() {
		return $this->name;
	}
	
	/**
	 * Sets the name
	 * @param string name the name
	 */
	public function setName($name) {
		$this->name = $name;
	}
	
	/**
	 * Gets the email address
	 * @return string the email address
	 */
	public function getEmail() {
		return $this->email;
	}
	
	/**
	 * Sets the email address
	 * @param string email the email address
	 */
	public function setEmail($email) {
		$this->email = $email;
	}
	
	/**
	 * Gets the website
	 * @return string the website
	 */
	public function getWebsite() {
		return $this->website;
	}
	
	/**
	 * Sets the website
	 * @param string email the website
	 */
	public function setWebsite($website) {
		$this->website = $website;
	}
	
	/**
	 * Gets the timezone identifier
	 * @return string the timezone identifier
	 */
	public function getTimezone() {
		return $this->timezone;
	}
	
	/**
	 * Sets the timezone identifier
	 * @param string timezone the timezone identifier
	 */
	public function setTimezone($timezone) {
		$this->timezone = $timezone;
	}
	
	/**
	 * Gets the last login time
	 * @return int the last login timestamp
	 */
	public function getLastLogin() {
		return $this->lastLogin;
	}
	
	/**
	 * Sets the last login time
	 * @param int lastLogin the last login timestamp
	 */
	public function setLastLogin($lastLogin) {
		$this->lastLogin = (int)$lastLogin;
	}
	
	/**
	 * Gets whether user has the given role
	 * @param int roleId the role id
	 * @return bool TRUE if user has role, else FALSE
	 */
	public function hasRole($roleId) {
		foreach ($this->getRoles() as $r) {
			if ($roleId == $r->getId() || $r->isSuperRole())
				return TRUE;
		}
		return FALSE;
	}
	
	/**
	 * Gets roles using lazy loading
	 * @return array the roles
	 */
	public function getRoles() {
		if ($this->roles === NULL)
			$this->roles = Dictionary::getUserService()->getUserRoles($this);
		
		return $this->roles;
	}
	
	/**
	 * Sets the roles
	 * @param roles array the roles
	 */
	public function setRoles($roles) {
		$this->roles = $roles;
	}
	
	/**
	 * Gets subscriptions using lazy loading
	 * @return array the subscriptions
	 */
	public function getSubscriptions() {
		if ($this->subscriptions === NULL)
			$this->subscriptions = Dictionary::getUserService()->getUserSubscriptions($this);
		
		return $this->subscriptions;
	}
	
	/**
	 * Sets the subscriptions
	 * @param subscriptions array the subscriptions
	 */
	public function setSubscriptions($subscriptions) {
		$this->subscriptions = $subscriptions;
	}
}

?>
