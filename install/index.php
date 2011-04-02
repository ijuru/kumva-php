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
 * Purpose: Install script
 */
 
include_once '../inc/kumva.php';
 
define('KUMVA_SQL_SCRIPT', 'tables_1.0.sql');
 
$dbhost = isset($_POST['dbhost']) ? $_POST['dbhost'] : (defined('KUMVA_DB_HOST') ? KUMVA_DB_HOST : 'localhost');
$dbuser = isset($_POST['dbuser']) ? $_POST['dbuser'] : (defined('KUMVA_DB_USER') ? KUMVA_DB_USER : 'kumva');
$dbpass = isset($_POST['dbpass']) ? $_POST['dbpass'] : (defined('KUMVA_DB_PASS') ? KUMVA_DB_PASS : '');
$dbname = isset($_POST['dbname']) ? $_POST['dbname'] : (defined('KUMVA_DB_NAME') ? KUMVA_DB_NAME : 'kumva');
$dbprefix = isset($_POST['dbprefix']) ? $_POST['dbprefix'] : (defined('KUMVA_DB_PREFIX') ? KUMVA_DB_PREFIX : 'kumva_');

$adminuser = isset($_POST['adminuser']) ? $_POST['adminuser'] : 'admin';
$adminpass = isset($_POST['adminpass']) ? $_POST['adminpass'] : '';
$adminname = isset($_POST['adminname']) ? $_POST['adminname'] : 'Administrator';
$adminemail = isset($_POST['adminemail']) ? $_POST['adminemail'] : '';

function kumva_error($msg) {
	echo '<li style="color: red">'.$msg.'</li>';
	return FALSE;
}

function kumva_info($msg) {
	echo '<li style="color: green">'.$msg.'</li>';	
}
	
function kumva_install() {	
	global $dbhost, $dbname, $dbuser, $dbpass, $dbprefix, $adminuser, $adminpass, $adminname, $adminemail;
	
	aka_dbconfigure($dbhost, $dbuser, $dbpass, $dbname);
	$db = Database::getCurrent();
	kumva_info("Connected to database");
	
	$f = @fopen(realpath(KUMVA_SQL_SCRIPT), "r");
	if ($f === FALSE)
		return kumva_error("Error opening sql script: ".KUMVA_SQL_SCRIPT);
		
	$sql = fread($f, filesize(KUMVA_SQL_SCRIPT));
	fclose($f);

	$sql = str_replace("{DBNAME}", $dbname, $sql);
	$sql = str_replace("{DBPREFIX}", $dbprefix, $sql);
	
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
	
	// Create standard relationships
	if ($db->query("INSERT INTO `".$dbprefix."relationship` VALUES(NULL, 'form', 'Form', 'Possible surface form of the definition', 1, 1, '@D')")
	 && $db->query("INSERT INTO `".$dbprefix."relationship` VALUES(NULL, 'variant', 'Variant', 'Valid variant spelling of the definition', 1, 1, '@D')")
	 && $db->query("INSERT INTO `".$dbprefix."relationship` VALUES(NULL, 'meaning', 'Meaning', 'Possible simple translation of the definition', 1, 1, '@M')")
	 && $db->query("INSERT INTO `".$dbprefix."relationship` VALUES(NULL, 'root', 'Root', 'Word from which definition is derived', 1, 0, '@D')"))
		kumva_info("Created standard relationships");
	else
		return kumva_error("Unable to create standard relationships");
		
	// Create standard user ranks
	if ($db->query("INSERT INTO `".$dbprefix."rank` VALUES(NULL, 'Indakemwa', 0)")
	 && $db->query("INSERT INTO `".$dbprefix."rank` VALUES(NULL, 'Indatwa', 25)")
	 && $db->query("INSERT INTO `".$dbprefix."rank` VALUES(NULL, 'Indangamirwa', 100)")
	 && $db->query("INSERT INTO `".$dbprefix."rank` VALUES(NULL, 'Inararibonye', 500)")
	 && $db->query("INSERT INTO `".$dbprefix."rank` VALUES(NULL, 'Intyoza', 2500)"))
		kumva_info("Created standard user ranks");
	else
		return kumva_error("Unable to create standard user ranks");
		
	// Create standard subscriptions
	if ($db->query("INSERT INTO `".$dbprefix."subscription` VALUES(NULL, 'New change', 'Another user has submitted a change proposal')"))
		kumva_info("Created subscriptions");
	else
		return kumva_error("Unable to create subscriptions");
		
	// Create standard user roles
	if ($db->query("INSERT INTO `".$dbprefix."role` VALUES(NULL, 'Administrator', 'Can manage the site')")
	 && $db->query("INSERT INTO `".$dbprefix."role` VALUES(NULL, 'Editor', 'Can approve changes')")
	 && $db->query("INSERT INTO `".$dbprefix."role` VALUES(NULL, 'Contributor', 'Can submit changes')"))
		kumva_info("Created standard user roles");
	else
		return kumva_error("Unable to create standard user roles");
		
	// Create standard languages
	if ($db->query("INSERT INTO `".$dbprefix."language` VALUES(NULL, 'en', 'English', '/lang/en/site.php', '/lang/en/lexical.php')")
	 && $db->query("INSERT INTO `".$dbprefix."language` VALUES(NULL, 'rw', 'Kinyarwanda', '/lang/rw/site.php', '/lang/rw/lexical.php')"))
		kumva_info("Created standard languages");
	else
		return kumva_error("Unable to create standard languages");
	
	// Create admin user
	$password = md5($adminpass);
	$salt = sha1(uniqid());
	$userId = $db->insert("INSERT INTO `".$dbprefix."user` VALUES(NULL, '$adminuser', SHA1(CONCAT('$salt', '$password')), '$salt', '$adminname', '$adminemail', NULL, NULL, NULL, 0)");	
	if ($userId !== FALSE
	 && $db->query("INSERT INTO `".$dbprefix."user_role` VALUES($userId, 1)")				// Give administrator role
	 && $db->query("INSERT INTO `".$dbprefix."user_subscription` VALUES($userId, 1)"))		// Subscribe to new change notifications
		kumva_info("Created admin user");
	else
		return kumva_error("Unable to add admin user");
		
	kumva_info("Finished creating all database tables");
	return TRUE;	
}

?>
<?php header('Content-type: text/html; charset=utf-8'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<html>
	<head>
		<title><?php echo KUMVA_TITLE_SHORT; ?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="../gfx/admin/default.css" />
		<link rel="shortcut icon" href="../gfx/admin/favicon.ico" />
		<script type="text/javascript" src="../lib/akabanga/js/master.js.php"></script>
	</head>
	<body>
		<div id="header">	
			<h1>Kumva Admin</h1>
		</div>
		<div id="topdivider"></div>
		
		<div class="panel"><h2>Installation</h2></div>
		
		<?php 
		// Run installation process	
		if (Request::isPost()) {
			?>
			<div class="warning">For security reasons you MUST delete the install directory when installation is complete</div>
			<div style="padding: 20px">
			Running installation script...
			<ul>
			<?php $success = kumva_install(); ?>
			</ul>
			<?php if ($success) { ?>
				<?php Templates::buttonLink('view', '../index.php', KU_STR_VIEWSITE); ?>
			<?php }
		}
		else {
			if (KUMVA_HASCONFIG)
				echo '<div class="warning">Config file already exists</div>'; 
		?>
		<form method="post">
			<table class="form">
				<tr>
					<td colspan="2" class="sectionheader"><?php echo KU_STR_DATABASEDETAILS; ?></td>
				</tr>
				<tr>
					<td width="300"><span class="fieldname">Host</span></td>
					<td><input type="text" name="dbhost" value="<?php echo $dbhost; ?>" class="text" /></td>
				</tr>
				<tr>
					<td width="300"><span class="fieldname">Database name</span></td>
					<td><input type="text" name="dbname" value="<?php echo $dbname; ?>" class="text" /></td>
				</tr>
				<tr>
					<td width="300"><span class="fieldname"><?php echo KU_STR_LOGIN; ?></span></td>
					<td><input type="text" name="dbuser" value="<?php echo $dbuser; ?>" class="text" /></td>
				</tr>
				<tr>
					<td width="300"><span class="fieldname"><?php echo KU_STR_PASSWORD; ?></span></td>
					<td><input type="text" name="dbpass" value="<?php echo $dbpass; ?>" class="text" /></td>
				</tr>
				<tr>
					<td width="300"><span class="fieldname">Table prefix</span></td>
					<td><input type="text" name="dbprefix" value="<?php echo $dbprefix; ?>" class="text" /></td>
				</tr>
				<tr>
					<td colspan="2" class="sectionheader"><?php echo KU_STR_ADMINISTRATORACCOUNT; ?></td>
				</tr>
				<tr>
					<td width="300"><span class="fieldname"><?php echo KU_STR_NAME; ?></span></td>
					<td><input type="text" name="adminname" value="<?php echo $adminname; ?>" class="text" /></td>
				</tr>
				<tr>
					<td width="300"><span class="fieldname"><?php echo KU_STR_LOGIN; ?></span></td>
					<td><input type="text" name="adminuser" value="<?php echo $adminuser; ?>" class="text" /></td>
				</tr>
				<tr>
					<td width="300"><span class="fieldname"><?php echo KU_STR_PASSWORD; ?></span></td>
					<td><input type="text" name="adminpass" value="<?php echo $adminpass; ?>" class="text" /></td>
				</tr>
				<tr>
					<td width="300"><span class="fieldname"><?php echo KU_STR_EMAIL; ?></span></td>
					<td><input type="text" name="adminemail" value="<?php echo $adminemail; ?>" class="text" /></td>
				</tr>
				<tr>
					<td colspan="2"><hr /><?php Templates::button('install', "aka_submit(this)", KU_STR_INSTALL); ?></td>
				</tr>
			</table>
		</form>	
		<?php } ?>
	</body>
</html>
