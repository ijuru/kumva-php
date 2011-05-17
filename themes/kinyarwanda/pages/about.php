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
 * Purpose: About page
 */
?>
<p>This is a Kinyarwanda - English dictionary created to help people who are learning either language. What makes it different to other 
dictionaries is that the software has been designed for a Bantu language. Words are stored with prefixes, stems and modifiers, so it allows you to search for different forms of the same word. You can search for singular forms and plural forms of nouns, and present tense or past tense forms of verbs.</p>

<h2><?php echo KU_STR_COPYRIGHT; ?></h2>	
<p>The dictionary content and reference materials are released under a <a href="http://creativecommons.org/licenses/by-nc-sa/2.0/uk/">Creative Commons licence</a> which permits you to share, modify, reproduce it in any form, provided that it is not used for commercial purposes, and that you give credit to this project. The site code is released under a <a href="http://www.gnu.org/licenses/gpl.html">GNU General Public License</a> and is available for download from <a href="<?php echo KUMVA_URL_PROJECT; ?>">Github</a>.</p>

<h2><?php echo KU_STR_CREDITS; ?></h2>
<p>This dictionary is continually improved and updated by the following online team:
	<?php Widgets::userList(); ?>
</p>
<p>Some of the content has been derived from the following sources:</p>
<ul>
	<li><a href="http://marston.freemethodistchurch.org/Dictionaries.htm">Kinyarwanda-English Dictionary</a> by <i>Betty Ellen Cox.</i></li>
	<li>Kinyarwanda Dictionary by <i>Emmanuel Habumuremyi</i>.</li>
	<li><a href="http://www.africanlocalization.net/">ANLoc ICT Terminology</a> by <i>Donatien Nsengiyumva</i>, <i>Philibert Ndandali</i>, <i>Stephen Holt</i> and the <a href="http://www.kamusi.org">Kamusi</a> project, for the African Network for Localisation</li>
	<li><a href="http://uir.unisa.ac.za/handle/10500/3646">Loanword allocation in Kinyarwanda</a>, L.J. Kayigema, PhD Thesis, University of South Africa</li>
</ul>

<p>Thanks also to <i>Nshimyimana Eugene</i> for the audio recordings used in the grammar reference.</p>
