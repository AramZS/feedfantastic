<?php
/**
* Template file for a header of a watch page
*/
$template = <<<EOT
<!DOCTYPE html>
<html>

  <head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>$title</title>
  <meta name="description" content="$description">

  <link rel="stylesheet" href="$site_css">
  <link rel="canonical" href="$link">
  <link rel="alternate" type="application/rss+xml" title="$site_title" href="$site_link/feed.xml">

  <meta name="author" content="$site_author" />

  <meta property="og:title" content="$site_title">
  <meta property="og:site_name" content="$site_title" />
  <meta property="og:description" content="$description">
  <meta property="og:url" content="$link" />
  <meta property="og:locale" content="en_US" />
  <meta name="twitter:site" content="$twitter_name" />
  <meta name="twitter:description" content="$description" />

$page_og_data

  	<link rel="icon" type="image/png" href="$favicon">
	<script>
	  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

	  ga('create', '$ga_id', 'auto');
	  ga('send', 'pageview');

	</script>
</head>


  <body>

	<header class="site-header">
		$site_header
	</header>
EOT;

echo $template;
