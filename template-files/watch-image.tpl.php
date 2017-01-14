<?php
/**
 * Template file for an image of the watch
 */
$template = <<<EOT
<div itemprop="associatedMedia">
  <span itemscope itemtype="http://schema.org/ImageObject"> 
	<img itemprop="contentURL"
	 src="$featured_image_src"/>
</div>
EOT;

echo $template;
