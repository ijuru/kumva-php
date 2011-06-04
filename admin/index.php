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

Session::requireUser();
	
$since = time() - 60 * 60 * 24 * 30;
$searchStats = Dictionary::getSearchService()->getSearchStatistics($since);
$searchTotal = $searchStats['total'];
$searchMisses = $searchStats['misses'];
$searchSuggestions = $searchStats['suggestions'];

$contentStats = Dictionary::getDefinitionService()->getContentStatistics();
$mediaStats = $contentStats['media'];

$changeStats = Dictionary::getChangeService()->getChangeStatistics();
$changePending = isset($changeStats[Status::PENDING]) ? $changeStats[Status::PENDING]['count'] : 0;
$changeAccepted = isset($changeStats[Status::ACCEPTED]) ? $changeStats[Status::ACCEPTED]['count'] : 0;
$changeRejected = isset($changeStats[Status::REJECTED]) ? $changeStats[Status::REJECTED]['count'] : 0;

include_once 'tpl/header.php';
?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td width="50%" valign="top">	
			<h3><?php echo KU_STR_CONTENT; ?></h3>
	
			<div class="description">
				Dictionary contains:
				<ul>
					<li><strong><?php echo $contentStats['entries']; ?></strong> entries</li>
					<li><strong><a href="entries.php?q=verified:no"><?php echo $contentStats['entries_unverified']; ?></a></strong> unverified entries</li>
					<li><strong><?php echo $mediaStats['audio']; ?></strong> entries with audio</li>
					<li><strong><?php echo $mediaStats['image']; ?></strong> entries with images</li>
				</ul>
			</div>
		</td>
		<td width="50%" valign="top">
			<h3><?php echo KU_STR_SEARCHES; ?></h3>
			
			<div class="description">
				In the last month there have been <strong><a href="searches.php"><?php echo $searchTotal; ?></a></strong> searches:
				<ul>
					<li><strong><a href="searches.php?misses=1"><?php echo $searchMisses; ?></a></strong> failed to match anything 
					(<?php echo $searchTotal ? round(100 * $searchMisses / $searchTotal) : 0; ?>%)</li> 
					<li><strong><a href="searches.php"><?php echo $searchSuggestions; ?></a></strong> returned a suggestion instead
					(<?php echo $searchTotal ? round(100 * $searchSuggestions / $searchTotal) : 0; ?>%)</li>
				</ul>			
				The most popular sources were: <?php 
				$results = array();
				foreach ($searchStats['sources'] as $result)
					$results[] = '<a href="searches.php?source='.urlencode($result['source']).'">'.htmlentities($result['source']).'</a> ('.$result['count'].')';
				echo implode(', ', $results);
				?>
			</div>
		</td>
	</tr>
	<tr>
		<td valign="top">
			<h3><?php echo KU_STR_CHANGES; ?></h3>
	
			<div class="description">
				<ul>
					<li><?php echo KU_STR_PENDING; ?>: <strong><a href="changes.php?status=0"><?php echo $changePending; ?></a></strong></li>
					<li><?php echo KU_STR_ACCEPTED; ?>: <strong><a href="changes.php?status=1"><?php echo $changeAccepted; ?></a></strong></li>
					<li><?php echo KU_STR_REJECTED; ?>: <strong><a href="changes.php?status=2"><?php echo $changeRejected; ?></a></strong></li>
				</ul>
			</div>
		</td>
		<td valign="top">
			<h3><?php echo KU_STR_SERVERINFORMATION; ?></h3>
	
			<div class="description">
				<ul>
					<li><?php echo KU_STR_KUMVAVERSION.': '.KUMVA_VERSION; ?></li>
					<li><?php echo KU_STR_PHPVERSION.': '.phpversion(); ?></li>
					<li><?php echo KU_STR_MYSQLVERSION.': '.Database::getCurrent()->getVersion(); ?></li>
				</ul>
			</div>
		</td>
	</tr>
</table>	

<?php include_once 'tpl/footer.php'; ?>
