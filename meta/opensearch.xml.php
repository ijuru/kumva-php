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
 * Purpose: OpenSearch definition XML
 */
 
include_once '../inc/kumva.php';
 
header("Content-type: text/xml");

echo '<?xml version="1.0" encoding="UTF-8" ?>';

?>
<OpenSearchDescription xmlns="http://a9.com/-/spec/opensearch/1.1/" xmlns:moz="http://www.mozilla.org/2006/browser/search/">
  <ShortName><?php echo KUMVA_TITLE_SHORT; ?></ShortName>
  <Description><?php echo KUMVA_TITLE_LONG; ?></Description>
  <Url type="text/html" method="get" template="<?php echo KUMVA_URL_ROOT; ?>/index.php?q={searchTerms}&amp;ref=os" />
  <Url type="application/x-suggestions+json" template="<?php echo KUMVA_URL_ROOT; ?>/meta/suggest.php?term={searchTerms}&amp;format=opensearch" />
  <Contact>rowanseymour@gmail.com</Contact>
  <Image width="16" height="16"><?php echo KUMVA_URL_THEME; ?>/gfx/favicon.ico</Image>
  <Developer>Rowan Seymour</Developer>
  <InputEncoding>UTF-8</InputEncoding>
  <moz:SearchForm><?php echo KUMVA_URL_ROOT; ?></moz:SearchForm>
  <Url type="application/opensearchdescription+xml" rel="self" template="<?php echo KUMVA_URL_ROOT; ?>/meta/opensearch.xml.php" />
  <moz:UpdateUrl><?php echo KUMVA_URL_ROOT; ?>/meta/opensearch.xml.php</moz:UpdateUrl>
</OpenSearchDescription>
