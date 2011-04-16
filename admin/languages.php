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
 * Purpose: Languages page
 */

include_once '../inc/kumva.php';

Session::requireUser();

$function = Request::getPostParam('function', NULL);
if ($function == 'reload' && Session::getCurrent()->hasRole(Role::ADMINISTRATOR))
	Dictionary::getLanguageService()->reloadLanguages();
	
$languages = Dictionary::getLanguageService()->getLanguages();

include_once 'tpl/header.php';
?>	
	<h3><?php echo KU_STR_LANGUAGES ?></h3>
	
	<?php if (Session::getCurrent()->hasRole(Role::ADMINISTRATOR)) { ?>
		<div class="listcontrols">
			<div style="float: right">
				<form method="post" action="">
					<input type="hidden" name="function" id="function" />
					<?php Templates::button('refresh', "$('#function').val('reload'); aka_submit(this);", KU_STR_RELOAD); ?>
				</form>
			</div>
		</div>
	<?php } ?> 
	
	<table class="list" cellspacing="0" border="0">
		<tr>
			<th style="width: 30px">&nbsp;</th>
			<th style="width: 20px">&nbsp;</th>
			<th><?php echo KU_STR_NAME; ?></th>
			<th><?php echo KU_STR_LOCALNAME; ?></th>
			<th><?php echo KU_STR_CODE; ?></th>
			<th><?php echo KU_STR_QUERYURL; ?></th>
			<th><?php echo KU_STR_SITETRANSLATION; ?></th>
			<th><?php echo KU_STR_LEXICALMODULE; ?></th>
			<th style="width: 30px">&nbsp;</th>
		</tr>
		<?php 
		foreach($languages as $language) { 
			$itemUrl = '#';
		?>
		<tr class="rowlink" onclick="aka_goto('<?php echo $itemUrl; ?>')">
			<td>&nbsp;</td>
			<td><?php Templates::icon('language'); ?></td>
			<td class="primarycol"><?php echo htmlspecialchars($language->getName()); ?></td>
			<td><?php echo htmlspecialchars($language->getLocalName()); ?></td>
			<td style="text-align: center"><?php echo $language->getCode(); ?></td>
			<td><?php echo htmlspecialchars($language->getQueryUrl()); ?></td>
			<td style="text-align: center"><?php if ($language->hasTranslation()) Templates::icon('tick'); ?></td>
			<td style="text-align: center"><?php if ($language->hasLexical()) Templates::icon('tick'); ?></td>
			<td>&nbsp;</td>
		</tr>
		<?php } ?>
	</table>
	<div class="panel"></div>
<?php
include_once 'tpl/footer.php';
?>
