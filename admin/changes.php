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
 * Purpose: Changes page
 */

include_once '../inc/kumva.php';

Session::requireUser();

$status = (Request::getGetParam('status') != 'any') ? (int)Request::getGetParam('status', Status::PENDING) : NULL;
$paging = new Paging('start', 20);		
$changes = Dictionary::getChangeService()->getChanges(NULL, $status, NULL, FALSE, $paging);

include_once 'tpl/header.php';
?>

<h3><?php echo KU_STR_CHANGES; ?></h3>

<div class="listcontrols">
	<div style="float: left">
		<form method="get" action="">
			<?php echo KU_STR_STATUS; ?>
			<select name="status">
				<option value="any"><?php echo KU_STR_ANY; ?></option>
				<option value="0" <?php echo $status === 0 ? 'selected="selected"' : ''; ?>><?php echo KU_STR_PENDING; ?></option>
				<option value="1" <?php echo $status === 1 ? 'selected="selected"' : ''; ?>><?php echo KU_STR_ACCEPTED; ?></option>
				<option value="2" <?php echo $status === 2 ? 'selected="selected"' : ''; ?>><?php echo KU_STR_REJECTED; ?></option>
			</select>
			<?php Templates::button('refresh', "aka_submit(this)", KU_STR_REFRESH); ?>
		</form>
	</div>
	<div style="float: right">
		<?php Templates::buttonLink('feed', KUMVA_URL_ROOT.'/meta/feeds.php?name=changes'.($status !== NULL ? '&amp;status='.$status : ''), KU_STR_SUBSCRIBE); ?>
	</div>
</div>

<?php Templates::changesTable($changes, TRUE, TRUE, TRUE); ?>

<div id="pager">
	<?php
	if ($paging->getTotalPages() > 1) {
		Templates::pagerButtons($paging);
		echo '&nbsp;&nbsp;';
	}
	printf(KU_MSG_PAGER, $paging->getStart() + 1, $paging->getStart() + count($changes), $paging->getTotal());
	?>			
</div>

<?php include_once 'tpl/footer.php'; ?>
