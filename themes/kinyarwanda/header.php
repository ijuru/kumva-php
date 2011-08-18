<?php header('Content-type: text/html; charset=utf-8'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
	<title><?php echo KUMVA_TITLE_LONG; ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	
	<?php Widgets::headers(); ?>
	
	<!-- Site specific headers -->
	<link rel="stylesheet" href="<?php echo KUMVA_URL_THEME; ?>/gfx/default.css?<?php echo KUMVA_VER_RESOURCES; ?>" type="text/css" />
	<link rel="shortcut icon" href="<?php echo KUMVA_URL_THEME; ?>/gfx/favicon.ico?<?php echo KUMVA_VER_RESOURCES; ?>" />
</head>

<?php

$isHome = strpos($_SERVER['SCRIPT_FILENAME'], 'index.php') !== FALSE;
	
function kumva_menulink($url, $title) {
	$active = strpos($_SERVER['SCRIPT_FILENAME'], $url) !== FALSE;	
	return '<a href="'.$url.'"'.($active ? ' class="active"' : '').'>'.$title.'</a>';
}

function kumva_pagelink($name) {
	$linkPage = Theme::getPage($name);
	
	// Is current url a theme page?
	$active = FALSE;
	if (strpos($_SERVER['SCRIPT_FILENAME'], 'page.php') !== FALSE) {
		$curPageName = Request::getGetParam('name');
		$curPage = ($curPageName != '') ? Theme::getPage($curPageName) : NULL;
		if ($curPage != NULL)
			$active = ($curPage->getName() == $linkPage->getName()) || $curPage->isAncestor($linkPage);
	}

	return '<a href="page.php?name='.$linkPage->getName().'"'.($active ? ' class="active"' : '').'>'.$linkPage->getTitle().'</a>';
}

?>
<body>
	<div id="fb-root"></div>
	<div id="wrap">
	
		<div id="header">
			<div id="gutter">
				<div style="float: left">
					<fb:like href="<?php echo KUMVA_URL_ROOT; ?>" send="false" layout="button_count" show_faces="false"></fb:like>
					
					<g:plusone size="small" href="<?php echo KUMVA_URL_ROOT; ?>"></g:plusone>
					
					<a href="http://twitter.com/kinyarwandanet" class="twitter-follow-button" data-show-count="false"> @kinyarwandanet</a>
					<script src="https://platform.twitter.com/widgets.js" type="text/javascript"></script>
					</span>
				</div>
				
				<div style="float: right">
					<?php if (Session::getCurrent()->getUser()) { 
						Templates::icon('administer');
						echo '&nbsp;&nbsp;<a href="'.KUMVA_URL_ROOT.'/admin">'.KU_STR_ADMINSITE.'</a> | ';
					} 
					echo KU_STR_LANGUAGE.': ';
					Templates::languageSelect(); 
					?>
				</div>
			</div>
			
			<div id="banner">
				<h1><?php echo KUMVA_TITLE_LONG; ?></h1>
			</div>
			
			<ul id="mainmenu">
				<li><?php echo kumva_menulink('index.php', KU_STR_DICTIONARY); ?></li>
				<li><?php echo kumva_pagelink('reference'); ?></li>
				<li><?php echo kumva_pagelink('statistics'); ?></li>
				<li><?php echo kumva_pagelink('faq'); ?></li>
				<li><?php echo kumva_pagelink('about'); ?></li>
			</ul>
			
			<div id="searchbar">
				<div <?php echo $isHome ? '' : 'style="float:right"'?>>
					<?php Widgets::searchForm(); ?>
				</div>
			</div> 
		</div>
			
		<div id="content">