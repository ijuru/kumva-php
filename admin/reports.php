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
 * Purpose: Reports page
 */

include_once '../inc/kumva.php';

Session::requireUser();

$reports = Dictionary::getReportService()->getReports();

include_once 'tpl/header.php';

?>
<h3><?php echo KU_STR_REPORTS; ?></h3>

<table class="list" cellspacing="0" border="0">
    <tr>
        <th style="width: 30px">&nbsp;</th>
        <th style="width: 20px">&nbsp;</th>
        <th style="text-align: left"><?php echo KU_STR_TITLE; ?></th>
        <th style="width: 30px">&nbsp;</th>
    </tr>
	<?php
    foreach($reports as $report) { 
		$itemUrl = 'report.php?name='.urlencode($report->getName());
	?>
        <tr class="rowlink" onclick="aka_goto('<?php echo $itemUrl; ?>')">
            <td>&nbsp;</td>
            <td><?php Templates::icon('report'); ?></td>
            <td><?php echo $report->getTitle(); ?></td>
            <td>&nbsp;</td>
        </tr>
    <?php } ?>
</table>
	
<?php include_once 'tpl/footer.php'; ?>
