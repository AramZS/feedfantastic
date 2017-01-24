<?php

class Watch_Mode {

	/**
	 * Starts this class, call with this function.
	 * @return [type] [description]
	 */
	public static function init() {
		static $instance;
		if ( ! is_a( $instance, 'Watch_Mode' ) ) {
			$instance = new self();
		}
		return $instance;
	}

	public function __construct(){
		$this->default_classes = 'join-the-watch';
		$this->link_to_principles = '';
		$this->watch_endpoint = 'on-watch';
		$this->site_data();
		add_action('init', array($this, 'on_watch_init') );
		add_action( 'template_redirect', array( $this, 'on_watch_view' ) );
	}


	public function on_watch_init(){
		add_rewrite_endpoint( $this->watch_endpoint, EP_PERMALINK | EP_PAGES | EP_TAGS | EP_CATEGORIES | EP_ROOT );
	}

	/**
	* embedable xml
	* @return [type] [description]
	*/
	public function on_watch_view(){
		global $wp_query;
		if ( is_home() ){
			echo $this->watch_assemble('homepage');
		}
		// if this is not a request for the view or singular object then bail
		if ( ! isset( $wp_query->query_vars[$this->watch_endpoint] ) || ! is_singular() ){
			return;
		}

		echo $this->a_watched_story(get_post());
		exit;
	}

	public function call_a_watch(){
		$term_id = get_queried_object()->term_id;
	}

	public function site_data(){
		$this->site_name = get_bloginfo('name');
	}

	public function site_info(){
		$keys = array(
			'site_name'
		);
		$info = array();
		foreach ($keys as $key){
			$info[$key] = $this->$key;
		}
		return $info;
	}

	public function site_nav(){
		$keys = array(
			'site_name'
		);
		$info = array();
		foreach ($keys as $key){
			$info[$key] = $this->$key;
		}
		return $info;
	}

	public function site_og(){
		$keys = array(
			'site_name'
		);
		$info = array();
		foreach ($keys as $key){
			$info[$key] = $this->$key;
		}
		return $info;
	}

	public function watch_assemble($term = 'homepage'){
		$posts = '';
		if ( have_posts() ) :

			// Start the loop.
			while ( have_posts() ) : the_post();

				$posts .= $this->a_watched_story(get_post())."\n";

			// End the loop.
			endwhile;

		endif;
		$site_info = $this->site_info();
		$site_info['site_nav'] = $this->site_nav();
		$site_info['page_og_data'] = $this->site_og();
		$header = $this->get_view('page/watch-page-header', $site_info);
		$body = $this->get_view('page/body/watch-home', array( 'watch_stories' => $posts) );
		$footer = $this->get_view('page/watch-page-footer', $this->site_footer() );
		return $header."\n".$body."\n".$footer;
	}

	public function a_watched_story($post, $full = true){
		$story = new A_Story_Of_The_Watch($post);
		$story_array = (array) $story;
		$story_array['byline'] = $this->get_view('watch-byline', array( 'author_byline' => 'By '.$story->item_author) );
		$story_array['featured_media'] = $this->get_view('watch-image', array( 'featured_image_src' => $story->featured_media) );
		$story_array['categories'] = '';
		foreach ($story->categories as $category){
			$story_array['categories'] .= $this->get_view('watch-category', array( 'term' => $category->category_nicename) );
		}
		$story_array['keywords'] = '';
		foreach ($story->tags as $tag){
			$story_array['keywords'] .= $this->get_view('watch-tag', array( 'term' => $tag->name) );
		}
		$story_array['hattip'] = '';
		$story_array['series'] = '';
		if ($full){
			$story_array['body'] = $this->get_view('watch-story-body', array('text' => $story->story_body));
		} else {
			$story_array['body'] = $this->get_view('watch-story-description', array('text' => $story->story_body));
		}

		$story_array['site_name'] = $this->site_name;
		$story_array['classes'] = $this->default_classes.' ';
		$story_array['link_to_principles'] = $this->link_to_principles;

		$rendered_story = $this->get_view('watch-story', $story_array);
		//var_dump($story_array);
		return $rendered_story;
	}

  	public function get_view_path( $view ){
		//var_dump(dirname( __FILE__ ) . './template-files/');
	    if ( ( file_exists( dirname( __FILE__ ) . '/template-files/' ) ) ) {
	      $template_dir = dirname( __FILE__ ) . '/template-files/';
		} else {
		  return false;
		}
	    $view_file = $template_dir . $view . '.tpl.php';
	    if ( ! file_exists( $view_file ) ){
	      return false;
	    }
	    return $view_file;
  	}

	/**
  	 * Get a given view (if it exists)
     *
     * This function is how to activate the View part of your singleton
     * plugin. But calling it with your template name and vars you pass
     * the vars to what should be a mostly non-PHP template file that takes
     * the variables and turns them into the view. The system
     * will automatically append .tpl.php to your view string, so that
     * should be the file naming pattern at the end of your template files
     * no matter where they are.
     *
     * This plugin will attempt to find the template file using the following
     * pattern:  1. It will look for a directory path set in the `setup_globals`
     * method with the class-level property key 'template_dir'; 2. It will
     * check for a folder local to the current directory (say a plugin or theme)
     * above the directory of this file with a tirectory name of template-parts;
     * 3. it will look at the common install-wide template folder located in the
     * mu-plugins directory.
     *
     * Additionally, you can force any view to use a template at the mu-plugins
     * level template folder by passing true as the third, final, paramater
     * of this method.
  	 *
  	 * @param string   $view         The slug of the view
     * @param array    $vars         An array of all variables to pass to the view.
     * @param bool     $use_mu       Controls if you want to force the use of a
     *                               system-wide mu-plugins-level template.
  	 * @return string                The view.
  	 */
  	public function get_view( $view, $vars = array(), $use_mu = false ) {
  		$view_file = $this->get_view_path($view, $use_mu);
  		if ( false === $view_file || ! file_exists( $view_file ) ){
  			return '';
  		}
        if (!empty($vars)){
  		        extract( $vars, EXTR_SKIP );
        }
  		ob_start();
  		include $view_file;
  		return ob_get_clean();
  	}
    /**
     * Echos a view.
     *
     * This function will echo the results of the `get_view` function.
     *
     * @see get_view
     */
    public function the_view( $view, $vars = array(), $use_mu = false ){
      echo $this->get_view($view, $vars, $use_mu);
    }
}

/**
 * Bootstrap
 *
 * You can also use this to get a value out of the global, eg
 *
 *    $foo = watch_tools()->bar;
 *
 * @since 1.0
 */
function watch_mode() {
    return Watch_Mode::init();
}

watch_mode();
