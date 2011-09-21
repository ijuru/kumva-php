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
 * Purpose: Kumva site definition XML
 */
 
include_once '../inc/kumva.php';
 
header("Content-type: text/xml");

Xml::header();

?>
	<site>
		<name><?php echo KUMVA_TITLE_LONG; ?></name>
		<definitionlang><?php echo KUMVA_LANG_DEFS; ?></definitionlang>
		<meaninglang><?php echo KUMVA_LANG_MEANING; ?></meaninglang>
		<kumvaversion><?php echo KUMVA_VERSION; ?></kumvaversion>
	</site>
<?php

Xml::footer();

?>
