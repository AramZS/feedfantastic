<?php
/**
* Template file for opengraph data of the watch homepage
*/
$template = <<<EOT
	<!-- Article specific OG data -->
  	<meta property="og:type" content="article" />
  	<meta property="article:published_time" content="$pubDate" />

  	<meta property="article:author" content="$author_facebook_url" />
    <meta property="article:publisher" content="$publisher_facebook_url" />

  	<meta property="article:section" content="Fight" />

	<meta property="article:tag" content="politics" />

	<meta property="article:tag" content="tools" />

	<meta property="article:tag" content="humans" />


  	<meta name="twitter:card" content="summary_large_image" />
  	<meta name="twitter:creator" content="$author_twitter_name" />
  	<meta name="twitter:title" content="$title" />

  	<meta property="og:image" content="$featured_image" />
  	<meta name="twitter:image" content="$featured_image" />
EOT;

echo $template;
