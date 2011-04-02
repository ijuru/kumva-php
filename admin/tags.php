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
 * Purpose: Tags page
 */

include_once '../inc/kumva.php';

Session::requireRole(Role::ADMINISTRATOR);

// Process tag function requests
$function = Request::getPostParam('function', NULL);
if ($function == 'cleanup')
	Dictionary::getTagService()->deleteOrphanTags();
elseif ($function == 'regenerate') {
	//Dictionary::getDefinitionService()->generateTagWeights();
	Dictionary::getTagService()->generateLexical();
}
	
$orphanTags = Dictionary::getTagService()->getOrphanTags();	

include_once 'tpl/header.php';
?>	
<h3><?php echo KU_STR_TAGTYPES ?></h3>
	
<table class="list" cellspacing="0" border="0">
    <tr>
        <th style="width: 30px">&nbsp;</th>
        <th style="width: 20px">&nbsp;</th>
        <th><?php echo KU_STR_NAME; ?></th>
        <th><?php echo KU_STR_TITLE; ?></th>
        <th><?php echo KU_STR_DESCRIPTION; ?></th>
        <th><?php echo KU_STR_DEFAULTLANG; ?></th>
        <th><?php echo KU_STR_MATCHBYDEFAULT; ?></th>
        <th style="width: 20px">&nbsp;</th>
        <th style="width: 30px">&nbsp;</th>
    </tr>
    <?php 
    $relationships = Dictionary::getTagService()->getRelationships();
    foreach($relationships as $relationship) { 
        $itemUrl = '#';
    ?>
    <tr class="rowlink" onclick="aka_goto('<?php echo $itemUrl; ?>')">
        <td>&nbsp;</td>
        <td><?php Templates::icon('tag'); ?></td>
        <td class="primarycol"><?php echo $relationship->getName(); ?></td>
        <td><?php echo $relationship->getTitle(); ?></td>
        <td><?php echo $relationship->getDescription(); ?></td>
        <td style="text-align: center"><?php echo $relationship->getDefaultLang(TRUE); ?></td>
        <td style="text-align: center"><?php echo $relationship->isMatchDefault() ? KU_STR_YES : KU_STR_NO; ?></td>
        <td><?php
        if ($relationship->isSystem())
            Templates::icon('lock', KU_STR_SYSTEM);
        else
            Templates::iconLink('edit', 'relationship.php?id='.$relationship->getId(), KU_STR_EDIT); 
        ?></td>
        <td>&nbsp;</td>
    </tr>
    <?php } ?>
</table>
	
<h3><?php echo KU_STR_TAGMANAGEMENT;; ?></h3>
	
<div class="description">
    <form action="" method="post">
        <input type="hidden" name="function" id="function" />
        <p>
        <?php 
        Templates::button('cleanup', "$('#function').val('cleanup'); aka_submit(this);", KU_STR_CLEANUP); 
        printf(' '.KU_MSG_TAGSCLEANUP, count($orphanTags)); 
        ?>
        </p><p>
        <?php 
        Templates::button('refresh', "$('#function').val('regenerate'); aka_submit(this);", KU_STR_REGENERATE); 
        echo ' '.KU_MSG_TAGSREGENERATE; 
        ?>
        </p>
    </form>
</div>

<?php include_once 'tpl/footer.php'; ?>
