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
 * Purpose: Statistics page
 */

$contentStats = Dictionary::getDefinitionService()->getContentStatistics();
$mediaStats = Dictionary::getDefinitionService()->getMediaCounts();
?>
<table border="0" width="100%">
	<tr>
		<td valign="top" width="50%">
			<h2><?php echo KU_STR_CONTENT; ?></h2>

			<ul>
				<li><?php echo KU_STR_TOTALENTRIES.': '.$contentStats['entries']; ?></li>
				<li>Entries with audio: <a href="index.php?q=has:audio"><?php echo $mediaStats['audio']; ?></a></li>
			</ul>
			
			<h3>Searchable tags</h3>
			
			<?php Widgets::tagStatistics(); ?>
		</td>
		<td valign="top">
			<h3><?php echo KU_STR_WORDCLASSES; ?></h3>
			
			<?php Widgets::wordClassStatistics(); ?>
		</td>
	</tr>
</table>
