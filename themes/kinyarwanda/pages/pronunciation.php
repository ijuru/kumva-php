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
 * Purpose: Pronunciation reference page
 */
?>   
<p>In some ways it's easier to ascertain to pronunciation of a Kinyarwanda word from it's written form than an English word. Because Kinyarwanda has only been a written language for about a hundred years, the pronunciation of different letters is almost completely consistent throughout the language. Even words that are borrowed from other languages are given a proper Kinyarwanda spelling (e.g. <?php ku_query('sock'); ?> &rarr; <span lang="rw"><?php ku_query('isogisi'); ?></span>).</p>

<p>However, Kinyarwanda is a tonal language and the tones can completely change the meaning of a word (e.g. <span lang="rw"><?php ku_query('gusura'); ?></span>). To be properly understood you will need to learn to distinguish different tones. 
</p>

<?php Widgets::tableOfContents($this); ?>