<?php
class A_Story_Of_The_Watch {

	public function __construct($post){
		$this->post = $post;
		$this->post_id = $post->ID;
		$this->title = $post->post_title;
	}

}
