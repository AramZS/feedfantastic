<?php
class A_Story_Of_The_Watch {

	public function __construct($post){
		$this->post = $post;
		$this->build_from_post($post);
	}

	public function build_from_post($post){
		$this->post_id = $post->ID;
		$this->title = $post->post_title;
		$this->slug = $post->post_name;
		$this->datetime = watch_tools()->date_maker($post->post_date_gmt);
		$this->datetime->setTimezone(new DateTimeZone('America/New_York'));
		$this->date = $this->datetime->format('Y-m-d H:i:s');
		$this->dateline = $this->datetime->format('l, F j Y. g:i a');
		$this->story_body = $post->post_content;
		$this->story_description = strange_excerpt($post->post_content);
		$this->aggregater_object = get_user_by('id', $post->post_author);
		$this->aggregater = $this->aggregater_object->display_name;
		$this->featured_media = get_the_post_thumbnail_url($post);
		$this->permalink = get_post_permalink($post->ID);
		$this->categories = get_the_category($post->ID);
		$this->tags = get_the_tags($post->ID);
		$this->wordcount = str_word_count($this->story_body);
		$this->build_from_pf_meta();
	}

	public function build_from_pf_meta(){
		$this->item_id = $this->get_pf_meta('item_id');
		$this->item_link = $this->get_pf_meta('item_link');
		$this->item_source = $this->get_pf_meta('source_title');
		$this->item_author = $this->get_pf_meta('item_author');
	}

	public function get_pf_meta($key){
		return pressforward('controller.metas')->get_post_pf_meta($this->post_id, $key, true);
	}

}
