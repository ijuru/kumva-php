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
 * Purpose: Import dictionary page
 */

include_once '../inc/kumva.php';

Session::requireRole(Role::ADMINISTRATOR);

if (isset($_FILES['importsrc'])) {
	$verified = (bool)Request::getPostParam('verified', FALSE);
	$importSrc = $_FILES['importsrc']['tmp_name'];
	
	$importer = new CSVImporter();// : new XMLImporter();
	
	$importer->run(FALSE, $importSrc, $verified);
}

include_once 'tpl/header.php';
?>
	
<h3><?php echo KU_STR_IMPORT ?></h3>

<div class="description"><?php echo sprintf(KU_MSG_IMPORTINSTRUCTIONS, 'https://github.com/rowanseymour/kumva/wiki/CSV-format'); ?></div>

<form method="post" id="importform" enctype="multipart/form-data" action="">
	<table class="form" cellspacing="0" border="0">
		<tr>
			<th><?php echo KU_STR_FILE; ?></th>
			<td><input type="file" name="importsrc" /></td>
		</tr>
		<tr>
			<th><?php echo KU_STR_VERIFIED; ?></th>
			<td><input type="checkbox" value="1" name="verified" /></td>
		</tr>
		<tr>
			<td colspan="2"><hr /><?php Templates::button('import', "aka_submit(this)", KU_STR_IMPORT); ?></td>
		</tr>
	</table>
		
	<?php 
	if (isset($importer)) {
		echo '<ul>';
		foreach ($importer->getMessages() as $message)
			echo '<li>'.$message.'</li>';
		echo '</ul>';
	} 
	?>
	
</form>

<?php include_once 'tpl/footer.php'; ?>
