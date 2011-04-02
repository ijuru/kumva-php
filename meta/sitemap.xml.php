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
 * Purpose: Sitemap definition XML
 */
 
require_once '../inc/kumva.php';
 
header("Content-type: text/xml");

echo '<?xml version="1.0" encoding="UTF-8" ?>';

$relationshipIds = array(Relationship::FORM, Relationship::VARIANT, Relationship::MEANING);
$tags = Dictionary::getTagService()->getTags($relationshipIds);

?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
	<url>
		<loc><?php echo KUMVA_URL_ROOT; ?></loc>
		<changefreq>daily</changefreq>
		<priority>1.0</priority>
	</url>
	<?php
	
	// Output URLs for theme pages
	foreach (Theme::getPages() as $page) {
		echo '<url>';
		echo '<loc>'.KUMVA_URL_ROOT.'/page.php?name='.$page->getName().'</loc>';
		echo '</url>';
	}
	
	// Output URLs for searchable tags
	foreach ($tags as $tag) {
		echo '<url>';
		echo '<loc>'.KUMVA_URL_ROOT.'/index.php?q='.str_replace(' ', '+', $tag->getText()).'</loc>';
		echo '</url>';
	}
	
	?>
</urlset>
