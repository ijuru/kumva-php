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
 * Purpose: How-to-use reference page
 */
?> 
<p>This dictionary is designed to be simple to use. You can search for an English word or a Kinyarwanda word. As you type a word into the search box it will show a list of suggestions.</p>

<a name="results"></a>
<h3>Results</h3>
<p>Each entry in the dictionary looks something like this:</p>   
<p align="center">
	<img src="<?php echo KUMVA_URL_THEME; ?>/gfx/entry.png" />
</p>
<p>
	<ol>
		<li><b>Prefix</b>: the prefix part of the entry. For nouns this will be the singular form prefix (assuming there is one) and for verbs it will be the infinitive form prefix.</li>
		<li><b>Stem</b>: the stem part of the entry.</li>
		<li><b>Modifier</b>: for nouns this is the plural prefix, and for verbs this is the <?php ku_page('verbs-pasttensestems', 'past tense stem ending'); ?>.</li>
		<li><b>Pronunciation</b>: shows how the word should be pronounced using the <?php ku_page('pronunciation-tones', 'amasaku'); ?>.</li>
		<li><b>Audio button</b>: some words have an <?php ku_query('has:audio', 'audio recording'); ?> which can be played by clicking this.</li>
		<li><b>Word class</b>: the abbreviated form of the word class. The <?php ku_page('statistics'); ?> page gives a list of all the word classes used in the dictionary.</li>
		<li><b>Noun classes</b>: this is the <?php ku_page('nouns', 'class of the noun', 'classes'); ?> in it's singular and plural forms. For other word classes this may be the class of noun which it agrees with.</li>
		<li><b>Variant spellings</b>: other accepted spellings of this word.</li>
		<li><b>English meanings</b>: the English words with equivalent meaning</li>
		<li><b>Root word</b>: the word that this word is derived from.</li>
	</ol>
</p>

<a name="advanced"></a>
<h3>Advanced searching</h3>
<p>You can use * character as a wildcard to search for part of a word. For example: searching for <?php ku_query('ikinya*'); ?> will return all entries that begin with "ikinya", and searching for <?php ku_query('*gura'); ?> will return all entries that end with "gura"</p>

<p>You can also use parameters to refine your searches. For example <?php ku_query('wclass:n has:audio'); ?> will return only nouns that have audio. For a complete list of parameters see <a href="https://github.com/ijuru/kumva/wiki/Query-syntax">here</a>.</p>

