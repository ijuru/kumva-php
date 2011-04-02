<?php
/**
 * This file is part of Akabanga.
 *
 * Akabanga is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Akabanga is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Akabanga.  If not, see <http://www.gnu.org/licenses/>.
 *
 * Copyright Rowan Seymour 2010
 * 
 * Purpose: HTTP request class
 */
	
$mobileDevices = array('android', 'iphone', 'ipod', 'blackberry');
$robotAgents = array('google', 'robot', 'yahoo', 'spider', 'archiver', 'curl');

/**
 * Class to represent a HTTP request
 */
class Request {
	/**
	 * Gets the user agent header
	 * @param bool complete return the complete header, otherwise returns only the application name/version part
	 */
	public static function getUserAgent($complete = TRUE) {
		$agent = $_SERVER['HTTP_USER_AGENT'];
		
		if (!$complete) {
			$openBracket = strpos($agent, '(');
			if ($openBracket !== FALSE)
				$agent = rtrim(substr($agent, 0, $openBracket));
		}
		
		return $agent;
	}

	/**
	 * Gets if HTTP client is a recognized mobile device
	 * @return bool TRUE if client is a mobile device, else FALSE
	 */
	public static function isMobileClient() {
		global $mobileDevices;
		$thisAgent = self::getUserAgent();
		
		foreach ($mobileDevices as $agent) {
			if (stristr($thisAgent, $agent) != FALSE)
				return TRUE;
		}
		return FALSE;
	}
	
	/**
	 * Gets if HTTP client is a recognized bot / spider
	 * @return bool TRUE if client is a bot, else FALSE
	 */
	public static function isRobot() {
		global $robotAgents;
		$thisAgent = self::getUserAgent();
		
		// Assume empty user agent is a bot
		if ($_SERVER['HTTP_USER_AGENT'] == '')
			return TRUE;
		
		foreach ($robotAgents as $agent) {
			if (stristr($thisAgent, $agent) != FALSE)
				return TRUE;
		}
		return FALSE;
	}

	/**
	 * Gets a cookie value from the request
	 * @param string name of the cookie
	 * @param string default the default value
	 * @return string the value of the cookie
	 */
	public static function getCookie($name, $default = '') {
		return isset($_COOKIE[$name]) ? $_COOKIE[$name] : $default;
	}
	
	/**
	 * Sets a cookie value from the request
	 * @param string name of the cookie
	 * @param string value the value
	 * @param int expiry milliseconds
	 * @return bool TRUE if successful, else FALSE
	 */
	public static function setCookie($name, $value, $expires) {
		return setcookie($name, $value, time() + (int)$expires, '/');
	}
	
	/**
	 * Clears a cookie from the request
	 * @param string name of the cookie
	 * @return bool TRUE if successful, else FALSE
	 */
	public static function clearCookie($name) {
		unset($_COOKIE[$name]);
		return setcookie($name, '', time());
	}

	/**
	 * Sends client a redirect response to the given url
	 * @param string url the URL to redirect to
	 */
	public static function redirect($url) {
		header('Location: '.$url);
		exit;
	}
	
	/**
	 * Gets if the named GET parameter exists
	 * @param string name the name of the parameter
	 * @return bool TRUE if parameter exists, else FALSE
	 */
	public static function hasGetParam($name) {
		return isset($_GET[$name]);
	}
	
	/**
	 * Gets the value of the named GET parameter
	 * @param string name the name of the parameter
	 * @param string default the default value
	 * @return string the value of the parameter
	 */
	public static function getGetParam($name, $default = NULL) {
		return self::hasGetParam($name) ? $_GET[$name] : $default;
	}
	
	/**
	 * Gets the value of the named POST parameter
	 * @param string name the name of the parameter
	 * @param string default the default value
	 * @return string the value of the parameter
	 */
	public static function getPostParam($name, $default = NULL) {
		return isset($_POST[$name]) ? $_POST[$name] : $default;
	}
	
	/**
	 * Gets an array of POST parameters and values
	 * @param string prefix a prefix to filter on and strip off (optional)
	 * @return array the parameter names
	 */
	public static function getPostParams($prefix = NULL) {
		if ($prefix == NULL)
			return $_POST;
		
		$params = array();
		foreach ($_POST as $param => $value) {
			if (aka_startswith($param, $prefix))	
				$params[substr($param, strlen($prefix))] = $value;
		} 
		return $params;
	}
	
	/**
	 * Gets if this request used HTTP POST
	 * @return TRUE if request used POST
	 */
	public static function isPost() {
		return count($_POST) > 0;
	}
}
 
?>
