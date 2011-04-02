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
 * Purpose: Word of the day Google gadget
 */
 
include_once '../inc/kumva.php';
 
header("Content-type: text/xml");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT\n");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");

echo '<?xml version="1.0" encoding="UTF-8" ?>';

?>
<Module>
  <ModulePrefs
   title="<?php echo KUMVA_TITLE_WOTD; ?>"
   scrolling="false"
   width="300"
   height="100"
   author="Rowan Seymour"  
   author_email="rowanseymour@gmail.com"
   description="Displays a random definition from the dictionary"
   thumbnail="<?php echo KUMVA_URL_ROOT; ?>/gfx/wotd.png"
   screenshot="<?php echo KUMVA_URL_ROOT; ?>/gfx/wotd-screenshot.png">
   <Require feature="dynamic-height"/>
  </ModulePrefs>
  <UserPref name="wotd" display_name="Word of the day mode" datatype="bool" default_value="true"/>
  
  <Content type="html">
	<![CDATA[ 
	<style type="text/css"> 
	a { text-decoration: none; }
	.kumvaprefix { color: #339; font-weight: bold; }
	.kumvalemma { color: #262; font-weight: bold; } 
	.kumvamodifier { color: #339; font-weight: bold; }
	.kumvameaning { font-style: italic }
	.kumvacomment { color: #777; font-size: smaller; font-style: italic }
	</style>
	<div id="content_div"></div>

	<script type="text/javascript">
	var wotd = null; 
	
	/**
	 * Called when widget is loaded
	 */ 
	function kumva_wotd_onload() {	  	      
		var prefs = new gadgets.Prefs();
		var url = "<?php echo KUMVA_URL_ROOT; ?>/meta/rand.xml.php";

		if (prefs.getBool("wotd"))
			url += "?&mode=wotd";

		var params = {};
		params["CONTENT_TYPE"] = gadgets.io.ContentType.DOM;
		gadgets.io.makeRequest(url, kumva_wotd_oncontentarrive, params); 
	}

	/**
	 * Called when XML definition has been fetched
	 */
	function kumva_wotd_oncontentarrive(response) {
		var xmldef = response.data;
		if (xmldef == null || xmldef.firstChild == null) {
			_gel("content_div").innerHTML = "<i>Invalid data.</i>";
			return;
		}

		var prefix = kumva_getnodevalue(xmldef, "prefix");
		var lemma = kumva_getnodevalue(xmldef, "lemma");
		var modifier = kumva_getnodevalue(xmldef, "modifier");     
		var meaning = kumva_getnodevalue(xmldef, "meaning");
		var comment = kumva_getnodevalue(xmldef, "comment");

		var html = "<div style='text-align:center;padding:10px'>";
		
		html += '<a href="<?php echo KUMVA_URL_ROOT; ?>/index.php?q=' + prefix + lemma + '" target="_blank">';
		
		if (prefix != "")
			html += '<span class="kumvaprefix">' + prefix + '</span>';

		html += '<span class="kumvalemma">' + lemma + '</span>';
		html += '</a>';
		

		if (modifier != "")
			html += ' (<span class="kumvamodifier">' + modifier + '</span>)';

		html += '<br /><span class="kumvameaning">' + meaning + '</span>';   

		if (comment != "")
			html += '<br /><span class="kumvameaning">' + comment + '</span>';      

		html += "</div>";      

		// Display HTML string in <div>
		_gel('content_div').innerHTML = html;

		// Tells gadget to resize itself
		gadgets.window.adjustHeight();         		    
	}

	/**
	 * Utility function to get value of an XML node
	 */
	function kumva_getnodevalue(xml, nodeName) {
		var nodedef = xml.getElementsByTagName("definition").item(0);
		var node = nodedef.getElementsByTagName(nodeName).item(0);
		return node.firstChild ? node.firstChild.nodeValue : "";
	} 

	gadgets.util.registerOnLoadHandler(kumva_wotd_onload);
  </script>
  ]]> 
  </Content>
</Module>
