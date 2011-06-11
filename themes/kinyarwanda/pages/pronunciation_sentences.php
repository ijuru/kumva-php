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
 * Purpose: Pronunciation > Sentences reference page
 */
?>   
<p>Words in a Kinyarwanda sentence interact with each other to allow the words to flow into each other. The most common interaction occurs when a word begins with a vowel and causes the preceding word to drop its final vowel. In some cases this interaction is written explicitly, like in the case of <span lang="rw"><?php ku_query('na'); ?></span> (e.g. <span lang="rw">na umugabo</span> should be written <span lang="rw">n'umugabo</span>), but in other cases the final vowel is retained in the spelling even though it is not pronounced. For example:</p>
<ul>
	<li><span lang="rw">ni umugabo</span> <i>noo-moo-ga-bo</i> <?php Widgets::sound(KUMVA_URL_THEME.'/mp3/ni_umugabo.mp3'); ?> - he is a man</li>
	<li><span lang="rw">uri umwarimu</span> <i>oo-room-wa-ree-moo</i> <?php Widgets::sound(KUMVA_URL_THEME.'/mp3/uri_umwarimu.mp3'); ?> - you are a teacher</li>
</ul>