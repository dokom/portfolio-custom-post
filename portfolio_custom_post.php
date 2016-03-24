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
             
        <div id="portfolio-container">
            <ul class="portnav">
                <?php if($showportfolio) { ?><li><a href="#portfolio">Featured Images</a></li><?php } ?>
            </ul>
             
            <div class="port-wrapper">
         
                <?php if($showportfolio) { ?>
                     
                    <div id="port" class="port-div">
                        <ul>
                            <?php // setup the query
                            $args='&suppress_filters=true&posts_per_page='.$numberPosts.'&post_type=portfolio_posts&order=DESC&orderby='.$feedsort.$metakey;                               
                            $widget_loop = new WP_Query($args); 
                            if ($widget_loop->have_posts()) : while ($widget_loop->have_posts()) : $widget_loop->the_post(); $postcount++;
                                // if we're sorting by date and this item does not have a date, hide it
                                $older = get_post_meta(get_the_ID(), "Older", $single = true); 
                                if(($older && $feedsort=="meta_value") || ($feedsort!="meta_value")) {                                     
                                    ?>
                                    <li>                                                                                  
                                         
                                        <?php portfolio_post_date($older); //show older posts ?> 
                                         
                                        <a class="post-title" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>                                                
                                         
                                    </li>
                                     
                                <?php } ?>
                                 
                            <?php endwhile; 
                            endif; 
                            wp_reset_query(); ?> 
                             
                            <li title="View all"><a href="/portfolio/">More</a></li>
        
                        </ul>
                    </div>
                     
                <?php } ?>


        </div>
                                      
        </div>
         
    <?php
    }
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
            <input class="radio" type="radio" <?php if($instance['display']=='older-posts') { ?>checked <?php } ?>name="<?php echo $this->get_field_name( 'display' ); ?>" value="older-posts" />
            Order images from oldest to newest<br />
            <input class="radio" type="radio" <?php if($instance['display']!='older-posts') { ?>checked <?php } ?>name="<?php echo $this->get_field_name( 'display' ); ?>" value="latest" />
            Order images from newest to oldest
        </p>  
     
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



