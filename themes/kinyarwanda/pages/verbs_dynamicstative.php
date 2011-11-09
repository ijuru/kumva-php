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
 * Purpose: Dynamic vs stative verbs reference page
 */
?>
<p>A <i>dynamic</i> verb describes an action on the part of the subject (e.g. I am walking) whereas a <i>stative</i> verb is one that expresses a property of the subject (e.g. I am tired). However a verb which is considered stative in English (e.g. to like) may be dynamic in Kinyarwanda (e.g. <span lang="rw"><?php ku_query('gukunda'); ?></span>) and visa versa.</p>

<h3>Present tense</h3>
<p>There are two important differences in Kinyarwanda in how dynamic and stative verbs are conjugated in the present tense. Firstly dynamic verbs use the present tense stem, e.g.
<ul>
	<li><span lang="rw">ara<b>kora</b></span> - he is doing (from <span lang="rw"><?php ku_query('gukora'); ?></span>)</li>
	<li><span lang="rw">ara<b>vuga</b></span> - he is speaking (from <span lang="rw"><?php ku_query('kuvuga'); ?></span>)</li>
	<li><span lang="rw">ar<b>iga</b></span> - he is learning (from <span lang="rw"><?php ku_query('kwiga'); ?></span>)</li>
</ul>
But stative verbs use the <?php ku_page('verbs-pasttensestems', 'past tense stem'); ?>, e.g.
<ul>	
	<li><span lang="rw">ara<b>konje</b></span> - he is cold (from <span lang="rw"><?php ku_query('gukonja'); ?></span>)</li>
	<li><span lang="rw">ara<b>naniwe</b></span> - he is tired (from <span lang="rw"><?php ku_query('kunanirwa'); ?></span>)</li>
	<li><span lang="rw">ara<b>rwaye</b></span> - he is sick (from <span lang="rw"><?php ku_query('kurwara'); ?></span>)</li>
</ul>
Secondly dynamic verbs always keep the <span lang="rw">ra</span> present tense marker when followed by an object, e.g.
<ul>
	<li><span lang="rw">a<b>ra</b>kora akazi</span> - he is doing work</li>
	<li><span lang="rw">a<b>ra</b>vuga akazi</span> - he is speaking about work</li>
	<li><span lang="rw">a<b>r</b>iga Ikinyarwanda</span> - he is learning Kinyarwanda</li>
</ul>
Most stative verbs can't take an object (i.e. they're instransitive) but if they do then they drop the present tense marker, e.g.
<ul>
	<li><span lang="rw">arwaye mutwe</span> - he has a headache (sickness of the head)</li>
</ul>
</p> 

<h3>Immediate past</h3>
<p>Conjugating a dynamic verb with the present tense marker but the past tense stem creates an <i>immediate past</i> tense which is equivalent to using 'just' in English, e.g.
<ul>
	<li><span lang="rw">a<b>ra</b>koze</span> - he just did</li>
	<li><span lang="rw">a<b>ra</b>vuze</span> - he just spoke</li>
</ul>
</p>


<h3>Exceptions</h3>
<ul>
	<li><span lang="rw"><?php ku_query('guteka'); ?></span> - to cook, is conjugated like stative verb, e.g.
		<ul>
			<li><span lang="rw">aratetse</span> - he is cooking (present tense)</li>
			<li><span lang="rw">atetse inyama</span> - he is cooking meat (present tense)</li>
		</ul>
	</li>
	<li><span lang="rw"><?php ku_query('kujya'); ?></span> - to go, has an immediate past when used without an object (like a dynamic verb), but not when used with an object (like a stative verb), e.g. 
		<ul>
			<li><span lang="rw">aragiye</span> - he just went (immediate past tense)</li>
			<li><span lang="rw">agiye mu mujyi</span> - he is going to town (present tense)</li>
		</ul>
	</li>
</ul>

