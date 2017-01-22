<?php
/**
* Template file for opengraph data of the watch homepage
*/
$template = <<<EOT
<!-- OG data for homepage -->
<meta property="og:image" content="$featured_image" />
<meta property="og:type" content="website" />
<meta name="twitter:card" content="summary" />
<meta name="twitter:title" content="$site_title" />
<meta name="twitter:image" content="$featured_image" />
EOT;

echo $template;
