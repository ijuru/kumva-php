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
 * Purpose: Templates class
 */
 
define('KUMVA_DATETIMEFORMAT', 'Y-m-d H:i');
 
/**
 * Utility class for generating HTML/XML templates
 */
class Templates {	
	/**
	 * Displays a revision as prefix+lemma
	 * @param Revision revision the revision
	 */
	public static function word($revision) {
		if ($revision->getPrefix()) 
			echo '<span class="prefix">'.$revision->getPrefix().'</span>';
			
		echo '<span class="lemma">'.$revision->getLemma().'</span>';
	}
	
	/**
	 * Displays a revision as prefix+lemma with link to entry page
	 * @param Revision revision the revision
	 */
	public static function wordLink($revision, $edit = FALSE) {
		$url = '?id='.$revision->getEntry()->getId().'&amp;ref='.urlencode(KUMVA_URL_CURRENT);
		
		echo '<a href="entry.php'.$url.'">';
		self::word($revision);
		echo '</a>';
		if ($edit) {
			echo '&nbsp;';
			self::iconLink('edit', 'entryedit.php'.$url, KU_STR_EDIT);
		}	
	}
	
	/**
	 * Displays an entry as full dictionary entry with examples etc
	 * @param Entry entry the entry
	 */
	public static function entry($entry) {
		// Authenticated users get to see proposals, everyone else only accepted revisions
		$revnumber = Session::getCurrent()->isAuthenticated() ? RevisionPreset::HEAD : RevisionPreset::ACCEPTED;

		$revision = Dictionary::getEntryService()->getEntryRevision($entry, $revnumber);
	
		// Display prefix+lemma
		self::word($revision);
		echo ' ';
	
		// Display modifier
		if ($revision->getModifier())
			echo ' (<span class="modifier">'.$revision->getModifier().'</span>) ';
			
		// Display pronunciation
		if ($revision->getPronunciation())
			echo ' /<span class="pronunciation">'.$revision->getPronunciation().'</span>/ ';
			
		// Display sound widget
		if ($entry->hasMedia(Media::AUDIO)) {
			Widgets::sound($entry->getId());
			echo ' ';
		}
			
		// Display word class
		$wordClass = $revision->getWordClass();
		if ($wordClass) {
			$wordClassName = WordClass::getNameFromCode($wordClass);
			$refPage = Theme::getPageForWordClass($wordClass);
			if ($refPage != NULL)
				echo ' <a class="wordclass" href="page.php?name='.$refPage.'" title="'.$wordClassName.'">'.$wordClass.'</a>';
			else
				echo ' <span class="wordclass" title="'.$wordClassName.'">'.$wordClass.'</span>';
		}
	
		// Display noun classes
		$nounClasses = $revision->getNounClasses();
		$nounPage = Theme::getPageForWordClass('n');
		foreach ($nounClasses as $cls) {
			$clsName = Theme::getNounClassName($cls);
			echo ' <a class="nounclass" href="page.php?name='.$nounPage.'#classes" title="'.KU_STR_NOUNCLASS.' '.$clsName.'">'.$cls.'</a>';
		}
	
		// Display variant tags
		$tags = $revision->getTags(Relationship::VARIANT);
		if (count($tags) > 0) {
			$tagsHtml = array();
			foreach ($tags as $tag) 
				$tagsHtml[] = '<span class="reference">'.$tag->getText().'</span>';
	
			echo ' ('.KU_STR_ALSO.' '.implode(', ', $tagsHtml).') ';
		}
		
		echo ' ';
		Templates::icon('bullet_arrow_right');
		echo ' ';

		// Display meanings with parsed references
		$meanings = $revision->getMeanings();
		$numbered = count($meanings) > 1;
		$number = 1;
		foreach ($meanings as $meaning) {
			$meaningText = self::parseReferences(aka_prephtml($meaning->getMeaning()), 'index.php');
			if ($numbered)
				echo ($number++).'. ';
				
			echo '<span class="meaning">'.$meaningText.'</span>';	
			
			// Display meaning flags
			foreach (Flags::values() as $flag) {
				if ($meaning->getFlag($flag))
					echo '&nbsp;<span class="flag">'.Flags::toString($flag).'</span>';
			}
			
			echo ' ';
		}
	
		// Display comment with parsed references
		if ($revision->getComment()) {
			$comment = self::parseReferences(htmlspecialchars($revision->getComment()), 'index.php');
			echo '&nbsp;<span class="comment">('.$comment.')</span>';
		}

		// Display root tags
		$rootTags = $revision->getTags(Relationship::ROOT);
		if (count($rootTags) > 0) {
			$rootsHtml = array();
			foreach ($rootTags as $rootTag) 
				$rootsHtml[] = self::reference($rootTag->getLang(), $rootTag->getText());
	
			echo ', '.KU_STR_FROM.' '.implode(' and ', $rootsHtml);
		}
		
		// Display edit link
		if (Session::getCurrent()->hasRole(Role::CONTRIBUTOR)) {
			echo '&nbsp;';
			Templates::iconLink('edit', KUMVA_URL_ROOT.'/admin/entry.php?id='.$revision->getEntry()->getId(), KU_STR_EDITENTRY);
		}
			
		// Display proposal warning
		if ($revision->getStatus() == RevisionStatus::PROPOSED)
			echo '&nbsp;<span class="proposalwarning">'.KU_STR_PROPOSAL.'</span>'; 

		// Display usage examples
		self::exampleList($revision->getExamples());	
	}
	
	/**
	 * Parses a string containing references e.g. "See {gukora}"
	 * @param string the string to parse
	 * @param string queryUrl the base url for queries
	 * @return string the parsed string
	 */
	public static function parseReferences($str, $queryUrl = '') {
		$matches = array();
		preg_match_all('/\{(.+?)\}/', $str, $matches, PREG_SET_ORDER);
		for ($r = 0; $r < count($matches); $r++) {
			$holder = $matches[$r][0];
			$ref = $matches[$r][1];
			$ref_html = self::reference(KUMVA_LANG_DEFS, $ref, $queryUrl);
			$str = str_replace($holder, $ref_html, $str);
		}
		return $str;
	}
	
	/**
	 * Gets the HTML for a reference link
	 * @param lang the reference language
	 * @param string reference the reference
	 * @param string queryUrl the base url for queries
	 * @return string HTML for reference
	 */
	public static function reference($lang, $reference, $queryUrl = '') {
		global $KUMVA_LANGS;
		
		if ($lang == KUMVA_LANG_DEFS)
			return '<a class="query link reference" href="'.$queryUrl.'?q='.$reference.'">'.$reference.'</a>';
		
		$language = Dictionary::getLanguageService()->getLanguageByCode($lang);
		if ($language) {
			if ($language->getQueryUrl()) {
				$url = str_replace('{QUERY}', urlencode($reference), $language->getQueryUrl());
				return '<span class="link" title="'.$language->getName().'">'.ucfirst($lang).'.</span> <a class="query link reference" href="'.$url.'">'.$reference.'</a>';
			}
			
			return '<span class="link" title="'.$language->getName().'">'.ucfirst($lang).'.</span> <span class="reference">'.$reference.'</span>';
		}
		
		return '<span class="link">'.ucfirst($lang).'.</span> <span class="reference">'.$reference.'</span>';
	}
	
	/**
 	 * Generates buttons for paging
 	 * @param Paging paging the paging
	 */
	public static function pagerButtons($paging) {
		if ($paging->getStart() != 0) {
			Templates::buttonLink('first', $paging->getUrlFirst(), NULL, KU_STR_FIRSTPAGE);
			Templates::buttonLink('previous', $paging->getUrlPrevious(), NULL, KU_STR_PREVIOUSPAGE);
		}
		else {
			Templates::button('first_disabled', '');
			Templates::button('previous_disabled', '');  
		}
	
		if ($paging->getStart() < $paging->getTotal() - $paging->getSize()) {
			Templates::buttonLink('next', $paging->getUrlNext(), NULL, KU_STR_NEXTPAGE);
			Templates::buttonLink('last', $paging->getUrlLast(), NULL, KU_STR_LASTPAGE);
		}
		else {
			Templates::button('next_disabled', '');
			Templates::button('last_disabled', '');  
		}
	}
	
	/**
	 * Creates a breadcrumb trail based on the ancestry of the given page
	 * @param Page page the page
	 */
	public static function pageHierarchy($page) {
		$ancestors = array_reverse($page->getHierarchy());
		foreach ($ancestors as $ancestor) {
			echo '<a href="page.php?name='.$ancestor->getName().'">'.$ancestor->getTitle().'</a>'.'&nbsp&gt;&nbsp;';
		}
		echo $page->getTitle();
	}
	
	/**
	 * Creates a textual representation of a UNIX timestamp
	 * @param int timestamp the timestamp
	 */
	public static function dateTime($timestamp) {
		if ($timestamp)
			echo date(KUMVA_DATETIMEFORMAT, $timestamp);
	}
	
	/**
	 * Creates a user link
	 * @param User user the user
	 */
	public static function userLink($user) {
		if ($user != NULL)
			echo '<a href="user.php?id='.$user->getId().'" title="'.$user->getName().'">'.$user->getLogin().'</a>';
	}
	
	/**
	 * Creates an icon image
	 * @param string name the name of the image
	 * @param string tooltip the tooltip (optional)
	 */
	public static function icon($name, $tooltip = NULL) {
		$text = $tooltip ? ('title="'.$tooltip.'"') : '';
		$url = KUMVA_URL_ROOT.'/gfx/icons/'.$name.'.png?'.KUMVA_VER_RESOURCES;
		echo '<img src="'.$url.'" '.$text.' alt="'.$name.'" width="16" height="16" style="vertical-align: middle" />';
	}
	
	/**
	 * Creates an icon image link
	 * @param string name the name of the image
	 * @param string url the URL of the link
	 * @param string tooltip the tooltip (optional)
	 */
	public static function iconLink($name, $url, $tooltip = NULL, $label = NULL) {
		echo '<a href="'.$url.'">';
		self::icon($name, $tooltip);
		if ($label)
			echo ' '.$label;
		echo '</a>';
	}
	
	/**
	 * Creates a 5 star based ranking
	 * @param Rank rank the 1...5 rank value
	 * @param bool showName TRUE if rank should be displayed
	 */
	public static function rank($rank, $showName = TRUE) {
		$value = $rank->getId();
		echo '<span style="white-space:nowrap">';
		for ($s = 1; $s <= $value; $s++)
			self::icon('star');
		for (; $s <= 5; $s++)
			self::icon('star_disabled');
		echo '</span>';
		if ($showName)
			echo ' ('.$rank->getName().')';
	}
	
	/**
	 * Creates a button
	 * @param string icon the name of the image
	 * @param string onClick javascript to be excuted when button is clicked
	 * @param string text of the button (optional)
	 * @param string tooltip the tooltip (optional)
	 @param bool disabled TRUE if buttton is disabled (optional)
	 */
	public static function button($icon, $onClick, $text = NULL, $tooltip = NULL, $disabled = FALSE) {
		$class = $disabled ? 'button' : 'button clickable';
		echo '<span onclick="'.$onClick.'" class="'.$class.'" '.($tooltip ? 'title="'.$tooltip.'"' : '').'>';
		self::icon($icon);
		echo ($text ? ' '.$text : '').'</span>';
	}
	
	/**
	 * Creates a button link
	 * @param string icon the name of the image
	 * @param string url the URL of the link
	 * @param string text of the button (optional)
	 * @param string tooltip the tooltip (optional)
	 * @param bool disabled TRUE if buttton is disabled (optional)
	 */
	public static function buttonLink($icon, $url, $text = NULL, $tooltip = NULL, $disabled = FALSE) {
		$class = $disabled ? 'button' : 'button clickable';
		echo '<span onclick="aka_goto(\''.$url.'\')" class="'.$class.'" '.($tooltip ? 'title="'.$tooltip.'"' : '').'>';
		self::icon($icon);
		echo ($text ? ' '.$text : '').'</span>';
	}
	
	/**
	 * Creates a dropdown menu of site languages
	 * @param string id the id and name of the control
	 * @param string value the initial value (if NULL then takes value from session)
	 */
	public static function languageSelect($id = NULL, $value = NULL) {
		$curLang = Session::getCurrent()->getLang();
		$params = $_GET;
		$params['lang'] = '__LANG__';
		$url = aka_buildurl('', $params);
		$url = str_replace('__LANG__', "'+this.value+'", $url);
		
		echo '<select '.($id ? 'id="'.$id.'" name="'.$id.'"' : '').' onchange="aka_goto(\''.$url.'\')">';
		foreach (Dictionary::getLanguageService()->getSiteLanguages() as $language) {
			$code = $language->getCode();
			echo '<option value="'.$code.'" '.(($code == $curLang) ? 'selected="selected"' : '').'>'.$language->getLocalName().'</option>';
		}
		echo '</select>';
	}
	
	/**
	 * Creates a dropdown menu of user timezones
	 * @param string id the id and name of the control
	 * @param string value the initial value
	 */
	public static function timezoneSelect($id = NULL, $value = NULL) {
		echo '<select '.($id ? 'id="'.$id.'" name="'.$id.'"' : '').'>';
		foreach (DateTimeZone::listIdentifiers() as $identifier) {
			echo '<option>'.$identifier.'</option>';
		}
		echo '</select>';
	}
	
	/**
	 * Creates a table of changes from an array of changes
	 * @param array changes the changes
	 * @param bool showHeader TRUE to display table header
	 * @param bool showRevision TRUE to display revision link
	 * @param bool showSubmitter TRUE to display change submitter
	 */
	public static function changesTable(&$changes, $showHeader = TRUE, $showRevision = TRUE, $showSubmitter = TRUE, $byResolved = FALSE) {
		$actionIcons = array('change_create', 'change_modify', 'change_delete');
		?>
		<table class="list" cellspacing="0" border="0">
			<?php if ($showHeader) { ?>
			<tr>
				<th style="width: 30px">&nbsp;</th>
				<th style="width: 20px">&nbsp;</th>
				<th>ID</th>
				<th><?php echo $byResolved ? KU_STR_RESOLVED : KU_STR_SUBMITTED; ?></th>
				<?php if ($showRevision) { ?>
					<th><?php echo KU_STR_ENTRY; ?></th>
				<?php } ?>
				<th><?php echo KU_STR_ACTION; ?></th>
				<?php if ($showSubmitter) { ?>
					<th><?php echo KU_STR_SUBMITTER; ?></th>
				<?php } ?>
				<th><?php echo KU_STR_COMMENTS; ?></th>
				<th><?php echo KU_STR_STATUS; ?></th>
				<th style="width: 30px">&nbsp;</th>
			</tr>
			<?php }
			foreach($changes as $change) { 
				if ($change->getAction() == Action::DELETE) {
					$entry = $change->getEntry();
					$revision = Dictionary::getEntryService()->getEntryRevision($entry, RevisionPreset::LAST);
				}
				else
					$revision = Dictionary::getChangeService()->getChangeRevision($change);

				$icon = $actionIcons[$change->getAction()];
				$itemUrl = 'change.php?id='.$change->getId().'&amp;ref='.urlencode(KUMVA_URL_CURRENT);
				$commentCounts = Dictionary::getChangeService()->getChangeCommentCounts($change);
				?>
				<tr class="rowlink" onclick="aka_goto('<?php echo $itemUrl; ?>')">
					<td>&nbsp;</td>
					<td><?php Templates::icon($icon); ?></td>
					<td><?php echo $change->getId(); ?></td>
					<td class="primarycol"><?php Templates::dateTime($byResolved ? $change->getResolved() : $change->getSubmitted()); ?></td>
					<?php if ($showRevision) { ?>
						<td><?php Templates::word($revision); ?></td>
					<?php } ?>
					<td><?php echo Action::toLocalizedString($change->getAction()); ?></td>
					<?php if ($showSubmitter) { ?>
						<td><?php Templates::userLink($change->getSubmitter()); ?></td>
					<?php } ?>
					<td style="text-align: center"><?php echo $commentCounts['comments']; ?> (<?php echo $commentCounts['approvals']; ?> <?php Templates::icon('approve'); ?>)</td>
					<td style="text-align: center" class="status-<?php echo $change->getStatus(); ?>">
					<?php echo Status::toLocalizedString($change->getStatus()); ?>
					</td>
					<td>&nbsp;</td>
				</tr>
			<?php } ?>
		</table>
		<?php if (count($changes) == 0) { ?>
			<div style="padding: 10px; text-align: center"><?php echo KU_MSG_NOMATCHINGCHANGES; ?></div>
		<?php }
	}
	
	/**
	 * Creates a list of usage examples
	 * @param array examples the usage examples
	 */
	public static function exampleList(&$examples) {
		if (count($examples) > 0) {
			echo '<ul>';
			foreach ($examples as $example)
				echo '<li><span class="exampleform">'.$example->getForm().'</span> - <span class="examplemeaning">'.$example->getMeaning().'</span></li>';
			echo '</ul>';
		}
	}
}

?>
