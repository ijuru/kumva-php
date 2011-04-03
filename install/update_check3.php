<?php
 
include_once '../inc/kumva.php';

// Get all changes
$changes = Dictionary::getChangeService()->getChanges();

echo "Loaded ".count($changes)." changes<br/>";

foreach ($changes as $change) {
	$entryId = $change->getEntry() ? $change->getEntry()->getId() : 'NULL';
	$propId = $change->getProposal() ? $change->getProposal()->getId() : 'NULL';
	echo "CHANGE-".$change->getId()." ENTRY:".$entryId." PROPOSAL:".$propId." <br/>";
}

?>
