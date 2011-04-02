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
 * Purpose: Language class
 */
 
/**
 * Language used for site messages or lexical processing
 */ 
class Language extends Entity {
	private $name;
	private $code;
	private $siteFile;
	private $lexicalFile;
	
	/**
	 * Constructs a language
	 * @param int id the id
	 * @param string code the language code
	 * @param string name the name
	 * @param string siteFile the site messages script file
	 * @param string lexicalFile the lexical script file
	 * @param bool system TRUE if this is a system type
	 */
	public function __construct($id, $code, $name, $siteFile, $lexicalFile) {
		$this->id = $id;
		$this->code = $code;
		$this->name = $name;
		$this->siteFile = $siteFile;
		$this->lexicalFile = $lexicalFile;
	}
	
	/**
	 * Creates a tag from the given row of database columns
	 * @param array the associative array
	 * @return Tag the tag
	 */
	public static function fromRow(&$row) {
		return new Language($row['language_id'], $row['code'], $row['name'], $row['sitefile'], $row['lexicalfile']);
	}
	
	/**
	 * Gets the name, e.g. 'English'
	 * @return string the name
	 */
	public function getName() {
		return $this->name;
	}
	
	/**
	 * Sets the name
	 * @param string name the name
	 */
	public function setName($name) {
		$this->name = $name;
	}
	
	/**
	 * Gets the code, e.g. 'en'
	 * @return string the code
	 */
	public function getCode() {
		return $this->code;
	}
	
	/**
	 * Sets the code
	 * @param string code the code
	 */
	public function setCode($code) {
		$this->code = $code;
	}
	
	/**
	 * Gets the site file path, e.g. '/en/site.php'
	 * @return string the site file path
	 */
	public function getSiteFile() {
		return $this->siteFile;
	}
	
	/**
	 * Sets the site file path
	 * @param string siteFile the site file path
	 */
	public function setSiteFile($siteFile) {
		$this->siteFile = $siteFile;
	}
	
	/**
	 * Includes the site file
	 * @return bool TRUE if successful, else FALSE
	 */
	public function includeSiteFile() {
		if (file_exists(KUMVA_DIR_ROOT.$this->siteFile)) {
			require_once KUMVA_DIR_ROOT.$this->siteFile;
			return TRUE;
		}
		return FALSE;
	}
	
	/**
	 * Includes the lexical file
	 * @return bool TRUE if successful, else FALSE
	 */
	public function includeLexicalFile() {
		if (file_exists(KUMVA_DIR_ROOT.$this->lexicalFile)) {
			require_once KUMVA_DIR_ROOT.$this->lexicalFile;
			return TRUE;
		}
		return FALSE;
	}
	
	/**
	 * Gets the lexical file path, e.g. '/en/lexical.php'
	 * @return string the lexical file path
	 */
	public function getLexicalFile() {
		return $this->lexicalFile;
	}
	
	/**
	 * Sets the lexical file path
	 * @param string lexicalFile the lexical file path
	 */
	public function setLexicalFile($lexicalFile) {
		$this->lexicalFile = $lexicalFile;
	}
	
	/**
	 * Gets the name of a language from its language code
	 * @param string lang the language code
	 * @return string the language name
	 */
	public static function getNameFromCode($code) {
		global $KUMVA_LOCALES;
		return isset($KUMVA_LOCALES[$code]) ? $KUMVA_LOCALES[$code] : 'Unknown';
	}

}

/**
 * Map of ISO 639-1 codes to language names
 */
$KUMVA_LOCALES = array(
 "aa" => "Afar",
 "ab" => "Abkhazian",
 "ae" => "Avestan",
 "af" => "Afrikaans",
 "ak" => "Akan",
 "am" => "Amharic",
 "an" => "Aragonese",
 "ar" => "Arabic",
 "as" => "Assamese",
 "av" => "Avaric",
 "ay" => "Aymara",
 "az" => "Azerbaijani",
 "ba" => "Bashkir",
 "be" => "Belarusian",
 "bg" => "Bulgarian",
 "bh" => "Bihari",
 "bi" => "Bislama",
 "bm" => "Bambara",
 "bn" => "Bengali",
 "bo" => "Tibetan",
 "br" => "Breton",
 "bs" => "Bosnian",
 "ca" => "Catalan",
 "ce" => "Chechen",
 "ch" => "Chamorro",
 "co" => "Corsican",
 "cr" => "Cree",
 "cs" => "Czech",
 "cu" => "Church Slavic",
 "cv" => "Chuvash",
 "cy" => "Welsh",
 "da" => "Danish",
 "de" => "German",
 "dv" => "Divehi",
 "dz" => "Dzongkha",
 "ee" => "Ewe",
 "el" => "Greek",
 "en" => "English",
 "eo" => "Esperanto",
 "es" => "Spanish",
 "et" => "Estonian",
 "eu" => "Basque",
 "fa" => "Persian",
 "ff" => "Fulah",
 "fi" => "Finnish",
 "fj" => "Fijian",
 "fo" => "Faroese",
 "fr" => "French",
 "fy" => "Western Frisian",
 "ga" => "Irish",
 "gd" => "Scottish Gaelic",
 "gl" => "Galician",
 "gn" => "Guarani",
 "gu" => "Gujarati",
 "gv" => "Manx",
 "ha" => "Hausa",
 "he" => "Hebrew",
 "hi" => "Hindi",
 "ho" => "Hiri Motu",
 "hr" => "Croatian",
 "ht" => "Haitian",
 "hu" => "Hungarian",
 "hy" => "Armenian",
 "hz" => "Herero",
 "ia" => "Interlingua (International Auxiliary Language Association)",
 "id" => "Indonesian",
 "ie" => "Interlingue",
 "ig" => "Igbo",
 "ii" => "Sichuan Yi",
 "ik" => "Inupiaq",
 "io" => "Ido",
 "is" => "Icelandic",
 "it" => "Italian",
 "iu" => "Inuktitut",
 "ja" => "Japanese",
 "jv" => "Javanese",
 "ka" => "Georgian",
 "kg" => "Kongo",
 "ki" => "Kikuyu",
 "kj" => "Kwanyama",
 "kk" => "Kazakh",
 "kl" => "Kalaallisut",
 "km" => "Khmer",
 "kn" => "Kannada",
 "ko" => "Korean",
 "kr" => "Kanuri",
 "ks" => "Kashmiri",
 "ku" => "Kurdish",
 "kv" => "Komi",
 "kw" => "Cornish",
 "ky" => "Kirghiz",
 "la" => "Latin",
 "lb" => "Luxembourgish",
 "lg" => "Luganda",
 "li" => "Limburgish",
 "ln" => "Lingala",
 "lo" => "Lao",
 "lt" => "Lithuanian",
 "lu" => "Luba-Katanga",
 "lv" => "Latvian",
 "mg" => "Malagasy",
 "mh" => "Marshallese",
 "mi" => "Maori",
 "mk" => "Macedonian",
 "ml" => "Malayalam",
 "mn" => "Mongolian",
 "mr" => "Marathi",
 "ms" => "Malay",
 "mt" => "Maltese",
 "my" => "Burmese",
 "na" => "Nauru",
 "nb" => "Norwegian Bokmal",
 "nd" => "North Ndebele",
 "ne" => "Nepali",
 "ng" => "Ndonga",
 "nl" => "Dutch",
 "nn" => "Norwegian Nynorsk",
 "no" => "Norwegian",
 "nr" => "South Ndebele",
 "nv" => "Navajo",
 "ny" => "Chichewa",
 "oc" => "Occitan",
 "oj" => "Ojibwa",
 "om" => "Oromo",
 "or" => "Oriya",
 "os" => "Ossetian",
 "pa" => "Panjabi",
 "pi" => "Pali",
 "pl" => "Polish",
 "ps" => "Pashto",
 "pt" => "Portuguese",
 "qu" => "Quechua",
 "rm" => "Raeto-Romance",
 "rn" => "Kirundi",
 "ro" => "Romanian",
 "ru" => "Russian",
 "rw" => "Kinyarwanda",
 "sa" => "Sanskrit",
 "sc" => "Sardinian",
 "sd" => "Sindhi",
 "se" => "Northern Sami",
 "sg" => "Sango",
 "si" => "Sinhala",
 "sk" => "Slovak",
 "sl" => "Slovenian",
 "sm" => "Samoan",
 "sn" => "Shona",
 "so" => "Somali",
 "sq" => "Albanian",
 "sr" => "Serbian",
 "ss" => "Swati",
 "st" => "Southern Sotho",
 "su" => "Sundanese",
 "sv" => "Swedish",
 "sw" => "Swahili",
 "ta" => "Tamil",
 "te" => "Telugu",
 "tg" => "Tajik",
 "th" => "Thai",
 "ti" => "Tigrinya",
 "tk" => "Turkmen",
 "tl" => "Tagalog",
 "tn" => "Tswana",
 "to" => "Tonga",
 "tr" => "Turkish",
 "ts" => "Tsonga",
 "tt" => "Tatar",
 "tw" => "Twi",
 "ty" => "Tahitian",
 "ug" => "Uighur",
 "uk" => "Ukrainian",
 "ur" => "Urdu",
 "uz" => "Uzbek",
 "ve" => "Venda",
 "vi" => "Vietnamese",
 "vo" => "Volapuk",
 "wa" => "Walloon",
 "wo" => "Wolof",
 "xh" => "Xhosa",
 "yi" => "Yiddish",
 "yo" => "Yoruba",
 "za" => "Zhuang",
 "zh" => "Chinese",
 "zu" => "Zulu"
);


?>
