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
<p>There are no definitive rules for how the past tense stem of a verb stem is formed, but there are common patterns. The following table lists the most common past tense stems based on how the verb stem ends:</p>

<table class="grammar">
	<tr>
		<th>Stem ending (present)</th>
		<th>Stem ending (past)</th>
		<th>Examples</th>
	</tr>
	<tr>
		<td><span lang="rw"><?php ku_query('*ba wclass:v', '-ba'); ?></span></td>
		<td><span lang="rw"><?php ku_query('*bye wclass:v', '-bye'); ?></span></td>
		<td><span lang="rw"><?php ku_query('gusaba'); ?></span> &rarr; <span lang="rw">-bye</span>, <span lang="rw"><?php ku_query('guhamba'); ?></span> &rarr; <span lang="rw">-hambye</span></td>
	</tr>
	<tr>
		<td><span lang="rw"><?php ku_query('*da wclass:v', '-da'); ?></span></td>
		<td><span lang="rw"><?php ku_query('*ze wclass:v', '-ze'); ?></span></td>
		<td><span lang="rw"><?php ku_query('gukunda'); ?></span> &rarr; <span lang="rw">-kunze</span>, <span lang="rw"><?php ku_query('kudoda'); ?></span> &rarr; <span lang="rw">-doze</span></td>
	</tr>
	<tr>
		<td><span lang="rw"><?php ku_query('*ga wclass:v', '-ga'); ?></span></td>
		<td><span lang="rw"><?php ku_query('*ze wclass:v', '-ze'); ?></span></td>
		<td><span lang="rw"><?php ku_query('guhaga'); ?></span> &rarr; <span lang="rw">-haze</span>, <span lang="rw"><?php ku_query('gufunga'); ?></span> &rarr; <span lang="rw">-funze</span></td>
	</tr>
	<tr>
		<td><span lang="rw"><?php ku_query('*ha wclass:v', '-ha'); ?></span></td>
		<td><span lang="rw"><?php ku_query('*shye wclass:v', '-shye'); ?></span></td>
		<td><span lang="rw"><?php ku_query('kuboha'); ?></span> &rarr; <span lang="rw">-bohashye</span>, <span lang="rw"><?php ku_query('gutaha'); ?></span> &rarr; <span lang="rw">-tashye</span></td>
	</tr>
	<tr>
		<td><span lang="rw"><?php ku_query('*ka wclass:v', '-ka'); ?></span></td>
		<td><span lang="rw"><?php ku_query('*tse wclass:v', '-tse'); ?></span></td>
		<td><span lang="rw"><?php ku_query('gushaka'); ?></span> &rarr; <span lang="rw">-shatse</span>, <span lang="rw"><?php ku_query('gucika'); ?></span> &rarr; <span lang="rw">-citse</span></td>
	</tr>
	<tr>
		<td><span lang="rw"><?php ku_query('*ma wclass:v', '-ma'); ?></span></td>
		<td><span lang="rw"><?php ku_query('*mye wclass:v', '-mye'); ?></span></td>
		<td><span lang="rw"><?php ku_query('gusoma'); ?></span> &rarr; <span lang="rw">-somye</span>, <span lang="rw"><?php ku_query('kurema'); ?></span> &rarr; <span lang="rw">-remye</span></td>
	</tr>
	<tr>
		<td rowspan="2"><span lang="rw"><?php ku_query('*na wclass:v', '-na'); ?></span></td>
		<td><span lang="rw"><?php ku_query('*nye wclass:v', '-nye'); ?></span></td>
		<td><span lang="rw"><?php ku_query('gukorana'); ?></span> &rarr; <span lang="rw">-koranye</span>, <span lang="rw"><?php ku_query('gufana'); ?></span> &rarr; <span lang="rw">-fanye</span></td>
	</tr>
	<tr>
		<td><span lang="rw"><?php ku_query('*nnye wclass:v', '-nnye'); ?></span></td>
		<td><span lang="rw"><?php ku_query('gukina'); ?></span> &rarr; <span lang="rw">-kinnye</span></td>
	</tr>
	<tr>
		<td rowspan="2"><span lang="rw"><?php ku_query('*nya wclass:v', '-nya'); ?></span></td>
		<td><span lang="rw"><?php ku_query('*nije wclass:v', '-nije'); ?></span></td>
		<td><span lang="rw"><?php ku_query('gukoranya'); ?></span> &rarr; <span lang="rw">-koranije</span>, <span lang="rw"><?php ku_query('gufatanya'); ?></span> &rarr; <span lang="rw">-fatanije</span></td>
	</tr>
	<tr>
		<td><span lang="rw"><?php ku_query('*nye wclass:v', '-nye'); ?></span></td>
		<td><span lang="rw"><?php ku_query('gutinya'); ?></span> &rarr; <span lang="rw">-tinye</span></td>
	</tr>
	
	<tr>
		<td rowspan="3"><span lang="rw"><?php ku_query('*ra wclass:v', '-ra'); ?></span></td>
		<td><span lang="rw"><?php ku_query('*ye wclass:v', '-ye'); ?></span></td>
		<td><span lang="rw"><?php ku_query('gusura'); ?></span> &rarr; <span lang="rw">-suye</span>, <span lang="rw"><?php ku_query('gutera'); ?></span> &rarr; <span lang="rw">-teye</span></td>
	</tr>
	<tr>
		<td><span lang="rw"><?php ku_query('*ze wclass:v', '-ze'); ?></span></td>
		<td><span lang="rw"><?php ku_query('gukora'); ?></span> &rarr; <span lang="rw">-koze</span>, <span lang="rw"><?php ku_query('kugura'); ?></span> &rarr; <span lang="rw">-guze</span></td>
	</tr>
	<tr>
		<td><span lang="rw"><?php ku_query('*reye wclass:v', '-reye'); ?></span>, <span lang="rw"><?php ku_query('*riye wclass:v', '-riye'); ?></span></td>
		<td><span lang="rw"><?php ku_query('guhera'); ?></span> &rarr; <span lang="rw">-hereye</span>, <span lang="rw"><?php ku_query('gusinzira'); ?></span> &rarr; <span lang="rw">-sinziriye</span></td>
	</tr>
	
	<tr>
		<td rowspan="3"><span lang="rw"><?php ku_query('*rwa wclass:v', '-rwa'); ?></span></td>
		<td><span lang="rw"><?php ku_query('*we wclass:v', '-we'); ?></span></td>
		<td><span lang="rw"><?php ku_query('guterwa'); ?></span> &rarr; <span lang="rw">-tewe</span>, <span lang="rw"><?php ku_query('kugirwa'); ?></span> &rarr; <span lang="rw">-giwe</span></td>
	</tr>
	<tr>
		<td><span lang="rw"><?php ku_query('*zwe wclass:v', '-zwe'); ?></span></td>
		<td><span lang="rw"><span lang="rw"><?php ku_query('kugurwa'); ?></span> &rarr; <span lang="rw">-guzwe</span>, <?php ku_query('gukorwa'); ?></span> &rarr; <span lang="rw">-kozwe</span></td>
	</tr>
	<tr>
		<td><span lang="rw"><?php ku_query('*rewe wclass:v', '-rewe'); ?></span>, <span lang="rw"><?php ku_query('*riwe wclass:v', '-riwe'); ?></span></td>
		<td><span lang="rw"><span lang="rw"><?php ku_query('gukererwa'); ?></span> &rarr; <span lang="rw">-kererewe</span>, <?php ku_query('guhirwa'); ?></span> &rarr; <span lang="rw">-hiriwe</span></td>
	</tr>
	
	<tr>
		<td rowspan="2"><span lang="rw"><?php ku_query('*sa wclass:v', '-sa'); ?></span></td>
		<td><span lang="rw"><?php ku_query('*she wclass:v', '-she'); ?></span></td>
		<td><span lang="rw"><?php ku_query('kumesa'); ?></span> &rarr; <span lang="rw">-meshe</span>, <span lang="rw"><?php ku_query('kurasa'); ?></span> &rarr; <span lang="rw">-rashe</span></td>
	</tr>
	<tr>
		<td><span lang="rw"><?php ku_query('*sheje wclass:v', '-sheje'); ?></span>, <span lang="rw"><?php ku_query('*shije wclass:v', '-shije'); ?></span></td>
		<td><span lang="rw"><?php ku_query('gukerensa'); ?></span> &rarr; <span lang="rw">-kerensheje</span>, <span lang="rw"><?php ku_query('gucugusa'); ?></span> &rarr; <span lang="rw">-cugushije</span></td>
	</tr>
	
	<tr>
		<td><span lang="rw"><?php ku_query('*sha wclass:v', '-sha'); ?></span></td>
		<td><span lang="rw"><?php ku_query('*sheje wclass:v', '-sheje'); ?></span>, <span lang="rw"><?php ku_query('*shije wclass:v', '-shije'); ?></span></td>
		<td><span lang="rw"><?php ku_query('kureshya'); ?></span> &rarr; <span lang="rw">-reheje</span>, <span lang="rw"><?php ku_query('gutashya'); ?></span> &rarr; <span lang="rw">-tahije</span></td>
	</tr>
	
	<tr>
		<td><span lang="rw"><?php ku_query('*shwa wclass:v', '-shwa'); ?></span></td>
		<td><span lang="rw"><?php ku_query('*shejwe wclass:v', '-shejwe'); ?></span>, <span lang="rw"><?php ku_query('*shijwe wclass:v', '-shijwe'); ?></span></td>
		<td><span lang="rw"><?php ku_query('gukoreshwa'); ?></span> &rarr; <span lang="rw">-koreshejwe</span>, <span lang="rw"><?php ku_query('kwigishwa'); ?></span> &rarr; <span lang="rw">-igishijwe</span></td>
	</tr>
	
	<tr>
		<td rowspan="2"><span lang="rw"><?php ku_query('*shya wclass:v', '-shya'); ?></span></td>
		<td><span lang="rw"><?php ku_query('*heje wclass:v', '-heje'); ?></span>, <span lang="rw"><?php ku_query('*hije wclass:v', '-hije'); ?></span></td>
		<td><span lang="rw"><?php ku_query('guhosha'); ?></span> &rarr; <span lang="rw">-hosheje</span>, <span lang="rw"><?php ku_query('gufasha'); ?></span> &rarr; <span lang="rw">-fashije</span></td>
	</tr>
	<tr>
		<td><span lang="rw"><?php ku_query('*shye wclass:v', '-shye'); ?></span></td>
		<td><span lang="rw"><?php ku_query('kubeshya'); ?></span> &rarr; <span lang="rw">-beshye</span></td>
	</tr>
	
	<tr>
		<td rowspan="2"><span lang="rw"><?php ku_query('*ta wclass:v', '-ta'); ?></span></td>
		<td><span lang="rw"><?php ku_query('*se wclass:v', '-se'); ?></span></td>
		<td><span lang="rw"><?php ku_query('guhita'); ?></span> &rarr; <span lang="rw">-hise</span>, <span lang="rw"><?php ku_query('kurata'); ?></span> &rarr; <span lang="rw">-rase</span></td>
	</tr>
	<tr>
		<td><span lang="rw"><?php ku_query('*she wclass:v', '-she'); ?></span></td>
		<td><span lang="rw"><?php ku_query('gufata'); ?></span> &rarr; <span lang="rw">-fashe</span></td>
	</tr>
	
	<tr>
		<td><span lang="rw"><?php ku_query('*tsa wclass:v', '-tsa'); ?></span></td>
		<td><span lang="rw"><?php ku_query('*keje wclass:v', '-keje'); ?></span>, <span lang="rw"><?php ku_query('*kije wclass:v', '-kije'); ?></span></td>
		<td><span lang="rw"><?php ku_query('kwotsa'); ?></span> &rarr; <span lang="rw">-okeje</span>, <span lang="rw"><?php ku_query('kwatsa'); ?></span> &rarr; <span lang="rw">-akije</span></td>
	</tr>
	
	<tr>
		<td rowspan="2"><span lang="rw"><?php ku_query('*za wclass:v', '-za'); ?></span></td>
		<td><span lang="rw"><?php ku_query('*je wclass:v', '-je'); ?></span></td>
		<td><span lang="rw"><?php ku_query('kuza'); ?></span> &rarr; <span lang="rw">-je</span>, <span lang="rw"><?php ku_query('gusokoza'); ?></span> &rarr; <span lang="rw">-sokoje</span></td>
	</tr>
	<tr>
		<td><span lang="rw"><?php ku_query('*jeje wclass:v', '-jeje'); ?></span>, <span lang="rw"><?php ku_query('*jije wclass:v', '-jije'); ?></span></td>
		<td><span lang="rw"><?php ku_query('gukoza'); ?></span> &rarr; <span lang="rw">-kojeje</span>, <span lang="rw"><?php ku_query('gukiza'); ?></span> &rarr; <span lang="rw">-kijije</span></td>
	</tr>
	
	<tr>
		<td rowspan="2"><span lang="rw"><?php ku_query('*zwa wclass:v', '-zwa'); ?></span></td>
		<td><span lang="rw"><?php ku_query('*jwe wclass:v', '-jwe'); ?></span></td>
		<td><span lang="rw"><?php ku_query('kuza'); ?></span> &rarr; <span lang="rw">-je</span>, <span lang="rw"><?php ku_query('gusokoza'); ?></span> &rarr; <span lang="rw">-sokoje</span></td>
	</tr>
	<tr>
		<td><span lang="rw"><?php ku_query('*jejwe wclass:v', '-jejwe'); ?></span>, <span lang="rw"><?php ku_query('*jijwe wclass:v', '-jijwe'); ?></span></td>
		<td><span lang="rw"><?php ku_query('gukizwa'); ?></span> &rarr; <span lang="rw">-kijijwe</span></td>
	</tr>
</table>

<p>Notice that when the past tense stem ending contains two vowels, the first vowel is subject to the <?php ku_page('spelling', 'A-I-U rule', 'aiurule'); ?>. For example <span lang="rw">-sha</span> can become <span lang="rw">-heje</span> or <span lang="rw">-hije</span> depending on the preceding vowel:</p>
<ul>
	<li><span lang="rw"><?php ku_query('guhosha'); ?></span> &rarr; <span lang="rw">-h<b>o</b>s<b>heje</b></span></li>
	<li><span lang="rw"><?php ku_query('gufasha'); ?></span> &rarr; <span lang="rw">-f<b>a</b>s<b>hije</b></span></li>
</ul>

<h3>Irregular verbs</h3>
<p>Most single syllable verb stems are irregular, e.g.</p>
<ul>
	<li><span lang="rw"><?php ku_query('kuba'); ?></span> &rarr; <span lang="rw">-baye</span></li>
	<li><span lang="rw"><?php ku_query('guca'); ?></span> &rarr; <span lang="rw">-ciye</span></li>
	<li><span lang="rw"><?php ku_query('kugwa'); ?></span> &rarr; <span lang="rw">-guye</span></li>
	<li><span lang="rw"><?php ku_query('guha'); ?></span> &rarr; <span lang="rw">-haye</span></li>
	<li><span lang="rw"><?php ku_query('kujya'); ?></span> &rarr; <span lang="rw">-giye</span></li>
	<li><span lang="rw"><?php ku_query('gupfa'); ?></span> &rarr; <span lang="rw">-pfuye</span></li>
	<li><span lang="rw"><?php ku_query('guta'); ?></span> &rarr; <span lang="rw">-teye</span></li>
	<li><span lang="rw"><?php ku_query('kuva'); ?></span> &rarr; <span lang="rw">-vuye</span></li>
</ul>

<h3>Other stem changes</h3>
<p>Sometimes more than just the ending of the verb stem changes, e.g.</p>
<ul>
	<li>The letter <b>s</b> may become <b>sh</b> like <span lang="rw"><?php ku_query('gusesa'); ?></span> which becomes <span lang="rw">-sheshe</span></li>
	<li>The letter <b>z</b> may become <b>j</b> like <span lang="rw"><?php ku_query('kunezeza'); ?></span> which becomes <span lang="rw">-nejeje</span></li>
</ul>



