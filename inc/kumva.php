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
 * Purpose: Main API entry point
 */
 
// Kumva version number
define('KUMVA_VERSION', '3.5 BETA');
define('KUMVA_VER_RESOURCES', '20110605'); // Used to version/cachebust resources

////////////////////////// Setup directory/URL constants /////////////////////////////
 
// Get absolute paths of standard directories
define('KUMVA_DIR_ROOT', realpath(dirname(__FILE__).'/../'));
define('KUMVA_DIR_INC', KUMVA_DIR_ROOT.'/inc');
define('KUMVA_DIR_LIB', KUMVA_DIR_ROOT.'/lib');
define('KUMVA_DIR_MEDIA', KUMVA_DIR_ROOT.'/media');

// Calculate root absolute URL from root directory
$tempPath1 = explode('/', str_replace('\\', '/', dirname($_SERVER['SCRIPT_FILENAME'])));
$tempPath2 = explode('/', KUMVA_DIR_ROOT);
$tempPath3 = explode('/', str_replace('\\', '/', dirname($_SERVER['PHP_SELF'])));
for ($i = count($tempPath2); $i < count($tempPath1); $i++)
    array_pop($tempPath3);
$urlRoot = 'http://'.$_SERVER['HTTP_HOST'].implode('/', $tempPath3);
// Remove trailing slash
if ($urlRoot{strlen($urlRoot) - 1} == '/')
	$urlRoot = substr($urlRoot, 0, -1);
define('KUMVA_URL_ROOT', $urlRoot);
unset($tempPath1, $tempPath2, $tempPath3, $urlRoot);

// Get current absolute URL and login URL
define('KUMVA_URL_CURRENT', 'http://'.$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]);
define('KUMVA_URL_LOGIN', KUMVA_URL_ROOT.'/admin/login.php');
define('KUMVA_URL_MEDIA', KUMVA_URL_ROOT.'/media');

// Project URLs
define('KUMVA_URL_PROJECT', 'https://github.com/ijuru/kumva');
define('KUMVA_URL_HELP', KUMVA_URL_PROJECT.'/wiki');
define('KUMVA_URL_REPORTBUG', KUMVA_URL_PROJECT.'/issues');

//////////////////////////////// Load libraries ////////////////////////////////////

require_once KUMVA_DIR_LIB.'/markdown/markdown.php';
require_once KUMVA_DIR_LIB.'/akabanga/akabanga.php';

//////////////////////////////// Load config ///////////////////////////////////////
 
// Try to load config file and configure the database
if (file_exists(KUMVA_DIR_INC.'/../config.php')) {
	require_once KUMVA_DIR_INC.'/../config.php';
	aka_dbconfigure(KUMVA_DB_HOST, KUMVA_DB_USER, KUMVA_DB_PASS, KUMVA_DB_NAME);
	define('KUMVA_DIR_THEME', KUMVA_DIR_ROOT.'/themes/'.KUMVA_THEME_NAME);
	define('KUMVA_URL_THEME', KUMVA_URL_ROOT.'/themes/'.KUMVA_THEME_NAME);
	define('KUMVA_THEME_FNFILE', KUMVA_DIR_THEME.'/functions.php');
}
else
	die('No config file found');

///////////////// Runtime mode (maintenance, debug or live) ////////////////////////

if (!defined('KUMVA_MODE'))
	define('KUMVA_MODE', 'live');
elseif (KUMVA_MODE == 'debug')
	define('AKABANGA_DEBUG', TRUE);

//////////////////////// Load user and language services ///////////////////////////

require_once KUMVA_DIR_INC.'/Dictionary.php';

require_once KUMVA_DIR_INC.'/language/Lexical.php';
require_once KUMVA_DIR_INC.'/language/Language.php';
require_once KUMVA_DIR_INC.'/language/LanguageService.php';

require_once KUMVA_DIR_INC.'/user/Role.php';
require_once KUMVA_DIR_INC.'/user/Subscription.php';
require_once KUMVA_DIR_INC.'/user/Rank.php';
require_once KUMVA_DIR_INC.'/user/User.php';
require_once KUMVA_DIR_INC.'/user/Session.php';
require_once KUMVA_DIR_INC.'/user/UserService.php';

//////////////////////////// Configure localization ////////////////////////////////

// Default to UTC
date_default_timezone_set('UTC');

if ($lang = Request::getGetParam('lang', '')) {
	$language = Dictionary::getLanguageService()->getLanguageByCode($lang);
	if ($language != NULL)
		Session::getCurrent()->setLang($language->getCode());
}	

// Load site translation
include_once(KUMVA_DIR_ROOT.'/lang/'.Session::getCurrent()->getLang().'/site.php');

// Configure timezone
if (Session::getCurrent()->isAuthenticated() && Session::getCurrent()->getUser()->getTimezone())
	date_default_timezone_set(Session::getCurrent()->getUser()->getTimezone());

// Initialize lexical processing engine
Lexical::initializeLanguages();

//////////////////////////// Load remaining services ////////////////////////////////

require_once KUMVA_DIR_INC.'/tag/Relationship.php';
require_once KUMVA_DIR_INC.'/tag/Tag.php';
require_once KUMVA_DIR_INC.'/tag/TagService.php';

require_once KUMVA_DIR_INC.'/definition/WordClass.php';
require_once KUMVA_DIR_INC.'/definition/Example.php';
require_once KUMVA_DIR_INC.'/definition/Meaning.php';
require_once KUMVA_DIR_INC.'/definition/Definition.php';
require_once KUMVA_DIR_INC.'/definition/Entry.php';
require_once KUMVA_DIR_INC.'/definition/DefinitionService.php';

require_once KUMVA_DIR_INC.'/change/Comment.php';
require_once KUMVA_DIR_INC.'/change/Change.php';
require_once KUMVA_DIR_INC.'/change/ChangeService.php';

require_once KUMVA_DIR_INC.'/search/Query.php';
require_once KUMVA_DIR_INC.'/search/Search.php';
require_once KUMVA_DIR_INC.'/search/SearchService.php';

require_once KUMVA_DIR_INC.'/report/Report.php';
require_once KUMVA_DIR_INC.'/report/ReportResults.php';
require_once KUMVA_DIR_INC.'/report/ReportService.php';

require_once KUMVA_DIR_INC.'/page/Page.php';
require_once KUMVA_DIR_INC.'/page/PageService.php';

require_once KUMVA_DIR_INC.'/theme/Markup.php';
require_once KUMVA_DIR_INC.'/theme/Theme.php';
require_once KUMVA_DIR_INC.'/theme/Templates.php';
require_once KUMVA_DIR_INC.'/theme/Diff.php';
require_once KUMVA_DIR_INC.'/theme/Widgets.php';
require_once KUMVA_DIR_INC.'/theme/FormRenderer.php';

require_once KUMVA_DIR_INC.'/mail/Mailer.php';
require_once KUMVA_DIR_INC.'/mail/Notifications.php';

require_once KUMVA_DIR_INC.'/io/Xml.php';
require_once KUMVA_DIR_INC.'/io/Importer.php';
require_once KUMVA_DIR_INC.'/io/CSVImporter.php';
require_once KUMVA_DIR_INC.'/io/XMLImporter.php';

//////////////////////////// Load site theme ////////////////////////////////
	
if (file_exists(KUMVA_THEME_FNFILE))
	include_once KUMVA_THEME_FNFILE;

?>
