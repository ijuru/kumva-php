<?php
 
include_once '../inc/kumva.php';

// Get all entries
$entries = Dictionary::getDefinitionService()->getEntries();

echo "Loaded ".count($entries)." entries<br/>";

foreach ($entries as $entry) {
	echo "ENTRY-".$entry->getId()." ACCEPTED:".$entry->getAcceptedRevision()." PROPOSED:".$entry->getProposedRevision()." <br/>";

	$definitions = Dictionary::getDefinitionService()->getEntryDefinitions($entry);
	foreach ($definitions as $definition) {
		echo "&nbsp;&nbsp;DEFINITION-".$definition->getId()." (".$definition->getPrefix().$definition->getLemma().") ";
		echo "REVISION:".$definition->getRevision()." PROPOSAL:".(int)$definition->isProposal()." VOIDED:".(int)$definition->isVoided()."<br/>";
	}
}

?>
