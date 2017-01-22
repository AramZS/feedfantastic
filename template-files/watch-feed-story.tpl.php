<?php
/**
* Template file for a watch feed
*/
foreach ($categories as $category){
	$categories = "<category>$category</category>\n"
}
$template = <<<EOT
	<item>
		<title>$title</title>
		<description>$full_text</description>
		<pubDate>$pubDate</pubDate>
		<link>$link</link>
		<guid isPermaLink="true">$permalink</guid>
		$categories
	</item>
EOT;

echo $template;
