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
	if ( 'fb' === $_GET['for'] ){
		$url = add_query_arg( array(
			'utm_source'	=>	'facebook',
			'utm_medium'	=>	'rss',
			'utm_campaign'	=>	'fbpage'
		), $url);
	}
	return $url;
});

function strange_excerpt( $content ){
	if ( (isset($_GET['for'])) && ('fb' === $_GET['for']) ){
		global $strange_excerpt;
		if ( 1 === $strange_excerpt){
			$strange_excerpt = 2;
			return $content;
		}
		$text = atest_closetags($content);
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
		$words = explode(' ', $text, $excerpt_length + 1);
		if (count($words)> $excerpt_length) {
		  array_pop($words);
		  array_push($words, '...');
		  $text = implode(' ', $words);
		}

		preg_match_all("/<p[^>]*>(.*)<\/p>/", atest_closetags($text), $matches);

		$coin_flip = random_int(1,2);
		if ( ( true === false ) && (count($matches[0]) > 1) && $coin_flip > 1){
			$text = str_replace($matches[0][0],'',$text);
		} else {
			preg_match_all("/<p[^>]*>(.*)<\/p>/", atest_closetags($clean_content), $matches_two);
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
//var_dump( $matches_two );
			$total_grafs = (count($matches_two[1]))-1;
			if ( $total_grafs > 0 ){
				$rand_graf = random_int(0, $total_grafs);
//var_dump($rand_graf);
				$text = atest_closetags($matches_two[1][$rand_graf]);
				if (!empty($matches_two[1][$rand_graf+1])){
					$text .= "\r\n\r\n".'"'.atest_closetags($matches_two[1][$rand_graf+1]);
				} else if (!empty($matches_two[1][$rand_graf-1])){
					$text = atest_closetags($matches_two[1][$rand_graf-1])."\r\n\r\n".'"'.$text;
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
	if ( (isset($_GET['for'])) && ('fb' === $_GET['for']) ){
		global $strange_excerpt;
		$strange_excerpt = 1;
	}
	return $excerpt;
}

add_filter( 'get_the_excerpt', 'unstrange_excerpt', 9 );

function atest_closetags($html) {
	$html = str_replace('"', "'", $html);
	$html = str_replace(array('<article', '</article>'), array('<div', '</div>'), $html);
	$html = str_replace(array('<!--', '-->'), array('<span class="commented-out-html" style="display:none;">', '</span>'), $html);
    $tags_and_content_to_strip = Array("title","script","link","meta");
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
		'Send a letter',
		'Images via',
		'ID',
		'Related Story',
		'Watch',
		'More Reading',
		'Also on',
		"And don't forget",
		"Update:",

	);
	$words = explode(" ", $graf);
	$wordcount = count($words);
	if ( $wordcount < 6 ){
		return false;
	}
	foreach ($cases as $case){
		if (0 == stripos($graf, $case)){
			return false;
		}
	}

	return true;


}
