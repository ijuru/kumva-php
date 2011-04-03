<?php
 
include_once '../inc/kumva.php';

// Get accepted definitions to become entries
$definitions = Dictionary::getDefinitionService()->getDefinitions(FALSE, FALSE);

echo "Loaded ".count($definitions)." head definitions<br/>";

foreach ($definitions as $definition) {
	$entry = $definition->getEntry();
	$revision = $definition->getRevision();
	
	echo "DEFINITION-".$definition->getId()." (".$definition->getPrefix().$definition->getLemma().") &rarr; 
		ENTRY-".$entry->getId()." REVISION-".$revision."<br/>";
		
	$changes = Dictionary::getChangeService()->getChangesForDefinition($definition);
	foreach ($changes as $change) {
		echo "&nbsp;&nbsp;CHANGE-".$change->getId()." ".date('Y-m-d H:i', $change->getSubmitted())."<br/>";
		if ($change->getProposal()) {
			$def = $change->getProposal();
			echo "&nbsp;&nbsp;&nbsp;&nbsp;PROPOSAL-".$def->getId()." &rarr; 
				ENTRY-".$def->getEntry()->getId()." REVISION-".$def->getRevision()."<br/>";
		}
	}
}

?>
