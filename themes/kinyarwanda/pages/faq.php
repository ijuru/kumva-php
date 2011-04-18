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
 * Purpose: FAQ page
 */
?>
<script type="text/javascript">
function installSearchEngine() {
	if (window.external && ("AddSearchProvider" in window.external))
		window.external.AddSearchProvider("<?php echo KUMVA_URL_ROOT; ?>/meta/opensearch.xml.php");
	else
		alert("Your browser does not support OpenSearch search engine plugins");
}

</script>

<ol>
	<li><a href="#cantfindword">Why doesn't this dictionary contain ...?</a></li>
    <li><a href="#howaccurate">How accurate is this dictionary?</a></li>
    <li><a href="#download">Can I download the dictionary?</a></li>
    <li><a href="#searchpartial">How do I search for part of a word?</a></li>
    <li><a href="#browserplugin">How can I add this dictionary to my browser's search engines?</a></li>
    <li><a href="#rudewords">Why does this dictionary contain rude words?</a></li>
	<li><a href="#loveletter">Can this dictionary help me write a love letter?</a></li>
</ol>

<h3><?php echo KU_STR_ANSWERS; ?></h3>

<ol>
	<li>
		<p><a name="cantfindword" /><b>Why doesn't this dictionary contain ...?</b><br/>
		There are many reasons why you might not be able to find the word you are looking for. Firstly, the dictionary might be missing the word. We try to spot missing words by looking at the logs of what people have searched for, and then add them if appropriate. Secondly you might be searching for a conjugated form of a verb rather than the general form. Try stripping off prefixes and suffixes, e.g. <i>ndagukunda</i> which means 'I love you' becomes just <i>kunda</i> which is the verb 'to love'.</p>
	</li>
	<li>
		<p><a name="howaccurate" /><b>How accurate is this dictionary?</b><br/>
		All dictionaries have mistakes and this dictionary is no exception. This dictionary is also very much still a work in progress. Some definitions have been copied from older dictionaries and have not yet been verified. If you find something that you think is incorrect, please <a href="feedback.php">tell us</a>.</p>
	</li>
    <li>
    	<p><a name="download" /><b>Can I download the dictionary?</b><br/>
		This dictionary is open source so <?php ku_page('feedback', 'contact us'); ?> and we can send you the data. Please remember that it is always being updated so such data will become out of date quickly, and there is no printable version of the dictionary.</p>
    </li>
    <li>
		<p><a name="searchpartial" /><b>How do I search for part of a word?</b><br/>
		You can use the * character as a wildcard, for example: 
			<ul>
				<li><a href="index.php?q=ama*">ama*</a> will match all words beginning with 'ama'</li>
				<li><a href="index.php?q=*gura">*gura</a> will match all words ending with 'gura'</li>
				<li><a href="index.php?q=*kinya*">*kinya*</a> will match all words containing 'kinya'</li>
			</ul>
		</p>
	</li>
	<li>
		<p><a name="browserplugin" /><b>How can I add this dictionary to my browser's search engines?</b><br/>
		<img src="gfx/browserplugin.png" style="float: right" />
		If you are using a browser that supports OpenSearch plugins (e.g. Firefox 2+, Internet Explorer 7+) then you can add this dictionary to your browser's search engines by clicking <a href="javascript:installSearchEngine()">here</a>.</p>
	</li>
	<li>
		<p><a name="rudewords" /><b>Why does this dictionary contain rude words?</b><br/>
		Most words that people might consider rude in one context have a legitimate meaning in another context. Therefore this dictionary strives to contain all valid words regardless of any rude connotations, but does denote these words as having such connotations.</p>
	</li>
	<li>
		<p><a name="loveletter" /><b>Can this dictionary help me write a love letter?</b><br/>
		The most popular search term is "I love you". If you are trying to write something to impress that special person, <a href="http://kinyarwanda.net/love/">this page</a> is for you.</p>
	</li>
</ol>
