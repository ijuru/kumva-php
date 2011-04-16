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
include_once 'Install.php';

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
		<div id="wrap">
    		<div id="header">
            	<div id="banner">		
					<h1>Kumva</h1>
				</div>
			</div>
			<div id="content">
				<h3>Setup</h3>	
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
				?>
				<form method="post">
					<table class="form">
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
			</div>
		</div>
	</body>
</html>
