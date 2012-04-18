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
		
		$taxonomies = array();
		
		$supports = array('title','editor','revisions');
		
		$post_type_args = array(
			'labels' 			=> $labels,
			'singular_label' 	=> __('Profile'),
			'public' 			=> true,
			'show_ui' 			=> true,
			'publicly_queryable'=> true,
			'query_var'			=> true,
			'capability_type'   => 'profile',
			'map_meta_cap'      => true,			
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
	wp_insert_term('undergraduate', 'profiletype',  array('description'=> 'Undergraduate Student Profile','slug' => 'undergraduate'));
	wp_insert_term('graduate', 'profiletype',  array('description'=> 'Graduate Student Profile','slug' => 'graduate'));
	wp_insert_term('spotlight', 'profiletype',  array('description'=> 'Faculty or Spotlight Feature','slug' => 'spotlight'));
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
					'name' 			=> 'Pull Quote',
					'desc' 			=> '',
					'id' 			=> 'ecpt_pull_quote',
					'class' 		=> 'ecpt_pull_quote',
					'type' 			=> 'textarea',
					'rich_editor' 	=> 1,			
					'max' 			=> 0													
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
			case 'text':
				echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" size="30" style="width:97%" /><br/>', '', $field['desc'];
				break;
			case 'date':
				if($meta) { $value = ecpt_timestamp_to_date($meta); } else {  $value = ''; }
				echo '<input type="text" class="ecpt_datepicker" name="' . $field['id'] . '" id="' . $field['id'] . '" value="'. $value . '" size="30" style="width:97%" />' . '' . $field['desc'];
				break;
			case 'upload':
				echo '<input type="text" class="ecpt_upload_field" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" size="30" style="width:80%" /><input class="ecpt_upload_image_button" type="button" value="Upload" /><br/>', '', $field['desc'];
				break;
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
			case 'select':
				echo '<select name="', $field['id'], '" id="', $field['id'], '">';
				foreach ($field['options'] as $option) {
					echo '<option value="' . $option . '"', $meta == $option ? ' selected="selected"' : '', '>', $option, '</option>';
				}
				echo '</select>', '', $field['desc'];
				break;
			case 'radio':
				foreach ($field['options'] as $option) {
					echo '<input type="radio" name="', $field['id'], '" value="', $option, '"', $meta == $option ? ' checked="checked"' : '', ' />&nbsp;', $option;
				}
				echo '<br/>' . $field['desc'];
				break;
			case 'checkbox':
				echo '<input type="checkbox" name="', $field['id'], '" id="', $field['id'], '"', $meta ? ' checked="checked"' : '', ' />&nbsp;';
				echo $field['desc'];
				break;
			case 'slider':
				echo '<input type="text" rel="' . $field['max'] . '" name="' . $field['id'] . '" id="' . $field['id'] . '" value="' . $meta . '" size="1" style="float: left; margin-right: 5px" />';
				echo '<div class="ecpt-slider" rel="' . $field['id'] . '" style="float: left; width: 60%; margin: 5px 0 0 0;"></div>';		
				echo '<div style="width: 100%; clear: both;">' . $field['desc'] . '</div>';
				break;
			case 'repeatable' :
				
				$field_html = '<input type="hidden" id="' . $field['id'] . '" class="ecpt_repeatable_field_name" value=""/>';
				if(is_array($meta)) {
					$count = 1;
					foreach($meta as $key => $value) {
						$field_html .= '<div class="ecpt_repeatable_wrapper"><input type="text" class="ecpt_repeatable_field" name="' . $field['id'] . '[' . $key . ']" id="' . $field['id'] . '[' . $key . ']" value="' . $meta[$key] . '" size="30" style="width:90%" />';
						if($count > 1) {
							$field_html .= '<a href="#" class="ecpt_remove_repeatable button-secondary">x</a><br/>';
						}
						$field_html .= '</div>';
						$count++;
					}
				} else {
					$field_html .= '<div class="ecpt_repeatable_wrapper"><input type="text" class="ecpt_repeatable_field" name="' . $field['id'] . '[0]" id="' . $field['id'] . '[0]" value="' . $meta . '" size="30" style="width:90%" /></div>';
				}
				$field_html .= '<button class="ecpt_add_new_field button-secondary">' . __('Add New', 'ecpt') . '</button>&nbsp;&nbsp;' . __(stripslashes($field['desc']));
				
				echo $field_html;
				
				break;
			
			case 'repeatable upload' :
			
				$field_html = '<input type="hidden" id="' . $field['id'] . '" class="ecpt_repeatable_upload_field_name" value=""/>';
				if(is_array($meta)) {
					$count = 1;
					foreach($meta as $key => $value) {
						$field_html .= '<div class="ecpt_repeatable_upload_wrapper"><input type="text" class="ecpt_repeatable_upload_field ecpt_upload_field" name="' . $field['id'] . '[' . $key . ']" id="' . $field['id'] . '[' . $key . ']" value="' . $meta[$key] . '" size="30" style="width:80%" /><button class="button-secondary ecpt_upload_image_button">Upload File</button>';
						if($count > 1) {
							$field_html .= '<a href="#" class="ecpt_remove_repeatable button-secondary">x</a><br/>';
						}
						$field_html .= '</div>';
						$count++;
					}
				} else {
					$field_html .= '<div class="ecpt_repeatable_upload_wrapper"><input type="text" class="ecpt_repeatable_upload_field ecpt_upload_field" name="' . $field['id'] . '[0]" id="' . $field['id'] . '[0]" value="' . $meta . '" size="30" style="width:80%" /><input class="button-secondary ecpt_upload_image_button" type="button" value="Upload File" /></div>';
				}
				$field_html .= '<button class="ecpt_add_new_upload_field button-secondary">' . __('Add New', 'ecpt') . '</button>&nbsp;&nbsp;' . __(stripslashes($field['desc']));		
			
				echo $field_html;
			
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
	if (!wp_verify_nonce($_POST['ecpt_pullquote_7_meta_box_nonce'], basename(__FILE__))) {
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

//Addd uploads box
$profileuploads_8_metabox = array( 
	'id' => 'profileuploads',
	'title' => 'Profile Uploads',
	'page' => array('profile'),
	'context' => 'side',
	'priority' => 'high',
	'fields' => array(

				
				array(
					'name' 			=> 'Upload Profile Photo',
					'desc' 			=> '',
					'id' 			=> 'ecpt_profile_photo',
					'class' 		=> 'ecpt_profile_photo',
					'type' 			=> 'upload',
					'rich_editor' 	=> 0,			
					'max' 			=> 0													
				),
												)
);			
			
add_action('admin_menu', 'ecpt_add_profileuploads_8_meta_box');
function ecpt_add_profileuploads_8_meta_box() {

	global $profileuploads_8_metabox;		

	foreach($profileuploads_8_metabox['page'] as $page) {
		add_meta_box($profileuploads_8_metabox['id'], $profileuploads_8_metabox['title'], 'ecpt_show_profileuploads_8_box', $page, 'side', 'high', $profileuploads_8_metabox);
	}
}

// function to show meta boxes
function ecpt_show_profileuploads_8_box()	{
	global $post;
	global $profileuploads_8_metabox;
	global $ecpt_prefix;
	global $wp_version;
	
	// Use nonce for verification
	echo '<input type="hidden" name="ecpt_profileuploads_8_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
	
	echo '<table class="form-table">';

	foreach ($profileuploads_8_metabox['fields'] as $field) {
		// get current post meta data

		$meta = get_post_meta($post->ID, $field['id'], true);
		
		echo '<tr>',
				'<th style="width:20%"><label for="', $field['id'], '">', $field['name'], '</label></th>',
				'<td class="ecpt_field_type_' . str_replace(' ', '_', $field['type']) . '">';
		switch ($field['type']) {
			case 'text':
				echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" size="30" style="width:97%" /><br/>', '', $field['desc'];
				break;
			case 'date':
				if($meta) { $value = ecpt_timestamp_to_date($meta); } else {  $value = ''; }
				echo '<input type="text" class="ecpt_datepicker" name="' . $field['id'] . '" id="' . $field['id'] . '" value="'. $value . '" size="30" style="width:97%" />' . '' . $field['desc'];
				break;
			case 'upload':
				echo '<input type="text" class="ecpt_upload_field" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" size="30" style="width:80%" /><input class="ecpt_upload_image_button" type="button" value="Upload" /><br/>', '', $field['desc'];
				break;
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
			case 'select':
				echo '<select name="', $field['id'], '" id="', $field['id'], '">';
				foreach ($field['options'] as $option) {
					echo '<option value="' . $option . '"', $meta == $option ? ' selected="selected"' : '', '>', $option, '</option>';
				}
				echo '</select>', '', $field['desc'];
				break;
			case 'radio':
				foreach ($field['options'] as $option) {
					echo '<input type="radio" name="', $field['id'], '" value="', $option, '"', $meta == $option ? ' checked="checked"' : '', ' />&nbsp;', $option;
				}
				echo '<br/>' . $field['desc'];
				break;
			case 'checkbox':
				echo '<input type="checkbox" name="', $field['id'], '" id="', $field['id'], '"', $meta ? ' checked="checked"' : '', ' />&nbsp;';
				echo $field['desc'];
				break;
			case 'slider':
				echo '<input type="text" rel="' . $field['max'] . '" name="' . $field['id'] . '" id="' . $field['id'] . '" value="' . $meta . '" size="1" style="float: left; margin-right: 5px" />';
				echo '<div class="ecpt-slider" rel="' . $field['id'] . '" style="float: left; width: 60%; margin: 5px 0 0 0;"></div>';		
				echo '<div style="width: 100%; clear: both;">' . $field['desc'] . '</div>';
				break;
			case 'repeatable' :
				
				$field_html = '<input type="hidden" id="' . $field['id'] . '" class="ecpt_repeatable_field_name" value=""/>';
				if(is_array($meta)) {
					$count = 1;
					foreach($meta as $key => $value) {
						$field_html .= '<div class="ecpt_repeatable_wrapper"><input type="text" class="ecpt_repeatable_field" name="' . $field['id'] . '[' . $key . ']" id="' . $field['id'] . '[' . $key . ']" value="' . $meta[$key] . '" size="30" style="width:90%" />';
						if($count > 1) {
							$field_html .= '<a href="#" class="ecpt_remove_repeatable button-secondary">x</a><br/>';
						}
						$field_html .= '</div>';
						$count++;
					}
				} else {
					$field_html .= '<div class="ecpt_repeatable_wrapper"><input type="text" class="ecpt_repeatable_field" name="' . $field['id'] . '[0]" id="' . $field['id'] . '[0]" value="' . $meta . '" size="30" style="width:90%" /></div>';
				}
				$field_html .= '<button class="ecpt_add_new_field button-secondary">' . __('Add New', 'ecpt') . '</button>&nbsp;&nbsp;' . __(stripslashes($field['desc']));
				
				echo $field_html;
				
				break;
			
			case 'repeatable upload' :
			
				$field_html = '<input type="hidden" id="' . $field['id'] . '" class="ecpt_repeatable_upload_field_name" value=""/>';
				if(is_array($meta)) {
					$count = 1;
					foreach($meta as $key => $value) {
						$field_html .= '<div class="ecpt_repeatable_upload_wrapper"><input type="text" class="ecpt_repeatable_upload_field ecpt_upload_field" name="' . $field['id'] . '[' . $key . ']" id="' . $field['id'] . '[' . $key . ']" value="' . $meta[$key] . '" size="30" style="width:80%" /><button class="button-secondary ecpt_upload_image_button">Upload File</button>';
						if($count > 1) {
							$field_html .= '<a href="#" class="ecpt_remove_repeatable button-secondary">x</a><br/>';
						}
						$field_html .= '</div>';
						$count++;
					}
				} else {
					$field_html .= '<div class="ecpt_repeatable_upload_wrapper"><input type="text" class="ecpt_repeatable_upload_field ecpt_upload_field" name="' . $field['id'] . '[0]" id="' . $field['id'] . '[0]" value="' . $meta . '" size="30" style="width:80%" /><input class="button-secondary ecpt_upload_image_button" type="button" value="Upload File" /></div>';
				}
				$field_html .= '<button class="ecpt_add_new_upload_field button-secondary">' . __('Add New', 'ecpt') . '</button>&nbsp;&nbsp;' . __(stripslashes($field['desc']));		
			
				echo $field_html;
			
				break;
		}
		echo     '<td>',
			'</tr>';
	}
	
	echo '</table>';
}	

add_action('save_post', 'ecpt_profileuploads_8_save');

// Save data from meta box
function ecpt_profileuploads_8_save($post_id) {
	global $post;
	global $profileuploads_8_metabox;
	
	// verify nonce
	if (!wp_verify_nonce($_POST['ecpt_profileuploads_8_meta_box_nonce'], basename(__FILE__))) {
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
	
	foreach ($profileuploads_8_metabox['fields'] as $field) {
	
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
		register_widget('Undergrad_Profile_Widget');
		register_widget('Graduate_Profile_Widget');
		register_widget('Spotlight_Widget');
	}
// Define undergrad student profile widget
class Undergrad_Profile_Widget extends WP_Widget {

	function Undergrad_Profile_Widget() {
		$widget_ops = array('classname' => 'widget_undergrad_profile', 'description' => __( "Undergrad Student Profile") );
		$this->WP_Widget('undergrad-profile-widget', 'Undergrad Student Profile', $widget_ops);
	}

	function widget( $args, $instance ) {
		extract($args);
		$title = apply_filters('widget_title', $instance['title'], $instance, $this->id_base);

		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title;                     
	

		global $post; ?>
		<?php $undergrad_profile_query = new WP_Query('post-type=profiles&profiletype=undergraduate&orderby=rand&posts_per_page=1'); ?>
					<?php while ($undergrad_profile_query->have_posts()) : $undergrad_profile_query->the_post(); ?>           
    	<div class="profile_box">
    	<div class="spotlight"></div>
		<a href="<?php the_permalink() ?>"><img src="<?php echo get_post_meta($post->ID, 'ecpt_profile_photo', true); ?>" /></a>
    	<h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
    	<p><?php echo get_post_meta($post->ID, 'ecpt_pull_quote', true); ?></p>
    	</div>
	
	
	<?php endwhile; ?>



<?php		echo $after_widget;

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
<?php 

// Define graduate student profile widget
class Graduate_Profile_Widget extends WP_Widget {

	function Graduate_Profile_Widget() {
		$widget_ops = array('classname' => 'widget_graduate_profile', 'description' => __( "Graduate Student Profile") );
		$this->WP_Widget('grad-profile-widget', 'Graduate Student Profile', $widget_ops);
	}

	function widget( $args, $instance ) {
		extract($args);
		$title = apply_filters('widget_title', $instance['title'], $instance, $this->id_base);

		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title;                     
	

		global $post; ?>
		<?php $graduate_profile_query = new WP_Query('post-type=profile&profiletype=graduate&orderby=rand&posts_per_page=1'); ?>
					<?php while ($graduate_profile_query->have_posts()) : $graduate_profile_query->the_post(); ?>
         <?php // get_the_ID(); ?>
         <?php //$profileid = $post->ID; ?>               
    	<div class="profile_box">
    	<div class="spotlight"></div>
		<a href="<?php the_permalink() ?>"><img src="<?php echo get_post_meta($post->ID, 'ecpt_profile_photo', true); ?>" /></a>
    	<h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
    	<p><?php echo get_post_meta($post->ID, 'ecpt_pull_quote', true); ?></p>
    	</div>
	
	
	<?php endwhile; ?>



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
// Define spotlight widget
class Spotlight_Widget extends WP_Widget {

	function Spotlight_Widget() {
		$widget_ops = array('classname' => 'widget_spotlight', 'description' => __( "Spotlight Widget") );
		$this->WP_Widget('spotlight-widget', 'Spotlight Widget', $widget_ops);
	}

	function widget( $args, $instance ) {
		extract($args);
		$title = apply_filters('widget_title', $instance['title'], $instance, $this->id_base);

		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title;                     
	

		global $post; ?>
		<?php $spotlight_query = new WP_Query('post-type=profile&profiletype=spotlight&orderby=rand&posts_per_page=1'); ?>
					<?php while ($spotlight_query->have_posts()) : $spotlight_query->the_post(); ?>
         <?php // get_the_ID(); ?>
         <?php //$profileid = $post->ID; ?>               
    	<div class="profile_box">
    	<div class="spotlight"></div>
		<a href="<?php the_permalink() ?>"><img src="<?php echo get_post_meta($post->ID, 'ecpt_profile_photo', true); ?>" /></a>
    	<h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
    	<p><?php echo get_post_meta($post->ID, 'ecpt_pull_quote', true); ?></p>
    	</div>
	
	
	<?php endwhile; ?>



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