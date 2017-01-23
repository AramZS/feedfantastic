<?php
/**
 * @package Feed Fantastic
 * @version 2
 */
/*
Plugin Name: Feed Fantastic
Plugin URI: http://chronoto.pe
Description: Whatev
Author: Aram ZS
Version: 2.0.0
Author URI: http://aramzs.me
*/

add_filter( 'the_permalink', function( $url ){
	if ( isset($_GET['for']) && 'fb' === $_GET['for'] ){
		$url = add_query_arg( array(
			'utm_source'	=>	'facebook',
			'utm_medium'	=>	'rss',
			'utm_campaign'	=>	'fbpage'
		), $url);
	}
	return $url;
});

add_filter( 'post_link', function( $url ){
	if ( isset($_GET['for']) && 'select' === $_GET['for'] ){
		$url = add_query_arg( array(
			'utm_source'	=>	'facebook',
			'utm_medium'	=>	'rss',
			'utm_campaign'	=>	'selectnews'
		), get_the_item_link());
	}
	return $url;
});

add_filter( 'post_link', function( $url ){
	if ( isset($_GET['summary']) && 'watch' === $_GET['summary'] && is_tag() ){
		$url = add_query_arg( array(
			'utm_source'	=>	get_queried_object()->slug,
			'utm_medium'	=>	'rss',
			'utm_campaign'	=>	'watch'
		), get_the_item_link());
	}
	return $url;
});

function ff_for_you(){
	if ( (isset($_GET['for'])) && (('fb' === $_GET['for']) || ('select' === $_GET['for']) ) || ( (isset($_GET['summary'])) ) ){
		return true;
	} else {
		return false;
	}
}

function strange_excerpt( $content ){
	if ( ff_for_you() ){
		global $strange_excerpt;
		if ( 1 === $strange_excerpt){
			$strange_excerpt = 2;
			return $content;
		}
		$text = $content;
		$text = str_replace('\]\]\>', ']]&gt;', $text);
		$text = preg_replace('@<script[^>]*?>.*?</script>@si', '', $text);
		$text = strip_tags($text, '<p>');
		$pattern = "/<p[^>]*><\\/p[^>]*>/";
		$pattern = "/<[^\/>]*>([\s]?)*<\/[^>]*>/";  //use this pattern to remove any empty tag
		$text = preg_replace($pattern, '', $text);
		$text = str_replace("&nbsp;", '', $text);
		$text = trim($text);
		$clean_content = $text;
		$excerpt_length = 80; //Would prefer a char count. Not sure how to do it.
		$words = explode(' ', $text, ($excerpt_length + 1) );
		if (count($words) > $excerpt_length) {
		  array_pop($words);
		  array_push($words, '...');
		  $text = implode(' ', $words);
		}

		preg_match_all("/<p[^>]*>(.*)<\/p>/", $text, $matches);

		$coin_flip = random_int(1,2);
		if ( ( true === false ) && (count($matches[0]) > 1) && $coin_flip > 1){
			$text = str_replace($matches[0][0],'',$text);
		} else {
			preg_match_all("/<p[^>]*>(.*)<\/p>/", $clean_content, $matches_two);
			$first_graf = atest_closetags(array_shift($matches_two[1]));
			$source_statement = array_pop($matches_two[1]);
//var_dump($source_statement, strpos($source_statement, 'Source'));
			if ( false === strpos($source_statement, 'Source') ){
				array_push($matches_two[1], $source_statement);
			}
			foreach ($matches_two[1] as $key=>$text){
				if ( (stripos($text, 'Follow') >= 0) && (stripos($text, 'Twitter') > 0) ){
					unset($matches_two[1][$key]);
				} else if ( stripos($text, ' ') === 0 ) {
					unset($matches_two[1][$key]);
				} else if ( !validate_graf($text) ){
					unset($matches_two[1][$key]);
				}
			}
//var_dump($matches_two[1]); die();
//var_dump( $matches_two );
			$total_grafs = (count($matches_two[1]))-1;
			//var_dump($total_grafs);
			if ( $total_grafs >= 0 ){
				$rand_graf = random_int(0, $total_grafs);
				//var_dump($matches_two[1]);
				$restart = false;
				while (!array_key_exists( $rand_graf, $matches_two[1] )) {
					if ($rand_graf >= count($matches_two[1])){
						if ($restart){
							return $first_graf;
						}
						$rand_graf = 0;
						$restart = true;
					}
					$rand_graf = $rand_graf+1;
				}
				$text = $matches_two[1][$rand_graf];
				$text = atest_closetags($text);
				$next_graf = $rand_graf+1;
				$restart = false;
				$back_walk = false;
				$pre_next = '';
				while (!array_key_exists( $next_graf, $matches_two[1] )) {
					if ($next_graf >= count($matches_two[1]) ){
						if ($restart){
							$back_walk = true;
							break;
						}
						$next_graf = 0;
						$restart = true;
					}
					$pre_next = ' [...]';
					$next_graf = $next_graf+1;
				}

				$prev_graf = $rand_graf-1;
				$restart = false;
				$end_walk = false;
				$post_last = '';
				while (!array_key_exists( $prev_graf, $matches_two[1] )) {
					if ($prev_graf <= 0 ){
						$end_walk = true;
						break;
					}
					$post_last = ' [...]';
					$prev_graf = $prev_graf-1;
				}
				if ( !$back_walk && !empty($matches_two[1][$next_graf])){
					$text .= $pre_next."\r\n\r\n".'"'.atest_closetags($matches_two[1][$next_graf]);
				} else if ( !$end_walk && !empty($matches_two[1][$rand_graf-1])){
					$text = atest_closetags($matches_two[1][$rand_graf-1]).$post_last."\r\n\r\n".'"'.$text;
				}
				while (empty($text) || ( $text == '<p></p>' ) ){
					unset($matches_two[1][$rand_graf]);
					$total_grafs = $total_grafs-1;
					if (($total_grafs) < 0){
						$text = atest_closetags($first_graf);
						break;
					} else {
						$rand_graf = random_int(0, ($total_grafs));
						$text = atest_closetags($matches_two[1][$rand_graf]);
					}
				}
			} else if (count($matches_two[1]) > 0) {
				$text = atest_closetags(array_shift($matches_two[1]));
			} else {
				$text = $first_graf;
			}
		}
//var_dump($text);
		$text = strip_tags($text);
		//$text = wpautop($text);
		//$text = preg_replace('/^.+\n/', '', $text);
//var_dump($text);
		$text = preg_replace('/<p[^>]*>(.*)<\/p[^>]*>/i', '$1', $text);
		$text = trim($text);
		//$categories = get_the_category();
		//$cat = "Filed to ".$categories[0]->name.": \n\n";
		return wpautop('"'.$text.'"');
	}
	return $content;
}


add_filter( 'the_content', 'strange_excerpt', 11 );

function unstrange_excerpt( $excerpt ){
	if ( ff_for_you() ){
		global $strange_excerpt;
		$strange_excerpt = 1;
	}
	return $excerpt;
}

add_filter( 'get_the_excerpt', 'unstrange_excerpt', 9 );

function atest_closetags($html) {
	$html = str_replace('"', "'", $html);
	$html = str_replace('“', "'", $html);
	$html = str_replace('”', "'", $html);
	$html = str_replace(array('<article', '</article>'), array('<div', '</div>'), $html);
	$html = str_replace(array('<!--', '-->'), array('<span class="commented-out-html" style="display:none;">', '</span>'), $html);
    $tags_and_content_to_strip = Array("title","script","link","meta","img");
    foreach ($tags_and_content_to_strip as $tag) {
           $html = preg_replace("/<" . $tag . ">(.|\s)*?<\/" . $tag . ">/","",$html);
		   $html = preg_replace("/<" . $tag . " (.|\s)*?>/","",$html);
    }
	#$html = preg_match_all('#<(article)*>#', $html, $resultc);
  #put all opened tags into an array
  preg_match_all('#<([a-z]+)(?: .*)?(?<![/|/ ])>#iU', $html, $result);
  $openedtags = $result[1];   #put all closed tags into an array
  preg_match_all('#</([a-z]+)>#iU', $html, $result);
  $closedtags = $result[1];
  $len_opened = count($openedtags);
  preg_match_all('#<(em|strong)*/>#', $html, $resultc);
  $malformedtags = $resultc[1];
  //print_r('Count <br />');
  foreach ($malformedtags as $tag){
	if ($tag == 'em'){
		$html .= '</em>';
	}
	if ($tag == 'strong'){
		$html .= '</strong>';
	}
  }
  # all tags are closed
  if (count($closedtags) == $len_opened) {
    return $html;
  }
  $openedtags = array_reverse($openedtags);
  # close tags
  for ($i=0; $i < $len_opened; $i++) {
    if (!in_array($openedtags[$i], $closedtags)){
      $html .= '</'.$openedtags[$i].'>';
    } else {
      unset($closedtags[array_search($openedtags[$i], $closedtags)]);    }
  }
  //print_r($html);
  return $html;
}

function validate_graf($graf){
	$cases = array(
		'Read more',
		'Continue reading',
		'Follow',
		'About the author',
		'Send',
		'story is developing',
		'Related',
		'Photo',
		'Photo By',
		'WireImage',
		'Getty',
		'Originally published',
		'-',
		'via',
		'—',
		'This is a breaking news story',
		'(',
		'[',
		'Send a letter',
		'Images via',
		'ID',
		'Related Story',
		'Watch',
		'More Reading',
		'Also on',
		"And don't forget",
		"Update:",
		'Source',
		'Figure',
		'Edit',
		'Addition',
		'PGP Fingerprint',
		'Catch up',
		'Subscribe',
		'Image',
		'Photographer',
		'Photo',
		'Flickr',
		'Getty',
		'storytext'

	);
	//var_dump($graf);
	$words = explode(" ", $graf);
	$wordcount = count($words);
	//var_dump($wordcount);
	if ( !$wordcount || $wordcount < 6 ){
		//var_dump($graf);
		return false;
	}
	foreach ($cases as $case){
		//var_dump($case);
		//var_dump(stripos($graf, $case));
		if (0 === stripos($graf, $case)){
			return false;
		}
	}
	//var_dump(stripos($graf, '<img'));
	if (stripos($graf, '<img') !== false){
		return false;
	}

	return true;


}
require_once "watch-tools.php";
require_once "a-story-of-the-watch.php";
require_once "call-the-watch.php";
require_once "watch-mode.php";
