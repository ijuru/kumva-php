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
		<?php } if (defined('KUMVA_FACEBOOK_SITEADMIN')) { ?>
		<meta property="fb:admins" content="<?php echo KUMVA_FACEBOOK_SITEADMIN; ?>" />
		<?php } if (defined('KUMVA_GOOGLE_SITEVERIFICATION')) { ?>
		<meta name="google-site-verification" content="<?php echo KUMVA_GOOGLE_SITEVERIFICATION; ?>" />
		<?php }
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
			<input id="start" name="<?php echo $startParam; ?>" type="hidden" value="<?php echo $startValue; ?>" />	
			<input id="query" name="<?php echo $queryParam; ?>" type="text" value="<?php echo $queryValue; ?>" size="36" />
			<?php if ($adminStyle) {
				Templates::button('search', "aka_submit(this)", KU_STR_SEARCH); 
			} else { ?>
				<input type="submit" value="<?php echo KU_STR_SEARCH; ?>" />
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
	 * Creates a table of function statistics
	 */
	public static function wordClassStatistics() {
		$stats = Dictionary::getDefinitionService()->getWordClassCounts();
		
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
	 * Displays a random definition that links to a query
	 */
	public static function randomDefinition() {
		$def = Dictionary::getDefinitionService()->getRandomDefinition();
		echo KU_STR_RANDOMENTRY;
		if ($def) {
			echo ': <a class="query link" style="color: black" href="'.KUMVA_URL_ROOT.'/index.php?q='.$def->getPrefix().$def->getLemma().'&amp;ref=rand">';
			Templates::definition($def, FALSE);
			echo '</a>';
		}
	}
	
	/**
	 * Creates a table of tag statistics
	 */
	public static function facebookLikeButton() {
		$url = urlencode(KUMVA_URL_ROOT);
		?>
        <iframe src="http://www.facebook.com/plugins/like.php?href=<?php echo $url; ?>&amp;layout=button_count&amp;show_faces=true&amp;width=90&amp;action=like&amp;colorscheme=light&amp;height=20" 
        scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:90px; height:20px;" allowTransparency="true"></iframe>
        <?php
	}
	
	/**
	 * Creates a small flash button to play the given sound file
	 * @param string sound the url of the sound file
	 */
	public static function soundPlayButton($sound) {
		$swfUrl = 'lib/xspf/player.swf?&song_url='.urlencode($sound);
		?>

		<span style="display:inline-block; vertical-align: middle">
			<object type="application/x-shockwave-flash" data="<?php echo $swfUrl; ?>" width="17" height="17">
				<param name="movie" value="<?php echo $swfUrl; ?>" />
			</object>
		</span>
		<?php
	}
	
	/**
	 * Creates an email feedback form
	 */
	public static function feedbackForm() {
		// Process email submission
		if (isset($_POST['feedback'])) {
			$name = trim(Request::getPostParam('name'));
			$email = trim(Request::getPostParam('email'));
			$feedback = trim(Request::getPostParam('feedback'));
			$email_sent = Notifications::newFeedback($name, $email, $feedback);
	
			if ($email_sent) {
				$feedback = "";
		
				// Send confirmation email
				if ($email != '') {
					Mailer::send($email, "Feedback received", "Thank you for your help!");
				}
			}
		} 
		else {
			$name = '';
			$email = '';
			$feedback = '';
		}	
	?>
		<form method="post">
		<?php if (!isset($email_sent)) { ?>
		<table align="center" cellspacing="5" cellpadding="0" border="0">
			<tr>
				<td width="100"><?php echo KU_STR_NAME; ?></td>
				<td width="200"><input type="text" name="name" style="width: 190px" value="<?php echo $name; ?>" /></td>
				<td width="100" align="center"><?php echo KU_STR_EMAIL; ?></td>
				<td width="200"><input type="text" name="email" style="width: 190px" value="<?php echo $email; ?>" /></td>
			</tr>
			<tr>
				<td width="100" valign="top"><?php echo KU_STR_COMMENT; ?></td>
				<td colspan="3"><textarea rows="3" name="feedback" style="width: 500px"><?php echo $feedback; ?></textarea></td>
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
	public static function userList() {
		echo '<ul>';
		foreach (Dictionary::getUserService()->getUsers() as $user) {
			echo '<li>';
			echo $user->getWebsite() ? '<a href="'.$user->getWebsite().'">'.$user->getName().'</a>' : $user->getName();
			echo '</li>';
		}
		echo '</ul>';
	}
}

?>
