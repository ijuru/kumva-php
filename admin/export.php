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
 * Purpose: Export dictionary page
 */

include_once '../inc/kumva.php';

Session::requireUser();

include_once 'tpl/header.php';
?>
<h3><?php echo KU_STR_EXPORT ?></h3>

<table class="form">
	<tr>
		<th><?php echo KU_STR_MODE; ?></th>
		<td>
			<select id="exporttype">
				<option value="csv"><?php echo KU_STR_ACCEPTEDREVISIONS; ?> (CSV)</option>
				<option value="xml"><?php echo KU_STR_ACCEPTEDREVISIONS; ?> (XML)</option>
				<?php if (Session::getCurrent()->hasRole(Role::ADMINISTRATOR)) { ?>
					<option value="xml&amp;changes=1"><?php echo KU_STR_COMPLETEENTRIES; ?> (XML)</option>
				<?php } ?>
			</select>
		</td>
	</tr>
	<tr>
		<td colspan="2"><hr /><?php Templates::button('export', "aka_goto('../meta/export.php?format=' + $('#exporttype').val())", KU_STR_EXPORT); ?></td>
	</tr>
</table>

<?php include_once 'tpl/footer.php'; ?>
