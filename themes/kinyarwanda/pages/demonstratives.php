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
 * Purpose: Demonstratives reference page
 */
?>    
<p>A demonstrative is used to indicate which object we are referring to. For example, if speaking about a particular man, we can say <span lang="rw">uyu mugabo</span> 'this man' or <span lang="rw">uwo mugabo</span> 'that man'. Kinyarwanda, like some European languages distinguishes between 'that object near' and 'that object far away' giving three possible <em>spatial</em> demonstratives:</p>
	
<ul>
	<li><span lang="rw">uyu mugabo</span> - this man (in the presence of the speaker)</li>
	<li><span lang="rw">uwo mugabo</span> - that man (near the speaker)</li>
	<li><span lang="rw">uriya mugabo</span> - that man (over there, far from the speaker)</li>
</ul> 

<p>In addition to these, there is another <em>discourse</em> demonstrative for indicating objects previously mentioned:</p>

<ul>
	<li><span lang="rw">wa mugabo</span> - that man (previously mentioned)</li>
</ul> 
	
<p>In each case, the demonstrative precedes the noun and causes it to drop its initial vowel. As with adjectives, demonstratives must agree with the noun they modify, and the table below shows an example of this for each noun class:</p>

<table class="grammar">
	<tr>
		<th>#</th>
		<th>Example</th>
		<th>This</th>
		<th>That near</th>
		<th>That far</th>
		<th>That mentioned</th>
	</tr>
	<tr>
		<td>1</td>
		<td><span lang="rw"><?php ku_query('umugabo'); ?></span></td>
		<td><span lang="rw"><?php ku_query('uyu'); ?> mugabo</span> - this man</td>
		<td><span lang="rw"><?php ku_query('uwo'); ?> mugabo</span> - that man</td>
		<td><span lang="rw"><?php ku_query('uriya'); ?> mugabo</span> - that man</td>
		<td><span lang="rw"><?php ku_query('wa'); ?> mugabo</span> - that man</td>
	</tr>
	<tr>
		<td>2</td>
		<td><span lang="rw"><?php ku_query('abagore'); ?></td>
		<td><span lang="rw"><?php ku_query('aba'); ?> bagore</span> - these women</td>
		<td><span lang="rw"><?php ku_query('abo'); ?> bagore</span> - those women</td>
		<td><span lang="rw"><?php ku_query('bariya'); ?> bagore</span> - those women</td>
		<td><span lang="rw"><?php ku_query('ba'); ?> bagore</span> - those women</td>
	</tr>
	<tr>
		<td>3</td>
		<td><span lang="rw"><?php ku_query('umusozi'); ?></td>
		<td><span lang="rw"><?php ku_query('uyu'); ?> musozi</span> - this hill</td>
		<td><span lang="rw"><?php ku_query('uwo'); ?> musozi</span> - that hill</td>
		<td><span lang="rw"><?php ku_query('uriya'); ?> musozi</span> - that hill</td>
		<td><span lang="rw"><?php ku_query('wa'); ?> musozi</span> - that hill</td>
	</tr>
	<tr>
		<td>4</td>
		<td><span lang="rw"><?php ku_query('imyenda'); ?></td>
		<td><span lang="rw"><?php ku_query('iyi'); ?> myenda</span> - these clothes</td>
		<td><span lang="rw"><?php ku_query('iyo'); ?> myenda</span> - those clothes</td>
		<td><span lang="rw"><?php ku_query('iriya'); ?> myenda</span> - those clothes</td>
		<td><span lang="rw"><?php ku_query('ya'); ?> myenda</span> - those clothes</td>
	</tr>
	<tr>
		<td>5</td>
		<td><span lang="rw"><?php ku_query('ijambo'); ?></td>
		<td><span lang="rw"><?php ku_query('iri'); ?> jambo</span> - this word</td>
		<td><span lang="rw"><?php ku_query('iryo'); ?> jambo</span> - that word</td>
		<td><span lang="rw"><?php ku_query('ririya'); ?> jambo</span> - that word</td>
		<td><span lang="rw"><?php ku_query('rya'); ?> jambo</span> - that word</td>
	</tr>
	<tr>
		<td>6</td>
		<td><span lang="rw"><?php ku_query('amazi'); ?></td>
		<td><span lang="rw"><?php ku_query('aya'); ?> mazi</span> - this water</td>
		<td><span lang="rw"><?php ku_query('ayo'); ?> mazi</span> - that water</td>
		<td><span lang="rw"><?php ku_query('ariya'); ?> mazi</span> - that water</td>
		<td><span lang="rw"><?php ku_query('ya'); ?> mazi</span> - that water</td>
	</tr>
	<tr>
		<td>7</td>
		<td><span lang="rw"><?php ku_query('igitabo'); ?></td>
		<td><span lang="rw"><?php ku_query('iki'); ?> gitabo</span> - this book</td>
		<td><span lang="rw"><?php ku_query('icyo'); ?> gitabo</span> - that book</td>
		<td><span lang="rw"><?php ku_query('kiriya'); ?> gitabo</span> - that book</td>
		<td><span lang="rw"><?php ku_query('cya'); ?> gitabo</span> - that book</td>
	</tr>
	<tr>
		<td>8</td>
		<td><span lang="rw"><?php ku_query('ibirayi'); ?></td>
		<td><span lang="rw"><?php ku_query('ibi'); ?> birayi</span> - this potato</td>
		<td><span lang="rw"><?php ku_query('ibyo'); ?> birayi</span> - that potato</td>
		<td><span lang="rw"><?php ku_query('biriya'); ?> birayi</span> - that potato</td>
		<td><span lang="rw"><?php ku_query('bya'); ?> birayi</span> - that potato</td>
	</tr>
	<tr>
		<td>9</td>
		<td rowspan="2"><span lang="rw"><?php ku_query('inka'); ?></span></td>
		<td><span lang="rw"><?php ku_query('iyi'); ?> nka</span> - this cow</td>
		<td><span lang="rw"><?php ku_query('iyo'); ?> nka</span> - that cow</td>
		<td><span lang="rw"><?php ku_query('iriya'); ?> nka</span> - that cow</td>
		<td><span lang="rw"><?php ku_query('ya'); ?> nka</span> - that cow</td>
	</tr>
	<tr>
		<td>10</td>
		<td><span lang="rw"><?php ku_query('izi'); ?> nka</span> - these cows</td>
		<td><span lang="rw"><?php ku_query('izo'); ?> nka</span> - those cows</td>
		<td><span lang="rw"><?php ku_query('ziriya'); ?> nka</span> - those cows</td>
		<td><span lang="rw"><?php ku_query('za'); ?> nka</span> - those cows</td>
	</tr>
	<tr>
		<td>11</td>
		<td><span lang="rw"><?php ku_query('urwego'); ?></td>
		<td><span lang="rw"><?php ku_query('uru'); ?> rwego</span> - this ladder</td>
		<td><span lang="rw"><?php ku_query('urwo'); ?> rwego</span> - that ladder</td>
		<td><span lang="rw"><?php ku_query('ruriya'); ?> rwego</span> - that ladder</td>
		<td><span lang="rw"><?php ku_query('rwa'); ?> rwego</span> - that ladder</td>
	</tr>
	<tr>
		<td>12</td>
		<td><span lang="rw"><?php ku_query('akabande'); ?></td>
		<td><span lang="rw"><?php ku_query('aka'); ?> kabande</span> - this valley</td>
		<td><span lang="rw"><?php ku_query('ako'); ?> kabande</span> - that valley</td>
		<td><span lang="rw"><?php ku_query('kariya'); ?> kabande</span> - that valley</td>
		<td><span lang="rw"><?php ku_query('ka'); ?> kabande</span> - that valley</td>
	</tr>
	<tr>
		<td>13</td>
		<td><span lang="rw"><?php ku_query('udutsiko'); ?></td>
		<td><span lang="rw"><?php ku_query('utu'); ?> dutsiko</span> - these groups</td>
		<td><span lang="rw"><?php ku_query('utwo'); ?> dutsiko</span> - those groups</td>
		<td><span lang="rw"><?php ku_query('turiya'); ?> dutsiko</span> - those groups</td>
		<td><span lang="rw"><?php ku_query('twa'); ?> dutsiko</span> - those groups</td>
	</tr>
	<tr>
		<td>14</td>
		<td><span lang="rw"><?php ku_query('ubwato'); ?></td>
		<td><span lang="rw"><?php ku_query('ubu'); ?> bwato</span> - this boat</td>
		<td><span lang="rw"><?php ku_query('ubwo'); ?> bwato</span> - that boat</td>
		<td><span lang="rw"><?php ku_query('buriya'); ?> bwato</span> - that boat</td>
		<td><span lang="rw"><?php ku_query('bwa'); ?> bwato</span> - that boat</td>
	</tr>
	<tr>
		<td>15</td>
		<td><span lang="rw"><?php ku_query('ukuguru'); ?></td>
		<td><span lang="rw"><?php ku_query('uku'); ?> kuguru</span> - this leg</td>
		<td><span lang="rw"><?php ku_query('ukwo'); ?> kuguru</span> - that leg</td>
		<td><span lang="rw"><?php ku_query('kuriya'); ?> kuguru</span> - that leg</td>
		<td><span lang="rw"><?php ku_query('kwa'); ?> kuguru</span> - that leg</td>
	</tr>
	<tr>
		<td>16</td>
		<td><span lang="rw"><?php ku_query('ahantu'); ?></td>
		<td><span lang="rw"><?php ku_query('aha'); ?> hantu</span> - this place</td>
		<td><span lang="rw"><?php ku_query('aho'); ?> hantu</span> - that place</td>
		<td><span lang="rw"><?php ku_query('hariya'); ?> hantu</span> - that place</td>
		<td><span lang="rw"><?php ku_query('ha'); ?> hantu</span> - that place</td>
	</tr>
</table>
