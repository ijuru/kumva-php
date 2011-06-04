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
 * Purpose: Roles page
 */

include_once '../inc/kumva.php';

$audioCount = 0;
$imageCount = 0;

$function = Request::getPostParam('function', NULL);
if ($function == 'scan' && Session::getCurrent()->hasRole(Role::ADMINISTRATOR)) {
	$entries = Dictionary::getDefinitionService()->getEntries();
	
	foreach ($entries as $entry) {
		// Check for matching audio and image file
		$audioPath = KUMVA_DIR_MEDIA.'/audio/'.$entry->getId().'.mp3';
		$imagePath = KUMVA_DIR_MEDIA.'/image/'.$entry->getId().'.jpg';
		$flags = 0;
		if (file_exists($audioPath)) {
			$audioCount++;
			$flags = aka_setbit($flags, Media::AUDIO);
		}
		if (file_exists($imagePath)) {
			$imageCount++;
			$flags = aka_setbit($flags, Media::IMAGE);
		}
		
		// Update entry if flags have changed
		if ($entry->getMedia() != $flags) {
			$entry->setMedia($flags);
			Dictionary::getDefinitionService()->saveEntry($entry);
		}
	}
}

include_once 'tpl/header.php';
?>	
<h3><?php echo KU_STR_MEDIA ?></h3>

<?php if (Session::getCurrent()->hasRole(Role::ADMINISTRATOR)) { ?>
	<div class="listcontrols">
		<div style="float: right">
			<form method="post" action="">
				<input type="hidden" name="function" id="function" />
				<?php Templates::button('scan', "$('#function').val('scan'); aka_submit(this);", KU_STR_SCAN); ?>
			</form>
		</div>
	</div>
<?php } ?> 

<table class="list" cellspacing="0" border="0">
	<tr>
		<th style="width: 30px">&nbsp;</th>
		<th style="width: 20px">&nbsp;</th>
		<th><?php echo KU_STR_PATH; ?></th>
		<th><?php echo KU_STR_TYPE; ?></th>
		<th><?php echo KU_STR_ENTRIES; ?></th>
		<th style="width: 30px">&nbsp;</th>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><?php Templates::icon('folder'); ?></td>
		<td class="primarycol">media/audio</td>
		<td><?php echo KU_STR_AUDIO; ?></td>
		<td style="text-align: center"><?php echo $audioCount; ?></td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><?php Templates::icon('folder'); ?></td>
		<td class="primarycol">media/image</td>
		<td><?php echo KU_STR_IMAGE; ?></td>
		<td style="text-align: center"><?php echo $imageCount; ?></td>
		<td>&nbsp;</td>
	</tr>
</table>
<div class="panel"></div>

<?php include_once 'tpl/footer.php'; ?>
