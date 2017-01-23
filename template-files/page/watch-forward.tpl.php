<?php
/**
* Template file for opengraph data of the watch article
*/
$template = $article_head;
$template .= <<<EOT
<META HTTP-EQUIV="refresh" CONTENT="'.$wait.';URL='.$link.'">
	<script type="text/javascript">console.log('You are being redirected to the source item.');</script>
</head><body></body></html>
EOT;

echo $template;
