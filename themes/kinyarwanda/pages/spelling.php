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
 * Purpose: Spelling reference page
 */
?>   

<p>There are several slightly different orthographies for Kinyarwanda including those created by the first Protestant and Catholic missionaries. There is also no official dictionary which means there is some disgreement about the spelling of many words. However, this dictionary tries to be consistent with what is taught in Rwandan schools.</p>

<h3>General rules</h3> 
<p>In Kinyarwanda there are many combinations of letters which will cause some letters to change. This is usually done to ease pronunciation, as can be observed if you try to pronounce something like <span lang="rw">umu+ana</span>.</p>

<table class="grammar">
	<tr>
		<th>Letters</th>
		<th>Result</th>
		<th>Examples</th>
	</tr>
	<tr>
		<td>u + vowel</td>
		<td>u &rarr; w</td>
		<td><span lang="rw">umu+ana</span> &rarr; <span lang="rw"><?php ku_query('umwana'); ?></span>, <span lang="rw">ku+iga</span> &rarr; <span lang="rw"><?php ku_query('kwiga'); ?></span></td>
	</tr>
	<tr>
		<td>i + vowel</td>
		<td>i &rarr; y</td>
		<td><span lang="rw">imi+enda</span> &rarr; <span lang="rw"><?php ku_query('imyenda'); ?></span>, <span lang="rw">iki+obo</span> &rarr; <span lang="rw"><?php ku_query('icyobo'); ?></span></td>
	</tr>
	<tr>
		<td>a + a</td>
		<td>a</td>
		<td><span lang="rw">aba+ana</span> &rarr; <span lang="rw"><?php ku_query('abana'); ?></span></td>
	</tr>
	<tr>
		<td>a + e</td>
		<td>e</td>
		<td><span lang="rw">ama+ezi</span> &rarr; <span lang="rw"><?php ku_query('amezi'); ?></span></td>
	</tr>
	<tr>
		<td>a + i</td>
		<td>i</td>
		<td><span lang="rw">ama+izero</span> &rarr; <span lang="rw"><?php ku_query('amizero'); ?></span></td>
	</tr>
	<tr>
		<td>a + i (start of adjective)</td>
		<td>e</td>
		<td><span lang="rw">ma+iza</span> &rarr; <span lang="rw"><?php ku_query('meza'); ?></span>, <span lang="rw">ba+inshi</span> &rarr; <span lang="rw"><?php ku_query('benshi'); ?></span></td>
	</tr>
	<tr>
		<td>n + labial (b, m, v, f, p)</td>
		<td>n &rarr; m</td>
		<td><span lang="rw">n+fite</span> &rarr; <span lang="rw"><?php ku_query('mfite'); ?></span>, <span lang="rw">in+bwa</span> &rarr; <span lang="rw"><?php ku_query('imbwa'); ?></span></td>
	</tr>
	<tr>
		<td>n + vowel (at start of stem)</td>
		<td>n &rarr; nz</td>
		<td><span lang="rw">in+oga</span> &rarr; <span lang="rw"><?php ku_query('inzoga'); ?></span>, <span lang="rw">n+iza</span> &rarr; <span lang="rw"><?php ku_query('nziza'); ?></span></td>
	</tr>
	<tr>
		<td>n + h</td>
		<td>nh &rarr; np &rarr; mp</td>
		<td><span lang="rw">in-hanuka</span> &rarr; <span lang="rw"><?php ku_query('impanuka'); ?></span>, <span lang="rw">n+huzamahanga</span> &rarr; <span lang="rw"><?php ku_query('mpuzamahanga'); ?></span></td>
	</tr>
	<tr>
		<td>n + r</td>
		<td>r &rarr; d</td>
		<td><span lang="rw">in+rwara</span> &rarr; <span lang="rw"><?php ku_query('indwara'); ?></span>, <span lang="rw">n+ra+kora</span> &rarr; <span lang="rw"><?php ku_query('ndakora'); ?></span></td>
	</tr>
</table>

<a name="changedownrule"></a>
<h3>The change down rule</h3>
<p>When the next consonant after <b>k</b> is one of <b>c</b>, <b>f</b>, <b>h</b>, <b>p</b>, <b>s</b>, <b>t</b> or <b>k</b>, then it becomes <b>g</b>, e.g.</p>
<ul>
	<li><span lang="rw">ku+kora</span> &rarr; <span lang="rw"><?php ku_query('gukora'); ?></span> - to do</li>
	<li><span lang="rw">aka+tsiko</span> &rarr; <span lang="rw"><?php ku_query('agatsiko'); ?></span> - group</li>
</ul>
<p>Likewise <b>t</b> becomes <b>d</b>, e.g.</p>
<ul>
	<li><span lang="rw">tu+fite</span> &rarr; <span lang="rw"><?php ku_query('dufite'); ?></span> - we have</li>
	<li><span lang="rw">utu+tsiko</span> &rarr; <span lang="rw"><?php ku_query('udutsiko'); ?></span> - groups</li>
</ul>

<a name="aiurule"></a>
<h3>The A-I-U rule</h3>
<p>This rule governs which vowels will occur in a verb suffix. If the next to the last syllable in a verb stem contains <b>a</b>, <b>i</b>, or <b>u</b>, the added suffix will contain <b>i</b> but if the next to the last syllable has <b>e</b> or <b>o</b>, the added suffix will contain <b>e</b>. For example, when adding the locative suffix to a verb:</p>
<ul>
	<li><span lang="rw"><?php ku_query('gukora'); ?></span> &rarr; <span lang="rw">gukor<b>er</b>a</span> - to work at, work for</li>
	<li><span lang="rw"><?php ku_query('kuririmba'); ?></span> &rarr; <span lang="rw">kuririmb<b>ir</b>a</span> - to sing at, sing for</li>
</ul>
<p>Similarly when added the causative suffix:</p>
<ul>
	<li><span lang="rw"><?php ku_query('gukora'); ?></span> &rarr; <span lang="rw">gukor<b>esh</b>a</span> - to cause to do</li>
	<li><span lang="rw"><?php ku_query('kuririmba'); ?></span> &rarr; <span lang="rw">kuririmb<b>ish</b>a</span> - to cause to sing</li>
</ul>

<a name="wordinteractions"></a>
<h3>Word interactions</h3>
<p>The <?php ku_page('pronunciation'); ?> rules of Kinyarwanda mean that usually vowels between words are not pronounced to allow words to flow into each other. Sometimes this is reflected in the spelling such as with the prepositions <span lang="rw"><?php ku_query('na'); ?></span> and <span lang="rw"><?php ku_query('nka'); ?></span>, e.g.</p>
<ul>
	<li><span lang="rw">na umugabo</span> &rarr; <span lang="rw">n'umugabo</span> - and the man</li>
	<li><span lang="rw">nka ijambo</span> &rarr; <span lang="rw">nk'ijambo</span> - like the word</li>
</ul>
<p>However sometimes the spelling remains unchanged despite the change in pronunciation, e.g.</p>
<ul>
	<li><span lang="rw">ni umugabo</span> - he is a man</li>
	<li><span lang="rw">uri umwarimu</span> - you are a teacher</li>
</ul>

<a name="muandku"></a>
<h4>Mu and ku</h4>
<p>The prepositions <span lang="rw"><?php ku_query('mu'); ?></span> and <span lang="rw"><?php ku_query('ku'); ?></span> usually cause the proceeding noun to drop its initial vowel (augment), e.g.</p>
<ul>
	<li><span lang="rw">mu umujyi</span> &rarr; <span lang="rw">mu mujyi</span> - in town</li>
	<li><span lang="rw">ku umugoroba</span> &rarr; <span lang="rw">ku mugoroba</span> - in the afternoon</li>
</ul>
<p>However if the noun is class 5 (<span lang="rw">i-</span>, <span lang="rw">iri-</span>) then the initial vowel is retained, e.g.</p>
<ul>
	<li><span lang="rw">mu ijuru</span> (pronounced <em>mw ijuru</em>) - in Heaven</li>
	<li><span lang="rw">ku isoko</span> (pronounced <em>kw isoko</em>) - at the market</li>
</ul>

<a name="variations"></a>
<h3>Common variations</h3>
<ul>
	<li>Sometimes <span lang="rw">ku+u</span> is written as <span lang="rw">kwu</span> rather than <span lang="rw">ku</span>. Likewise <span lang="rw">ku+o</span> is sometimes written as <span lang="rw">kwo</span> rather than <span lang="rw">ko</span>. This is most commonly seen in verb infinitives like <span lang="rw"><?php ku_query('kumva'); ?></span> or <span lang="rw"><?php ku_query('koga'); ?></span> which are sometimes spelled <span lang="rw">kwumva</span> and <span lang="rw">kwoga</span>.</li>
	<li>A few words commonly occur without their final syllable, such as <span lang="rw"><?php ku_query('ikizamini'); ?></span> (<span lang="rw">ikizami</span>) and <span lang="rw"><?php ku_query('imodokari'); ?></span> (<span lang="rw">imodoka</span>)</li>
</ul>
