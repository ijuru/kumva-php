<?php header('Content-type: text/html; charset=utf-8'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?php echo KUMVA_TITLE_LONG; ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" type="text/css" href="../gfx/admin/default.css?<?php echo KUMVA_VER_RESOURCES; ?>" />
	<link rel="stylesheet" type="text/css" href="../gfx/autocomplete.css?<?php echo KUMVA_VER_RESOURCES; ?>" />
	<link rel="shortcut icon" href="../gfx/admin/favicon.ico?<?php echo KUMVA_VER_RESOURCES; ?>" />
	<script type="text/javascript" src="../lib/akabanga/js/master.js.php"></script>
	<script type="text/javascript" src="../js/jquery-ui-1.8.10.custom.min.js?<?php echo KUMVA_VER_RESOURCES; ?>"></script>
	<script type="text/javascript" src="../js/kumva.js?<?php echo KUMVA_VER_RESOURCES; ?>"></script>
</head>

<?php

// Admin menu items
$menu = array(
	array('index.php', KU_STR_HOME, 'home', NULL, array(
		array('index.php', KU_STR_OVERVIEW, 'overview', NULL),
		array('searches.php', KU_STR_SEARCHES, 'searches', NULL),
		array('reports.php', KU_STR_REPORTS, 'reports', NULL)
	)),
	array('entries.php', KU_STR_DICTIONARY, 'dictionary', NULL, array(
		array('entries.php', KU_STR_ENTRIES, 'entry', NULL),
		array('changes.php', KU_STR_CHANGES, 'changes', NULL),
		array('tags.php', KU_STR_TAGS, 'tags', Role::ADMINISTRATOR),
		array('export.php', KU_STR_EXPORT, 'export', NULL),
		array('import.php', KU_STR_IMPORT, 'import', Role::ADMINISTRATOR)
	)),
	array('users.php', KU_STR_COMMUNITY, 'users', NULL, array( 
		array('users.php', KU_STR_USERS, 'users', NULL),
		array('roles.php', KU_STR_ROLES, 'roles', Role::ADMINISTRATOR),
		array('ranks.php', KU_STR_RANKS, 'rank', NULL)
	)),
	array('pages.php', KU_STR_SITE, 'site', NULL, array(
		array('pages.php', KU_STR_PAGES, 'pages', NULL),
		array('media.php', KU_STR_MEDIA, 'media', NULL),
		array('languages.php', KU_STR_LANGUAGES, 'languages', NULL),
		array('settings.php', KU_STR_SETTINGS, 'settings', Role::ADMINISTRATOR)
	))
);

// Find current top level and sub level menu items based on current url
$currentTopItem = NULL;
$currentSubItem = NULL;
foreach ($menu as $topItem) {
	if (strpos($_SERVER['SCRIPT_FILENAME'], $topItem[0]))
		$currentTopItem = $topItem;
	foreach ($topItem[4] as $subItem) {
		if (strpos($_SERVER['SCRIPT_FILENAME'], $subItem[0])) {
			$currentTopItem = $topItem;
			$currentSubItem = $subItem;
			break;
		}
	}
}
	
function kumva_menulink($url, $title, $icon, $active) {
	echo '<li><a href="'.$url.'"'.($active ? ' class="active"' : '').'>';
	Templates::icon($icon);
	echo ' '.$title.'</a></li>';
}

$curUser = Session::getCurrent()->getUser();

?>

<body>
	<div id="wrap">
    	<div id="header">
    		<?php if (KUMVA_MODE == 'debug') { ?>
    			<div style="color: white; background-color: red; padding: 6px; text-align: center">DEBUG MODE ACTIVE</div>
    		<?php } ?>
            <div id="banner">	
                <h1>Kumva Admin</h1>
                <h2><?php echo KUMVA_TITLE_SHORT; ?></h2>
                
                <div id="usermenu">
                    <?php 
                    if ($curUser) 
                        echo KU_STR_WELCOME.' <a href="user.php?id='.$curUser->getId().'">'.$curUser->getName().'</a>&nbsp;&nbsp;|&nbsp;&nbsp;';
					?>
                    <a href="<?php echo KUMVA_URL_ROOT; ?>"><?php echo KU_STR_VIEWSITE; ?></a>
					&nbsp;&nbsp;|&nbsp;&nbsp;
                    <?php Templates::languageSelect(); ?>
                    
                    <?php if ($curUser) { ?>
                        &nbsp;&nbsp;|&nbsp;&nbsp;
                        <a href="login.php?logout"><?php echo KU_STR_LOGOUT; ?>
                        <?php Templates::icon('logout'); ?>
                        </a>
                    <?php } ?>
                </div>
            </div>
            
            
            <ul id="pagemenu">
                <?php
                foreach ($menu as $topItem) {
                    if ($curUser && (!$topItem[3] || Session::getCurrent()->hasRole($topItem[3]))) {
                        $active = $currentTopItem ? ($currentTopItem[0] == $topItem[0]) : FALSE;
                        kumva_menulink($topItem[0], $topItem[1], $topItem[2], $active);
                    }
                }
                ?>
            </ul>
           
            
            <?php if ($curUser) { ?>
            <ul id="submenu">
                <?php
                if ($curUser && $currentTopItem) {
                    foreach ($currentTopItem[4] as $subItem) {
                        if (!$subItem[3] || Session::getCurrent()->hasRole($subItem[3])) {
                            $active = $currentSubItem ? ($currentSubItem[0] == $subItem[0]) : FALSE;
                            kumva_menulink($subItem[0], $subItem[1], $subItem[2], $active);
                        }
                    }
                    if ($currentSubItem && $currentSubItem[0] != 'entries.php') {
                    ?>
                    <div style="float: right; margin: 6px">
                        <?php Widgets::searchForm('entries.php', 'q', 'start', TRUE); ?>
                    </div>
                    <?php
                    }
                }
                ?>
            </ul>
            <?php } ?>
            
        </div>
        <div id="content">