<?php
/**
* Template file for a watch feed
*/
$template = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
  <channel>
    <title>$title</title>
    <description>$feed_description
	</description>
    <link>$site_link</link>
    <atom:link href="$feed_link" rel="self" type="application/rss+xml"/>
    <pubDate>$pubdate</pubDate>
    <lastBuildDate>$lastBuildDate</lastBuildDate>
    <generator>Join The Watch $version</generator>
EOT;
foreach ($feed_items as $feed_story){
	$template .= $feed_story
}

$template .= <<<EOT
	</channel>
</rss>
EOT;

echo $template;
