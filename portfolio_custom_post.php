<?php
/**
 * Plugin Name: Portfolio Custom Post
 * Plugin URI: http://phoenix.sheridanc.on.ca
 * Description: This plugin will create a custom post type displaying a portfolio through a custom post type
 * Version: 1.0
 * Authors: HY Luo, Piotr Sadujko, Bismel Khan
 */

// Create Custom Post Type
function portfolio_posts() {
	$args = array(
		'label' => 'Portfolio', // this is the name of the custom post menu label, this will be the first thing that you see on the dashboard
		'public' => true, 
		'show_ui' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'rewrite' => array( 'slug' => 'portfolio' ), // this is the category name that all the custom posts will be under
		'query_var' => true,
		'supports' => array(
			'title', // it will support the ability to add a title 
			'comments', // it will support the ability to add a comments
			'editor', // it will support the ability to add a editor
			'thumbnail', // it will support the ability to add a thumbnail
			'author', // it will support the ability to add a author
			'page-attributes' // it will support the ability to add a page-attributes
		),
		'taxonomies' => array(
			'post_tag',
			'category'
		)
	);
	register_post_type( 'portfolio', $args ); // this registers the post type title 
}
add_action( 'init', 'portfolio_posts' ); // this registers the custom post type 


// Create Widget
class portfolio_post_widget extends WP_Widget {
    function portfolio_post_widget() {
        // widget settings
        $widget_ops = array(
			'classname' => 'Featured Images',
			'description' => 'Display your most recent images.' );
        // widget control settings
        $control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => 'portfolio_post_widget' );
        // create the widget
        $this->WP_Widget( 'portfolio_post_widget', 'Featured Images', $widget_ops, $control_ops );
    }   
    function widget( $args, $instance ) {
         
        extract( $args );
 
        // User Settings
        $display = $instance['display'];
        $showportfolio = $instance['showportfolio'];
        $numberPosts = $instance['numberPosts'];        
         
        if($display=="older-posts") {
            $feedsort="meta_value";
            $metakey="&meta_key=Older";
        } else {
            $feedsort="date";
            $metakey="";
        }
