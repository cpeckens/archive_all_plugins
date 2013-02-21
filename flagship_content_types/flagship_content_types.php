<?php
/*
Plugin Name: Krieger Flagship Content Types
Plugin URI: http://krieger2.jhu.edu/comm/web/plugins/flagship
Description: This plugin should be only be used on the flagship krieger.jhu.edu site.  Creates the custom post types: Fields of Study and Evergreens (Homepage Slides).
Version: 1.0
Author: Cara Peckens
Author URI: mailto:cpeckens@jhu.edu
License: GPL2
*/

// registration code for Fields of Study post type
	function register_studyfields_posttype() {
		$labels = array(
			'name' 				=> _x( 'Fields of Study', 'post type general name' ),
			'singular_name'		=> _x( 'Field of Study', 'post type singular name' ),
			'add_new' 			=> __( 'Add New Field of Study' ),
			'add_new_item' 		=> __( 'Add New Field of Study' ),
			'edit_item' 		=> __( 'Edit Field of Study' ),
			'new_item' 			=> __( 'New Field of Study' ),
			'view_item' 		=> __( 'View Field of Study' ),
			'search_items' 		=> __( 'Search Fields of Study' ),
			'not_found' 		=> __( 'No Fields of Study found' ),
			'not_found_in_trash'=> __( 'No Fields of Study found in Trash' ),
			'parent_item_colon' => __( '' ),
			'menu_name'			=> __( 'Fields of Study' )
		);
		
		$taxonomies = array();
		
		$supports = array('title','revisions','page-attributes' );
		
		$post_type_args = array(
			'labels' 			=> $labels,
			'singular_label' 	=> __('Field of Study'),
			'public' 			=> true,
			'show_ui' 			=> true,
			'publicly_queryable'=> true,
			'query_var'			=> true,
			'capability_type' 	=> 'post',
			'has_archive' 		=> false,
			'hierarchical' 		=> true,
			'rewrite' 			=> array('slug' => 'fields', 'with_front' => false ),
			'supports' 			=> $supports,
			'menu_position' 	=> 5,
			'taxonomies'		=> $taxonomies,
			'show_in_nav_menus' => true
		 );
		 register_post_type('studyfields',$post_type_args);
	}
	add_action('init', 'register_studyfields_posttype');


	
// registration code for Homepage evergreens post type
function register_evergreen_posttype() {
		$labels = array(
			'name' 				=> _x( 'Big Ideas', 'post type general name' ),
			'singular_name'		=> _x( 'Big Idea', 'post type singular name' ),
			'add_new' 			=> __( 'Add New Big Idea' ),
			'add_new_item' 		=> __( 'Add New Big Idea' ),
			'edit_item' 		=> __( 'Edit Big Idea' ),
			'new_item' 			=> __( 'New Big Idea' ),
			'view_item' 		=> __( 'View Big Idea' ),
			'search_items' 		=> __( 'Search Big Ideas' ),
			'not_found' 		=> __( 'No Big Ideas found' ),
			'not_found_in_trash'=> __( 'No Big Ideass found in Trash' ),
			'parent_item_colon' => __( '' ),
			'menu_name'			=> __( 'Big Ideas' )
		);
		
		$taxonomies = array();
		
		$supports = array('title','editor','custom-fields','revisions');
		
		$post_type_args = array(
			'labels' 			=> $labels,
			'singular_label' 	=> __('Evergreen'),
			'public' 			=> true,
			'show_ui' 			=> true,
			'publicly_queryable'=> true,
			'query_var'			=> true,
			'capability_type' 	=> 'post',
			'has_archive' 		=> false,
			'hierarchical' 		=> false,
			'rewrite' 			=> array('slug' => 'evergreen', 'with_front' => false ),
			'supports' 			=> $supports,
			'menu_position' 	=> 5,
			'taxonomies'		=> $taxonomies
		 );
		 register_post_type('evergreen',$post_type_args);
	}
	add_action('init', 'register_evergreen_posttype');

	function register_deptextra_posttype() {
		$labels = array(
			'name' 				=> _x( 'Department Extras', 'post type general name' ),
			'singular_name'		=> _x( 'Department Extra', 'post type singular name' ),
			'add_new' 			=> __( 'Add New Department Extra' ),
			'add_new_item' 		=> __( 'Add New Department Extra' ),
			'edit_item' 		=> __( 'Edit Department Extra' ),
			'new_item' 			=> __( 'New Department Extra' ),
			'view_item' 		=> __( 'View Department Extra' ),
			'search_items' 		=> __( 'Search Department Extras' ),
			'not_found' 		=> __( 'No Department Extras found' ),
			'not_found_in_trash'=> __( 'No Department Extras found in Trash' ),
			'parent_item_colon' => __( '' ),
			'menu_name'			=> __( 'Department Extras' )
		);
		
		$taxonomies = array('category');
		
		$supports = array('title', 'editor', 'revisions', 'post-formats', 'excerpt', 'thumbnail');
		
		$post_type_args = array(
			'labels' 			=> $labels,
			'singular_label' 	=> __('Department Extra'),
			'public' 			=> true,
			'show_ui' 			=> true,
			'publicly_queryable'=> true,
			'query_var'			=> true,
			'capability_type' 	=> 'post',
			'has_archive' 		=> false,
			'hierarchical' 		=> false,
			'rewrite' 			=> array('slug' => 'dept_extras', 'with_front' => false ),
			'supports' 			=> $supports,
			'menu_position' 	=> 5,
			'taxonomies'		=> $taxonomies,
			'show_in_nav_menus' => false
		 );
		 register_post_type('deptextra',$post_type_args);
	}
	add_action('init', 'register_deptextra_posttype');
	
function my_rewrite_flush() {
    // First, we "add" the custom post type via the above written function.
    // Note: "add" is written with quotes, as CPTs don't get added to the DB,
    // They are only referenced in the post_type column with a post entry, 
    // when you add a post of this CPT.
    register_studyfields_posttype();
    register_evergreen_posttype();
    register_deptextra_posttype();
    // ATTENTION: This is *only* done during plugin activation hook in this example!
    // You should *NEVER EVER* do this on every page load!!
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'my_rewrite_flush' );	
$imageuploads_3_metabox = array( 
	'id' => 'imageuploads',
	'title' => 'Image Uploads and Caption',
	'page' => array('evergreen'),
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(

				
				array(
					'name' 			=> 'Full Image',
					'desc' 			=> '',
					'id' 				=> 'ecpt_fullimage',
					'class' 			=> 'ecpt_fullimage',
					'type' 			=> 'upload',
					'rich_editor' 	=> 0,			
					'max' 			=> 0,
					'std'			=> ''				
				),
				array(
					'name' 			=> 'Tablet Image',
					'desc' 			=> '',
					'id' 				=> 'ecpt_tabletimage',
					'class' 			=> 'ecpt_tabletimage',
					'type' 			=> 'upload',
					'rich_editor' 	=> 0,			
					'max' 			=> 0,
					'std'			=> ''				
				),
				array(
					'name' 			=> 'Mobile Image',
					'desc' 			=> '',
					'id' 				=> 'ecpt_mobileimage',
					'class' 			=> 'ecpt_mobileimage',
					'type' 			=> 'upload',
					'rich_editor' 	=> 0,			
					'max' 			=> 0,
					'std'			=> ''				
				),
				array(
					'name' 			=> 'Image Caption and Photo Credit',
					'desc' 			=> '',
					'id' 				=> 'ecpt_caption_credit',
					'class' 			=> 'ecpt_caption_credit',
					'type' 			=> 'textarea',
					'rich_editor' 	=> 0,			
					'max' 			=> 0,
					'std'			=> ''				
				),
				)
);			
			
add_action('admin_menu', 'ecpt_add_imageuploads_3_meta_box');
function ecpt_add_imageuploads_3_meta_box() {

	global $imageuploads_3_metabox;		

	foreach($imageuploads_3_metabox['page'] as $page) {
		add_meta_box($imageuploads_3_metabox['id'], $imageuploads_3_metabox['title'], 'ecpt_show_imageuploads_3_box', $page, 'normal', 'high', $imageuploads_3_metabox);
	}
}

// function to show meta boxes
function ecpt_show_imageuploads_3_box()	{
	global $post;
	global $imageuploads_3_metabox;
	global $ecpt_prefix;
	global $wp_version;
	
	// Use nonce for verification
	echo '<input type="hidden" name="ecpt_imageuploads_3_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
	
	echo '<table class="form-table">';

	foreach ($imageuploads_3_metabox['fields'] as $field) {
		// get current post meta data

		$meta = get_post_meta($post->ID, $field['id'], true);
		
		echo '<tr>',
				'<th style="width:20%"><label for="', $field['id'], '">', stripslashes($field['name']), '</label></th>',
				'<td class="ecpt_field_type_' . str_replace(' ', '_', $field['type']) . '">';
		switch ($field['type']) {
			case 'upload':
				echo '<input type="text" class="ecpt_upload_field" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" size="30" style="width:80%" /><input class="ecpt_upload_image_button" type="button" value="Upload Image" /><br/>', '', stripslashes($field['desc']);
				break;
			case 'textarea':
			
				if($field['rich_editor'] == 1) {
					echo wp_editor($meta, $field['id'], array('textarea_name' => $field['id'], 'wpautop' => false)); }
					 else {
					echo '<div style="width: 100%;"><textarea name="', $field['id'], '" class="', $field['class'], '" id="', $field['id'], '" cols="60" rows="8" style="width:97%">', $meta ? $meta : $field['std'], '</textarea></div>', '', stripslashes($field['desc']);				
				}
				
				break;			
		}
		echo     '<td>',
			'</tr>';
	}
	
	echo '</table>';
}	

// Save data from meta box
add_action('save_post', 'ecpt_imageuploads_3_save');
function ecpt_imageuploads_3_save($post_id) {
	global $post;
	global $imageuploads_3_metabox;
	
	// verify nonce
	if (!isset($_POST['ecpt_imageuploads_3_meta_box_nonce']) || !wp_verify_nonce($_POST['ecpt_imageuploads_3_meta_box_nonce'], basename(__FILE__))) {
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
	
	foreach ($imageuploads_3_metabox['fields'] as $field) {
	
		$old = get_post_meta($post_id, $field['id'], true);
		$new = $_POST[$field['id']];
		
		if ($new && $new != $old) {
			if($field['type'] == 'date') {
				$new = ecpt_format_date($new);
				update_post_meta($post_id, $field['id'], $new);
			} else {
				if(is_string($new)) {
					$new = $new;
				} 
				update_post_meta($post_id, $field['id'], $new);
				
				
			}
		} elseif ('' == $new && $old) {
			delete_post_meta($post_id, $field['id'], $old);
		}
	}
}

$taxonomies_4_metabox = array( 
	'id' => 'taxonomies',
	'title' => 'Taxonomies',
	'page' => array('studyfields'),
	'context' => 'side',
	'priority' => 'high',
	'fields' => array(

				
				array(
					'name' 			=> 'Discipline',
					'desc' 			=> '',
					'id' 				=> 'ecpt_discipline',
					'class' 			=> 'ecpt_discipline',
					'type' 			=> 'multicheck',
					'rich_editor' 	=> 1,			
					'options' => array('humanities','social','natural'),
					'max' 			=> 0,
					'std'			=> ''				
				),
							
				array(
					'name' 			=> 'Structure',
					'desc' 			=> '',
					'id' 				=> 'ecpt_structure',
					'class' 			=> 'ecpt_structure',
					'type' 			=> 'select',
					'rich_editor' 	=> 1,			
					'options' => array('department','interdisciplinary','arts'),
					'max' 			=> 0,
					'std'			=> ''				
				),
				)
);			
			
add_action('admin_menu', 'ecpt_add_taxonomies_4_meta_box');
function ecpt_add_taxonomies_4_meta_box() {

	global $taxonomies_4_metabox;		

	foreach($taxonomies_4_metabox['page'] as $page) {
		add_meta_box($taxonomies_4_metabox['id'], $taxonomies_4_metabox['title'], 'ecpt_show_taxonomies_4_box', $page, 'side', 'high', $taxonomies_4_metabox);
	}
}

// function to show meta boxes
function ecpt_show_taxonomies_4_box()	{
	global $post;
	global $taxonomies_4_metabox;
	global $ecpt_prefix;
	global $wp_version;
	
	// Use nonce for verification
	echo '<input type="hidden" name="ecpt_taxonomies_4_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
	
	echo '<table class="form-table">';

	foreach ($taxonomies_4_metabox['fields'] as $field) {
		// get current post meta data

		$meta = get_post_meta($post->ID, $field['id'], true);
		
		echo '<tr>',
				'<th style="width:20%"><label for="', $field['id'], '">', stripslashes($field['name']), '</label></th>',
				'<td class="ecpt_field_type_' . str_replace(' ', '_', $field['type']) . '">';
		switch ($field['type']) {
			case 'select':
				echo '<select name="', $field['id'], '" id="', $field['id'], '">';
				foreach ($field['options'] as $option) {
					echo '<option value="' . $option . '"', $meta == $option ? ' selected="selected"' : '', '>', $option, '</option>';
				}
				echo '</select>', '', stripslashes($field['desc']);
				break;
			case 'multicheck':
				foreach ($field['options'] as $option) {
					echo '<input type="checkbox" name="' . $field['id'] . '[' . $option . ']" value="' . $option . '"' . checked( true, in_array( $option, $meta ), false ) . '/> ' . $option;
				}
				echo '<br/>' . stripslashes($field['desc']);
				break;
			
		}
		echo     '<td>',
			'</tr>';
	}
	
	echo '</table>';
}	

// Save data from meta box
add_action('save_post', 'ecpt_taxonomies_4_save');
function ecpt_taxonomies_4_save($post_id) {
	global $post;
	global $taxonomies_4_metabox;
	
	// verify nonce
	if (!isset($_POST['ecpt_taxonomies_4_meta_box_nonce']) || !wp_verify_nonce($_POST['ecpt_taxonomies_4_meta_box_nonce'], basename(__FILE__))) {
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
	
	foreach ($taxonomies_4_metabox['fields'] as $field) {
	
		$old = get_post_meta($post_id, $field['id'], true);
		$new = $_POST[$field['id']];
		
		if ($new && $new != $old) {
			if($field['type'] == 'date') {
				$new = ecpt_format_date($new);
				update_post_meta($post_id, $field['id'], $new);
			} else {
				if(is_string($new)) {
					$new = $new;
				} 
				update_post_meta($post_id, $field['id'], $new);
				
				
			}
		} elseif ('' == $new && $old) {
			delete_post_meta($post_id, $field['id'], $old);
		}
	}
}

$basicinformation_5_metabox = array( 
	'id' => 'basicinformation',
	'title' => 'Basic Information',
	'page' => array('studyfields'),
	'context' => 'normal',
	'priority' => 'default',
	'fields' => array(

				
				array(
					'name' 			=> 'Phone Number',
					'desc' 			=> '',
					'id' 				=> 'ecpt_phonenumber',
					'class' 			=> 'ecpt_phonenumber',
					'type' 			=> 'text',
					'rich_editor' 	=> 0,			
					'max' 			=> 0,
					'std'			=> ''				
				),
							
				array(
					'name' 			=> 'Email Address',
					'desc' 			=> '',
					'id' 				=> 'ecpt_emailaddress',
					'class' 			=> 'ecpt_emailaddress',
					'type' 			=> 'text',
					'rich_editor' 	=> 0,			
					'max' 			=> 0,
					'std'			=> ''				
				),
							
				array(
					'name' 			=> 'Majors',
					'desc' 			=> 'Separate with commas',
					'id' 				=> 'ecpt_majors',
					'class' 			=> 'ecpt_majors',
					'type' 			=> 'textarea',
					'rich_editor' 	=> 0,			
					'max' 			=> 0,
					'std'			=> ''				
				),
							
				array(
					'name' 			=> 'Minors',
					'desc' 			=> 'Separate with commas',
					'id' 				=> 'ecpt_minors',
					'class' 			=> 'ecpt_minors',
					'type' 			=> 'textarea',
					'rich_editor' 	=> 0,			
					'max' 			=> 0,
					'std'			=> ''				
				),
				
				array(
					'name' 			=> 'Degrees Offered',
					'desc' 			=> 'Separate with commas',
					'id' 				=> 'ecpt_degreesoffered',
					'class' 			=> 'ecpt_degreesoffered',
					'type' 			=> 'text',
					'rich_editor' 	=> 1,			
					'max' 			=> 0,
					'std'			=> ''				
				),
				
				array(
					'name' 			=> 'Alt Major/Minor Area text',
					'desc' 			=> 'Separate with commas',
					'id' 				=> 'ecpt_pcitext',
					'class' 			=> 'ecpt_pcitext',
					'type' 			=> 'textarea',
					'rich_editor' 	=> 0,			
					'max' 			=> 0,
					'std'			=> ''				
				),				
				
				array(
					'name' 			=> 'Location',
					'desc' 			=> 'Building and room number only',
					'id' 				=> 'ecpt_location',
					'class' 			=> 'ecpt_location',
					'type' 			=> 'text',
					'rich_editor' 	=> 0,			
					'max' 			=> 0,
					'std'			=> ''				
				),
				)
);			
			
add_action('admin_menu', 'ecpt_add_basicinformation_5_meta_box');
function ecpt_add_basicinformation_5_meta_box() {

	global $basicinformation_5_metabox;		

	foreach($basicinformation_5_metabox['page'] as $page) {
		add_meta_box($basicinformation_5_metabox['id'], $basicinformation_5_metabox['title'], 'ecpt_show_basicinformation_5_box', $page, 'normal', 'default', $basicinformation_5_metabox);
	}
}

// function to show meta boxes
function ecpt_show_basicinformation_5_box()	{
	global $post;
	global $basicinformation_5_metabox;
	global $ecpt_prefix;
	global $wp_version;
	
	// Use nonce for verification
	echo '<input type="hidden" name="ecpt_basicinformation_5_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
	
	echo '<table class="form-table">';

	foreach ($basicinformation_5_metabox['fields'] as $field) {
		// get current post meta data

		$meta = get_post_meta($post->ID, $field['id'], true);
		
		echo '<tr>',
				'<th style="width:20%"><label for="', $field['id'], '">', stripslashes($field['name']), '</label></th>',
				'<td class="ecpt_field_type_' . str_replace(' ', '_', $field['type']) . '">';
		switch ($field['type']) {
			case 'text':
				echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" size="30" style="width:97%" /><br/>', '', stripslashes($field['desc']);
				break;
			case 'textarea':
			
				if($field['rich_editor'] == 1) {
						echo wp_editor($meta, $field['id'], array('textarea_name' => $field['id'], 'wpautop' => false)); }
					 else {
					echo '<div style="width: 100%;"><textarea name="', $field['id'], '" class="', $field['class'], '" id="', $field['id'], '" cols="60" rows="8" style="width:97%">', $meta ? $meta : $field['std'], '</textarea></div>', '', stripslashes($field['desc']);				
				}
				
				break;			
		}
		echo     '<td>',
			'</tr>';
	}
	
	echo '</table>';
}	

// Save data from meta box
add_action('save_post', 'ecpt_basicinformation_5_save');
function ecpt_basicinformation_5_save($post_id) {
	global $post;
	global $basicinformation_5_metabox;
	
	// verify nonce
	if (!isset($_POST['ecpt_basicinformation_5_meta_box_nonce']) || !wp_verify_nonce($_POST['ecpt_basicinformation_5_meta_box_nonce'], basename(__FILE__))) {
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
	
	foreach ($basicinformation_5_metabox['fields'] as $field) {
	
		$old = get_post_meta($post_id, $field['id'], true);
		$new = $_POST[$field['id']];
		
		if ($new && $new != $old) {
			if($field['type'] == 'date') {
				$new = ecpt_format_date($new);
				update_post_meta($post_id, $field['id'], $new);
			} else {
				if(is_string($new)) {
					$new = $new;
				} 
				update_post_meta($post_id, $field['id'], $new);
				
				
			}
		} elseif ('' == $new && $old) {
			delete_post_meta($post_id, $field['id'], $old);
		}
	}
}

$websiteurls_6_metabox = array( 
	'id' => 'websiteurls',
	'title' => 'Website URLs (do NOT include http://)',
	'page' => array('studyfields'),
	'context' => 'normal',
	'priority' => 'default',
	'fields' => array(

				
				array(
					'name' 			=> 'Homepage',
					'desc' 			=> 'Required for all',
					'id' 				=> 'ecpt_homepage',
					'class' 			=> 'ecpt_homepage',
					'type' 			=> 'text',
					'rich_editor' 	=> 1,			
					'max' 			=> 0,
					'std'			=> ''				
				),
							
				array(
					'name' 			=> 'Faculty page',
					'desc' 			=> 'Only for departments',
					'id' 				=> 'ecpt_facultypage',
					'class' 			=> 'ecpt_facultypage',
					'type' 			=> 'text',
					'rich_editor' 	=> 1,			
					'max' 			=> 0,
					'std'			=> ''				
				),
							
				array(
					'name' 			=> 'Undergraduate program page',
					'desc' 			=> 'Only for departments',
					'id' 				=> 'ecpt_undergraduatepage',
					'class' 			=> 'ecpt_undergraduatepage',
					'type' 			=> 'text',
					'rich_editor' 	=> 1,			
					'max' 			=> 0,
					'std'			=> ''				
				),
							
				array(
					'name' 			=> 'Graduate program page',
					'desc' 			=> 'Only for departments',
					'id' 				=> 'ecpt_graduatepage',
					'class' 			=> 'ecpt_graduatepage',
					'type' 			=> 'text',
					'rich_editor' 	=> 0,			
					'max' 			=> 0,
					'std'			=> ''				
				),
				)
);			
			
add_action('admin_menu', 'ecpt_add_websiteurls_6_meta_box');
function ecpt_add_websiteurls_6_meta_box() {

	global $websiteurls_6_metabox;		

	foreach($websiteurls_6_metabox['page'] as $page) {
		add_meta_box($websiteurls_6_metabox['id'], $websiteurls_6_metabox['title'], 'ecpt_show_websiteurls_6_box', $page, 'normal', 'default', $websiteurls_6_metabox);
	}
}

// function to show meta boxes
function ecpt_show_websiteurls_6_box()	{
	global $post;
	global $websiteurls_6_metabox;
	global $ecpt_prefix;
	global $wp_version;
	
	// Use nonce for verification
	echo '<input type="hidden" name="ecpt_websiteurls_6_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
	
	echo '<table class="form-table">';

	foreach ($websiteurls_6_metabox['fields'] as $field) {
		// get current post meta data

		$meta = get_post_meta($post->ID, $field['id'], true);
		
		echo '<tr>',
				'<th style="width:20%"><label for="', $field['id'], '">', stripslashes($field['name']), '</label></th>',
				'<td class="ecpt_field_type_' . str_replace(' ', '_', $field['type']) . '">';
		switch ($field['type']) {
			case 'text':
				echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" size="30" style="width:97%" /><br/>', '', stripslashes($field['desc']);
				break;			
		}
		echo     '<td>',
			'</tr>';
	}
	
	echo '</table>';
}	

// Save data from meta box
add_action('save_post', 'ecpt_websiteurls_6_save');
function ecpt_websiteurls_6_save($post_id) {
	global $post;
	global $websiteurls_6_metabox;
	
	// verify nonce
	if (!isset($_POST['ecpt_websiteurls_6_meta_box_nonce']) || !wp_verify_nonce($_POST['ecpt_websiteurls_6_meta_box_nonce'], basename(__FILE__))) {
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
	
	foreach ($websiteurls_6_metabox['fields'] as $field) {
	
		$old = get_post_meta($post_id, $field['id'], true);
		$new = $_POST[$field['id']];
		
		if ($new && $new != $old) {
			if($field['type'] == 'date') {
				$new = ecpt_format_date($new);
				update_post_meta($post_id, $field['id'], $new);
			} else {
				if(is_string($new)) {
					$new = $new;
				} 
				update_post_meta($post_id, $field['id'], $new);
				
				
			}
		} elseif ('' == $new && $old) {
			delete_post_meta($post_id, $field['id'], $old);
		}
	}
}

$calloutbox_7_metabox = array( 
	'id' => 'calloutbox',
	'title' => 'Callout Box',
	'page' => array('studyfields'),
	'context' => 'normal',
	'priority' => 'default',
	'fields' => array(

				
				array(
					'name' 			=> 'Title',
					'desc' 			=> '',
					'id' 				=> 'ecpt_title',
					'class' 			=> 'ecpt_title',
					'type' 			=> 'text',
					'rich_editor' 	=> 0,			
					'max' 			=> 0,
					'std'			=> 'Degrees & Concentrations'				
				),
							
				array(
					'name' 			=> 'Content',
					'desc' 			=> 'Please format as bulleted list',
					'id' 				=> 'ecpt_content',
					'class' 			=> 'ecpt_content',
					'type' 			=> 'textarea',
					'rich_editor' 	=> 1,			
					'max' 			=> 0,
					'std'			=> ''				
				),
				)
);			
			
add_action('admin_menu', 'ecpt_add_calloutbox_7_meta_box');
function ecpt_add_calloutbox_7_meta_box() {

	global $calloutbox_7_metabox;		

	foreach($calloutbox_7_metabox['page'] as $page) {
		add_meta_box($calloutbox_7_metabox['id'], $calloutbox_7_metabox['title'], 'ecpt_show_calloutbox_7_box', $page, 'normal', 'default', $calloutbox_7_metabox);
	}
}

// function to show meta boxes
function ecpt_show_calloutbox_7_box()	{
	global $post;
	global $calloutbox_7_metabox;
	global $ecpt_prefix;
	global $wp_version;
	
	// Use nonce for verification
	echo '<input type="hidden" name="ecpt_calloutbox_7_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
	
	echo '<table class="form-table">';

	foreach ($calloutbox_7_metabox['fields'] as $field) {
		// get current post meta data

		$meta = get_post_meta($post->ID, $field['id'], true);
		
		echo '<tr>',
				'<th style="width:20%"><label for="', $field['id'], '">', stripslashes($field['name']), '</label></th>',
				'<td class="ecpt_field_type_' . str_replace(' ', '_', $field['type']) . '">';
		switch ($field['type']) {
			case 'text':
				echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" size="30" style="width:97%" /><br/>', '', stripslashes($field['desc']);
				break;
			case 'textarea':
			
				if($field['rich_editor'] == 1) {
						echo wp_editor($meta, $field['id'], array('textarea_name' => $field['id'], 'wpautop' => false)); }
					 else {
					echo '<div style="width: 100%;"><textarea name="', $field['id'], '" class="', $field['class'], '" id="', $field['id'], '" cols="60" rows="8" style="width:97%">', $meta ? $meta : $field['std'], '</textarea></div>', '', stripslashes($field['desc']);				
				}
				
				break;
			
		}
		echo     '<td>',
			'</tr>';
	}
	
	echo '</table>';
}	

// Save data from meta box
add_action('save_post', 'ecpt_calloutbox_7_save');
function ecpt_calloutbox_7_save($post_id) {
	global $post;
	global $calloutbox_7_metabox;
	
	// verify nonce
	if (!isset($_POST['ecpt_calloutbox_7_meta_box_nonce']) || !wp_verify_nonce($_POST['ecpt_calloutbox_7_meta_box_nonce'], basename(__FILE__))) {
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
	
	foreach ($calloutbox_7_metabox['fields'] as $field) {
	
		$old = get_post_meta($post_id, $field['id'], true);
		$new = $_POST[$field['id']];
		
		if ($new && $new != $old) {
			if($field['type'] == 'date') {
				$new = ecpt_format_date($new);
				update_post_meta($post_id, $field['id'], $new);
			} else {
				if(is_string($new)) {
					$new = $new;
				} 
				update_post_meta($post_id, $field['id'], $new);
				
				
			}
		} elseif ('' == $new && $old) {
			delete_post_meta($post_id, $field['id'], $old);
		}
	}
}

$maincontent_8_metabox = array( 
	'id' => 'maincontent',
	'title' => 'Main Content',
	'page' => array('studyfields'),
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(

				
				array(
					'name' 			=> 'Section 1 Content',
					'desc' 			=> 'This is the main description',
					'id' 				=> 'ecpt_section1',
					'class' 			=> 'ecpt_section1',
					'type' 			=> 'textarea',
					'rich_editor' 	=> 1,			
					'max' 			=> 0,
					'std'			=> ''				
				),
							
				array(
					'name' 			=> 'Section 2 Heading',
					'desc' 			=> 'This field is optional.  Defaults to: What can you do with your degree?',
					'id' 				=> 'ecpt_section2heading',
					'class' 			=> 'ecpt_section2heading',
					'type' 			=> 'text',
					'rich_editor' 	=> 0,			
					'max' 			=> 0,
					'std'			=> ''				
				),
							
				array(
					'name' 			=> 'Section 2 Content',
					'desc' 			=> '',
					'id' 				=> 'ecpt_section2content',
					'class' 			=> 'ecpt_section2content',
					'type' 			=> 'textarea',
					'rich_editor' 	=> 1,			
					'max' 			=> 0,
					'std'			=> ''				
				),
							
				array(
					'name' 			=> 'Section 3 Heading',
					'desc' 			=> 'This field is optional. Defaults to: Related programs and centers',
					'id' 				=> 'ecpt_section3heading',
					'class' 			=> 'ecpt_section3heading',
					'type' 			=> 'text',
					'rich_editor' 	=> 0,			
					'max' 			=> 0,
					'std'			=> ''				
				),
							
				array(
					'name' 			=> 'Section 3 Content',
					'desc' 			=> '',
					'id' 				=> 'ecpt_section3content',
					'class' 			=> 'ecpt_section3content',
					'type' 			=> 'textarea',
					'rich_editor' 	=> 1,			
					'max' 			=> 0,
					'std'			=> ''				
				),
				)
);			
			
add_action('admin_menu', 'ecpt_add_maincontent_8_meta_box');
function ecpt_add_maincontent_8_meta_box() {

	global $maincontent_8_metabox;		

	foreach($maincontent_8_metabox['page'] as $page) {
		add_meta_box($maincontent_8_metabox['id'], $maincontent_8_metabox['title'], 'ecpt_show_maincontent_8_box', $page, 'normal', 'high', $maincontent_8_metabox);
	}
}

// function to show meta boxes
function ecpt_show_maincontent_8_box()	{
	global $post;
	global $maincontent_8_metabox;
	global $ecpt_prefix;
	global $wp_version;
	
	// Use nonce for verification
	echo '<input type="hidden" name="ecpt_maincontent_8_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
	
	echo '<table class="form-table">';

	foreach ($maincontent_8_metabox['fields'] as $field) {
		// get current post meta data

		$meta = get_post_meta($post->ID, $field['id'], true);
		
		echo '<tr>',
				'<th style="width:20%"><label for="', $field['id'], '">', stripslashes($field['name']), '</label></th>',
				'<td class="ecpt_field_type_' . str_replace(' ', '_', $field['type']) . '">';
		switch ($field['type']) {
			case 'text':
				echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" size="30" style="width:97%" /><br/>', '', stripslashes($field['desc']);
				break;
			case 'textarea':
			
				if($field['rich_editor'] == 1) {
						echo wp_editor($meta, $field['id'], array('textarea_name' => $field['id'], 'wpautop' => false)); }
					 else {
					echo '<div style="width: 100%;"><textarea name="', $field['id'], '" class="', $field['class'], '" id="', $field['id'], '" cols="60" rows="8" style="width:97%">', $meta ? $meta : $field['std'], '</textarea></div>', '', stripslashes($field['desc']);				
				}
				
				break;
		}
		echo     '<td>',
			'</tr>';
	}
	
	echo '</table>';
}	

// Save data from meta box
add_action('save_post', 'ecpt_maincontent_8_save');
function ecpt_maincontent_8_save($post_id) {
	global $post;
	global $maincontent_8_metabox;
	
	// verify nonce
	if (!isset($_POST['ecpt_maincontent_8_meta_box_nonce']) || !wp_verify_nonce($_POST['ecpt_maincontent_8_meta_box_nonce'], basename(__FILE__))) {
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
	
	foreach ($maincontent_8_metabox['fields'] as $field) {
	
		$old = get_post_meta($post_id, $field['id'], true);
		$new = $_POST[$field['id']];
		
		if ($new && $new != $old) {
			if($field['type'] == 'date') {
				$new = ecpt_format_date($new);
				update_post_meta($post_id, $field['id'], $new);
			} else {
				if(is_string($new)) {
					$new = $new;
				} 
				update_post_meta($post_id, $field['id'], $new);
				
				
			}
		} elseif ('' == $new && $old) {
			delete_post_meta($post_id, $field['id'], $old);
		}
	}
}

$multimediaoptions_departmentspotlight_9_metabox = array( 
	'id' => 'multimediaoptions-departmentspotlight',
	'title' => 'Multimedia Options - Department Spotlight',
	'page' => array('deptextra'),
	'context' => 'normal',
	'priority' => 'default',
	'fields' => array(

				
				array(
					'name' 			=> 'Video',
					'desc' 			=> 'Insert iframe code here. This is for department spotlights only',
					'id' 				=> 'ecpt_spotlightvideo',
					'class' 			=> 'ecpt_spotlightvideo',
					'type' 			=> 'textarea',
					'rich_editor' 	=> 0,			
					'max' 			=> 0,
					'std'			=> ''				
				),
							
				array(
					'name' 			=> 'Gallery',
					'desc' 			=> 'Insert gallery short code here.  This is for department spotlights only.',
					'id' 				=> 'ecpt_gallery',
					'class' 			=> 'ecpt_gallery',
					'type' 			=> 'text',
					'rich_editor' 	=> 0,			
					'max' 			=> 0,
					'std'			=> ''				
				),
				)
);			
			
add_action('admin_menu', 'ecpt_add_multimediaoptions_departmentspotlight_9_meta_box');
function ecpt_add_multimediaoptions_departmentspotlight_9_meta_box() {

	global $multimediaoptions_departmentspotlight_9_metabox;		

	foreach($multimediaoptions_departmentspotlight_9_metabox['page'] as $page) {
		add_meta_box($multimediaoptions_departmentspotlight_9_metabox['id'], $multimediaoptions_departmentspotlight_9_metabox['title'], 'ecpt_show_multimediaoptions_departmentspotlight_9_box', $page, 'normal', 'default', $multimediaoptions_departmentspotlight_9_metabox);
	}
}

// function to show meta boxes
function ecpt_show_multimediaoptions_departmentspotlight_9_box()	{
	global $post;
	global $multimediaoptions_departmentspotlight_9_metabox;
	global $ecpt_prefix;
	global $wp_version;
	
	// Use nonce for verification
	echo '<input type="hidden" name="ecpt_multimediaoptions_departmentspotlight_9_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
	
	echo '<table class="form-table">';

	foreach ($multimediaoptions_departmentspotlight_9_metabox['fields'] as $field) {
		// get current post meta data

		$meta = get_post_meta($post->ID, $field['id'], true);
		
		echo '<tr>',
				'<th style="width:20%"><label for="', $field['id'], '">', stripslashes($field['name']), '</label></th>',
				'<td class="ecpt_field_type_' . str_replace(' ', '_', $field['type']) . '">';
		switch ($field['type']) {
			case 'text':
				echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" size="30" style="width:97%" /><br/>', '', stripslashes($field['desc']);
				break;
			case 'textarea':
			
				if($field['rich_editor'] == 1) {
						echo wp_editor($meta, $field['id'], array('textarea_name' => $field['id'], 'wpautop' => false)); }
					 else {
					echo '<div style="width: 100%;"><textarea name="', $field['id'], '" class="', $field['class'], '" id="', $field['id'], '" cols="60" rows="8" style="width:97%">', $meta ? $meta : $field['std'], '</textarea></div>', '', stripslashes($field['desc']);				
				}
				
				break;
		}
		echo     '<td>',
			'</tr>';
	}
	
	echo '</table>';
}	

// Save data from meta box
add_action('save_post', 'ecpt_multimediaoptions_departmentspotlight_9_save');
function ecpt_multimediaoptions_departmentspotlight_9_save($post_id) {
	global $post;
	global $multimediaoptions_departmentspotlight_9_metabox;
	
	// verify nonce
	if (!isset($_POST['ecpt_multimediaoptions_departmentspotlight_9_meta_box_nonce']) || !wp_verify_nonce($_POST['ecpt_multimediaoptions_departmentspotlight_9_meta_box_nonce'], basename(__FILE__))) {
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
	
	foreach ($multimediaoptions_departmentspotlight_9_metabox['fields'] as $field) {
	
		$old = get_post_meta($post_id, $field['id'], true);
		$new = $_POST[$field['id']];
		
		if ($new && $new != $old) {
			if($field['type'] == 'date') {
				$new = ecpt_format_date($new);
				update_post_meta($post_id, $field['id'], $new);
			} else {
				if(is_string($new)) {
					$new = $new;
				} 
				update_post_meta($post_id, $field['id'], $new);
				
				
			}
		} elseif ('' == $new && $old) {
			delete_post_meta($post_id, $field['id'], $old);
		}
	}
}

$topsection_10_metabox = array( 
	'id' => 'topsection',
	'title' => 'Top Section',
	'page' => array('studyfields'),
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(

				
				array(
					'name' 			=> 'Headline',
					'desc' 			=> '',
					'id' 				=> 'ecpt_headline',
					'class' 			=> 'ecpt_headline',
					'type' 			=> 'text',
					'rich_editor' 	=> 0,			
					'max' 			=> 0,
					'std'			=> ''				
				),
							
				array(
					'name' 			=> 'Subhead',
					'desc' 			=> '',
					'id' 				=> 'ecpt_subhead',
					'class' 			=> 'ecpt_subhead',
					'type' 			=> 'text',
					'rich_editor' 	=> 0,			
					'max' 			=> 0,
					'std'			=> ''				
				),
							
				array(
					'name' 			=> 'Image',
					'desc' 			=> 'This is the image that displays on the landing page',
					'id' 				=> 'ecpt_image',
					'class' 			=> 'ecpt_image',
					'type' 			=> 'upload',
					'rich_editor' 	=> 0,			
					'max' 			=> 0,
					'std'			=> ''				
				),
				)
);			
			
add_action('admin_menu', 'ecpt_add_topsection_10_meta_box');
function ecpt_add_topsection_10_meta_box() {

	global $topsection_10_metabox;		

	foreach($topsection_10_metabox['page'] as $page) {
		add_meta_box($topsection_10_metabox['id'], $topsection_10_metabox['title'], 'ecpt_show_topsection_10_box', $page, 'normal', 'high', $topsection_10_metabox);
	}
}

// function to show meta boxes
function ecpt_show_topsection_10_box()	{
	global $post;
	global $topsection_10_metabox;
	global $ecpt_prefix;
	global $wp_version;
	
	// Use nonce for verification
	echo '<input type="hidden" name="ecpt_topsection_10_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
	
	echo '<table class="form-table">';

	foreach ($topsection_10_metabox['fields'] as $field) {
		// get current post meta data

		$meta = get_post_meta($post->ID, $field['id'], true);
		
		echo '<tr>',
				'<th style="width:20%"><label for="', $field['id'], '">', stripslashes($field['name']), '</label></th>',
				'<td class="ecpt_field_type_' . str_replace(' ', '_', $field['type']) . '">';
		switch ($field['type']) {
			case 'text':
				echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" size="30" style="width:97%" /><br/>', '', stripslashes($field['desc']);
				break;
			case 'upload':
				echo '<input type="text" class="ecpt_upload_field" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" size="30" style="width:80%" /><input class="ecpt_upload_image_button" type="button" value="Upload Image" /><br/>', '', stripslashes($field['desc']);
				break;
		}
		echo     '<td>',
			'</tr>';
	}
	
	echo '</table>';
}	

// Save data from meta box
add_action('save_post', 'ecpt_topsection_10_save');
function ecpt_topsection_10_save($post_id) {
	global $post;
	global $topsection_10_metabox;
	
	// verify nonce
	if (!isset($_POST['ecpt_topsection_10_meta_box_nonce']) || !wp_verify_nonce($_POST['ecpt_topsection_10_meta_box_nonce'], basename(__FILE__))) {
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
	
	foreach ($topsection_10_metabox['fields'] as $field) {
	
		$old = get_post_meta($post_id, $field['id'], true);
		$new = $_POST[$field['id']];
		
		if ($new && $new != $old) {
			if($field['type'] == 'date') {
				$new = ecpt_format_date($new);
				update_post_meta($post_id, $field['id'], $new);
			} else {
				if(is_string($new)) {
					$new = $new;
				} 
				update_post_meta($post_id, $field['id'], $new);
				
				
			}
		} elseif ('' == $new && $old) {
			delete_post_meta($post_id, $field['id'], $old);
		}
	}
}

$indexing_11_metabox = array( 
	'id' => 'indexing',
	'title' => 'Indexing',
	'page' => array('studyfields'),
	'context' => 'side',
	'priority' => 'default',
	'fields' => array(

				
				array(
					'name' 			=> 'Index Image',
					'desc' 			=> 'This is the thumbnail image that displays on the fields of study index page',
					'id' 				=> 'ecpt_indeximage',
					'class' 			=> 'ecpt_indeximage',
					'type' 			=> 'upload',
					'rich_editor' 	=> 0,			
					'max' 			=> 0,
					'std'			=> ''				
				),
							
				array(
					'name' 			=> 'Keywords',
					'desc' 			=> 'These will not be displayed.  These are for search terms only. No commas necessary',
					'id' 				=> 'ecpt_keywords',
					'class' 			=> 'ecpt_keywords',
					'type' 			=> 'textarea',
					'rich_editor' 	=> 0,			
					'max' 			=> 0,
					'std'			=> ''				
				),
				)
);			
			
add_action('admin_menu', 'ecpt_add_indexing_11_meta_box');
function ecpt_add_indexing_11_meta_box() {

	global $indexing_11_metabox;		

	foreach($indexing_11_metabox['page'] as $page) {
		add_meta_box($indexing_11_metabox['id'], $indexing_11_metabox['title'], 'ecpt_show_indexing_11_box', $page, 'side', 'default', $indexing_11_metabox);
	}
}

// function to show meta boxes
function ecpt_show_indexing_11_box()	{
	global $post;
	global $indexing_11_metabox;
	global $ecpt_prefix;
	global $wp_version;
	
	// Use nonce for verification
	echo '<input type="hidden" name="ecpt_indexing_11_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
	
	echo '<table class="form-table">';

	foreach ($indexing_11_metabox['fields'] as $field) {
		// get current post meta data

		$meta = get_post_meta($post->ID, $field['id'], true);
		
		echo '<tr>',
				'<th style="width:20%"><label for="', $field['id'], '">', stripslashes($field['name']), '</label></th>',
				'<td class="ecpt_field_type_' . str_replace(' ', '_', $field['type']) . '">';
		switch ($field['type']) {
			case 'upload':
				echo '<input type="text" class="ecpt_upload_field" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" size="30" style="width:80%" /><input class="ecpt_upload_image_button" type="button" value="Upload Image" /><br/>', '', stripslashes($field['desc']);
				break;
			case 'textarea':
			
				if($field['rich_editor'] == 1) {
						echo wp_editor($meta, $field['id'], array('textarea_name' => $field['id'], 'wpautop' => false)); }
					 else {
					echo '<div style="width: 100%;"><textarea name="', $field['id'], '" class="', $field['class'], '" id="', $field['id'], '" cols="60" rows="8" style="width:97%">', $meta ? $meta : $field['std'], '</textarea></div>', '', stripslashes($field['desc']);				
				}
				
				break;
		}
		echo     '<td>',
			'</tr>';
	}
	
	echo '</table>';
}	

// Save data from meta box
add_action('save_post', 'ecpt_indexing_11_save');
function ecpt_indexing_11_save($post_id) {
	global $post;
	global $indexing_11_metabox;
	
	// verify nonce
	if (!isset($_POST['ecpt_indexing_11_meta_box_nonce']) || !wp_verify_nonce($_POST['ecpt_indexing_11_meta_box_nonce'], basename(__FILE__))) {
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
	
	foreach ($indexing_11_metabox['fields'] as $field) {
	
		$old = get_post_meta($post_id, $field['id'], true);
		$new = $_POST[$field['id']];
		
		if ($new && $new != $old) {
			if($field['type'] == 'date') {
				$new = ecpt_format_date($new);
				update_post_meta($post_id, $field['id'], $new);
			} else {
				if(is_string($new)) {
					$new = $new;
				} 
				update_post_meta($post_id, $field['id'], $new);
				
				
			}
		} elseif ('' == $new && $old) {
			delete_post_meta($post_id, $field['id'], $old);
		}
	}
}

function register_admindept_tax() {
		$labels = array(
			'name' 					=> _x( 'Admin Depts', 'taxonomy general name' ),
			'singular_name' 		=> _x( 'Admin Dept', 'taxonomy singular name' ),
			'add_new' 				=> _x( 'Add New Admin Dept', 'Admin Dept'),
			'add_new_item' 			=> __( 'Add New Admin Dept' ),
			'edit_item' 			=> __( 'Edit Admin Dept' ),
			'new_item' 				=> __( 'New Admin Dept' ),
			'view_item' 			=> __( 'View Admin Dept' ),
			'search_items' 			=> __( 'Search Admin Depts' ),
			'not_found' 			=> __( 'No Admin Dept found' ),
			'not_found_in_trash' 	=> __( 'No Admin Dept found in Trash' ),
		);
		
		$pages = array('people');
					
		$args = array(
			'labels' 			=> $labels,
			'singular_label' 	=> __('Admin Dept'),
			'public' 			=> true,
			'show_ui' 			=> true,
			'hierarchical' 		=> true,
			'show_tagcloud' 	=> false,
			'show_in_nav_menus' => false,
			'rewrite' 			=> array('slug' => 'department', 'with_front' => false ),
		 );
		register_taxonomy('admindept', $pages, $args);
	}
	add_action('init', 'register_admindept_tax');

	function add_admindept_terms() {
		wp_insert_term('Deans Office', 'admindept',  array('description'=> '','slug' => 'dean'));
		wp_insert_term('Marketing and Communications', 'admindept',  array('description'=> '','slug' => 'comm'));
	}
	add_action('init', 'add_admindept_terms');
?>