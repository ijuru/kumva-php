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
 * Purpose: Nouns reference page
 */
?>    
<p>A noun (<span lang="rw"><?php ku_query('izina'); ?></span>) is the name of a person, place or thing.</p>

<a name="components"></a>
<h3>Noun components</h3>
<p>Every noun in Kinyarwanda has three parts:</p>
<ol>
	<li>The <i>augment</i> (<span lang="rw"><?php ku_query('indomo'); ?></span>), i.e. <span lang="rw">u</span>, <span lang="rw">i</span> or <span lang="rw">a</span></li>
	<li>The <i>class marker</i> (<span lang="rw"><?php ku_query('indanganteko'); ?></span>), i.e. <span lang="rw">mu</span>, <span lang="rw">ba</span>, <span lang="rw">mi</span>, <span lang="rw">ri</span>, <span lang="rw">ma</span>, <span lang="rw">ki</span>, <span lang="rw">bi</span>, <span lang="rw">n</span>, <span lang="rw">ru</span>, <span lang="rw">ka</span>, <span lang="rw">tu</span>, <span lang="rw">bu</span>, <span lang="rw">ku</span>, <span lang="rw">ha</span></li>
	<li>The <i>root</i> (<span lang="rw"><?php ku_query('igicumbi'); ?></span>)</li>
</ol>

<p>Different words can share the same root, and will usually have related meanings. For example:</p>

<ul>
	<li><span lang="rw"><?php ku_query('umuntu'); ?></span> (<span lang="rw">u</span> + <span lang="rw">mu</span> + <span lang="rw">ntu</span>) - person</li>
	<li><span lang="rw"><?php ku_query('abantu'); ?></span> (<span lang="rw">a</span> + <span lang="rw">ba</span> + <span lang="rw">ntu</span>) - people</li>
	<li><span lang="rw"><?php ku_query('ikintu'); ?></span> (<span lang="rw">i</span> + <span lang="rw">ki</span> + <span lang="rw">ntu</span>) - thing</li>
	<li><span lang="rw"><?php ku_query('ibintu'); ?></span> (<span lang="rw">i</span> + <span lang="rw">bi</span> + <span lang="rw">ntu</span>) - things</li>
	<li><span lang="rw"><?php ku_query('ubuntu'); ?></span> (<span lang="rw">u</span> + <span lang="rw">bu</span> + <span lang="rw">ntu</span>) - grace</li>
	<li><span lang="rw"><?php ku_query('ukuntu'); ?></span> (<span lang="rw">u</span> + <span lang="rw">ku</span> + <span lang="rw">ntu</span>) - means</li>
	<li><span lang="rw"><?php ku_query('ahantu'); ?></span> (<span lang="rw">a</span> + <span lang="rw">ha</span> + <span lang="rw">ntu</span>) - place</li>
</ul>

<a name="classes"></a>
<h3>Noun classes</h3>
	
<p>Different noun classification systems have been used for Kinyarwanda, but this dictionary uses the standard <a href="http://en.wikipedia.org/wiki/Noun_class#Bantu_languages">Bantu classes</a> as taught in Rwandan schools. Nouns in each class usually have similarities in meaning, but there are exceptions in almost every class. For example, class 15 mostly contains actions and gerunds, but also contains <span lang="rw"><?php ku_query('ukwezi'); ?></span> (month) and <span lang="rw"><?php ku_query('ugutwi'); ?></span> (ear).</p>

<table class="grammar">
	<tr>
		<th>#</th>
		<th>Nouns</th>
		<th>Description</th>
		<th>Quantity</th>
		<th>Examples</th>
		<th>Adjectives</th>
		<th>Subject</th>
		<th>Object</th>
		<th>Of</th>
		<th>This</th>
	</tr>
	<tr>
		<td>1</td>
		<td><span lang="rw"><?php ku_query('umu*', 'umu-'); ?></span>, <span lang="rw"><?php ku_query('umw*', 'umw-'); ?></span></td>
		<td rowspan="2">people</td>
		<td>singular</td>
		<td><span lang="rw"><?php ku_query('umuntu'); ?></span>, <span lang="rw"><?php ku_query('umwarimu'); ?></span></td>
		<td>mu-</td>
		<td>n-, u-, a-</td>
		<td>-n-, -ku-, -mu-</td>
		<td><span lang="rw"><?php ku_query('wa wclass:prep', 'wa'); ?></span></td>
		<td><span lang="rw"><?php ku_query('uyu wclass:dem', 'uyu'); ?></span></td>
	</tr>
	<tr>
		<td>2</td>
		<td><span lang="rw"><?php ku_query('aba*', 'aba-'); ?></span>, <span lang="rw"><?php ku_query('ab*', 'ab-'); ?></span></td>
		<td>plural</td>
		<td><span lang="rw"><?php ku_query('abantu'); ?></span>, <span lang="rw"><?php ku_query('abarimu'); ?></span></td>
		<td>ba-</td>
		<td>tu-, mu-, ba-</td>
		<td>-tu-, -ba-, -ba-</td>
		<td><span lang="rw"><?php ku_query('ba wclass:prep', 'ba'); ?></span></td>
		<td><span lang="rw"><?php ku_query('aba wclass:dem', 'aba'); ?></span></td>
	</tr>
	<tr>
		<td>3</td>
		<td><span lang="rw"><?php ku_query('umu*', 'umu-'); ?></span>, <span lang="rw"><?php ku_query('umw*', 'umw-'); ?></span></td>
		<td rowspan="2">trees, shrubs and things that extend</td>
		<td>singular</td>
		<td><span lang="rw"><?php ku_query('umusozi'); ?></span>, <span lang="rw"><?php ku_query('umwotsi'); ?></span></td>
		<td>mu-</td>
		<td>u-</td>
		<td>-wu-</td>
		<td><span lang="rw"><?php ku_query('wa wclass:prep', 'wa'); ?></span></td>
		<td><span lang="rw"><?php ku_query('uyu wclass:dem', 'uyu'); ?></span></td>
	</tr>
	<tr>
		<td>4</td>
		<td><span lang="rw"><?php ku_query('imi*', 'imi-'); ?></span>, <span lang="rw"><?php ku_query('imy*', 'imy-'); ?></span></td>
		<td>plural</td>
		<td><span lang="rw"><?php ku_query('imisozi'); ?></span>, <span lang="rw"><?php ku_query('imyotsi'); ?></span></td>
		<td>mi-</td>
		<td>i-</td>
		<td>-yi-</td>
		<td><span lang="rw"><?php ku_query('ya wclass:prep', 'ya'); ?></span></td>
		<td><span lang="rw"><?php ku_query('iyi wclass:dem', 'iyi'); ?></span></td>
	</tr>
	<tr>
		<td>5</td>
		<td><span lang="rw"><?php ku_query('i*', 'i-'); ?></span>, <span lang="rw"><?php ku_query('iri*', 'iri-'); ?></span></td>
		<td rowspan="2">things in quantities, body parts and liquids</td>
		<td>singular</td>
		<td><span lang="rw"><?php ku_query('ifaranga'); ?></span>, <span lang="rw"><?php ku_query('iryinyo'); ?></span></td>
		<td>ri-</td>
		<td>ri-</td>
		<td>-ri-</td>
		<td><span lang="rw"><?php ku_query('rya wclass:prep', 'rya'); ?></span></td>
		<td><span lang="rw"><?php ku_query('iri wclass:dem', 'iri'); ?></span></td>
	</tr>
	<tr>
		<td>6</td>
		<td><span lang="rw"><?php ku_query('ama*', 'ama-'); ?></span>, <span lang="rw"><?php ku_query('am*', 'am-'); ?></span></td>
		<td>plural</td>
		<td><span lang="rw"><?php ku_query('amafaranga'); ?></span>, <span lang="rw"><?php ku_query('amenyo'); ?></span></td>
		<td>ma-</td>
		<td>a-</td>
		<td>-ya-</td>
		<td><span lang="rw"><?php ku_query('ya wclass:prep', 'ya'); ?></span></td>
		<td><span lang="rw"><?php ku_query('aya wclass:dem', 'aya'); ?></span></td>
	</tr>
	<tr>
		<td>7</td>
		<td><span lang="rw"><?php ku_query('iki*', 'iki-'); ?></span>, <span lang="rw"><?php ku_query('icy*', 'icy-'); ?></span>, <span lang="rw"><?php ku_query('igi*', 'igi-'); ?></span></td>
		<td rowspan="2">generic, large, or abnormal things</td>
		<td>singular</td>
		<td><span lang="rw"><?php ku_query('ikintu'); ?></span>, <span lang="rw"><?php ku_query('icyaha'); ?></span>, <span lang="rw"><?php ku_query('igitabo'); ?></span></td>
		<td>ki-</td>
		<td>ki-</td>
		<td>-ki-</td>
		<td><span lang="rw"><?php ku_query('cya wclass:prep', 'cya'); ?></span></td>
		<td><span lang="rw"><?php ku_query('iki wclass:dem', 'iki'); ?></span></td>
	</tr>
	<tr>
		<td>8</td>
		<td><span lang="rw"><?php ku_query('ibi*', 'ibi-'); ?></span>, <span lang="rw"><?php ku_query('iby*', 'ibi-'); ?></span></td>
		<td>plural</td>
		<td><span lang="rw"><?php ku_query('ibintu'); ?></span>, <span lang="rw"><?php ku_query('ibyaha'); ?></span>, <span lang="rw"><?php ku_query('ibitabo'); ?></span></td>
		<td>bi-</td>
		<td>bi-</td>
		<td>-bi-</td>
		<td><span lang="rw"><?php ku_query('bya wclass:prep', 'bya'); ?></span></td>
		<td><span lang="rw"><?php ku_query('ibi wclass:dem', 'ibi'); ?></span></td>
	</tr>
	<tr>
		<td>9</td>
		<td rowspan="2"><span lang="rw"><?php ku_query('i*', 'i-'); ?></span>, <span lang="rw"><?php ku_query('in*', 'in-'); ?></span>, <span lang="rw"><?php ku_query('inz*', 'inz-'); ?></span></td>
		<td rowspan="2">some plants, animals and household implements</td>
		<td>singular</td>
		<td rowspan="2"><span lang="rw"><?php ku_query('ifi'); ?></span>, <span lang="rw"><?php ku_query('inka'); ?></span>, <span lang="rw"><?php ku_query('inzoga'); ?></span></td>
		<td>n-</td>
		<td>i-</td>
		<td>-yi-</td>
		<td><span lang="rw"><?php ku_query('ya wclass:prep', 'ya'); ?></span></td>
		<td><span lang="rw"><?php ku_query('iyi wclass:dem', 'iyi'); ?></span></td>
	</tr>
	<tr>
		<td>10</td>
		<td>plural</td>
		<td>n-</td>
		<td>zi-</td>
		<td>-zi-</td>
		<td><span lang="rw"><?php ku_query('za wclass:prep', 'za'); ?></span></td>
		<td><span lang="rw"><?php ku_query('izi wclass:dem', 'izi'); ?></span></td>
	</tr>
	<tr>
		<td>11</td>
		<td><span lang="rw"><?php ku_query('uru*', 'uru-'); ?></span>, <span lang="rw"><?php ku_query('urw*', 'urw-'); ?></span></td>
		<td>mixture</td>
		<td>singular</td>
		<td><span lang="rw"><?php ku_query('urugi'); ?></span>, <span lang="rw"><?php ku_query('urwego'); ?></span></td>
		<td>ru-</td>
		<td>ru-</td>
		<td>-ru-</td>
		<td><span lang="rw"><?php ku_query('rwa wclass:prep', 'rwa'); ?></span></td>
		<td><span lang="rw"><?php ku_query('uru wclass:dem', 'uru'); ?></span></td>
	</tr>
	<tr>
		<td>12</td>
		<td><span lang="rw"><?php ku_query('aka*', 'aka-'); ?></span>, <span lang="rw"><?php ku_query('ak*', 'ak-'); ?></span>, <span lang="rw"><?php ku_query('aga*', 'aga-'); ?></span></td>
		<td rowspan="2">diminutive forms of other nouns</td>
		<td>singular</td>
		<td><span lang="rw"><?php ku_query('akantu'); ?></span>, <span lang="rw"><?php ku_query('akitso'); ?></span>, <span lang="rw"><?php ku_query('agacupa'); ?></span></td>
		<td>ka-</td>
		<td>ka-</td>
		<td>-ka-</td>
		<td><span lang="rw"><?php ku_query('ka wclass:prep', 'ka'); ?></span></td>
		<td><span lang="rw"><?php ku_query('aka wclass:dem', 'aka'); ?></span></td>
	</tr>
	<tr>
		<td>13</td>
		<td><span lang="rw"><?php ku_query('utu*', 'utu-'); ?></span>, <span lang="rw"><?php ku_query('utw*', 'utw-'); ?></span>, <span lang="rw"><?php ku_query('udu*', 'udu-'); ?></span></td>
		<td>plural</td>
		<td><span lang="rw"><?php ku_query('utuntu'); ?></span>, <span lang="rw"><?php ku_query('utwitso'); ?></span>, <span lang="rw"><?php ku_query('uducupa'); ?></span></td>
		<td>tu-</td>
		<td>tu-</td>
		<td>-tu-</td>
		<td><span lang="rw"><?php ku_query('twa wclass:prep', 'twa'); ?></span></td>
		<td><span lang="rw"><?php ku_query('utu wclass:dem', 'utu'); ?></span></td>
	</tr>
	<tr>
		<td>14</td>
		<td><span lang="rw"><?php ku_query('ubu*', 'ubu-'); ?></span>, <span lang="rw"><?php ku_query('ubw*', 'ubw-'); ?></span></td>
		<td>abstract nouns, qualities or states</td>
		<td>n/a</td>
		<td><span lang="rw"><?php ku_query('ubuntu'); ?></span>, <span lang="rw"><?php ku_query('ubwana'); ?></span></td>
		<td>bu-</td>
		<td>bu-</td>
		<td>-bu-</td>
		<td><span lang="rw"><?php ku_query('bwa wclass:prep', 'bwa'); ?></span></td>
		<td><span lang="rw"><?php ku_query('ubu wclass:dem', 'ubu'); ?></span></td>
	</tr>
	<tr>
		<td>15</td>
		<td><span lang="rw"><?php ku_query('uku*', 'uku-'); ?></span>, <span lang="rw"><?php ku_query('ukw*', 'ukw-'); ?></span>, <span lang="rw"><?php ku_query('ugu*', 'ugu-'); ?></span></td>
		<td>actions, verbal nouns and gerunds</td>
		<td>n/a</td>
		<td><span lang="rw"><?php ku_query('ukuntu'); ?></span>, <span lang="rw"><?php ku_query('ukwezwa'); ?></span>, <span lang="rw"><?php ku_query('ugukiranuka'); ?></span></td>
		<td>ku-</td>
		<td>ku-</td>
		<td>-ku-</td>
		<td><span lang="rw"><?php ku_query('kwa wclass:prep', 'kwa'); ?></span></td>
		<td><span lang="rw"><?php ku_query('uku wclass:dem', 'uku'); ?></span></td>
	</tr>
	<tr>
		<td>16</td>
		<td><span lang="rw"><?php ku_query('aha*', 'aha-'); ?></span>, <span lang="rw"><?php ku_query('ah*', 'ah-'); ?></span></td>
		<td>places</td>
		<td>n/a</td>
		<td><span lang="rw"><?php ku_query('ahantu'); ?></span>, <span lang="rw"><?php ku_query('ahirengeye'); ?></span></td>
		<td>ha-</td>
		<td>ha-</td>
		<td>-ha-</td>
		<td><span lang="rw"><?php ku_query('ha wclass:prep', 'ha'); ?></span></td>
		<td><span lang="rw"><?php ku_query('aha wclass:dem', 'aha'); ?></span></td>
	</tr>
</table>

<h3>Articles</h3>
<p>Kinyarwanda does not have definite (the) and indefinite (a, an) articles like English. For example <span lang="rw">umuntu</span> can mean 'a person' or
'the person'. It does however have <?php ku_page('demonstratives'); ?> which are equivalent to 'this' and 'that' in English.</p>

<a name="propernouns"></a>
<h3>Proper nouns</h3>
<p>When nouns become proper nouns (names of people or places) they usually lose their augment (e.g. <span lang="rw"><?php ku_query('umugabo'); ?></span> 
becomes <span lang="rw">Mugabo</span> when used a name). Similarly when using a noun to address someone, the augment is dropped 
(e.g. <span lang="rw"><?php ku_query('umuzungu'); ?></span> becomes <span lang="rw">muzungu</span> when shouting it at the nearest white person).</p>

<p>There are several different types of geographical place names:</p>
<ul>
	<li>Pure Kinyarwanda country names such as <span lang="rw"><?php ku_query('Uburundi'); ?></span> and <span lang="rw"><?php ku_query('Ubufaransa'); ?></span> which are used as class 14 nouns, e.g. <span lang="rw">Ubufaransa burakonje</span> - France is cold</li>
	<li>Pure Kinyarwanda place names such as <span lang="rw"><?php ku_query('Kigali'); ?></span> and <span lang="rw"><?php ku_query('Butare'); ?></span> which may appear to belong to particular noun classes but are generally used as class 9 nouns, e.g. <span lang="rw">Kigali ni nini</span> - Kigali is big</li>
	<li>Imported place names such as <span lang="rw"><?php ku_query('Esipanye'); ?></span> and <span lang="rw"><?php ku_query('Amerika'); ?></span> which are used as class 9 nouns, e.g. <span lang="rw">Esipanye irashyushye</span> - Spain is hot</li>
	<li><span lang="rw"><?php ku_query('Rwanda'); ?></span> which is used as a class 11 noun, e.g. <span lang="rw">u Rwanda ni rwiza</span> - Rwanda is beautiful</li>
</ul>



