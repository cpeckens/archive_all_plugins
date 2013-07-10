<?php
/*
Plugin Name: KSAS Student/Faculty Profiles and Spotlights
Plugin URI: http://krieger2.jhu.edu/comm/web/plugins/profiles
Description: Creates a custom post type for profiles/spotlights. This plugin also creates widgets to display different profile types in the sidebar. Please remember to designate the profile type in order for the profiles to display properly.
Version: 1.0
Author: Cara Peckens
Author URI: mailto:cpeckens@jhu.edu
License: GPL2
*/

// registration code for profile post type
	function register_profile_posttype() {
		$labels = array(
			'name' 				=> _x( 'Profiles', 'post type general name' ),
			'singular_name'		=> _x( 'Profile', 'post type singular name' ),
			'add_new' 			=> _x( 'Add New', 'Profile'),
			'add_new_item' 		=> __( 'Add New Profile '),
			'edit_item' 		=> __( 'Edit Profile '),
			'new_item' 			=> __( 'New Profile '),
			'view_item' 		=> __( 'View Profile '),
			'search_items' 		=> __( 'Search Profiles '),
			'not_found' 		=>  __( 'No Profile found' ),
			'not_found_in_trash'=> __( 'No Profiles found in Trash' ),
			'parent_item_colon' => ''
		);
		
		$taxonomies = array('category');
		
		$supports = array('title','editor','revisions', 'thumbnail');
		
		$post_type_args = array(
			'labels' 			=> $labels,
			'singular_label' 	=> __('Profile'),
			'public' 			=> true,
			'show_ui' 			=> true,
			'publicly_queryable'=> true,
			'query_var'			=> true,
			'capability_type'   => 'profile',
			'capabilities' => array(
				'publish_posts' => 'publish_profiles',
				'edit_posts' => 'edit_profiles',
				'edit_others_posts' => 'edit_others_profiles',
				'delete_posts' => 'delete_profiles',
				'delete_others_posts' => 'delete_others_profiles',
				'read_private_posts' => 'read_private_profiles',
				'edit_post' => 'edit_profile',
				'delete_post' => 'delete_profile',
				'read_post' => 'read_profile',),			
			'has_archive' 		=> false,
			'hierarchical' 		=> false,
			'rewrite' 			=> array('slug' => 'profiles', 'with_front' => false ),
			'supports' 			=> $supports,
			'menu_position' 	=> 5,
			'taxonomies'		=> $taxonomies
		 );
		 register_post_type('profile',$post_type_args);
	}
	add_action('init', 'register_profile_posttype');

// registration code for profiletype taxonomy
function register_profiletype_tax() {
	$labels = array(
		'name' 					=> _x( 'Profile Types', 'taxonomy general name' ),
		'singular_name' 		=> _x( 'Profile Type', 'taxonomy singular name' ),
		'add_new' 				=> _x( 'Add New Profile Type', 'Profile Type'),
		'add_new_item' 			=> __( 'Add New Profile Type' ),
		'edit_item' 			=> __( 'Edit Profile Type' ),
		'new_item' 				=> __( 'New Profile Type' ),
		'view_item' 			=> __( 'View Profile Type' ),
		'search_items' 			=> __( 'Search Profile Types' ),
		'not_found' 			=> __( 'No Profile Type found' ),
		'not_found_in_trash' 	=> __( 'No Profile Type found in Trash' ),
	);
	
	$pages = array('profile');
				
	$args = array(
		'labels' 			=> $labels,
		'singular_label' 	=> __('Profile Type'),
		'public' 			=> true,
		'show_ui' 			=> true,
		'hierarchical' 		=> true,
		'show_tagcloud' 	=> false,
		'show_in_nav_menus' => false,
		'rewrite' 			=> array('slug' => 'profiletype', 'with_front' => false ),
	 );
	register_taxonomy('profiletype', $pages, $args);
}
add_action('init', 'register_profiletype_tax');								

function add_profiletype_terms() {
	wp_insert_term('undergraduate', 'profiletype',  array('description'=> 'Undergraduate Student Profile','slug' => 'undergraduate-profile'));
	wp_insert_term('graduate', 'profiletype',  array('description'=> 'Graduate Student Profile','slug' => 'graduate-profile'));
	wp_insert_term('spotlight', 'profiletype',  array('description'=> 'Faculty or Spotlight Feature','slug' => 'spotlight'));
	wp_insert_term('faculty', 'profiletype',  array('description'=> 'Faculty or Spotlight Feature','slug' => 'faculty-profile'));
	wp_insert_term('research', 'profiletype',  array('description'=> 'Faculty or Spotlight Feature','slug' => 'research-profile'));
}
add_action('init', 'add_profiletype_terms');

//Add pull quote box
$pullquote_7_metabox = array( 
	'id' => 'pullquote',
	'title' => 'Pull Quote',
	'page' => array('profile'),
	'context' => 'normal',
	'priority' => 'default',
	'fields' => array(

				
				array(
					'name' 			=> 'Pull Quote or Research Topic',
					'desc' 			=> 'This is the text shown on profile index page, widgets and homepage sliders',
					'id' 			=> 'ecpt_pull_quote',
					'class' 		=> 'ecpt_pull_quote',
					'type' 			=> 'textarea',
					'rich_editor' 	=> 1,			
					'max' 			=> 0,
					'std'			=> ''													
				),
												)
);			
			
add_action('admin_menu', 'ecpt_add_pullquote_7_meta_box');
function ecpt_add_pullquote_7_meta_box() {

	global $pullquote_7_metabox;		

	foreach($pullquote_7_metabox['page'] as $page) {
		add_meta_box($pullquote_7_metabox['id'], $pullquote_7_metabox['title'], 'ecpt_show_pullquote_7_box', $page, 'normal', 'default', $pullquote_7_metabox);
	}
}

// function to show meta boxes
function ecpt_show_pullquote_7_box()	{
	global $post;
	global $pullquote_7_metabox;
	global $ecpt_prefix;
	global $wp_version;
	
	// Use nonce for verification
	echo '<input type="hidden" name="ecpt_pullquote_7_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
	
	echo '<table class="form-table">';

	foreach ($pullquote_7_metabox['fields'] as $field) {
		// get current post meta data

		$meta = get_post_meta($post->ID, $field['id'], true);
		
		echo '<tr>',
				'<th style="width:20%"><label for="', $field['id'], '">', $field['name'], '</label></th>',
				'<td class="ecpt_field_type_' . str_replace(' ', '_', $field['type']) . '">';
		switch ($field['type']) {
			case 'textarea':
			
				if($field['rich_editor'] == 1) {
					if($wp_version >= 3.3) {
						echo wp_editor($meta, $field['id'], array('textarea_name' => $field['id'], 'wpautop' => false));
					} else {
						// older versions of WP
						$editor = '';
						if(!post_type_supports($post->post_type, 'editor')) {
							$editor = wp_tiny_mce(true, array('editor_selector' => $field['class'], 'remove_linebreaks' => false) );
						}
						$field_html = '<div style="width: 97%; border: 1px solid #DFDFDF;"><textarea name="' . $field['id'] . '" class="' . $field['class'] . '" id="' . $field['id'] . '" cols="60" rows="8" style="width:100%">'. $meta . '</textarea></div><br/>' . __($field['desc']);
						echo $editor . $field_html;
					}
				} else {
					echo '<div style="width: 100%;"><textarea name="', $field['id'], '" class="', $field['class'], '" id="', $field['id'], '" cols="60" rows="8" style="width:97%">', $meta ? $meta : $field['std'], '</textarea></div>', '', $field['desc'];				
				}
				
				break;
		}
		echo     '<td>',
			'</tr>';
	}
	
	echo '</table>';
}	

add_action('save_post', 'ecpt_pullquote_7_save');

// Save data from meta box
function ecpt_pullquote_7_save($post_id) {
	global $post;
	global $pullquote_7_metabox;
	
	// verify nonce
	if (!isset($_POST['ecpt_pullquote_7_meta_box_nonce']) || !wp_verify_nonce($_POST['ecpt_pullquote_7_meta_box_nonce'], basename(__FILE__))) {
		return $post_id;
	}

	// check autosave
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return $post_id;
	}

	// check permissions
	if ('page' == $_POST['post_type']) {
		if (!current_user_can('edit_page', $post_id)) {
			return $post_id;
		}
	} elseif (!current_user_can('edit_post', $post_id)) {
		return $post_id;
	}
	
	foreach ($pullquote_7_metabox['fields'] as $field) {
	
		$old = get_post_meta($post_id, $field['id'], true);
		$new = $_POST[$field['id']];
		
		if ($new && $new != $old) {
			if($field['type'] == 'date') {
				$new = ecpt_format_date($new);
				update_post_meta($post_id, $field['id'], $new);
			} else {
				update_post_meta($post_id, $field['id'], $new);
				
				
			}
		} elseif ('' == $new && $old) {
			delete_post_meta($post_id, $field['id'], $old);
		}
	}
}

//register widgets
add_action('widgets_init', 'ksas_register_profile_widgets');
	function ksas_register_profile_widgets() {
		register_widget('Profile_Widget');
	}
class Profile_Widget extends WP_Widget {
	function Profile_Widget() {
		$widget_options = array( 'classname' => 'ksas_profile', 'description' => __('Displays a random profile', 'ksas_profile') );
		$control_options = array( 'width' => 300, 'height' => 350, 'id_base' => 'ksas_profile-widget' );
		$this->WP_Widget( 'ksas_profile-widget', __('Bulletin Board', 'ksas_profile'), $widget_options, $control_options );
	}

	/* Widget Display */
	function widget( $args, $instance ) {
		extract( $args );

		/* Our variables from the widget settings. */
		$title = apply_filters('widget_title', $instance['title'] );
		$category_choice = $instance['category_choice'];
		echo $before_widget;

		/* Display the widget title if one was input (before and after defined by themes). */
		if ( $title )
			echo $before_title . $title . $after_title;
			global $switched;
		$bulletin_query = new WP_Query(array(
					'post_type' => 'profile',
					'profiletype' => $category_choice,
					'orderby' => 'rand'
					'posts_per_page' => 1));
		if ( $bulletin_query->have_posts() ) :  while ($bulletin_query->have_posts()) : $bulletin_query->the_post(); ?>
				<article class="row">
					<div class="twelve columns">
						<a href="<?php the_permalink(); ?>">
							<p><b><?php the_title(); ?></b></br>
							<?php echo get_post_meta($post->ID, 'ecpt_pull_quote', true); ?></p>
						</a>
					</div>
				</article>
		<?php endwhile; endif; echo $after_widget;
	}

	/* Update/Save the widget settings. */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* Strip tags for title and name to remove HTML (important for text inputs). */
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['category_choice'] = $new_instance['category_choice'];

		return $instance;
	}

	/* Widget Options */
	function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults = array( 'title' => __('Bulletin Board', 'ksas_profile'), 'quantity' => __('3', 'ksas_profile'), 'category_choice' => '1' );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'hybrid'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
		</p>


		<!-- Choose Profile Type: Select Box -->
		<p>
			<label for="<?php echo $this->get_field_id( 'category_choice' ); ?>"><?php _e('Choose Profile Type:', 'ksas_profile'); ?></label> 
			<select id="<?php echo $this->get_field_id( 'category_choice' ); ?>" name="<?php echo $this->get_field_name( 'category_choice' ); ?>" class="widefat" style="width:100%;">
			<?php global $wpdb;
				$categories = get_categories(array(
								'orderby'                  => 'name',
								'order'                    => 'ASC',
								'hide_empty'               => 1,
								'taxonomy' => 'profiletype'));
		    foreach($categories as $category){
		    	$category_choice = $category->slug;
		        $category_title = $category->name; ?>
		       <option value="<?php echo $category_choice; ?>" <?php if ( $category_choice == $instance['category_choice'] ) echo 'selected="selected"'; ?>><?php echo $category_title; ?></option>
		    <?php } ?>
			</select>
		</p>
	<?php
	}
}

?>