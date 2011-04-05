<?php
 
include_once '../inc/kumva.php';

if (mysql_query('UPDATE `rw_definition` SET entry_id = NULL, revision = 0') !== FALSE 
	&& mysql_query('TRUNCATE `rw_entry`') !== FALSE)
	echo "Cleared existing entry/revision information<br/>";
else
	echo "Unable to clear existing entry/revision information<br/>";

$count_accepted = 0;
$count_pending_create = 0;
$count_rejected = 0;
$count_deleted = 0;
$count_changes = 0;
$count_changes = 0;

// Get all definitions 
$definitions = Dictionary::getDefinitionService()->getDefinitions(FALSE, FALSE);
foreach ($definitions as $definition) {
	$entry = new Entry(); 
	Dictionary::getDefinitionService()->saveEntry($entry);
	
	$rev = 1;
	
	// Update resolved revisions of this definition
	$pastRevisions = array();
	$changes = array_reverse(Dictionary::getChangeService()->getChangesForDefinition($definition));
	foreach ($changes as $change) {
		if ($change->getProposal() && !$change->getProposal()->equals($definition) && $change->getStatus() != Status::PENDING) {
			$revision = $change->getProposal();
			$revision->setEntry($entry);
			$revision->setRevision($rev);
			if (!Dictionary::getDefinitionService()->saveDefinition($revision))
				echo "Unable to update definition #".$revision->getId();
			$rev++;				
		}
	}
	
	// Head definition gets last revision number
	$definition->setEntry($entry);
	$definition->setRevision($rev);
	if (!Dictionary::getDefinitionService()->saveDefinition($definition))
		echo "Unable to update definition #".$definition->getId();
	$entry->setAccepted($definition);
	$rev++;
	
	// Update pending revisions
	$changes = Dictionary::getChangeService()->getChangesForDefinition($definition, Status::PENDING);
	if (count($changes) == 1) {
		$change = $changes[0];
		if ($change->getProposal()) {
			$revision = $change->getProposal();
			$revision->setEntry($entry);
			$revision->setRevision($rev);
			if (!Dictionary::getDefinitionService()->saveDefinition($revision))
				echo "Unable to update definition #".$revision->getId();
			$entry->setProposed($revision);	
			
			$rev++;				
		}
	}
	
	// Save entry again since accepted/proposed fields have been updated
	Dictionary::getDefinitionService()->saveEntry($entry);
	$count_accepted++;
}

// Get all remaining definitions with no entry
$definitions = Dictionary::getDefinitionService()->getDefinitions(TRUE, TRUE);
foreach ($definitions as $definition) {
	if (!$definition->getEntry()) {
		// Is this a pending create?
		if ($definition->isProposal() && !$definition->isVoided()) {
			$entry = new Entry(0, 0, $definition->getId()); 
			Dictionary::getDefinitionService()->saveEntry($entry);
			$definition->setEntry($entry);
			$definition->setRevision(1);
			Dictionary::getDefinitionService()->saveDefinition($definition);
			$count_pending_create++;
		}
		// Is this a rejected create/delete?
		elseif ($definition->isProposal() && $definition->isVoided()) {
			$entry = new Entry(); 
			Dictionary::getDefinitionService()->saveEntry($entry);
			$definition->setEntry($entry);
			$definition->setRevision(1);
			Dictionary::getDefinitionService()->saveDefinition($definition);
			$count_rejected++;
		}
		// Is this deleted definition?
		elseif (!$definition->isProposal() && $definition->isVoided()) {
			$entry = new Entry(); 
			Dictionary::getDefinitionService()->saveEntry($entry);
			$definition->setEntry($entry);
			$definition->setRevision(1);
			Dictionary::getDefinitionService()->saveDefinition($definition);
			$count_deleted++;
		}	
	}
}

// Update all changes
$changes = Dictionary::getChangeService()->getChanges();
foreach ($changes as $change) {
	$definition = $change->getDefinition() ? $change->getDefinition() : $change->getProposal();
	$entry = $definition->getEntry();
	$change->setEntry($entry);
	if (!Dictionary::getChangeService()->saveChange($change))
		echo "Unable to save change #".$change->getId();
	$count_changes++;
}

echo "Updated ".$count_accepted." accepted definitions<br/>";
echo "Updated ".$count_pending_create." pending create definitions<br/>";
echo "Updated ".$count_rejected." rejected create/delete definitions<br/>";
echo "Updated ".$count_deleted." deleted definitions<br/>";
echo "Updated ".$count_changes." changes<br/>";

?>
