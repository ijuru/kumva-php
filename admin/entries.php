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
 * Purpose: Entry search page
 */

include_once '../inc/kumva.php';

Session::requireUser();

$q = Request::getGetParam('q');

if ($q != '') {	
	$paging = new Paging('start', 20);		
	$search = new Search($q, TRUE, $paging);
	$search->setDefaultOrderBy(OrderBy::STEM);
	$search->run(NULL);
}

include_once 'tpl/header.php';

?>
<script type="text/javascript">
$(function() {
	$('#query').focus();
});
</script>

<h3><?php echo KU_STR_ENTRIES; ?></h3>

<div class="listcontrols">
	<div style="float: left">
		<?php Widgets::searchForm('entries.php', 'q', 'start', TRUE); ?>
		&nbsp;&nbsp;
		<a href="https://github.com/ijuru/kumva/wiki/Query-syntax">Syntax guide...</a>
	</div>
	<div style="float: right">
		<?php Templates::buttonLink('add', 'entryedit.php?new='.urlencode($q), KU_STR_ADD); ?>
	</div>
</div> 

<?php if (isset($search)) { ?>	
	<table class="list" cellspacing="0" border="0">
		<tr>
			<th style="width: 30px">&nbsp;</th>
			<th style="width: 20px">&nbsp;</th>
			<th><?php echo KU_STR_PREFIX; ?></th>
			<th><?php echo KU_STR_LEMMA; ?></th>
			<th><?php echo KU_STR_MODIFIER; ?></th>
			<th><?php echo KU_STR_WORDCLASS; ?></th>
			<th><?php echo KU_STR_NOUNCLASSES; ?></th>
			<th><?php echo KU_STR_MEANINGS; ?></th>
			<th><?php echo KU_STR_VERIFIED; ?></th>
			<th style="width: 20px">&nbsp;</th>
			<th style="width: 30px">&nbsp;</th>
		</tr>
		<?php 
		if ($search->hasResults()) {
			foreach($search->getResults() as $definition) {
				$entry = $definition->getEntry();
				$itemUrl = 'entry.php?id='.$entry->getId().'&amp;ref='.urlencode(KUMVA_URL_CURRENT);
				$editUrl = 'entryedit.php?id='.$entry->getId().'&amp;ref='.urlencode(KUMVA_URL_CURRENT);
				$meanings = BeanUtils::getPropertyOfAll($definition->getMeanings(), 'meaning');
				?>
				<tr class="rowlink" onclick="aka_goto('<?php echo $itemUrl; ?>')">
					<td>&nbsp;</td>
					<td>
					<?php 
						if ($definition->isProposedRevision())
							Templates::icon('proposal', KU_STR_PROPOSAL);
						else
						 	Templates::icon('entry', KU_STR_ENTRY);
					?>
					</td>
					<td class="prefix" style="padding-right: 0; text-align: right"><?php echo $definition->getPrefix(); ?></td>
					<td class="lemma primarycol" style="padding-left: 0; text-align: left"><?php echo $definition->getLemma(); ?></td>
					<td><?php echo $definition->getModifier(); ?></td>
					<td style="text-align: center"><?php echo $definition->getWordClass(); ?></td>
					<td style="text-align: center"><?php echo aka_makecsv($definition->getNounClasses()); ?></td>
					<td><?php echo implode('<br/>', $meanings); ?></td>
					<td style="text-align: center"><?php if (!$definition->isUnverified()) Templates::icon('tick'); ?></td>
					<td>
					<?php 
					if (Session::getCurrent()->hasRole(Role::CONTRIBUTOR))
						Templates::iconLink('edit', $editUrl, KU_STR_EDIT); 
					?></td>
					<td>&nbsp;</td>
				</tr>
				<?php
			} 
		} 
		?>
	</table>
	<?php if ($search->getResultCount() == 0) { ?>
		<div style="padding: 10px; text-align: center"><?php echo KU_MSG_NOMATCHINGWORDS; ?></div>
	<?php } ?>
    <div id="pager">
		<?php
        if ($paging->getTotalPages() > 1) {
            Templates::pagerButtons($paging);
            echo '&nbsp;&nbsp;';
        }
        if ($search->getResultCount())
            printf(KU_MSG_PAGER, $paging->getStart() + 1, $paging->getStart() + $search->getResultCount(), $paging->getTotal());
        ?>			
    </div>
<?php 
} else {
?>
	<div class="description"><?php echo KU_MSG_ENTRYSEARCH; ?></div>
<?php
}

include_once 'tpl/footer.php';
?>
