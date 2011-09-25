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
 * Purpose: Session class
 */
 
define('KUMVA_MAXFAILEDLOGINS', 3);
define('KUMVA_ACCOUNTLOCKOUTSECONDS', 60 * 60);

// Start the PHP session engine
session_start();

/**
 * Class for user sessions
 */
class Session {
 	const SESSION_KEY = 'aka_sites';
 	const SITE_KEY = KUMVA_TITLE_SHORT;
	private $user;
	private $lang;
	private $attributes = array();
	
	public function __construct() {
		// Attempt authentication by cookies
		$login = Request::getCookie('login', null);
		$token = Request::getCookie('token', null);	
		if ($login && $token)
			$this->login($login, null, $token);
		
		// TODO make default language configurable
		$this->lang = 'en';
	}
	
	/**
	 * Gets the current session
	 * @return Session the current session
	 */
	public static function getCurrent() {
		// Create site specific session array in HTTP session
		if (!isset($_SESSION[self::SESSION_KEY]))
			$_SESSION[self::SESSION_KEY] = array();
		
		// Create session for this site	
		if (!isset($_SESSION[self::SESSION_KEY][self::SITE_KEY]))
			$_SESSION[self::SESSION_KEY][self::SITE_KEY] = new Session();
			
		return $_SESSION[self::SESSION_KEY][self::SITE_KEY];
	}

	/**
	 * Attempts to login the specified user
	 * @param string login the login name
	 * @param string password the (client) encrypted password
	 * @param string token the "remember me" token
	 * @return bool TRUE if login was successfull, else FALSE
	 */
	public function login($login, $password, $token, $remember = true) {
		session_regenerate_id();
		
		$user = Dictionary::getUserService()->getUserByLogin($login);

		if ($user) {
			$timeSinceAttempt = time() - $user->getLastLoginAttempt();
		
			// Check if user should be considered locked out
			if ($user->getFailedLoginAttempts() >= KUMVA_MAXFAILEDLOGINS && $timeSinceAttempt < KUMVA_ACCOUNTLOCKOUTSECONDS) {
					
				$this->setAttribute('login_message', ku_message('msg_accountlocked'));
				
				// Clear cookies to prevent further auto-logins with these credentials
				$this->logout();
				return false;
			}
			
			// Do check by password or token, whatever was provided
			$result = $password ? $user->checkPassword($password) : ($token && $token == $user->getRememberToken());
			
			if ($result) {
				// Update user login record
				$now = time();
				$token = $token ? $token : sha1(uniqid());
				
				$user->setRememberToken($token);
				$user->setLastLogin($now);
				$user->setLastLoginAttempt($now);
				$user->setFailedLoginAttempts(0);
				Dictionary::getUserService()->saveUser($user, null, false);
			
				// Store login and new "remember me" token in cookies
				if ($remember) {
					Request::setCookie('login', $login, 60*60*24*7);
					Request::setCookie('token', $token, 60*60*24*7);
				}
				
				$this->user = $user;
				return true;
			}
			else {	
				// If lockout time has expired then reset failed attempts count	
				$failedAttempts = $timeSinceAttempt < KUMVA_ACCOUNTLOCKOUTSECONDS ? $user->getFailedLoginAttempts() + 1 : 1;
				
				// Update user failed login record
				$user->setFailedLoginAttempts($failedAttempts);
				$user->setLastLoginAttempt(time());
				Dictionary::getUserService()->saveUser($user, null, false);	
			}
		}

		$this->setAttribute('login_message', ku_message('msg_invalidlogin'));
		return false;
	}
	
	/**
	 * Logout the current user
	 */
	public function logout() {
		$this->user = null;
		
		// Clear the username/password cookies
		Request::clearCookie('login');
		Request::clearCookie('token');
	}
	
	/**
	 * Gets the whether there is an authenticated user associated with this session
	 * @return bool TRUE if session is authenticated, else FALSE
	 */
	public function isAuthenticated() {
		return $this->user != null;
	}

	/**
	 * Gets the language code
	 * @return string the language code
	 */
	public function getLang() {
		return $this->lang;
	}
	
	/**
	 * Sets the language code
	 * @param string lang the language code
	 */
	public function setLang($lang) {
		$this->lang = $lang;
	}
	
	/**
	 * Gets the user associated with this session or NULL if there is no user
	 * @return User the user if session is valid, else NULL
	 */
	public function getUser() {
		return $this->user;
	}
	
	/**
	 * Reloads the user associated with this session from the database
	 */
	public function reloadUser() {
		$this->user = Dictionary::getUserService()->getUser($this->user->getId());
	}
	
	/**
	 * Sets the value of a named attribute
	 * @param string name the name of the attribute
	 * @param string value the value of the attribute 
	 */
	public function setAttribute($name, $value) {
		$this->attributes[$name] = $value;
	}
	
	/**
	 * Gets the value of a named attribute
	 * @param string name the name of the attribute
	 * @return string the value of the attribute 
	 */
	public function getAttribute($name, $default = NULL) {
		return isset($this->attributes[$name]) ? $this->attributes[$name] : $default;
	}
	
	/**
	 * Removes the named attribute
	 * @param string name the name of the attribute
	 */
	public function removeAttribute($name) {
		unset($this->attributes[$name]);
	}
	
	/**
	 * Gets whether the current user has the specified role
	 * @param int roleId the id of the required role
	 * @return bool TRUE if user has role, else FALSE
	 */
	public function hasRole($roleId) {
		return $this->user && $this->user->hasRole($roleId);
	}
	
	/**
	 * Checks that there is a logged in user and redirects client to login URL if there isn't
	 * @return bool TRUE if user is authenticated, else function never returns
	 */
	public static function requireUser() {
		if (!self::getCurrent()->getUser()) {
			self::getCurrent()->setAttribute('login_message', 'Must be logged in');
			Request::redirect(KUMVA_URL_LOGIN.'?ref='.urlencode(KUMVA_URL_CURRENT));
		}
		return TRUE;
	}
	
	/**
	 * Checks that the current user has the given role and redirects client to login URL if they don't
	 * @param int roleId the id of the required role
	 * @return bool TRUE if user has role, else function never returns
	 */
	public static function requireRole($roleId) {
		if (!self::getCurrent()->hasRole($roleId)) {
			$role = Dictionary::getUserService()->getRole($roleId);
			self::getCurrent()->setAttribute('login_message', $role->getName().' role required');
			Request::redirect(KUMVA_URL_LOGIN.'?ref='.urlencode(KUMVA_URL_CURRENT));
		}
		
		return TRUE;
	}
}

?>
