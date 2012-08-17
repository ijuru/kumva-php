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
 * Purpose: Verbs reference page
 */
?>
<p>A verb (<span lang="rw"><?php ku_query('inshinga'); ?></span>) is an action or a state of being. Unlike English verbs, Kinyarwanda verbs can be conjugated to include the verb's subject, and optionally the verb's object and indirect object. This can make it hard for beginners to recognize different verbs, until they first learn to recognize the prefixes and infixes used to conjugate verbs with nouns. For example, consider the following three sentences:</p>

<ul>
	<li><span lang="rw">nkunda umupira</span> - I like football</li>
	<li><span lang="rw">ukunda umupira</span> - you like football</li>
	<li><span lang="rw">akunda umupira</span> - he likes football</li>
</ul>

<p>We can see that <span lang="rw">-kunda</span> is common to each of the conjugations so we call this the verb <em>stem</em>, and the prefixes <span lang="rw">n</span>, <span lang="rw">u</span> and <span lang="rw">a</span> denote the subject of the verb. In this dictionary, verbs are always given in their infinitive form (if they have one), which is formed by adding a <span lang="rw">ku</span> prefix. However because of Kinyarwanda's <?php ku_page('spelling'); ?> rules, this may take several forms, e.g.</p>

<ul>
	<li><span lang="rw"><?php ku_query('kuvuga'); ?></span> - to speak</li>
	<li><span lang="rw"><?php ku_query('gukora'); ?></span> - to do</li>
	<li><span lang="rw"><?php ku_query('kwiga'); ?></span> - to study</li>
</ul>

<p>In addition to multiple prefixes (and suffixes as shown later) almost every verb has a <?php ku_page('pasttensestems', 'past tense stem'); ?>. For example, consider the following conjugations of the verb <span lang="rw"><?php ku_query('gukora'); ?></span>:</p>

<ul>
	<li><span lang="rw">akora</span> - he does</li>
	<li><span lang="rw">arakora</span> - he is doing</li>
	<li><span lang="rw">yakoze</span> - he did (earlier today)</li>
	<li><span lang="rw">yarakoze</span> - he did (before today)</li>
</ul>

<p>We call <span lang="rw">-kora</span> the present tense stem, and <span lang="rw">-koze</span> the past tense stem.</p>

<h3>Tenses</h3>
<p>Kinyarwanda's different tenses are formed through different combinations of prefixes, suffixes and stems. For example, for the verb <span lang="rw"><?php ku_query('gukora'); ?></span>:</p>

<table class="grammar">
	<tr>
		<th rowspan="2">Person</th>
		<th colspan="4">Past</th>
		<th colspan="2">Present</th>
		<th rowspan="2">Future</th>
	</tr>
	<tr>
		<th>Habitual</th>
		<th>Far</th>
		<th>Near</th>
		<th>Immediate</th>
		<th>Regular</th>
		<th>Habitual</th>
	</tr>
	<tr>
		<td>I</td>
		<td><span lang="rw">n<b>a</b>kora<b>ga</b></span> - I was doing</td>
		<td><span lang="rw">n<b>ara</b>koze</span> - I did</td>
		<td><span lang="rw">n<b>a</b>koze</span> - I did (today)</td>
		<td><span lang="rw">n<b>da</b>koze</span> - I just did</td>
		<td><span lang="rw">n<b>da</b>kora</span> - I am doing</td>
		<td><span lang="rw">nkora</span> - I do</td>
		<td><span lang="rw">n<b>za</b>kora</span> - I will do</td>
	</tr>
	<tr>
		<td>you (sg.)</td>
		<td><span lang="rw">w<b>a</b>kora<b>ga</b></span> - I was doing</td>
		<td><span lang="rw">w<b>ara</b>koze</span> - I did</td>
		<td><span lang="rw">w<b>a</b>koze</span> - I did (today)</td>
		<td><span lang="rw">u<b>ra</b>koze</span> - I just did</td>
		<td><span lang="rw">u<b>ra</b>kora</span> - I am doing</td>
		<td><span lang="rw">ukora</span> - I do</td>
		<td><span lang="rw">u<b>za</b>kora</span> - I will do</td>
	</tr>
	<tr>
		<td>he/she</td>
		<td><span lang="rw">y<b>a</b>kora<b>ga</b></span> - I was doing</td>
		<td><span lang="rw">y<b>ara</b>koze</span> - I did</td>
		<td><span lang="rw">y<b>a</b>koze</span> - I did (today)</td>
		<td><span lang="rw">a<b>ra</b>koze</span> - I just did</td>
		<td><span lang="rw">a<b>ra</b>kora</span> - I am doing</td>
		<td><span lang="rw">akora</span> - I do</td>
		<td><span lang="rw">a<b>za</b>kora</span> - I will do</td>
	</tr>
	<tr>
		<td>we</td>
		<td><span lang="rw">tw<b>a</b>kora<b>ga</b></span> - I was doing</td>
		<td><span lang="rw">tw<b>ara</b>koze</span> - I did</td>
		<td><span lang="rw">tw<b>a</b>koze</span> - I did (today)</td>
		<td><span lang="rw">tu<b>ra</b>koze</span> - I just did</td>
		<td><span lang="rw">tu<b>ra</b>kora</span> - I am doing</td>
		<td><span lang="rw">dukora</span> - I do</td>
		<td><span lang="rw">tu<b>za</b>kora</span> - I will do</td>
	</tr>
	<tr>
		<td>you (pl.)</td>
		<td><span lang="rw">mw<b>a</b>kora<b>ga</b></span> - I was doing</td>
		<td><span lang="rw">mw<b>ara</b>koze</span> - I did</td>
		<td><span lang="rw">mw<b>a</b>koze</span> - I did (today)</td>
		<td><span lang="rw">mu<b>ra</b>koze</span> - I just did</td>
		<td><span lang="rw">mu<b>ra</b>kora</span> - I am doing</td>
		<td><span lang="rw">mukora</span> - I do</td>
		<td><span lang="rw">mu<b>za</b>kora</span> - I will do</td>
	</tr>
	<tr>
		<td>they (pl.)</td>
		<td><span lang="rw">b<b>a</b>kora<b>ga</b></span> - I was doing</td>
		<td><span lang="rw">b<b>ara</b>koze</span> - I did</td>
		<td><span lang="rw">b<b>a</b>koze</span> - I did (today)</td>
		<td><span lang="rw">ba<b>ra</b>koze</span> - I just did</td>
		<td><span lang="rw">ba<b>ra</b>kora</span> - I am doing</td>
		<td><span lang="rw">bakora</span> - I do</td>
		<td><span lang="rw">ba<b>za</b>kora</span> - I will do</td>
	</tr>
</table>

<h3>Making sentences</h3>

<p>We still need to use the appropriate prefixes even if the subject is given separately, so that the verb agrees with the noun:</p>

<ul>
	<li><span lang="rw">akunda umupira</span> - he likes football</li>
	<li><span lang="rw">Jean akunda umupira</span> - Jean (he) likes football</li>
	<li><span lang="rw">bakunda umupira</span> - they like football</li>
	<li><span lang="rw">Abantu bakunda umupira</span> - people (they) like football</li>
</ul>

<p>Like in English, verbs usually follow the order <em>subject</em> &rarr; <em>verb</em> &rarr; <em>object</em> (e.g. <span lang="rw">Jean akunda umupira</span> - John likes football)</p>

