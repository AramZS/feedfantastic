<?php
/**
* Template file for a series tag of the watch
* https://schema.org/NewsArticle
*/
$template = <<<EOT
	<a class="watch-story__part-of" href="$watch_url" itemprop="isPartOf">
		This is a story of the watch on $topic.
	</a>
EOT;

echo $template;
