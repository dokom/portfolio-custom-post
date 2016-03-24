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
		'label' => 'Portfolio',
		'public' => true,
		'show_ui' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'rewrite' => array( 'slug' => 'portfolio' ),
		'query_var' => true,
		'supports' => array(
			'title',
			'comments',
			'editor',
			'thumbnail',
			'author',
			'page-attributes'
		),
		'taxonomies' => array(
			'post_tag',
			'category'
		)
	);
	register_post_type( 'portfolio', $args );
}
add_action( 'init', 'portfolio_posts' );


