<?php
/**
* Template file for a watch homepage
*/
$template = <<<EOT
    <div class="page-content">
      	<div class="wrapper">
        	<div class="home">

	  			<h1 class="page-heading">Now Watching:</h1>

	  			<div class="post-list">
					$watch_stories
				</div>
			</div>
		</div>
	</div>
EOT;

echo $template;
