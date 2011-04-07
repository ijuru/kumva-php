<?php
 
include_once '../inc/kumva.php';

$count_accepted = 0;
$count_pending_create = 0;
$count_rejected_create = 0;
$count_deleted = 0;

// Get all accepted definitions 
$definitions = Dictionary::getDefinitionService()->getDefinitions(FALSE, FALSE);
foreach ($definitions as $definition) {
	$entry = new Entry(); 
	if (!Dictionary::getDefinitionService()->saveEntry($entry))
		echo "Unable to save entry #".$entry->getId();
		
	$defIsProposal = FALSE;
	$revNum = 1;
	
	// Update resolved revisions of this definition
	$pastRevisions = array();
	$changes = array_reverse(Dictionary::getChangeService()->getChangesForDefinition($definition));
	foreach ($changes as $change) {
		if ($change->getProposal() && $change->getStatus() != Status::PENDING) {
			$revision = $change->getProposal();
			$revision->setEntry($entry);
			$revision->setRevision($revNum++);
			$revision->setChange($change);
			if (!Dictionary::getDefinitionService()->saveDefinition($revision))
				echo "Unable to update definition #".$revision->getId();	
				
			if ($revision->equals($definition))
				$defIsProposal = TRUE;
		} 
		elseif ($change->getAction() == Action::DELETE) { // Accepted delete change
			$entry->setDeleteChange($change);
		}
	}
	
	if (!$defIsProposal) {
		// Head definition becomes accepted definition of the entry
		$definition->setEntry($entry);
		$definition->setRevision($revNum++);
		if (!Dictionary::getDefinitionService()->saveDefinition($definition))
			echo "Unable to update definition #".$definition->getId();
	}
	
	// Convert any pending change's proposal to the entry's proposed revision
	$changes = Dictionary::getChangeService()->getChangesForDefinition($definition, Status::PENDING);
	if (count($changes) == 1) {
		$change = $changes[0];
		if ($change->getProposal()) {  // Pending create/modify
			$revision = $change->getProposal();
			$revision->setEntry($entry);
			$revision->setChange($change);
			$revision->setRevision($revNum++);
			if (!Dictionary::getDefinitionService()->saveDefinition($revision))
				echo "Unable to update definition #".$revision->getId();			
		}
	}
	
	// Save entry
	if (!Dictionary::getDefinitionService()->saveEntry($entry))
		echo "Unable to update entry #".$entry->getId();
		
	$count_accepted++;
}

// Get all remaining definitions with no entry
$definitions = Dictionary::getDefinitionService()->getDefinitions(TRUE, TRUE);
foreach ($definitions as $definition) {
	if ($definition->getEntry())
		continue;
		
	//echo "Found entryless definition #".$definition->getId()." (proposal:".$definition->isProposal().", voided:".$definition->isVoided().")<br/>";
	
	// Is this a pending create?
	if ($definition->isProposal() && !$definition->isVoided()) {
		$change = Dictionary::getChangeService()->getChangeForProposal($definition);
		$entry = new Entry(0, NULL, $definition->getId()); 
		if (!Dictionary::getDefinitionService()->saveEntry($entry))	
			echo "Unable to save entry #".$entry->getId()." for pending create";
		$definition->setEntry($entry);
		$definition->setChange($change);
		$definition->setRevision(1);
		if (!Dictionary::getDefinitionService()->saveDefinition($definition))
			echo "Unable to update pending create definition #".$definition->getId();
		$count_pending_create++;
	}
	// Is this a rejected create?
	elseif ($definition->isProposal() && $definition->isVoided()) {
		$change = Dictionary::getChangeService()->getChangeForProposal($definition);
		$entry = new Entry(0, NULL, NULL); 
		if (!Dictionary::getDefinitionService()->saveEntry($entry))	
			echo "Unable to save entry #".$entry->getId()." for rejected create";
		$definition->setEntry($entry);
		$definition->setChange($change);
		$definition->setRevision(1);
		if (!Dictionary::getDefinitionService()->saveDefinition($definition))
			echo "Unable to update reject create definition #".$definition->getId();
		$count_rejected_create++;
	}
	// Is this deleted definition?
	elseif (!$definition->isProposal() && $definition->isVoided()) {
		$changes = Dictionary::getChangeService()->getChangesForDefinition($definition);
		$change = $changes[0];
		$entry = new Entry(0, NULL, NULL, $change->getId()); 
		if (!Dictionary::getDefinitionService()->saveEntry($entry))	
			echo "Unable to save entry #".$entry->getId()." for rejected delete";
		$definition->setEntry($entry);
		$definition->setRevision(1);
		if (!Dictionary::getDefinitionService()->saveDefinition($definition))
			echo "Unable to update deleted definition #".$definition->getId();
		$count_deleted++;
	}
}

echo "Updated ".$count_accepted." accepted definitions<br/>";
echo "Updated ".$count_pending_create." pending create definitions<br/>";
echo "Updated ".$count_rejected_create." rejected create/delete definitions<br/>";
echo "Updated ".$count_deleted." deleted definitions<br/>";

mysql_query('UPDATE rw_change SET proposal_id = NULL, original_id = NULL');
echo "Cleared old change-definition references<br/>";

// Clean entries with ghost definitions etc
$entries = Dictionary::getDefinitionService()->getEntries();
echo "Loaded ".count($entries)." entries for cleaning<br/>";

$deleted_ghosts = 0;

foreach ($entries as $entry) {
	$revisions = $entry->getRevisions();
	if (count($revisions) == 0)
		echo "Found empty entry #".$entry->getId()."<br/>";
	
	// Find redundant defs that have a nonrejected change come before a new def with no change
	if (count($revisions) > 1 && !$revisions[0]->getChange() && $revisions[1]->getChange() && $revisions[1]->getChange()->getStatus() != Status::REJECTED) {
		$head = $revisions[0];	
		$redundant = $revisions[1];
		$change = $redundant->getChange();
		
		// Move change to head
		$head->setChange($change);
		if (!Dictionary::getDefinitionService()->saveDefinition($head))
			echo "Unable to update change of definition #".$head->getId()."<br/>";
		
		// Delete redundant def	
		if (!Dictionary::getDefinitionService()->deleteDefinition($redundant))
			echo "Unable to update revision number of definition #".$redundant->getId()."<br/>";
			
		$deleted_ghosts++;
	}
	
	// Reset revision numbers
	$definitions = array_reverse(Dictionary::getDefinitionService()->getEntryDefinitions($entry));
	$revNum = 1;
	foreach ($definitions as $definition) {
		$definition->setRevision($revNum);
		if (!Dictionary::getDefinitionService()->saveDefinition($definition))
			echo "Unable to update revision number of definition #".$definition->getId()."<br/>";
		$revNum++;
	}
	
	// Reset accepted/proposed
	$definitions = array_reverse($definitions);
	$accepted = NULL;
	$proposed = NULL;
	foreach ($definitions as $definition) {
		if (!$definition->getChange() || $definition->getChange()->getStatus() == Status::ACCEPTED) {
			$accepted = $definition;
			break;
		}
	}
	foreach ($definitions as $definition) {
		if ($definition->getChange() && $definition->getChange()->getStatus() == Status::PENDING) {
			$proposed = $definition;
			break;
		}
	}
	$entry->setAccepted($accepted);
	$entry->setProposed($proposed);
	if (!Dictionary::getDefinitionService()->saveEntry($entry))
		echo "Unable to update entry #".$entry->getId();
		
	// Check for proposed rev < accepted rev
	if ($accepted && $proposed && $accepted->getRevision() >= $proposed->getRevision())
		echo "Found proposed rev &lt; accepted rev on entry #".$entry->getId();
}

echo "Deleted ".$deleted_ghosts." redundant definitions<br/>";

?>
