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
 * Purpose: Dictionary class
 */
 
/**
 * Class to hold all the different services
 */
class Dictionary {
	private static $userService;
	private static $definitionService;
	private static $tagService;
	private static $pageService;
	private static $languageService;
	private static $changeService;
	private static $searchService;
	private static $reportService;
	
	/**
	 * Gets the user service
	 * @return UserService the singleton instance of the service
	 */
	public static function getUserService() {
		if (self::$userService == NULL)
			self::$userService = new UserService(Database::getCurrent());
	
		return self::$userService;
	}
	
	/**
	 * Gets the definition service
	 * @return DefinitionService the singleton instance of the service
	 */
	public static function getDefinitionService() {
		if (self::$definitionService == NULL)
			self::$definitionService = new DefinitionService(Database::getCurrent());
			
		return self::$definitionService;
	}
	
	/**
	 * Gets the tag service
	 * @return TagService the singleton instance of the service
	 */
	public static function getTagService() {
		if (self::$tagService == NULL)
			self::$tagService = new TagService(Database::getCurrent());
	
		return self::$tagService;
	}
	
	/**
	 * Gets the page service
	 * @return PageService the singleton instance of the service
	 */
	public static function getPageService() {
		if (self::$pageService == NULL)
			self::$pageService = new PageService(Database::getCurrent());
	
		return self::$pageService;
	}
	
	/**
	 * Gets the language service
	 * @return LanguageService the singleton instance of the service
	 */
	public static function getLanguageService() {
		if (self::$languageService == NULL)
			self::$languageService = new LanguageService(Database::getCurrent());
	
		return self::$languageService;
	}
	
	/**
	 * Gets the change service
	 * @return ChangeService the singleton instance of the service
	 */
	public static function getChangeService() {
		if (self::$changeService == NULL)
			self::$changeService = new ChangeService(Database::getCurrent());
	
		return self::$changeService;
	}
	
	/**
	 * Gets the search service
	 * @return SearchService the singleton instance of the service
	 */
	public static function getSearchService() {
		if (self::$searchService == NULL)
			self::$searchService = new SearchService(Database::getCurrent());
	
		return self::$searchService;
	}
	
	/**
	 * Gets the report service
	 * @return ReportService the singleton instance of the service
	 */
	public static function getReportService() {
		if (self::$reportService == NULL)
			self::$reportService = new ReportService(Database::getCurrent());
	
		return self::$reportService;
	}
}

?>
