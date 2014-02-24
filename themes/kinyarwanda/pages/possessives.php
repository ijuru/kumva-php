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
<p>Possessives modify a noun to describe its ownership by, or relationship to, another noun.</p>

<a name="participles"></a>	
<h3>Possessive participles</h3>

<p>Each <?php ku_page('nouns', 'noun class', 'classes'); ?> has a participle which is used to described an 'owned by' or 'of' relationship to another noun. The participle is formed by <?php ku_page('nouns', 'noun prefix', 'components'); ?> + <span lang="rw">a</span> (and dropping any initial <span lang="rw">m</span>). The following table shows participles for each noun class:</p>

<table class="grammar">
	<tr>
		<th>#</th>
		<th>Nouns</th>
		<th>Participle</th>
		<th>Example</th>
		<th>Example (before vowel)</th>
	</tr>
	<tr>
		<td>1</td>
		<td><span lang="rw"><?php ku_query('umu*', 'umu-'); ?></span>, <span lang="rw"><?php ku_query('umw*', 'umw-'); ?></span></td>
		<td><span lang="rw">wa</span></td>
		<td><span lang="rw">umwana wa Teta</span> - Teta's child</td>
		<td><span lang="rw">umwana w'umugore</span> - the woman's child</td>
	</tr>
	<tr>
		<td>2</td>
		<td><span lang="rw"><?php ku_query('aba*', 'aba-'); ?></span>, <span lang="rw"><?php ku_query('ab*', 'ab-'); ?></span></td>
		<td><span lang="rw">ba</span></td>
		<td><span lang="rw">abana ba Jean</span> - Jean's children</td>
		<td><span lang="rw">abana b'Imana</span> - the children of God</td>
	</tr>
	<tr>
		<td>3</td>
		<td><span lang="rw"><?php ku_query('umu*', 'umu-'); ?></span>, <span lang="rw"><?php ku_query('umw*', 'umw-'); ?></span></td>
		<td><span lang="rw">wa</span></td>
		<td><span lang="rw">umusozi wa Kigali</span> - Kigali's hill</td>
		<td><span lang="rw">umusozi w'igihugu</span> - the hill of the country</td>
	</tr>
	<tr>
		<td>4</td>
		<td><span lang="rw"><?php ku_query('imi*', 'imi-'); ?></span>, <span lang="rw"><?php ku_query('imy*', 'imy-'); ?></span></td>
		<td><span lang="rw">ya</span></td>
		<td><span lang="rw">imisozi ya Tanzaniya</span> - Tanzania's hills</td>
		<td><span lang="rw">imisozi y'u Rwanda</span> - the hills of Rwanda</td>
	</tr>
	<tr>
		<td>5</td>
		<td><span lang="rw"><?php ku_query('i*', 'i-'); ?></span>, <span lang="rw"><?php ku_query('iri*', 'iri-'); ?></span></td>
		<td><span lang="rw">rya</span></td>
		<td><span lang="rw">isomo rya Isaac</span> - Isaac's lesson</td>
		<td><span lang="rw">isomo ry'Ikinyarwanda</span> - a Kinyarwanda lesson</td>
	</tr>
	<tr>
		<td>6</td>
		<td><span lang="rw"><?php ku_query('ama*', 'ama-'); ?></span>, <span lang="rw"><?php ku_query('am*', 'am-'); ?></span></td>
		<td><span lang="rw">ya</span></td>
		<td><span lang="rw">amasomo ya Ben</span> - Ben's lessons</td>
		<td><span lang="rw">amasomo y'Igifaransa</span> - French lessons</td>
	</tr>
	<tr>
		<td>7</td>
		<td><span lang="rw"><?php ku_query('iki*', 'iki-'); ?></span>, <span lang="rw"><?php ku_query('icy*', 'icy-'); ?></span>, <span lang="rw"><?php ku_query('igi*', 'igi-'); ?></span></td>
		<td><span lang="rw">cya</span></td>
		<td><span lang="rw">igitabo cya Neza</span> - Neza's book</td>
		<td><span lang="rw">igitabo cy'umwarimu</span> - the teacher's book</td>
	</tr>
	<tr>
		<td>8</td>
		<td><span lang="rw"><?php ku_query('ibi*', 'ibi-'); ?></span>, <span lang="rw"><?php ku_query('iby*', 'ibi-'); ?></span></td>
		<td><span lang="rw">bya</span></td>
		<td><span lang="rw">ibitabo bya Claire</span> - Claire's books</td>
		<td><span lang="rw">ibitabo by'umunyeshuri</span> - the student's books</td>
	</tr>
	<tr>
		<td>9</td>
		<td rowspan="2"><span lang="rw"><?php ku_query('i*', 'i-'); ?></span>, <span lang="rw"><?php ku_query('in*', 'in-'); ?></span>, <span lang="rw"><?php ku_query('inz*', 'inz-'); ?></span></td>
		<td><span lang="rw">ya</span></td>
		<td><span lang="rw">inka ya Manzi</span> - Manzi's cow</td>
		<td><span lang="rw">inka y'umugabo</span> - the man's cow</td>
	</tr>
	<tr>
		<td>10</td>
		<td><span lang="rw">za</span></td>
		<td><span lang="rw">inka za Manzi</span> - Manzi's cows</td>
		<td><span lang="rw">inka z'umugabo</span> - the man's cows</td>
	</tr>
	<tr>
		<td>11</td>
		<td><span lang="rw"><?php ku_query('uru*', 'uru-'); ?></span>, <span lang="rw"><?php ku_query('urw*', 'urw-'); ?></span></td>
		<td><span lang="rw">rwa</span></td>
		<td><span lang="rw">ururimi rwa Gana</span> - the language of Ghana</td>
		<td><span lang="rw">ururimi rw'u Burundi</span> - the language of Burundi</td>
	</tr>
	<tr>
		<td>12</td>
		<td><span lang="rw"><?php ku_query('aka*', 'aka-'); ?></span>, <span lang="rw"><?php ku_query('ak*', 'ak-'); ?></span>, <span lang="rw"><?php ku_query('aga*', 'aga-'); ?></span></td>
		<td><span lang="rw">ka</span></td>
		<td><span lang="rw">akarere ka Gasabo</span> - the district of Gisabo</td>
		<td><span lang="rw">akarere k'abantu</span> - the district of the people</td>
	</tr>
	<tr>
		<td>13</td>
		<td><span lang="rw"><?php ku_query('utu*', 'utu-'); ?></span>, <span lang="rw"><?php ku_query('utw*', 'utw-'); ?></span>, <span lang="rw"><?php ku_query('udu*', 'udu-'); ?></span></td>
		<td><span lang="rw">twa</span></td>
		<td><span lang="rw">uturere twa Leta</span> - the Government's districts</td>
		<td><span lang="rw">uturere tw'u Rwanda</span> - the districts of Rwanda</td>
	</tr>
	<tr>
		<td>14</td>
		<td><span lang="rw"><?php ku_query('ubu*', 'ubu-'); ?></span>, <span lang="rw"><?php ku_query('ubw*', 'ubw-'); ?></span></td>
		<td><span lang="rw">bwa</span></td>
		<td><span lang="rw">ubwato bwa Peter</span> - Peter's boat</td>
		<td><span lang="rw">ubwato bw'umurobyi</span> - the fisherman's boat</td>
	</tr>
	<tr>
		<td>15</td>
		<td><span lang="rw"><?php ku_query('uku*', 'uku-'); ?></span>, <span lang="rw"><?php ku_query('ukw*', 'ukw-'); ?></span>, <span lang="rw"><?php ku_query('ugu*', 'ugu-'); ?></span></td>
		<td><span lang="rw">kwa</span></td>
		<td><span lang="rw">ugutwi kwa Grace</span> - Grace's ear</td>
		<td><span lang="rw">ugutwi kw'umukobwa</span> - the girl's ear</td>
	</tr>
	<tr>
		<td>16</td>
		<td><span lang="rw"><?php ku_query('aha*', 'aha-'); ?></span>, <span lang="rw"><?php ku_query('ah*', 'ah-'); ?></span></td>
		<td><span lang="rw">ha</span></td>
		<td><span lang="rw">ahantu ha Lambert</span> - Lambert's place</td>
		<td><span lang="rw">ahantu h'inzozi</span> - the place of dreams</td>
	</tr>
</table>

<h3>Possessive adjectives</h3>
<p>These are used to show ownership of a noun, like 'my' or 'your' in English and are formed by adding the possessive participle to a stem. For example:</p>

<table class="grammar">
	<tr>
		<th>English</th>
		<th>Stem</th>
		<th>Example (<span lang="rw"><?php ku_query('umu*', 'umu-'); ?></span>)</th>
		<th>Example (<span lang="rw"><?php ku_query('iki*', 'iki-'); ?></span>)</th>
		<th>Example (<span lang="rw"><?php ku_query('in*', 'in-'); ?></span>)</th>
	</tr>
	<tr>
		<td>my</td>
		<td><span lang="rw"><?php ku_query('-njye wclass:adj', '-njye'); ?></span></td>
		<td><span lang="rw">umwana wanjye</span> - my child</td>
		<td><span lang="rw">igitabo cyanjye</span> - my book</td>
		<td><span lang="rw">inka yanjye</span> - my cow</td>
	</tr>
	<tr>
		<td>your (singular)</td>
		<td><span lang="rw"><?php ku_query('-we wclass:adj', '-we'); ?></span></td>
		<td><span lang="rw">umwana wawe</span> - your child</td>
		<td><span lang="rw">igitabo cyawe</span> - your book</td>
		<td><span lang="rw">inka yawe</span> - your cow</td>
	</tr>
	<tr>
		<td>his/her</td>
		<td><span lang="rw"><?php ku_query('-e wclass:adj', '-e'); ?></span></td>
		<td><span lang="rw">umwana we</span> - your child</td>
		<td><span lang="rw">igitabo cye</span> - your book</td>
		<td><span lang="rw">inka ye</span> - his/her cow</td>
	</tr>
	<tr>
		<td>our</td>
		<td><span lang="rw"><?php ku_query('-cu wclass:adj', '-cu'); ?></span></td>
		<td><span lang="rw">umwana wacu</span> - our child</td>
		<td><span lang="rw">igitabo cyacu</span> - our book</td>
		<td><span lang="rw">inka yacu</span> - our cow</td>
	</tr>
	<tr>
		<td>your (plural)</td>
		<td><span lang="rw"><?php ku_query('-nyu wclass:adj', '-nyu'); ?></span></td>
		<td><span lang="rw">umwana wanyu</span> - your child</td>
		<td><span lang="rw">igitabo cyanyu</span> - your book</td>
		<td><span lang="rw">inka yanyu</span> - your cow</td>
	</tr>
	<tr>
		<td>their</td>
		<td><span lang="rw"><?php ku_query('-bo wclass:adj', '-bo'); ?></span></td>
		<td><span lang="rw">umwana wabo</span> - their child</td>
		<td><span lang="rw">igitabo cyabo</span> - their book</td>
		<td><span lang="rw">inka yabo</span> - their cow</td>
	</tr>
</table>

<p>The above stems are only used when describing ownership by a person or people (i.e. a class 1 or 2 noun), but of course a noun of any class can own a noun of any other class. For the remaining classes, the stem is created from the possessive participle by replacing the <span lang="rw">a</span> with <span lang="rw">o</span>, For example, if we are referring to something owned by a cow, then the stem is <span lang="rw">-yo:</span></p>
<ul>
	<li><span lang="rw">umutwe wayo</span> - its head</li>
	<li><span lang="rw">ikirengi cyayo</span> - its foot</li>
	<li><span lang="rw">amahembe yayo</span> - its horns</li>
	<li><span lang="rw">ubwoya bwayo</span> - its hair</li>
	<li><span lang="rw">ukuboko kwayo</span> - its front leg (lit. arm)</li>
</ul>

<p>This does mean there are an intimidatingly large number of possessive adjectives, so one shouldn't try to memorize them all, but rather understand how they are formed. The stems for all of the remaining classes are given below:</p>

<table class="grammar">
	<tr>
		<th>#</th>
		<th>Nouns</th>
		<th>Stem</th>
		<th>Example (<span lang="rw"><?php ku_query('umu*', 'umu-'); ?></span>)</th>
		<th>Example (<span lang="rw"><?php ku_query('iki*', 'iki-'); ?></span>)</th>
		<th>Example (<span lang="rw"><?php ku_query('in*', 'in-'); ?></span>)</th>
	</tr>
	<tr>
		<td>3</td>
		<td><span lang="rw"><?php ku_query('umu*', 'umu-'); ?></span>, <span lang="rw"><?php ku_query('umw*', 'umw-'); ?></span></td>
		<td><span lang="rw">-wo</span></td>
		<td><span lang="rw">umwana wawo</span> - its child</td>
		<td><span lang="rw">igitabo cyawo</span> - its book</td>
		<td><span lang="rw">inka yawo</span> - its cow</td>
	</tr>
	<tr>
		<td>4</td>
		<td><span lang="rw"><?php ku_query('imi*', 'imi-'); ?></span>, <span lang="rw"><?php ku_query('imy*', 'imy-'); ?></span></td>
		<td><span lang="rw">-yo</span></td>
		<td><span lang="rw">umwana wayo</span> - its child</td>
		<td><span lang="rw">igitabo cyayo</span> - its book</td>
		<td><span lang="rw">inka yayo</span> - its cow</td>
	</tr>
	<tr>
		<td>5</td>
		<td><span lang="rw"><?php ku_query('i*', 'i-'); ?></span>, <span lang="rw"><?php ku_query('iri*', 'iri-'); ?></span></td>
		<td><span lang="rw">-ryo</span></td>
		<td><span lang="rw">umwana waryo</span> - its child</td>
		<td><span lang="rw">igitabo cyaryo</span> - its book</td>
		<td><span lang="rw">inka yaryo</span> - its cow</td>
	</tr>
	<tr>
		<td>6</td>
		<td><span lang="rw"><?php ku_query('ama*', 'ama-'); ?></span>, <span lang="rw"><?php ku_query('am*', 'am-'); ?></span></td>
		<td><span lang="rw">-yo</span></td>
		<td><span lang="rw">umwana wayo</span> - its child</td>
		<td><span lang="rw">igitabo cyayo</span> - its book</td>
		<td><span lang="rw">inka yayo</span> - its cow</td>
	</tr>
	<tr>
		<td>7</td>
		<td><span lang="rw"><?php ku_query('iki*', 'iki-'); ?></span>, <span lang="rw"><?php ku_query('icy*', 'icy-'); ?></span>, <span lang="rw"><?php ku_query('igi*', 'igi-'); ?></span></td>
		<td><span lang="rw">-cyo</span></td>
		<td><span lang="rw">umwana wacyo</span> - its child</td>
		<td><span lang="rw">igitabo cyacyo</span> - its book</td>
		<td><span lang="rw">inka yacyo</span> - its cow</td>
	</tr>
	<tr>
		<td>8</td>
		<td><span lang="rw"><?php ku_query('ibi*', 'ibi-'); ?></span>, <span lang="rw"><?php ku_query('iby*', 'ibi-'); ?></span></td>
		<td><span lang="rw">-byo</span></td>
		<td><span lang="rw">umwana wabyo</span> - its child</td>
		<td><span lang="rw">igitabo cyabyo</span> - its book</td>
		<td><span lang="rw">inka yabyo</span> - its cow</td>
	</tr>
	<tr>
		<td>9</td>
		<td rowspan="2"><span lang="rw"><?php ku_query('i*', 'i-'); ?></span>, <span lang="rw"><?php ku_query('in*', 'in-'); ?></span>, <span lang="rw"><?php ku_query('inz*', 'inz-'); ?></span></td>
		<td><span lang="rw">-yo</span></td>
		<td><span lang="rw">umwana wayo</span> - its child</td>
		<td><span lang="rw">igitabo cyayo</span> - its book</td>
		<td><span lang="rw">inka yayo</span> - its cow</td>
	</tr>
	<tr>
		<td>10</td>
		<td><span lang="rw">-zo</span></td>
		<td><span lang="rw">umwana wazo</span> - its child</td>
		<td><span lang="rw">igitabo cyazo</span> - its book</td>
		<td><span lang="rw">inka yazo</span> - its cow</td>
	</tr>
	<tr>
		<td>11</td>
		<td><span lang="rw"><?php ku_query('uru*', 'uru-'); ?></span>, <span lang="rw"><?php ku_query('urw*', 'urw-'); ?></span></td>
		<td><span lang="rw">-rwo</span></td>
		<td><span lang="rw">umwana warwo</span> - its child</td>
		<td><span lang="rw">igitabo cyarwo</span> - its book</td>
		<td><span lang="rw">inka yarwo</span> - its cow</td>
	</tr>
	<tr>
		<td>12</td>
		<td><span lang="rw"><?php ku_query('aka*', 'aka-'); ?></span>, <span lang="rw"><?php ku_query('ak*', 'ak-'); ?></span>, <span lang="rw"><?php ku_query('aga*', 'aga-'); ?></span></td>
		<td><span lang="rw">-ko</span></td>
		<td><span lang="rw">umwana wako</span> - its child</td>
		<td><span lang="rw">igitabo cyako</span> - its book</td>
		<td><span lang="rw">inka yako</span> - its cow</td>
	</tr>
	<tr>
		<td>13</td>
		<td><span lang="rw"><?php ku_query('utu*', 'utu-'); ?></span>, <span lang="rw"><?php ku_query('utw*', 'utw-'); ?></span>, <span lang="rw"><?php ku_query('udu*', 'udu-'); ?></span></td>
		<td><span lang="rw">-two</span></td>
		<td><span lang="rw">umwana watwo</span> - its child</td>
		<td><span lang="rw">igitabo cyatwo</span> - its book</td>
		<td><span lang="rw">inka yatwo</span> - its cow</td>
	</tr>
	<tr>
		<td>14</td>
		<td><span lang="rw"><?php ku_query('ubu*', 'ubu-'); ?></span>, <span lang="rw"><?php ku_query('ubw*', 'ubw-'); ?></span></td>
		<td><span lang="rw">-bwo</span></td>
		<td><span lang="rw">umwana wabwo</span> - its child</td>
		<td><span lang="rw">igitabo cyabwo</span> - its book</td>
		<td><span lang="rw">inka yabwo</span> - its cow</td>
	</tr>
	<tr>
		<td>15</td>
		<td><span lang="rw"><?php ku_query('uku*', 'uku-'); ?></span>, <span lang="rw"><?php ku_query('ukw*', 'ukw-'); ?></span>, <span lang="rw"><?php ku_query('ugu*', 'ugu-'); ?></span></td>
		<td><span lang="rw">-kwo</span></td>
		<td><span lang="rw">umwana wakwo</span> - its child</td>
		<td><span lang="rw">igitabo cyakwo</span> - its book</td>
		<td><span lang="rw">inka yakwo</span> - its cow</td>
	</tr>
	<tr>
		<td>16</td>
		<td><span lang="rw"><?php ku_query('aha*', 'aha-'); ?></span>, <span lang="rw"><?php ku_query('ah*', 'ah-'); ?></span></td>
		<td><span lang="rw">-ho</span></td>
		<td><span lang="rw">umwana waho</span> - its child</td>
		<td><span lang="rw">igitabo cyaho</span> - its book</td>
		<td><span lang="rw">inka yaho</span> - its cow</td>
	</tr>
</table>

