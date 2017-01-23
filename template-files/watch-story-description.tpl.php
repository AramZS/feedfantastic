<?php
/**
* Template file for a description body of the watch
* https://schema.org/NewsArticle
*/
$template = <<<EOT
<div itemprop="description" class="watch-story__description post-preview">
	$text
</div>
EOT;

echo $template;
