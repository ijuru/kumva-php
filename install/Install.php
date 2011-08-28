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
 * Purpose: Install functions
 */
 
define('KUMVA_SQL_SCRIPT', 'tables_1.0.sql');
	
function kumva_install() {
		
	$db = Database::getCurrent();
	
	// Create tables from scripts
	if (!kumva_install_createtables($db))
		return FALSE;
		
	// Insert standard data
	if (!kumva_install_insertdata($db))
		return FALSE;
		
	// Load languages from lang directory
	if (!Dictionary::getLanguageService()->reloadLanguages())
		return kumva_error('Unable to load languages');
		
	kumva_info('Loaded languages');
		
	return TRUE;
}

/**
 * Creates the database tables by executing the script(s)
 * @param Database db the database connection
 */
function kumva_install_createtables($db) {
	$f = @fopen(realpath(KUMVA_SQL_SCRIPT), "r");
	if ($f === FALSE)
		return kumva_error("Error opening sql script: ".KUMVA_SQL_SCRIPT);
		
	$sql = fread($f, filesize(KUMVA_SQL_SCRIPT));
	fclose($f);

	$sql = str_replace("{DBNAME}", KUMVA_DB_NAME, $sql);
	$sql = str_replace("{DBPREFIX}", KUMVA_DB_PREFIX, $sql);
	
	// Break into separate statements
	$statements = explode(';', $sql);

	// Execute each statement
	foreach ($statements as $statement) {
		$statement = trim($statement);
	
		if (strlen($statement) > 0) {
			$result = $db->query($statement);
			if (!$result){
				kumva_error("Error executing statement: ".$statement);
				break;
			}
		}
	}
	kumva_info("Created all database tables");
	return TRUE;
}

/**
 * Populates database tables with required data
 * @param Database db the database connection
 */
function kumva_install_insertdata($db) {
	global $adminuser, $adminpass, $adminname, $adminemail;

	// Create standard relationships
	if ($db->query("INSERT INTO `".KUMVA_DB_PREFIX."relationship` VALUES(NULL, 'form', 'Form', 'Possible surface forms of the entry', 1, 1, '@D')")
	 && $db->query("INSERT INTO `".KUMVA_DB_PREFIX."relationship` VALUES(NULL, 'variant', 'Variant', 'Valid alternative spellings of the entry', 1, 1, '@D')")
	 && $db->query("INSERT INTO `".KUMVA_DB_PREFIX."relationship` VALUES(NULL, 'meaning', 'Meaning', 'Translations of the entry', 1, 1, '@M')")
	 && $db->query("INSERT INTO `".KUMVA_DB_PREFIX."relationship` VALUES(NULL, 'root', 'Root', 'Words from which entry is derived', 1, 0, '@D')")
	 && $db->query("INSERT INTO `".KUMVA_DB_PREFIX."relationship` VALUES(NULL, 'category', 'Category', 'Categories which describe the domains of the entry', 1, 0, '@M')"))
		kumva_info("Created standard relationships");
	else
		return kumva_error("Unable to create standard relationships");
		
	// Create standard user ranks
	if ($db->query("INSERT INTO `".KUMVA_DB_PREFIX."rank` VALUES(NULL, 'Indakemwa', 0)")
	 && $db->query("INSERT INTO `".KUMVA_DB_PREFIX."rank` VALUES(NULL, 'Indatwa', 25)")
	 && $db->query("INSERT INTO `".KUMVA_DB_PREFIX."rank` VALUES(NULL, 'Indangamirwa', 100)")
	 && $db->query("INSERT INTO `".KUMVA_DB_PREFIX."rank` VALUES(NULL, 'Inararibonye', 500)")
	 && $db->query("INSERT INTO `".KUMVA_DB_PREFIX."rank` VALUES(NULL, 'Intyoza', 2500)"))
		kumva_info("Created standard user ranks");
	else
		return kumva_error("Unable to create standard user ranks");
		
	// Create standard subscriptions
	if ($db->query("INSERT INTO `".KUMVA_DB_PREFIX."subscription` VALUES(NULL, 'New change', 'Another user has submitted a change proposal')")
	 && $db->query("INSERT INTO `".KUMVA_DB_PREFIX."subscription` VALUES(NULL, 'New comment', 'Another user has commented on a change proposal')")
	 && $db->query("INSERT INTO `".KUMVA_DB_PREFIX."subscription` VALUES(NULL, 'Change resolved', 'A change has been resolved by an editor')"))
		kumva_info("Created subscriptions");
	else
		return kumva_error("Unable to create subscriptions");
		
	// Create standard user roles
	if ($db->query("INSERT INTO `".KUMVA_DB_PREFIX."role` VALUES(NULL, 'Administrator', 'Can manage the site')")
	 && $db->query("INSERT INTO `".KUMVA_DB_PREFIX."role` VALUES(NULL, 'Editor', 'Can approve changes')")
	 && $db->query("INSERT INTO `".KUMVA_DB_PREFIX."role` VALUES(NULL, 'Contributor', 'Can submit changes')"))
		kumva_info("Created standard user roles");
	else
		return kumva_error("Unable to create standard user roles");
	
	// Create admin user
	$password = md5($adminpass);
	$salt = sha1(uniqid());
	$userId = $db->insert("INSERT INTO `".KUMVA_DB_PREFIX."user` VALUES(NULL, '$adminuser', SHA1(CONCAT('$salt', '$password')), '$salt', '$adminname', '$adminemail', NULL, NULL, NULL, 0)");	
	if ($userId !== FALSE
	 && $db->query("INSERT INTO `".KUMVA_DB_PREFIX."user_role` VALUES($userId, 1)")				// Give administrator role
	 && $db->query("INSERT INTO `".KUMVA_DB_PREFIX."user_subscription` VALUES($userId, 1)"))		// Subscribe to new change notifications
		kumva_info("Created admin user");
	else
		return kumva_error("Unable to add admin user");
		
	return TRUE;
}

?>