<?php
/**
 * Template file for an image of the watch
 */
$template = <<<EOT
<div itemprop="associatedMedia">
	<meta itemprop="thumbnailUrl" src="$featured_image_src" />
	<span itemscope itemtype="http://schema.org/ImageObject">
		<img itemprop="contentURL"
	 	src="$featured_image_src"/>
	</span>
</div>
EOT;

echo $template;
