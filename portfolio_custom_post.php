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


    // HTML Output
        ?>
             
        <div id="side-grid">
		<?php
			$paged = ( get_query_var ( 'page' ) );
			$query = new WP_Query(
				array(
					'post_type' => 'portfolio', // This calls the Custom Post Type
					'posts_per_page' => $numberPosts, // This calls for the amount of posts you want to display on one page
					'order_by' => ASC // This will make it so the most recent posts show up first
				)
			);
			
			$postcount = 1;
			$counter = 2;
			
			if( $query->have_posts() ) : while( $query->have_posts() ) : $query->the_post();
                
				if( $postcount == 1 ) : ?>
				<div class="left">
					<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
						<?php the_post_thumbnail(); ?>
					</a>
					<h2 class="portfolio-title"><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
						<?php the_title(); ?>
					</a></h2>
                 </div>
				 
			<?php elseif( $postcount == $counter ) : ?>
				 
				<div class="right">
					<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
						<?php the_post_thumbnail(); ?>
					</a>
					<h2 class="portfolio-title"><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
						<?php the_title(); ?>
					</a></h2>
                 </div>
				 <div class="clear"></div>
                     
            <?php
				$postcount == 0;
				endif;
				$postcount++;
				
				endwhile;
				endif;
			?>
			
			</div>
                     
        <?php }
    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
         
        $display = $instance['display'];
        $showportfolio = $instance['showportfolio'];
        $numberPosts = $instance['numberPosts'];        
 
        // Strip tags and update settings
        $instance['display'] = strip_tags( $new_instance['display'] );
        $instance['showportfolio'] = isset( $new_instance['showportfolio'] );
        $instance['numberPosts'] = strip_tags( $new_instance['numberPosts'] );
 
        return $instance;
    }
    function form( $instance ) {
 
        // Default settings
        $defaults = array( 'sort' => 'latest', 'showportfolio' => true, 'numberPosts' => 4 );
        $instance = wp_parse_args( (array) $instance, $defaults ); ?>
         
       
     
        <p>
            <input class="checkbox" type="checkbox" <?php checked(isset( $instance['showportfolio']) ? $instance['showportfolio'] : 0  ); ?> id="<?php echo $this->get_field_id( 'showportfolio' ); ?>" name="<?php echo $this->get_field_name( 'showportfolio' ); ?>" />
            Display 
            <input id="<?php echo $this->get_field_id( 'numberPosts' ); ?>" name="<?php echo $this->get_field_name( 'numberPosts' ); ?>" value="<?php echo $instance['numberPosts']; ?>" style="width:30px" />
            Images                    
             
        </p>   
         
        <?php
    }
}

add_action( 'widgets_init', function() {
	register_widget( 'portfolio_post_widget' );
} );



