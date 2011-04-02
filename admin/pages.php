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
 * Purpose: Pages page
 */

include_once '../inc/kumva.php';

Session::requireUser();

include_once 'tpl/header.php';
?>	
<h3><?php echo KU_STR_PAGES ?></h3>

<table class="list" cellspacing="0" border="0">
    <tr>
        <th style="width: 30px">&nbsp;</th>
        <th style="width: 20px">&nbsp;</th>
        <th><?php echo KU_STR_NAME; ?></th>
        <th><?php echo KU_STR_TITLE; ?></th>
        <th><?php echo KU_STR_FILE; ?></th>
        <th style="width: 20px">&nbsp;</th>
        <th style="width: 30px">&nbsp;</th>
    </tr>
    <?php 
    $pages = Dictionary::getPageService()->getPages();
    foreach($pages as $page) { 
        $itemUrl = '#';
    ?>
    <tr class="rowlink" onclick="aka_goto('<?php echo $itemUrl; ?>')">
        <td>&nbsp;</td>
        <td><?php Templates::icon('page'); ?></td>
        <td class="primarycol"><?php echo $page->getName(); ?></td>
        <td><?php echo $page->getTitle(); ?></td>
        <td><?php echo $page->getFile(); ?></td>
        <td><?php Templates::iconLink('view', KUMVA_URL_ROOT.'/page.php?name='.$page->getName()); ?></td>
        <td>&nbsp;</td>
    </tr>
    <?php } ?>
</table>
	
<?php include_once 'tpl/footer.php'; ?>
