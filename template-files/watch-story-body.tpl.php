<?php
/**
* Template file for a full text body of the watch
* https://schema.org/NewsArticle
*/
$template = <<<EOT
<div itemprop="articleBody" class="watch-story__articleBody post-content">
	$text
</div>
EOT;

echo $template;
