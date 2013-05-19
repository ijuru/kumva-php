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
 * Purpose: Widgets class 
 */
 
/**
 * Utility class for generating widgets in site pages
 */
class Widgets {	
	/**
	 * Generates required HTML headers required for Kumva
	 */
	public static function headers() {
		$curPageName = Request::getGetParam('name', NULL);
		$curPage = ($curPageName != NULL && aka_endswith($_SERVER['SCRIPT_NAME'], 'page.php')) ? Theme::getPage($curPageName) : NULL;
		/*$curQuery = Request::getGetParam('q', NULL);
		
		if (aka_endswith($_SERVER['SCRIPT_NAME'], 'index.php') && $curQuery != NULL) {
			$ogType = 'kumva:search';
			$ogUrl = aka_absoluteurl('index.php?q='.$curQuery);
			$ogTitle = KUMVA_TITLE_SHORT.': '.$curQuery;
		} else*/if ($curPage != NULL) {
			$ogType = 'article';
			$ogUrl = KUMVA_URL_ROOT.'/page.php?name='.$curPage->getName();
			$ogSiteName = KUMVA_TITLE_SHORT;
			$ogTitle = $curPage->getTitle();	
		} else {
			$ogType = 'website';
			$ogUrl = KUMVA_URL_ROOT;
			$ogSiteName = NULL;
			$ogTitle = KUMVA_TITLE_LONG;
		}
		?>
		<link rel="stylesheet" type="text/css" href="gfx/autocomplete.css?<?php echo KUMVA_VER_RESOURCES; ?>" />
		<link rel="search" type="application/opensearchdescription+xml" href="meta/opensearch.xml.php" title="<?php echo KUMVA_TITLE_SHORT; ?>" />
		<script type="text/javascript" src="lib/akabanga/js/master.js.php?<?php echo KUMVA_VER_RESOURCES; ?>"></script>
		<script type="text/javascript" src="js/jquery-ui-1.8.10.custom.min.js?<?php echo KUMVA_VER_RESOURCES; ?>"></script>
		<script type="text/javascript" src="js/kumva.js?<?php echo KUMVA_VER_RESOURCES; ?>"></script>
		
		<!-- OpenGraph headers -->
		<meta property="og:title" content="<?php echo $ogTitle; ?>" />
		<meta property="og:type" content="<?php echo $ogType; ?>" />
		<meta property="og:image" content="<?php echo KUMVA_URL_THEME.'/gfx/opengraph-image.png'; ?>" />
		<meta property="og:url" content="<?php echo $ogUrl; ?>" />
		
		<?php if ($ogSiteName != NULL) { ?>
		<meta property="og:site_name" content="<?php echo $ogSiteName; ?>" />
		<?php } if (defined('KUMVA_GOOGLE_SITEVERIFICATION')) { ?>
		<meta name="google-site-verification" content="<?php echo KUMVA_GOOGLE_SITEVERIFICATION; ?>" />
		<?php } ?>
	
		<!-- SoundManager2 -->
		<script type="text/javascript" src="js/sm2/soundmanager2-nodebug-jsmin.js"></script>
		<script type="text/javascript" src="js/sm2/mp3-player-button.js"></script>
		<script type="text/javascript">
			soundManager.url = 'js/sm2/'; // required: path to directory containing SM2 SWF files
		</script>
		
		<?php
	}
	
	/**
	 * Creates a search form
	 * @param bool full TRUE for full control, else FALSE
	 */
	public static function searchForm($url = 'index.php', $queryParam = 'q', $startParam = 'start', $adminStyle = FALSE) {
		$queryValue = Request::getGetParam($queryParam);
		$startValue = max((int)Request::getGetParam($startParam, 0), 0);
		
		?>
		<script type="text/javascript">
		/* <![CDATA[ */
		$(function() {
				$("#query").autocomplete({
					source: "<?php echo KUMVA_URL_ROOT; ?>/meta/suggest.php", 
					minLength: 3, 
					select: function(event, ui) {
						$('#query').val(ui.item.value);
						$('#searchForm').submit();
					}
				}).data('autocomplete')._renderItem = function(ul, item) {
					element = $('<li></li>').data('item.autocomplete', item)
						.append('<a class="item-' + item.lang + '">' + item.label + '</a>')
						.appendTo(ul);
					return element;
				};
		});
		/* ]]> */
		</script>
		<form id="searchForm" method="get" action="<?php echo $url; ?>" onsubmit="$('#start').val(0)">
			<input id="query" name="<?php echo $queryParam; ?>" type="text" value="<?php echo $queryValue; ?>" size="40" maxlength="40" />
			<input id="start" name="<?php echo $startParam; ?>" type="hidden" value="<?php echo $startValue; ?>" />	
			<?php if ($adminStyle) {
				Templates::button('search', "aka_submit(this)", KU_STR_SEARCH); 
			} else { ?>
				<input type="submit" id="searchbtn" value="<?php echo KU_STR_SEARCH; ?>" />
			<?php } ?>
			
		</form>
		<?php
	}
	
	/**
	 * Creates a table of tag statistics
	 */
	public static function tagStatistics() {
		$tagStats = Dictionary::getTagService()->getTagStatistics();
		$relationships = Dictionary::getTagService()->getRelationships();
		
		echo '<table class="widget-tagstats">';
		echo '<tr>';
		echo '<th style="text-align: left">'.KU_STR_LANGUAGE.'</th>';
		
		foreach ($relationships as $relationship)
			echo '<th>'.$relationship->getTitle().'</th>';
		echo '</tr>';
			
		foreach ($tagStats as $lang => $tagCounts) {
			$language = Dictionary::getLanguageService()->getLanguageByCode($lang);
			$langName = $language ? $language->getName() : $lang;
			echo '<tr>';
			echo '<td style="text-align: left">'.$langName.'</td>';
			foreach ($relationships as $relationship) {
					$count = $tagCounts[$relationship->getName()];
					
					echo '<td>';
					if ($count > 0)
						echo '<a href="index.php?q=lang:'.$lang.'+match:'.$relationship->getName().'">'.$count.'</a>';
					else
						echo $count;	
					echo '</td>';
			}
			echo '</tr>';
		} 
		echo '</table>';
	}

	/**
	 * Creates a cloud of category tags
	 */
	public static function categoryCloud($showCounts) {
		echo '<div class="widget-catcloud">';

		$catCounts = Dictionary::getTagService()->getCategoryCounts();

		foreach ($catCounts as $c) {
			$category = $c['category'];
			$count = $c['count'];
			$rank = (int) min(sqrt(2 * $count), 10);
			echo '<a href="index.php?q=match:category+'.$category.'" class="rank'.$rank.'">';
			echo $category;
			if ($showCounts) {
				echo ' ('.$count.')';
			}
			echo '</a>';
		}

		echo '</div>';
	}
	
	/**
	 * Creates a table of function statistics
	 */
	public static function wordClassStatistics() {
		$stats = Dictionary::getEntryService()->getWordClassCounts();
		
		echo '<table class="widget-tagstats">';
		echo '<tr>';
		echo '<th style="text-align: left">'.KU_STR_CLASS.'</th>';
		echo '<th>'.KU_STR_ABBREVIATION.'</th>';
		echo '<th>'.KU_STR_TOTAL.'</th>';
		echo '</tr>';
			
		foreach ($stats as $row) {
			$abbr = $row['wordclass'];
			if ($abbr != NULL) {
				echo '<tr>';
				$page = Theme::getPageForWordClass($abbr);
				if ($page != NULL)
					echo '<td style="text-align: left"><a href="page.php?name='.$page.'">'.WordClass::getNameFromCode($abbr).'</a></td>';
				else	
					echo '<td style="text-align: left">'.WordClass::getNameFromCode($abbr).'</td>';
					
				echo '<td>'.$abbr.'</td>';
				echo '<td><a href="index.php?q=wclass:'.$abbr.'">'.$row['count'].'</td>';
				echo '</tr>';
			}
		} 
		echo '</table>';
	}
	
	/**
	 * Creates a small flash button to play the given sound file
	 * @param mixed sound the url of the sound file or id of an entry
	 */
	public static function sound($sound) {
		if (is_int($sound))
			$sound = KUMVA_URL_MEDIA.'/audio/'.$sound.'.mp3';
		
		echo '<a href="'.$sound.'" class="sm2_button" title="'.KU_STR_LISTEN.'">&gt;</a>';
	}
	
	/**
	 * Creates an email feedback form
	 */
	public static function feedbackForm() {
		// Process email submission
		if (Request::hasPostParam('feedback_message')) {
			$name = trim(Request::getPostParam('feedback_name'));
			$email = trim(Request::getPostParam('feedback_email'));
			$message = trim(Request::getPostParam('feedback_message'));
			$email_sent = Notifications::newFeedback($name, $email, $message);
	
			if ($email_sent)
				$feedback = '';
		} 
		else {
			$name = '';
			$email = '';
			$message = '';
		}	
	?>
	<script type="text/javascript">
	function checkFields() {
		var valid = $('#feedback_name').val() != '' && $('#feedback_email').val() != '' && $('#feedback_message').val() != '';
		if (!valid)
			alert("<?php echo KU_MSG_PLEASECOMPLETEALL; ?>");
		return valid;
	}
	</script>
	<form method="post" onsubmit="return checkFields();">
		<?php if (!isset($email_sent)) { ?>
			<table align="center" cellspacing="5" cellpadding="0" border="0">
				<tr>
					<td width="100"><?php echo KU_STR_NAME; ?></td>
					<td width="200"><input type="text" id="feedback_name" name="feedback_name" style="width: 190px" value="<?php echo $name; ?>" /></td>
					<td width="100" align="center"><?php echo KU_STR_EMAIL; ?></td>
					<td width="200"><input type="text" id="feedback_email" name="feedback_email" style="width: 190px" value="<?php echo $email; ?>" /></td>
				</tr>
				<tr>
					<td width="100" valign="top"><?php echo KU_STR_COMMENT; ?></td>
					<td colspan="3"><textarea rows="3" id="feedback_message" name="feedback_message" style="width: 500px"><?php echo $message; ?></textarea></td>
				</tr>
				<tr>
					<td colspan="4" align="center"><input type="submit" value="Send" /></td>
				</tr>
			</table>
		<?php } elseif ($email_sent) { ?>
			<div class="msginfo" style="text-align: center">Feedback sent - thank you for your help!</div>
		<?php } else { ?>
			<div class="msgerror" style="text-align: center">Feedback could not be sent, sorry</div>
		<?php } ?>	
	</form>
	<?php
	}
	
	/**
	 * Creates a table of contents based on the children and grandchildren of the specified page
	 * @param Page the page
	 */
	public static function tableOfContents($page) {
		echo '<ul>';
		foreach ($page->getChildren() as $child) {
			echo '<li><a href="page.php?name='.$child->getName().'">'.$child->getTitle().'</a>';
			$grandchildren = $child->getChildren();
			if (count($grandchildren) > 0) {
				echo '<ul>';
				foreach ($grandchildren as $grandchild)
					echo '<li><a href="page.php?name='.$grandchild->getName().'">'.$grandchild->getTitle().'</a></li>';
				echo '</ul>';
			}
			echo '</li>';
		}
		echo '</ul>';
	}
	
	/**
	 * Creates a list of active users
	 */
	public static function userList($activeOnly = true, $excludeUsers = null) {
		echo '<ul>';
		foreach (Dictionary::getUserService()->getUsers() as $user) {
			if ($excludeUsers && in_array($user->getId(), $excludeUsers)) {
				continue;
			}

			if ($activeOnly) {
				$userStats = Dictionary::getUserService()->getUserActivity($user);
				if ($userStats['proposals'] < 5) {
					continue;
				}
			} 

			echo '<li>';
			echo $user->getWebsite() ? '<a href="'.$user->getWebsite().'">'.$user->getName().'</a>' : $user->getName();
			echo '</li>';
		}
		echo '</ul>';
	}
}

?>
