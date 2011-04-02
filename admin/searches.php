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
 * Purpose: Search statistics page
 */

include_once '../inc/kumva.php';

Session::requireUser();

$remoteAddr = Request::getGetParam('who', NULL);
$source = Request::getGetParam('source', NULL);
$showCurrentUser = (bool)Request::getGetParam('curuser');
$showOnlyMisses = (bool)Request::getGetParam('misses');

$paging = new Paging('start', 20);		
$history = Dictionary::getSearchService()->getSearchHistory($remoteAddr, $source, $showCurrentUser, $showOnlyMisses, $paging);

include_once 'tpl/header.php';
?>
<h3><?php echo KU_STR_SEARCHHISTORY; ?></h3>

<div class="listcontrols">
    <form method="get" id="listform" action="">
        <?php echo KU_STR_WHO; ?> <input type="text" name="who" value="<?php echo $remoteAddr; ?>" />&nbsp;&nbsp;
        <?php echo KU_STR_SOURCE; ?> <input type="text" name="source" value="<?php echo $source; ?>" />&nbsp;&nbsp;
        <input type="checkbox" name="curuser" value="1" <?php echo $showCurrentUser ? 'checked="checked"' : ''; ?>/><?php echo KU_STR_SHOWCURRENTUSER; ?>&nbsp;&nbsp;
        <input type="checkbox" name="misses" value="1" <?php echo $showOnlyMisses ? 'checked="checked"' : ''; ?>/><?php echo KU_STR_SHOWONLYMISSES; ?>&nbsp;&nbsp;
        <?php Templates::button('refresh', "$('#listform').submit()", KU_STR_REFRESH); ?>
    </form>
</div>

<table class="list" cellspacing="0" border="0">
    <tr>
        <th style="width: 30px">&nbsp;</th>
        <th><?php echo KU_STR_WHEN; ?></th>
        <th><?php echo KU_STR_WHO; ?></th>
        <th><?php echo KU_STR_QUERY; ?></th>
        <th><?php echo KU_STR_SUGGESTION; ?></th>
        <th><?php echo KU_STR_NUMRESULTS; ?></th>
        <th><?php echo KU_STR_NUMITERATIONS; ?></th>
        <th><?php echo KU_STR_TIMETAKEN; ?></th>
        <th><?php echo KU_STR_SOURCE; ?></th>
        <th style="width: 30px">&nbsp;</th>
    </tr>
<?php foreach($history as $search) { 
$when = aka_timefromsql($search['timestamp']);
$pattern = htmlspecialchars($search['query']);
$suggest = $search['suggest'] != NULL ? htmlspecialchars($search['suggest']) : '';
$results = (int)$search['results'];
?>
    <tr <?php echo ($results == 0) ? 'style="background-color:#FDD"' : ''; ?>>
        <td>&nbsp;</td>
        <td><?php Templates::dateTime($when); ?></td>
        <td>
            <a href="searches.php?who=<?php echo urlencode($search['remoteaddr']); ?>"><?php echo $search['remoteaddr']; ?></a>
            <?php echo $search['login'] != NULL ? ' ('.$search['login'].')' : '';?>
        </td>
        <td><a href="../index.php?q=<?php echo urlencode($pattern); ?>"><?php echo htmlentities($pattern); ?></a></td>
        <td><a href="../index.php?q=<?php echo urlencode($suggest); ?>"><?php echo htmlentities($suggest); ?></a></td>
        <td style="text-align: center"><?php echo $results; ?></td>
        <td style="text-align: center"><?php echo $search['iterations']; ?></td>
        <td style="text-align: center"><?php echo $search['timetaken']; ?> ms</td>
        <td style="text-align: center"><a href="searches.php?source=<?php echo urlencode($search['source']); ?>"><?php echo htmlentities($search['source']); ?></a></td>
        <td>&nbsp;</td>
    </tr>
<?php } ?>
</table>
	
<div id="pager">
	<?php
	if ($paging->getTotalPages() > 1) {
		Templates::pagerButtons($paging);
		echo '&nbsp;&nbsp;';
	}
	printf(KU_MSG_PAGER, $paging->getStart() + 1, $paging->getStart() + count($history), $paging->getTotal());
	?>
</div>

<?php
include_once 'tpl/footer.php';
?>
