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
 * Purpose: Adverbs reference page
 */
?>    
<p>Because Kinyarwanda has only been a written language for about a hundred years, the pronunciation of different letters is almost completely consistent throughout the language. This makes it relatively easy to ascertain the pronunciation of a word from its written form, compared to a language such as English. However, many of the sounds in Kinyarwanda will be new to an English speaker and thus difficult to pronounce without the help of a native speaker and much practice.</p>

<table class="grammar">
	<tr>
		<th>Letters</th>
		<th>Pronunciation</th>
		<th>Example</th>
	</tr>
	<tr>
		<th colspan="3" class="group">Vowels (<span lang="rw"><?php ku_query('inyajwi'); ?></span>)</td>
	</tr>
	<tr>
		<td><span lang="rw">a</span></td>
		<td>broad as in 'far'</td>
		<td><span lang="rw"><?php ku_query('amata'); ?></span> <i>a-ma-ta</i> <?php Widgets::sound(3659); ?></td>
	</tr>
	<tr>
		<td><span lang="rw">e</span></td>
		<td>long like 'a' in 'hay' or short like 'e' in 'bet'</td>
		<td><span lang="rw"><?php ku_query('ejo'); ?></span> <i>ay-joh</i> <?php Widgets::sound(1529); ?></td>
	</tr>
	<tr>
		<td><span lang="rw">i</span></td>
		<td>long like 'ee' in 'bee' or short like 'i' in 'bit'</td>
		<td><span lang="rw"><?php ku_query('igiti'); ?></span> <i>ee-ji-tee</i> <?php Widgets::sound(3765); ?></td>
	</tr>
	<tr>
		<td><span lang="rw">o</span></td>
		<td>long like 'o' in 'tone' or short like 'o' in 'lot'</td>
		<td><span lang="rw"><?php ku_query('amahoro'); ?></span> <i>a-ma-haw-ro</i> <?php Widgets::sound(2585); ?></td>
	</tr>
	<tr>
		<td><span lang="rw">u</span></td>
		<td>like 'oo' in 'food'</td>
		<td><span lang="rw"><?php ku_query('umuntu'); ?></span> <i>oo-moon-hoo</i> <?php Widgets::sound(3039); ?></td>
	</tr>
	<tr>
		<th colspan="3" class="group">Consonants (<span lang="rw"><?php ku_query('ingombajwi'); ?></span>)</td>
	</tr>	
	<tr>
		<td><span lang="rw">d</span>, <span lang="rw">f</span>, <span lang="rw">g</span>, <span lang="rw">h</span>, <span lang="rw">k</span>, <span lang="rw">m</span>, <span lang="rw">n</span>, <span lang="rw">p</span>, <span lang="rw">s</span>, <span lang="rw">t</span>, <span lang="rw">v</span>, <span lang="rw">w</span></td>
		<td>pronounced the same as in English</td>
		<td><span lang="rw">&nbsp;</td>
	</tr>
	<tr>
		<td><span lang="rw">b</span></td>
		<td>softer than in English with the lips barely touching</td>
		<td><span lang="rw"><?php ku_query('ibibabi'); ?></span> <i>i-bi-ba-bi</i> <?php Widgets::sound(KUMVA_URL_THEME.'/mp3/ibibabi.mp3'); ?></td>
	</tr>	
	<tr>
		<td><span lang="rw">c</span></td>
		<td>like 'ch' in 'church'</td>
		<td><span lang="rw"><?php ku_query('icupa'); ?></span> <i>i-choo-pah</i> <?php Widgets::sound(2159); ?></td>
	</tr>
	<tr>
		<td><span lang="rw">j</span></td>
		<td>soft like 'z' in 'azure'</td>
		<td><span lang="rw"><?php ku_query('ijuru'); ?></span> <i>i-joo-roo</i> <?php Widgets::sound(2717); ?></td>
	</tr>
	<tr>
		<td><span lang="rw">l</span></td>
		<td>almost like 'r'</td>
		<td><span lang="rw"><?php ku_query('leta'); ?></span> <i>lay-tah</i> <?php Widgets::sound(2937); ?></td>
	</tr>
	<tr>
		<td><span lang="rw">r</span></td>
		<td>as in English but with slight trill sound</td>
		<td><span lang="rw"><?php ku_query('amarira'); ?></span> <i>a-ma-ree-rah</i> <?php Widgets::sound(3334); ?></td>
	</tr>
	<tr>
		<td><span lang="rw">y</span></td>
		<td>like 'y' in 'you' and never a vowel like 'sky'</td>
		<td><span lang="rw"><?php ku_query('ikiyiko'); ?></span> <i>ee-chee-yee-koh</i> <?php Widgets::sound(3946); ?></td>
	</tr>
	<tr>
		<td><span lang="rw">z</span></td>
		<td>like 'z' in 'zone'</td>
		<td><span lang="rw"><?php ku_query('izuba'); ?></span> <i>ee-zoo-bah</i> <?php Widgets::sound(3995); ?></td>
	</tr>
	<tr>
		<th colspan="3" class="group">Consonant combinations (<span lang="rw"><?php ku_query('ibihekane'); ?></span>)</td>
	</tr>
	<tr>
		<td><span lang="rw">bw</span></td>
		<td>as 'bg' (even written 'bg' in some older books)</td>
		<td><span lang="rw"><?php ku_query('ubwoba'); ?></span> <i>oob-go-bah</i> <?php Widgets::sound(3140); ?></td>
	</tr>
	<tr>
		<td><span lang="rw">jy</span></td>
		<td>hard 'j' like in 'jam'</td>
		<td><span lang="rw"><?php ku_query('kujya'); ?></span> <i>koo-jah</i> <?php Widgets::sound(680); ?></td>
	</tr>
	<tr>
		<td><span lang="rw">mp</span></td>
		<td>as 'mh'</td>
		<td><span lang="rw"><?php ku_query('impanga'); ?></span> <i>eem-hang-gah</i> <?php Widgets::sound(3176); ?></td>
	</tr>
	<tr>
		<td><span lang="rw">nn</span></td>
		<td>as if there was a slight 'i' between them</td>
		<td><span lang="rw"><?php ku_query('ubuvunnyi'); ?></span> <i>oo-boo-voo-n-in-yee</i> <?php Widgets::sound(3922); ?></td>
	</tr>
	<tr>
		<td><span lang="rw">nk</span></td>
		<td>as 'ngh'</td>
		<td><span lang="rw"><?php ku_query('inka'); ?></span> <i>eeng-ha</i> <?php Widgets::sound(2728); ?></td>
	</tr>
	<tr>
		<td><span lang="rw">nt</span></td>
		<td>as 'nh'</td>
		<td><span lang="rw"><?php ku_query('umuntu'); ?></span> <i>oo-moon-hoo</i> <?php Widgets::sound(3039); ?></td>
	</tr>
	<tr>
		<td><span lang="rw">rw</span></td>
		<td>as if there were a 'g' between them</td>
		<td><span lang="rw"><?php ku_query('u Rwanda'); ?></span> <i>oor-gwan-dah</i> <?php Widgets::sound(4019); ?></td>
	</tr>
	<tr>
		<td><span lang="rw">ry</span></td>
		<td>as 'rdj' or 'dy'</td>
		<td><span lang="rw"><?php ku_query('umuryango'); ?></span> <i>oo-moord-jang-goh</i> <?php Widgets::sound(3384); ?></td>
	</tr>
	<tr>
		<td><span lang="rw">sw</span></td>
		<td>as if there was a slight 'k' between them</td>
		<td><span lang="rw"><?php ku_query('umuswa'); ?></span> <i>oo-moos-kwa</i> <?php Widgets::sound(3655); ?></td>
	</tr>
    <tr>
		<td><span lang="rw">tw</span></td>
		<td>as if there was a slight 'g' between them</td>
		<td><span lang="rw"><?php ku_query('ugutwi'); ?></span> <i>oo-goot-gwi</i> <?php Widgets::sound(3862); ?></td>
	</tr>
</table>

<h3>Word interactions</h3>
<p>Words in a Kinyarwanda sentence interact with each other to allow the words to flow into each other. The most common interaction occurs when a word begins with a vowel and causes the preceding word to drop its final vowel. In some cases this interaction is written explicitly, like in the case of <span lang="rw"><?php ku_query('na'); ?></span> (e.g. <span lang="rw">na umugabo</span> should be written <span lang="rw">n'umugabo</span>), but in other cases the final vowel is retained in the spelling even though it is not pronounced. For example:</p>
<ul>
	<li><span lang="rw">ni umugabo</span> <i>noo-moo-ga-bo</i> <?php Widgets::sound(KUMVA_URL_THEME.'/mp3/ni_umugabo.mp3'); ?> - he is a man</li>
	<li><span lang="rw">uri umwarimu</span> <i>oo-room-wa-ree-moo</i> <?php Widgets::sound(KUMVA_URL_THEME.'/mp3/uri_umwarimu.mp3'); ?> - you are a teacher</li>
</ul>

<h3>Regional variation</h3>
<ul>
	<li><span lang="rw">ge</span> and <span lang="rw">gi</span> are pronounced hard by people living near to the DRC and Burundi, but soft by people from central Rwanda, e.g. <span lang="rw"><?php ku_query('tugende'); ?></span> sounds like <i>too-jen-day</i> <?php Widgets::sound(4224); ?> in Kigali
	<li><span lang="rw">ke</span> and <span lang="rw">ki</span> can be hard or soft. People from <span lang="rw"><?php ku_query('Kigali'); ?></span> usually pronounce it as <i>chee-ga-ree</i> <?php Widgets::sound(4050); ?></li>
</ul>
