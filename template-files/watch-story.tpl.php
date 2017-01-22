<?php
/**
* Template file for a story of the watch
* https://schema.org/NewsArticle
*/
$template = <<<EOT
<article id="$slug $post_id $item_id" class="$classes watch-story" itemscope itemtype="http://schema.org/Article">
	<header>
		<h3 class="watch-story__hed" itemprop="headline">$title</h3>
		<div class="watch-story__credit">
			$byline
			<div class="watch-story__aggregator">
				<a href="publishingPrinciples" src="$link_to_principles">Aggregated</a> by <span itempprop="translator">$user_name</span> for <span itemprop="publisher">$site_name</span>
			</div>
		</div>
		<div>
		   <meta itemprop="dateCreated" content="$date"/>
		   <time pubdate="pubdate">$dateline</time>
		</div>
	</header>
	<div class="watch-story__featured-media">
		$featured_media
	</div>
	$body
	<footer>
		<div class="watch-story__metas" itemprop="keywords">
			<p itemprop="wordCount" class="watch-story__wordcount" content="$wordcount">Source story at $wordcount words.</p>
			<p>Section: $categories</p>
			<p>Tagged: $keywords</p>
		</div>
		<div class="watch-story__source">
			Source: <a itemprop="isBasedOn" href="$item_link">$item_title</a> from <em><span itemprop="sourceOrganization">$item_source</span></em>
		</div>
		<a class="watch-story__part-of" href="$watch_url" itemprop="isPartOf">This is a story of the watch on $topic.</a>
		$hattip
	</footer>
</article>
	EOT;

echo $template;
