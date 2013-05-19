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
 * Purpose: Kinyarwanda theme functions
 */

Theme::createPage('reference', KU_STR_REFERENCE, 'reference.php', NULL);

Theme::createPage('howtouse', 'Using the dictionary', 'howtouse.php', 'reference');

Theme::createPage('pronunciation', 'Pronunciation', 'pronunciation.php', 'reference');
Theme::createPage('pronunciation-letters', 'Letters', 'pronunciation_letters.php', 'pronunciation');
Theme::createPage('pronunciation-tones', 'Tones', 'pronunciation_tones.php', 'pronunciation');
Theme::createPage('pronunciation-sentences', 'Sentences', 'pronunciation_sentences.php', 'pronunciation');

Theme::createPage('spelling', 'Spelling', 'spelling.php', 'reference');
Theme::createPage('nouns', 'Nouns', 'nouns.php', 'reference');
Theme::createPage('adjectives', 'Adjectives', 'adjectives.php', 'reference');
Theme::createPage('possessives', 'Possessives', 'possessives.php', 'reference');

Theme::createPage('verbs', 'Verbs', 'verbs.php', 'reference');
Theme::createPage('verbs-dynamicstative', 'Dynamic vs stative verbs', 'verbs_dynamicstative.php', 'verbs');
Theme::createPage('verbs-pasttensestems', 'Past tense stems', 'verbs_pasttensestems.php', 'verbs');

Theme::createPage('adverbs', 'Adverbs', 'adverbs.php', 'reference');
Theme::createPage('demonstratives', 'Demonstratives', 'demonstratives.php', 'reference');

Theme::createPage('explore', 'Explore', 'explore.php', NULL);
Theme::createPage('statistics', KU_STR_STATISTICS, 'statistics.php', NULL);
Theme::createPage('faq', KU_STR_FAQ, 'faq.php', NULL);
Theme::createPage('about', KU_STR_ABOUT, 'about.php', NULL);
Theme::createPage('feedback', KU_STR_FEEDBACK, 'feedback.php', NULL);
Theme::createPage('sms-service', KU_STR_SMSSERVICE, 'sms_service.php', NULL);
Theme::createPage('support', 'Support', 'support.php', NULL);

$nounClasses = array(
	1 => '1 (umu-)',
	2 => '2 (aba-)',
	3 => '3 (umu-)',
	4 => '4 (imi-)',
	5 => '5 (i,iri-)',
	6 => '6 (ama-)',
	7 => '7 (iki-)',
	8 => '8 (ibi-)',
	9 => '9 (i,in-)',
	10 => '10 (i,in-)',
	11 => '11 (uru-)',
	12 => '12 (aka-)',
	13 => '13 (utu-)',
	14 => '14 (ubu-)',
	15 => '15 (uku-)',
	16 => '16 (aha-)'
);

// Reference page names for word classes
$wordClassPages = array(
	'adj' => 'adjectives',
	'adv' => 'adverbs',
	'dem' => 'demonstratives',
	'n' => 'nouns',
	'pn' => 'nouns#propernouns',
	'v' => 'verbs'
);
 
/**
 * Called by Theme::getPageForWordClass()
 */
function kumva_theme_getpageforwordclass($cls) {
	global $wordClassPages;
	return isset($wordClassPages[$cls]) ? $wordClassPages[$cls] : NULL;
}

/**
 * Called by Theme::getNounClassName()
 */
function kumva_theme_getnounclassname($cls) {
	global $nounClasses;
	return isset($nounClasses[$cls]) ? $nounClasses[$cls] : 'unknown';
}

?>
