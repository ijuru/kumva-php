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
 * Purpose: Main library include file
 */
 
// Kumva version number
define('AKABANGA_VERSION', '1.0 BETA');

require_once 'utils.php';
require_once 'Enum.php';
require_once 'Request.php';
require_once 'Paging.php';
require_once 'Database.php';
require_once 'Service.php';
require_once 'Entity.php';
require_once 'BeanUtils.php';
require_once 'Errors.php';
require_once 'Validator.php';
require_once 'Renderer.php';
require_once 'Form.php';

$aka_config = array();

aka_includejs('jquery-1.4.4.min.js');
aka_includejs('md5.js');

/** 
 * Configures akabanga to connect to a database
 * @param string dbHost the server host name
 * @param string dbUser the user name
 * @param string dbPass the password
 * @param string dbName the database name
 */
function aka_dbconfigure($dbHost, $dbUser, $dbPass, $dbName) {
	global $aka_config;
	$aka_config['DB_HOST'] = $dbHost;
	$aka_config['DB_USER'] = $dbUser;
	$aka_config['DB_PASS'] = $dbPass;
	$aka_config['DB_NAME'] = $dbName;
}
?>
