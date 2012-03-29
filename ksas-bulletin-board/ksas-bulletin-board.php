<?php
/*
Plugin Name: KSAS Bulletin Board
Plugin URI: http://krieger2.jhu.edu/comm/web/plugins/bulletin-board
Description: Creates a custom post type for bulletins.  Link to http://siteurl/archive-bulletinboard.php to display bulletins.  Bulletins do not display in homepage news feed. Plugin also creates a widget to display bulletins in sidebars.  Use in conjunction with Post Expirator plugin if you want bulletins to automatically expire/archive/delete.
Version: 1.0
Author: Cara Peckens
Author URI: mailto:cpeckens@jhu.edu
License: GPL2
*/

// registration code for bulletinboard post type
	function register_bulletinboard_posttype() {
		$labels = array(
			'name' 				=> _x( 'Bulletins', 'post type general name' ),
			'singular_name'		=> _x( 'Bulletin', 'post type singular name' ),
			'add_new' 			=> _x( 'Add New', 'Bulletin'),
			'add_new_item' 		=> __( 'Add New Bulletin '),
			'edit_item' 		=> __( 'Edit Bulletin '),
			'new_item' 			=> __( 'New Bulletin '),
			'view_item' 		=> __( 'View Bulletin '),
			'search_items' 		=> __( 'Search Bulletins '),
			'not_found' 		=>  __( 'No Bulletin found' ),
			'not_found_in_trash'=> __( 'No Bulletins found in Trash' ),
			'parent_item_colon' => ''
		);
		
		$taxonomies = array();
		
		$supports = array('title','editor','thumbnail','excerpt','revisions');
		
		$post_type_args = array(
			'labels' 			=> $labels,
			'singular_label' 	=> __('Bulletin'),
			'public' 			=> true,
			'show_ui' 			=> true,
			'publicly_queryable'=> true,
			'query_var'			=> true,
			'capability_type'   => 'bulletin',
			'map_meta_cap'      => true,			
			'has_archive' 		=> true,
			'hierarchical' 		=> false,
			'rewrite' 			=> array('slug' => 'bulletin_board', 'with_front' => false ),
			'supports' 			=> $supports,
			'menu_position' 	=> 5,
			'taxonomies'		=> $taxonomies
		 );
		 register_post_type('bulletinboard',$post_type_args);
	}
	add_action('init', 'register_bulletinboard_posttype');

//Register bulletin board widget
add_action('widgets_init', 'ksas_register_bulletin_widgets');
	function ksas_register_bulletin_widgets() {
		register_widget('Bulletin_Board_Widget');
	}

// Define bulletin board widget
class Bulletin_Board_Widget extends WP_Widget {

	function Bulletin_Board_Widget() {
		$widget_ops = array('classname' => 'widget_bulletin_board', 'description' => __( "Bulletin Board Widget") );
		$this->WP_Widget('bulletin-board-widget', 'Bulletin Board Widget', $widget_ops);
	}

	function widget( $args, $instance ) {
		extract($args);
		$title = apply_filters('widget_title', $instance['title'], $instance, $this->id_base);

		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title;                     
	

		global $post; ?>
		<?php global $wp_query;
			$bulletin_board_query = new WP_Query("post_type=bulletinboard&post_status=publish&posts_per_page=5"); ?>
<div class="bulletinboard">
    	<h3><img src="<?php bloginfo('template_url'); ?>/assets/img/arrow.png" width="25" height="25" />Bulletin Board</h3>
					<?php while ($bulletin_board_query->have_posts()) : $bulletin_board_query->the_post(); ?>
    	
    	<h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
    	<?php the_excerpt(); ?>
    	
	
	
	<?php endwhile; ?>
	<p align="right"><a href="<?php bloginfo('url'); ?>/bulletin_board">View all &gt;&gt;</a></p>
</div>


<?php echo $after_widget;

	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '') );
		$title = $instance['title'];
?>
		
<?php
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$new_instance = wp_parse_args((array) $new_instance, array( 'title' => ''));
		$instance['title'] = strip_tags($new_instance['title']);
		return $instance;
	}

}

?>