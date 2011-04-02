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
 * Purpose: Configuration settings
 */
 
// Database constants
define('KUMVA_DB_HOST', 'localhost');
define('KUMVA_DB_NAME', 'databasename');
define('KUMVA_DB_USER', 'username');
define('KUMVA_DB_PASS', 'password');
define('KUMVA_DB_PREFIX', 'tableprefix_');

// Site constants
define('KUMVA_TITLE_SHORT', 'Kumva');
define('KUMVA_TITLE_LONG', 'Kumva: Bantu-English Dictionary');
define('KUMVA_TITLE_WOTD', 'Kumva Word of the Day');
define('KUMVA_THEME_NAME', 'mytheme');
define('KUMVA_LANG_DEFS', 'rw');		// Language of definitions
define('KUMVA_LANG_MEANING', 'en');		// Language of meanings
define('KUMVA_PAGE_SIZE', 10);

// Optional extras
define('KUMVA_FACEBOOK_SITEADMIN', 'xxxxxxxxx');
define('KUMVA_GOOGLE_SITEVERIFICATION', 'xxxxxxxxxxxxxxxxxxxxx');

?>
