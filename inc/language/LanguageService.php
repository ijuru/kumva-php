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
 * Purpose: Language service class
 */
 
$kumva_messages = array();
 
function ku_message($code) {
	global $kumva_messages;
	return isset($kumva_messages[$code]) ? $kumva_messages[$code] : $code;
}
 
function ku_setmessage($code, $text) {
	global $kumva_messages;
	$kumva_messages[strtolower($code)] = $text;
}

function ku_loadlang($lang) {
	include_once(KUMVA_DIR_ROOT.'/lang/'.$lang.'/site.php');
}

function ku_oldsiteconstants() {
	global $kumva_messages;
	
	foreach ($kumva_messages as $code => $text)
		define('KU_'.strtoupper($code), $text);
}

/**
 * Language functions
 */
class LanguageService extends Service {
	/**
	 * Gets the language with the given id
	 * @param int id the id
	 * @return Language the language
	 */
	public function getLanguage($id) {
		$row = $this->database->row("SELECT * FROM `".KUMVA_DB_PREFIX."language` WHERE language_id = $id");	
		return ($row != NULL) ? Language::fromRow($row) : NULL;
	}
	
	/**
	 * Gets the language with the given code
	 * @param string code the code
	 * @return Language the language
	 */
	public function getLanguageByCode($code) {
		$row = $this->database->row("SELECT * FROM `".KUMVA_DB_PREFIX."language` WHERE `code` = '$code'");	
		return ($row != NULL) ? Language::fromRow($row) : NULL;
	}
	
	/**
	 * Gets all languages
	 * @param bool withTranslation include only languages with a site translation
	 * @param bool withLexical include only languages with a lexical module
	 * @return array the languages
	 */
	public function getLanguages($withTranslation = NULL, $withLexical = NULL) {
		$sql = 'SELECT l.* FROM `'.KUMVA_DB_PREFIX.'language` l WHERE 1=1 ';
		if ($withTranslation === TRUE)
			$sql .= 'AND l.hastranslation = 1 ';
		if ($withLexical === TRUE)
			$sql .= 'AND l.haslexical = 1 ';
				
		$sql .= 'ORDER BY `name`';
		return Language::fromQuery($this->database->query($sql));
	}
	
	/**
	 * Gets all languages with a site translation file
	 * @return array the languages
	 */
	public function getSiteLanguages() {
		return $this->getLanguages(TRUE, NULL);
	}
	
	/**
	 * Gets all languages with a lexical module file
	 * @param bool codesOnly TRUE to return just the language codes
	 * @return array the languages
	 */
	public function getLexicalLanguages($codesOnly = FALSE) {
		$languages = $this->getLanguages(NULL, TRUE);
		if (!$codesOnly)
			return $languages;
			
		$langs = array();
		foreach ($languages as $language)
			$langs[] = $language->getCode();
		return $langs;
	}
	
	/**
	 * Saves the specified language
	 * @param Language language the language
	 * @return bool TRUE if successful, else FALSE
	 */
	public function saveLanguage($language) {
		if ($language->isNew()) {
			$sql = 'INSERT INTO `'.KUMVA_DB_PREFIX.'language` VALUES('
				.'NULL,'
				.aka_prepsqlval($language->getCode()).','
				.aka_prepsqlval($language->getName()).','
				.aka_prepsqlval($language->getLocalName()).','
				.aka_prepsqlval($language->getQueryUrl()).','
				.aka_prepsqlval($language->hasTranslation()).','
				.aka_prepsqlval($language->hasLexical()).')';
			
			$res = $this->database->insert($sql);
			if ($res === FALSE)
				return FALSE;
			$language->setId($res);
		}
		else {
			$sql = 'UPDATE `'.KUMVA_DB_PREFIX.'language` SET '
				.'code = '.aka_prepsqlval($language->getCode()).','
				.'name = '.aka_prepsqlval($language->getName()).','
				.'localname = '.aka_prepsqlval($language->getLocalName()).','
				.'queryurl = '.aka_prepsqlval($language->getQueryUrl()).','
				.'hastranslation = '.aka_prepsqlval($language->hasTranslation()).','
				.'haslexical = '.aka_prepsqlval($language->hasLexical()).' '
				.'WHERE language_id = '.$language->getId();
			
			$res = $this->database->query($sql);
			if ($res === FALSE)
				return FALSE;
		}	
		return TRUE;
	}
	
	/**
	 * Reloads languages from filesystem
	 * @return bool TRUE if successful, else FALSE
	 */
	public function reloadLanguages() {
		clearstatcache();
	
		$dir = opendir(KUMVA_DIR_ROOT.'/lang');
		if ($dir === FALSE)
			return FALSE;
		
		// Delete any existing languages
		$this->database->query('DELETE FROM `'.KUMVA_DB_PREFIX.'language`');
	
		while (FALSE !== ($item = readdir($dir))) {
			if (is_dir(KUMVA_DIR_ROOT.'/lang/'.$item) && $item[0] != '.') {
				$code = $item;
				$infFile = KUMVA_DIR_ROOT.'/lang/'.$item.'/lang.inf';
				$fields = parse_ini_file($infFile, FALSE);
				
				$name = $fields['name'];
				$localName = isset($fields['localname']) ? $fields['localname'] : $fields['name'];
				$queryUrl = isset($fields['queryurl']) ? $fields['queryurl'] : NULL;
				$hasTranslation = file_exists(KUMVA_DIR_ROOT.'/lang/'.$code.'/site.php');
				$hasLexical = file_exists(KUMVA_DIR_ROOT.'/lang/'.$code.'/lexical.php');
				$language = new Language(0, $code, $name, $localName, $queryUrl, $hasTranslation, $hasLexical);
			
				if(!$this->saveLanguage($language))
					return FALSE;
			}
		}
		closedir($dir);
		
		return TRUE;
	}
}

?>
