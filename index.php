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
 * Purpose: Main search page
 */
 
include_once 'inc/kumva.php';

$q = Request::getGetParam('q');
$source = Request::getGetParam('ref', 'home');

if ($q != '') {	
	$paging = new Paging('start', KUMVA_PAGE_SIZE);			
	$search = new Search($q, FALSE, $paging);
	$search->run($source);
}

Theme::header();

if (isset($search)) {
	// Did smart search use a suggested query?
	if ($search->hasResults() && $search->isBySuggestion()) {
		$suggestion = $search->isBySoundSuggestion()
		 	? 'similar sounding words'
		 	: '<em><a href="?q='.$search->getSuggestionPattern().'">'.$search->getSuggestionPattern().'</a></em>';
		?>
		<div class="info">No matching words. Showing results for <?php echo $suggestion; ?> instead...</div>
		<?php
	}

	// Show results if they exist
	if ($search->hasResults()) {
	?>
		<div id="page">
			<ul id="results">
			<?php
				foreach ($search->getResults() as $def) {
					echo '<li class="definition">';
					Templates::entry($def);
					echo '</li>';
				}
			?>		
			</ul>
		</div>
		<div class="info">
			<div style="float: left">
			<?php
			if ($paging->getTotalPages() > 1) {
				Templates::pagerButtons($paging);
				echo '&nbsp;&nbsp;';
			}
			printf(KU_MSG_PAGER, $paging->getStart() + 1, $paging->getStart() + $search->getResultCount(), $paging->getTotal());
			?>			
			</div>
			<div style="float: right">
				<small><?php echo sprintf(KU_MSG_SEARCHTIME, $search->getTime()); ?></small>
			</div>
		</div>
	<?php } else { ?>
		<div class="info"><?php echo KU_MSG_NOMATCHINGWORDS; ?></div>
	<?php
	}	
} else {
?>
<div style="padding: 32px; text-align: center">
	<?php Widgets::randomDefinition(); ?>
</div>
<?php
}

Theme::footer();
?>
