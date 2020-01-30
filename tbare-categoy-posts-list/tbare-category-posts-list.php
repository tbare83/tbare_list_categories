<?php
/*
Plugin Name: TBare - List Categories and posts in columns
Plugin URI: http://www.tbare.com/
Description: Generates a columned list of categories and posts in that category -- [tbare_list_categories columns=3]
Author: Tim Bare
Version: 1.0.0
Author URI: http://www.tbare.com/
*/


add_shortcode( 'tbare_list_categories', 'tbarelistcatsandposts_handler' );


function tbarelistcatsandposts_handler($atts, $content = null) {
	global $post;
	
	$values = shortcode_atts( array(
        'columns'	=> '1',
    ), $atts );
	
	//for each category, show all posts
	$cat_args=array(
	  'orderby' => 'name',
	  'order' => 'ASC'
	   );
	   
	$i = 0;
	$return = "";
	$categories=get_categories($cat_args);
	  foreach($categories as $category) {
		$args=array(
		  'showposts' => -1,
		  'category__in' => array($category->term_id),
		  'caller_get_posts'=>1
		);
		$posts=get_posts($args);
		  if ($posts) {
			if($i == 0) { //first record, add CSS)
				$width = 50;
				if(esc_attr($values['columns']) <> '1') {
					$width = 100 / esc_attr($values['columns']);
				}
				$return .= '<style>
				
				#tbare-category-columns {
					display: -ms-flex;
					display: -webkit-flex;
					display: flex;
					flex-wrap: wrap;
					justify-content: center;
					padding: 0;
				}
				
				#tbare-category-columns div {
					flex-basis: '.$width.'%;
				}
				
				#tbare-category-columns ul {
					list-style: none;
					padding: 10px;
				}
				
				#tbare-category-columns ul li {
					margin-bottom: 5px;
				}
				
				#tbare-category-columns ul h4.category {
					border-bottom: 1px solid; 
					padding-bottom: 4px; 
					font-size: 14pt;
				}
				
				#tbare-category-columns ul li a {
					margin-bottom: 8px !important;
					padding-bottom: 8px;
					border-bottom: 1px dashed #d0d0d0;
					display: block;
				}
				
				@media (max-width: 400px) {
				  #tbare-category-columns {
					display: block;
				  }
				}
				</style>
				
				<div id="tbare-category-columns">';
				
				
			} // first record
			$i += 1;
			$return .= '<div><ul><li><h4 class="category">'.$category->name.'</h4></li>';
			foreach($posts as $post) {
			  setup_postdata($post);
			  $return .= '<li><a href="'.get_permalink().'" rel="bookmark" title="Permanent Link to '.$post->post_title.'">'.$post->post_title.'</a></li>';
			} // foreach($posts
			$return .= "</ul></div>";
		  } // if ($posts
		} // foreach($categories
		$return .= "</div>";
	
	return $return;
	
}



