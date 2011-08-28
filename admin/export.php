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
<div class="description">Dictionary entries can be downloaded as XML files.</div>
<ul>
	<li><?php echo KU_STR_ACCEPTEDREVISIONS; ?>: <a href="../meta/export.xml.php">link</a></li>
	<li><?php echo KU_STR_COMPLETEENTRIES; ?>: <a href="../meta/export.xml.php?changes=1">link</a></li>
</ul>

<?php include_once 'tpl/footer.php'; ?>
