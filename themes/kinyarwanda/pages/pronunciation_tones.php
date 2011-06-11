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
 * Purpose: Pronunciation > Tones reference page
 */
?> 
<p>There exist several different systems (<span lang="rw"><?php ku_query('amasaku'); ?></span>) for describing the tones of a Kinyarwanda word. This dictionary uses the <i>scientific system</i> which is the official system, and what is taught in Rwandan schools. This system uses only a circumflex (&circ;) to show the tone, but duplicates vowels to show long vowels.</p>  

<table class="grammar">
	<tr>
		<th>Vowel</th>
		<th>Length</th>
		<th>Tone</th>
		<th>Examples</th>
	</tr>
	<tr>
		<td align="center">a</td>
		<td align="center">Short</td>
		<td align="center">Low</td>
		<td>
			<span lang="rw"><?php ku_query('umugabo'); ?></span> &rarr; <i>umugabo</i> <?php Widgets::sound(2337); ?>,
			<span lang="rw"><?php ku_query('inzu'); ?></span> &rarr; <i>inzu</i> <?php Widgets::sound(3994); ?>,
			<span lang="rw"><?php ku_query('kubara'); ?></span> &rarr; <i>kubara</i> <?php Widgets::sound(65); ?>
		</td>
	</tr>
	<tr>
		<td align="center">&acirc;</td>
		<td align="center">Short</td>
		<td align="center">High</td>
		<td>
			<span lang="rw"><?php ku_query('umugore'); ?></span> &rarr; <i>umugor&ecirc;</i> <?php Widgets::sound(2451); ?>,
			<span lang="rw"><?php ku_query('inka'); ?></span> &rarr; <i>ink&acirc;</i> <?php Widgets::sound(2728); ?>,
			<span lang="rw"><?php ku_query('kubona'); ?></span> &rarr; <i>kub&ocirc;na</i> <?php Widgets::sound(97); ?>
		</td>
	</tr>
	<tr>
		<td align="center">aa</td>
		<td align="center">Long</td>
		<td align="center">Low</td>
		<td>
			<span lang="rw"><?php ku_query('umuntu'); ?></span> &rarr; <i>umuuntu</i> <?php Widgets::sound(3039); ?>,
			<span lang="rw"><?php ku_query('intama'); ?></span> &rarr; <i>intaama</i> <?php Widgets::sound(3676); ?>,
			<span lang="rw"><?php ku_query('kugenda'); ?></span> &rarr; <i>kugeenda</i> <?php Widgets::sound(287); ?>
		</td>
	</tr>
	<tr>
		<td align="center">a&acirc;</td>
		<td align="center">Long</td>
		<td align="center">Rising</td>
		<td>
			<span lang="rw"><?php ku_query('umukobwa'); ?></span> &rarr; <i>umuko&ocirc;bwa</i> <?php Widgets::sound(2830); ?>,
			<span lang="rw"><?php ku_query('indege'); ?></span> &rarr; <i>inde&ecirc;ge</i> <?php Widgets::sound(2204); ?>
		</td>
	</tr>
	<tr>
		<td align="center">&acirc;a</td>
		<td align="center">Long</td>
		<td align="center">Falling</td>
		<td>
			<span lang="rw"><?php ku_query('umwana'); ?></span> &rarr; <i>umw&acirc;ana</i> <?php Widgets::sound(1921); ?>,
			<span lang="rw"><?php ku_query('inyota'); ?></span> &rarr; <i>iny&ocirc;ota</i> <?php Widgets::sound(3127); ?>,
			<span lang="rw"><?php ku_query('guteka'); ?></span> &rarr; <i>gut&ecirc;eka</i> <?php Widgets::sound(1277); ?>
		</td>
	</tr>
	<tr>
		<td align="center">&acirc;&acirc;</td>
		<td align="center">Long</td>
		<td align="center">High</td>
		<td>&nbsp;</td>
	</tr>
</table>
