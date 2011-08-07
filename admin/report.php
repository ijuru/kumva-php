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
 * Purpose: Report view page
 */

include_once '../inc/kumva.php';

Session::requireUser();

// Run the requested report
$name = Request::getGetParam('name');
$format = Request::getGetParam('format');
$report = Dictionary::getReportService()->getReportByName($name);

if ($report && $format == NULL) {
	$paging = new Paging('start', 20);
	$results = $report->run($paging);
} elseif ($report && $format == 'csv') {
	$results = $report->run();
	header('Content-type: text/csv');
	header('Content-Disposition: attachment; filename=report-'.$report->getName().'-'.date('Y-m-d').'.csv');
	echo $results->toCSVString();
	exit;
}


include_once 'tpl/header.php';

?>
<h3><?php echo KU_STR_VIEWREPORT.': <i>'.htmlspecialchars($report->getTitle()).'</i>'; ?></h3>

<div class="listcontrols">
	<div style="float: left">
        <?php Templates::buttonLink('back', 'reports.php', KU_STR_BACK) ?>
    </div>
    <div style="float: right">
        <?php Templates::buttonLink('csv', 'report.php?name='.urlencode($report->getName()).'&amp;format=csv', KU_STR_EXPORT) ?>
    </div>
</div> 

<table class="list" cellspacing="0" border="0">
    <tr>
        <th style="width: 30px">&nbsp;</th>
        <?php 
        foreach ($results->getFields() as $field)
        	echo '<th>'.$field.'</td>';
        ?>
        <th style="width: 30px">&nbsp;</th>
    </tr>
    <?php 
    if ($results->getRowCount() > 0) {
        foreach($results->getRows() as $row) { 
            ?>
            <tr>
                <td>&nbsp;</td>
                <?php 
                $fields = $results->getFields(TRUE);
                foreach ($fields as $field) {
                	$char1 = $field[0]; 
                    if ($char1 != '_') {
                        echo '<td>';
                        if ($char1 == '?') {
                        	$query =  $row[$field];
                            echo '<a href="entries.php?q='.urlencode($query).'">'.$query.'</a>';
                        }
                        elseif ($char1 == '>') {
                        	$query =  $row['_'.substr($field, 1)];
                        	echo '<a href="entries.php?q='.urlencode($query).'">'.$row[$field].'</a>';
                        }
						elseif ($char1 == '#') {
							$definition = Dictionary::getDefinitionService()->getDefinition((int)$row[$field]);
                            Templates::definitionLink($definition);
						} else
                            echo $row[$field];
                        echo '</td>';
                    }
                }
                ?>
                <td>&nbsp;</td>
            </tr>
            <?php
        } 
    } 
    ?>
</table>
<?php if ($results->getRowCount() == 0) { ?>
    <div style="padding: 10px; text-align: center"><?php echo KU_MSG_NOREPORTRESULTS; ?></div>
<?php } ?>

<div id="pager">
    <?php
    if ($paging->getTotalPages() > 1) {
        Templates::pagerButtons($paging);
        echo '&nbsp;&nbsp;';
    }
    if ($results->getRowCount())
        printf(KU_MSG_PAGER, $paging->getStart() + 1, $paging->getStart() + $results->getRowCount(), $paging->getTotal());
    ?>
</div>
    
<?php include_once 'tpl/footer.php'; ?>
