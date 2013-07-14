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
 * Purpose: Past tense stems reference page
 */
?>    
<p>Kinyarwanda has a complex set of modifications that can be applied to a verb stem to produce related verbs. The learner should not assume that any
modification can be applied to any verb, but understanding these rules will make it easier to remember new verbs.</p>

<table class="grammar">
	<tr>
		<th>&nbsp;</th>
		<th>Stem Modification</th>
		<th>Examples</th>
	</tr>
	<tr>
		<td><b>Simple</b><br />Subject acts</td>
		<td>&nbsp;</td>
		<td>
			<span lang="rw"><?php ku_query('-kora'); ?></span> - do<br />
			<span lang="rw"><?php ku_query('-bona'); ?></span> - see<br />
			<span lang="rw"><?php ku_query('-gura'); ?></span> - buy<br />
			<span lang="rw"><?php ku_query('-vuna'); ?></span> - break<br />
			<span lang="rw"><?php ku_query('-ha'); ?></span> - give<br />
			<span lang="rw"><?php ku_query('-ca'); ?></span> - cut<br />
		</td>
	</tr>
	<tr>
		<td><b>Reflexive</b><br />Subject acts upon themself</td>
		<td>
			<ul>
				<li><span lang="rw">i-</span></li>
				<li><span lang="rw">iy-</span> when stem begins with a vowel</li>
			</ul>
		</td>
		<td>
			<span lang="rw"><?php ku_query('-kora'); ?></span> &rarr; <span lang="rw"><?php ku_query('-ikora'); ?></span> - do oneself<br />
			<span lang="rw"><?php ku_query('-bona'); ?></span> &rarr; <span lang="rw"><?php ku_query('-ibona'); ?></span> - see oneself<br />
		</td>
	</tr>
	<tr>
		<td><b>Passive</b><br />Subject is acted upon by another</td>
		<td>
			<ul>
				<li><span lang="rw">-w-</span></li>
				<li><span lang="rw">-yw-</span> when final syllable is <span lang="rw">ba</span></li>
				<li><span lang="rw">-bwa</span> with monosyllabic stems - it replaces the final <span lang="rw">-ye</span> of the past tense form</li>
			</ul>
		</td>
		<td>
			<span lang="rw"><?php ku_query('-kora'); ?></span> &rarr; <span lang="rw"><?php ku_query('-korwa'); ?></span> - be done<br />
			<span lang="rw"><?php ku_query('-bona'); ?></span> &rarr; <span lang="rw"><?php ku_query('-bonwa'); ?></span> - be seen<br />
			<span lang="rw"><?php ku_query('-gura'); ?></span> &rarr; <span lang="rw"><?php ku_query('-gurwa'); ?></span> - be bought<br />
			<span lang="rw"><?php ku_query('-vuna'); ?></span> &rarr; <span lang="rw"><?php ku_query('-vunwa'); ?></span> - be broken<br />
			<span lang="rw"><?php ku_query('-haba'); ?></span> &rarr; <span lang="rw"><?php ku_query('-habywa'); ?></span> - be lost<br />
			<span lang="rw"><?php ku_query('-ha'); ?></span> &rarr; <span lang="rw"><?php ku_query('-habwa'); ?></span> - be given<br />
			<span lang="rw"><?php ku_query('-ca'); ?></span> &rarr; <span lang="rw"><?php ku_query('-cibwa'); ?></span> - be cut<br />
		</td>
	</tr>
	<tr>
		<td><b>Neuter</b><br />Subject is in a state or condition</td>
		<td>
			<ul>
				<li><span lang="rw">-ek-</span>, <span lang="rw">-ik-</span> (follows the <?php ku_page('spelling#auirule', 'AUI rule'); ?>)</li>
				<li><span lang="rw">-ka</span> for some verbs ending <span lang="rw">-ra</span></li>
			</ul>
		</td>
		<td>
			<span lang="rw"><?php ku_query('-bona'); ?></span> &rarr; <span lang="rw"><?php ku_query('-boneka'); ?></span> - be visible<br />
			<span lang="rw"><?php ku_query('-vuna'); ?></span> &rarr; <span lang="rw"><?php ku_query('-vunika'); ?></span> - be broken<br />
			<span lang="rw"><?php ku_query('-ca'); ?></span> &rarr; <span lang="rw"><?php ku_query('-cika'); ?></span> - be torn<br />
			<span lang="rw"><?php ku_query('-sohora'); ?></span> &rarr; <span lang="rw"><?php ku_query('-sohoka'); ?></span> - go out<br />
		</td>
	</tr>
	<tr>
		<td><b>Causative</b><br />Subject causes another to act</td>
		<td>
			<ul>
				<li><span lang="rw">-esh-</span>, <span lang="rw">-ish-</span> (follows the <?php ku_page('spelling#auirule', 'AUI rule'); ?>)</li>
				<li><span lang="rw">-sha</span> with monosyllabic stems - it replaces the final <span lang="rw">-ye</span> of the past tense form</li>
				<li><span lang="rw">-za</span> for some verbs ending <span lang="rw">-ra</span></li>
			</ul> 
		</td>
		<td>
			<span lang="rw"><?php ku_query('-kora'); ?></span> &rarr; <span lang="rw"><?php ku_query('-koresha'); ?></span> - cause to do (use)<br />
			<span lang="rw"><?php ku_query('-gura'); ?></span> &rarr; <span lang="rw"><?php ku_query('-gurisha'); ?></span> - cause to buy (sell)<br />
			<span lang="rw"><?php ku_query('-ca'); ?></span> &rarr; <span lang="rw"><?php ku_query('-cisha'); ?></span> - cause to cut<br />
			<span lang="rw"><?php ku_query('-hora'); ?></span> &rarr; <span lang="rw"><?php ku_query('-hoza'); ?></span> - cause to cool<br />
		</td>
	</tr>
	<tr>
		<td><b>Prepositional</b><br />Subject acts for another or at a particular place</td>
		<td>
			<ul>
				<li><span lang="rw">-er-</span>, <span lang="rw">-ir-</span> (follows the <?php ku_page('spelling#auirule', 'AUI rule'); ?>)</li>
			</ul>
		</td>
		<td>
			<span lang="rw"><?php ku_query('-kora'); ?></span> &rarr; <span lang="rw"><?php ku_query('-korera'); ?></span> - do for, do at<br />
			<span lang="rw"><?php ku_query('-bona'); ?></span> &rarr; <span lang="rw"><?php ku_query('-bonera'); ?></span> - see for, see at<br />
			<span lang="rw"><?php ku_query('-gura'); ?></span> &rarr; <span lang="rw"><?php ku_query('-gurira'); ?></span> - buy for, buy at<br />
		</td>
	</tr>
	<tr>
		<td><b>Associative</b><br />Subject acts with another</td>
		<td>
			<ul>
				<li><span lang="rw">-an-</span></li>
			</ul>
		</td>
		<td>
			<span lang="rw"><?php ku_query('-kora'); ?></span> &rarr; <span lang="rw"><?php ku_query('-korana'); ?></span> - do together<br />
		</td>
	</tr>
	<tr>
		<td><b>Reciprocal</b><br />Subjects act upon each other</td>
		<td>
			<ul>
				<li><span lang="rw">-an-</span> or <span lang="rw">-any-</span> (only transitive verbs)</li>
			</ul>
		</td>
		<td>
			<span lang="rw"><?php ku_query('-bona'); ?></span> &rarr; <span lang="rw"><?php ku_query('-bonana'); ?></span> - see each other<br />
			<span lang="rw"><?php ku_query('-fasha'); ?></span> &rarr; <span lang="rw"><?php ku_query('-fashanya'); ?></span> - help each other<br />
		</td>
	</tr>
	<tr>
		<td><b>Reversive</b><br />Subject reverses a previous act</td>
		<td>
			<ul>
				<li><span lang="rw">-ur-</span></li>
			</ul>
		</td>
		<td>
			<span lang="rw"><?php ku_query('-sasa'); ?></span> &rarr; <span lang="rw"><?php ku_query('-sasura'); ?></span> - unmake a bed<br />
		</td>
	</tr>
	<tr>
		<td><b>Intensive</b><br />Subjects acts intensely or repeatedly</td>
		<td>
			<ul>
				<li><span lang="rw">-gura</span></li>
			</ul>
		</td>
		<td>
			<span lang="rw"><?php ku_query('-soma'); ?></span> &rarr; <span lang="rw"><?php ku_query('-somagura'); ?></span> - kiss passionately<br />
			<span lang="rw"><?php ku_query('-vuna'); ?></span> &rarr; <span lang="rw"><?php ku_query('-vunagura'); ?></span> - smash<br />		
		</td>
	</tr>
</table>