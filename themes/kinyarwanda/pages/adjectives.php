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
 * Purpose: Adjectives reference page
 */
?>    
<p>An adjective provides more information about a noun.</p>

<h3>True adjectives</h3><a name="true"></a>
<p>Kinyarwanda has only a few <?php ku_query('wclass:adj', 'true adjectives'); ?>. These must always agree with the noun they modify. For example, we say <span lang="rw">umugabo munini</span> 'big man', but <span lang="rw">igitabo kinini</span> 'big book'. The prefix for adjectives is the same as the normal noun prefix for each class, minus the initial vowel.</p>

<table class="grammar">
	<tr>
		<th>#</th>
		<th>Nouns</th>
		<th>Adjectives</th>
		<th>Example (<span lang="rw"><?php ku_query('-nini', '-nini'); ?></span>)</th>
		<th>Example (<span lang="rw"><?php ku_query('-iza', '-iza'); ?></span>)</th>
	</tr>
	<tr>
		<td>1</td>
		<td><span lang="rw"><?php ku_query('umu*', 'umu-'); ?></span>, <span lang="rw"><?php ku_query('umw*', 'umw-'); ?></span></td>
		<td><span lang="rw">mu-</span></td>
		<td><span lang="rw">umugabo munini</span> - big man</td>
		<td><span lang="rw">umukobwa mwiza</span> - nice girl</td>
	</tr>
	<tr>
		<td>2</td>
		<td><span lang="rw"><?php ku_query('aba*', 'aba-'); ?></span>, <span lang="rw"><?php ku_query('ab*', 'ab-'); ?></span></td>
		<td><span lang="rw">ba-</span></td>
		<td><span lang="rw">abagore banini</span> - big women</td>
		<td><span lang="rw">abana beza</span> - good children</td>
	</tr>
	<tr>
		<td>3</td>
		<td><span lang="rw"><?php ku_query('umu*', 'umu-'); ?></span>, <span lang="rw"><?php ku_query('umw*', 'umw-'); ?></span></td>
		<td><span lang="rw">mu-</span></td>
		<td><span lang="rw">umusozi munini</span> - big mountain</td>
		<td><span lang="rw">umuti mwiza</span> - good medicine</td>
	</tr>
	<tr>
		<td>4</td>
		<td><span lang="rw"><?php ku_query('imi*', 'imi-'); ?></span>, <span lang="rw"><?php ku_query('imy*', 'imy-'); ?></span></td>
		<td><span lang="rw">mi-</span></td>
		<td><span lang="rw">imibu minini</span> - big mosquitos</td>
		<td><span lang="rw">imihanda myiza</span> - good roads</td>
	</tr>
	<tr>
		<td>5</td>
		<td><span lang="rw"><?php ku_query('i*', 'i-'); ?></span>, <span lang="rw"><?php ku_query('iri*', 'iri-'); ?></span></td>
		<td><span lang="rw">ri-</span></td>
		<td><span lang="rw">ijambo rinini</span> - big word</td>
		<td><span lang="rw">ijoro ryiza</span> - good night</td>
	</tr>
	<tr>
		<td>6</td>
		<td><span lang="rw"><?php ku_query('ama*', 'ama-'); ?></span>, <span lang="rw"><?php ku_query('am*', 'am-'); ?></span></td>
		<td><span lang="rw">ma-</span></td>
		<td><span lang="rw">amarira manini</span> - big tears</td>
		<td><span lang="rw">amata meza</span> - good milk</td>
	</tr>
	<tr>
		<td>7</td>
		<td><span lang="rw"><?php ku_query('iki*', 'iki-'); ?></span>, <span lang="rw"><?php ku_query('icy*', 'icy-'); ?></span>, <span lang="rw"><?php ku_query('igi*', 'igi-'); ?></span></td>
		<td><span lang="rw">ki-</span></td>
		<td><span lang="rw">ikintu kinini</span> - big thing</td>
		<td><span lang="rw">igitabo cyiza</span> - good book</td>
	</tr>
	<tr>
		<td>8</td>
		<td><span lang="rw"><?php ku_query('ibi*', 'ibi-'); ?></span>, <span lang="rw"><?php ku_query('iby*', 'ibi-'); ?></span></td>
		<td><span lang="rw">bi-</span></td>
		<td><span lang="rw">ibintu binini</span> - big things</td>
		<td><span lang="rw">ibitabo byiza</span> - good books</td>
	</tr>
	<tr>
		<td>9</td>
		<td rowspan="2"><span lang="rw"><?php ku_query('i*', 'i-'); ?></span>, <span lang="rw"><?php ku_query('in*', 'in-'); ?></span>, <span lang="rw"><?php ku_query('inz*', 'inz-'); ?></span></td>
		<td><span lang="rw">n-</span></td>
		<td rowspan="2"><span lang="rw">inka nini</span> - big cow(s)</td>
		<td rowspan="2"><span lang="rw">ihene nziza</span> - nice goat(s)</td>
	</tr>
	<tr>
		<td>10</td>
		<td><span lang="rw">n-</span></td>
	</tr>
	<tr>
		<td>11</td>
		<td><span lang="rw"><?php ku_query('uru*', 'uru-'); ?></span>, <span lang="rw"><?php ku_query('urw*', 'urw-'); ?></span></td>
		<td><span lang="rw">ru-</span></td>
		<td><span lang="rw">urwego runini</span> - big ladder</td>
		<td><span lang="rw">urugendo rwiza</span> - good journey</td>
	</tr>
	<tr>
		<td>12</td>
		<td><span lang="rw"><?php ku_query('aka*', 'aka-'); ?></span>, <span lang="rw"><?php ku_query('ak*', 'ak-'); ?></span>, <span lang="rw"><?php ku_query('aga*', 'aga-'); ?></span></td>
		<td><span lang="rw">ka-</span></td>
		<td><span lang="rw">akanwa</span> - big mouth</td>
		<td><span lang="rw">akazi keza</span> - good work</td>
	</tr>
	<tr>
		<td>13</td>
		<td><span lang="rw"><?php ku_query('utu*', 'utu-'); ?></span>, <span lang="rw"><?php ku_query('utw*', 'utw-'); ?></span>, <span lang="rw"><?php ku_query('udu*', 'udu-'); ?></span></td>
		<td><span lang="rw">tu-</span></td>
		<td><span lang="rw">utugari tunini</span> - big cells</td>
		<td><span lang="rw">utuyoga twiza</span> - good banana beers</td>
	</tr>
	<tr>
		<td>14</td>
		<td><span lang="rw"><?php ku_query('ubu*', 'ubu-'); ?></span>, <span lang="rw"><?php ku_query('ubw*', 'ubw-'); ?></span></td>
		<td><span lang="rw">bu-</span></td>
		<td><span lang="rw">ubwato bunini</span> - big boat</td>
		<td><span lang="rw">ubwana bwiza</span> - good childhood</td>
	</tr>
	<tr>
		<td>15</td>
		<td><span lang="rw"><?php ku_query('uku*', 'uku-'); ?></span>, <span lang="rw"><?php ku_query('ukw*', 'ukw-'); ?></span>, <span lang="rw"><?php ku_query('ugu*', 'ugu-'); ?></span></td>
		<td><span lang="rw">ku-</span></td>
		<td><span lang="rw">ugutwi kunini</span> - big ear</td>
		<td><span lang="rw">ukwezi kwiza</span> - good month</td>
	</tr>
	<tr>
		<td>16</td>
		<td><span lang="rw"><?php ku_query('aha*', 'aha-'); ?></span>, <span lang="rw"><?php ku_query('ah*', 'ah-'); ?></span></td>
		<td><span lang="rw">ha-</span></td>
		<td><span lang="rw">ahantu hanini</span> - big place</td>
		<td><span lang="rw">ahantu heza</span> - nice place</td>
	</tr>
</table>

<a name="possessive"></a>
<h3>Possessive adjectives</h3>
<p>These are used to show ownership of a noun like 'my' or 'your' in English (see <?php ku_page('possessives'); ?> for a full explanation). For example:</p>
<ul>
	<li><span lang="rw">umugore wawe</span> - your wife</li>
	<li><span lang="rw">inzu ye</span> - his house</li>
	<li><span lang="rw">ibintu byabo</span> - their things</li>
</ul>

<a name="denominal"></a>
<h3>Denominal adjectives</h3>
<p>These are adjectives which are formed from other nouns using the <?php ku_page('possessives#participles', 'possessive participle'); ?>. For example:</p>
<ul>
	<li><span lang="rw">umujyi w'amateka</span> - an historical city, i.e. a city of (<span lang="rw"><?php ku_query('wa wclass:prep', 'wa'); ?></span>) history (<span lang="rw"><?php ku_query('amateka'); ?></span>)</li>
	<li><span lang="rw">inshuti y'ukuri</span> - a true friend, i.e. a friend of (<span lang="rw"><?php ku_query('wa wclass:prep', 'ya'); ?></span>) truth (<span lang="rw"><?php ku_query('ukuri'); ?></span>)</li>
	<li><span lang="rw">ikizamini cy'ubuvuzi</span> - a medical examination, i.e. an examination of (<span lang="rw"><?php ku_query('cya wclass:prep', 'cya'); ?></span>) medicine (<span lang="rw"><?php ku_query('ubuvuzi'); ?></span>)</li>
</ul>

<a name="verbal"></a>
<h3>Verbal adjectives</h3>
<p>Some adjectives in English only exist as verbs in Kinyarwanda. For example:</p>
<ul>

	<li><span lang="rw">ifanta ikonje</span> - cold Fanta (using the verb <span lang="rw"><?php ku_query('gukonja'); ?></span>)</li>
	<li><span lang="rw">amazi ashyushye</span> - hot water (using the verb <span lang="rw"><?php ku_query('gushyuha'); ?></span>)</li>
	<li><span lang="rw">umugabo ushaje</span> - old man (using the verb <span lang="rw"><?php ku_query('gusaza'); ?></span>)</li>
</ul>

<a name="nominal"></a>
<h3>Nominal adjectives</h3>
<p>These are nouns which are formed from adjectives. There is an implied noun, like in English we can say 'the old' which implies 'the old people'. In Kinyarwanda such a noun is formed by retaining the initial vowel of the prefix. For example:</p>
<ul>
	<li><span lang="rw">umunini</span> - the big (person)</li>
	<li><span lang="rw">abeza</span> - the good (people)</li>
	<li><span lang="rw">icyiza</span> - the good (thing)</li>
</ul>

<a name="order"></a>
<h3>Adjective order</h3>
<p>Adjectives always follow the noun and usually occur in the following order: <i>possessive</i> &rarr; <i>descriptive</i> &rarr; <i>quantitative</i>. For example:</p>
<ul>
    <li><span lang="rw">abana babo beza benshi</span> - their many good children</li>
    <li><span lang="rw">inka ze mbi nke</span> - his few bad cows</li>
</ul>
