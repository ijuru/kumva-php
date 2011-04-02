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
 * Purpose: Changes feed
 */
 
require_once '../inc/kumva.php';
 
header("Content-type: application/rss+xml");
echo '<?xml version="1.0" encoding="UTF-8" ?>';

$feedName = Request::getGetParam('name', NULL);
$feedItems = array();
$paging = new Paging('start', 10);

if ($feedName == 'changes') {
	$status = Request::hasGetParam('status') ? (int)Request::getGetParam('status', Status::PENDING) : NULL;
	
	$feedTitle = KUMVA_TITLE_SHORT.': recent changes';
	$feedDescription = 'Most recent changes to the dictionary';
	$feedUrl = KUMVA_URL_ROOT.'/admin/changes.php'.($status !== NULL ? '?status='.$status : '');
	
	$changes = Dictionary::getChangeService()->getChanges(NULL, NULL, $status, NULL, FALSE, $paging);
	
	foreach ($changes as $change) {
		$itemTitle = $change->toString().' ['.Status::toString($change->getStatus()).']';
		$itemDescription = 'Submitted by '.$change->getSubmitter()->getName();
		$itemUrl = KUMVA_URL_ROOT.'/admin/change.php?id='.$change->getId();
		$feedItems[] = array('title' => $itemTitle, 'description' => $itemDescription, 'url' => $itemUrl);
	}
}
else
	die('Unknown feed name');

?>
<rss version="2.0">
	<channel>
		<title><?php echo $feedTitle; ?></title>
        <description><?php echo $feedDescription; ?></description>
        <link><?php echo $feedUrl; ?></link>
        <generator>Kumva <?php echo KUMVA_VERSION; ?></generator>
	</channel>
		
	<?php foreach ($feedItems as $feedItem) { ?>
		<item>
			<title><?php echo $feedItem['title']; ?></title>
			<link><?php echo $feedItem['url']; ?></link>
			<description><?php echo $feedItem['description']; ?></description>
		</item>	
	<?php } ?>
	
</rss>
