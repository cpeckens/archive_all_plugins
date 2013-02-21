<?php
/*
Plugin Name: KSAS People Directory
Plugin URI: http://krieger2.jhu.edu/comm/web/plugins/people
Description: Creates a custom post type for people.  Use only for faculty, staff and job market candidates.  To create a directory page, create a page with the URL of either faculty, staff, or job-market-candidates. Then choose 'Directory' from the page template drop-down.  This plugin also creates a widget to display Job Candidates in the sidebar. Please remember to designate your Academic Department and Role for proper search indexing.
Version: 1.0
Author: Cara Peckens
Author URI: mailto:cpeckens@jhu.edu
License: GPL2
*/

// registration code for people post type
	function register_people_posttype() {
		$labels = array(
			'name' 				=> _x( 'People', 'post type general name' ),
			'singular_name'		=> _x( 'Person', 'post type singular name' ),
			'add_new' 			=> _x( 'Add New', 'Person'),
			'add_new_item' 		=> __( 'Add New Person '),
			'edit_item' 		=> __( 'Edit Person '),
			'new_item' 			=> __( 'New Person '),
			'view_item' 		=> __( 'View Person '),
			'search_items' 		=> __( 'Search People '),
			'not_found' 		=>  __( 'No Person found' ),
			'not_found_in_trash'=> __( 'No People found in Trash' ),
			'parent_item_colon' => ''
		);
		
		$taxonomies = array();
		
		$supports = array('title','revisions');
		
		$post_type_args = array(
			'labels' 			=> $labels,
			'singular_label' 	=> __('Person'),
			'public' 			=> true,
			'show_ui' 			=> true,
			'publicly_queryable'=> true,
			'query_var'			=> true,
			'capability_type'   => 'person',
			'capabilities' => array(
				'publish_posts' => 'publish_persons',
				'edit_posts' => 'edit_persons',
				'edit_others_posts' => 'edit_others_persons',
				'delete_posts' => 'delete_persons',
				'delete_others_posts' => 'delete_others_persons',
				'read_private_posts' => 'read_private_persons',
				'edit_post' => 'edit_person',
				'delete_post' => 'delete_person',
				'read_post' => 'read_person',),			
			'has_archive' 		=> false,
			'hierarchical' 		=> false,
			'rewrite' 			=> array('slug' => 'directory', 'with_front' => false ),
			'supports' 			=> $supports,
			'menu_position' 	=> 5,
			'taxonomies'		=> $taxonomies
		 );
		 register_post_type('people',$post_type_args);
	}
	add_action('init', 'register_people_posttype');

// registration code for role taxonomy
function register_role_tax() {
	$labels = array(
		'name' 					=> _x( 'Roles', 'taxonomy general name' ),
		'singular_name' 		=> _x( 'Role', 'taxonomy singular name' ),
		'add_new' 				=> _x( 'Add New Role', 'Role'),
		'add_new_item' 			=> __( 'Add New Role' ),
		'edit_item' 			=> __( 'Edit Role' ),
		'new_item' 				=> __( 'New Role' ),
		'view_item' 			=> __( 'View Role' ),
		'search_items' 			=> __( 'Search Roles' ),
		'not_found' 			=> __( 'No Role found' ),
		'not_found_in_trash' 	=> __( 'No Role found in Trash' ),
	);
	
	$pages = array('people');
				
	$args = array(
		'labels' 			=> $labels,
		'singular_label' 	=> __('Role'),
		'public' 			=> true,
		'show_ui' 			=> true,
		'hierarchical' 		=> true,
		'show_tagcloud' 	=> false,
		'show_in_nav_menus' => false,
		'rewrite' 			=> array('slug' => 'role', 'with_front' => false ),
	 );
	register_taxonomy('role', $pages, $args);
}
add_action('init', 'register_role_tax');

function add_role_terms() {
	wp_insert_term('faculty', 'role',  array('description'=> 'Faculty Member','slug' => 'faculty'));
	wp_insert_term('staff', 'role',  array('description'=> 'Staff Member','slug' => 'staff'));
	wp_insert_term('job market candidate', 'role',  array('description'=> 'Job Market Candidate','slug' => 'job-market-candidate'));
	wp_insert_term('professor emeriti', 'role',  array('description'=> 'Professor Emeriti','slug' => 'professor-emeriti'));
	wp_insert_term('leadership', 'role',  array('description'=> 'Leadership','slug' => 'leadership'));
}
add_action('init', 'add_role_terms');

//Add directory filter taxonomy
function register_filter_tax() {
	$labels = array(
		'name' 					=> _x( 'Directory Filters', 'taxonomy general name' ),
		'singular_name' 		=> _x( 'Directory Filter', 'taxonomy singular name' ),
		'add_new' 				=> _x( 'Add New Directory Filter', 'Directory Filter'),
		'add_new_item' 			=> __( 'Add New Directory Filter' ),
		'edit_item' 			=> __( 'Edit Directory Filter' ),
		'new_item' 				=> __( 'New Directory Filter' ),
		'view_item' 			=> __( 'View Directory Filter' ),
		'search_items' 			=> __( 'Search Directory Filters' ),
		'not_found' 			=> __( 'No Directory Filter found' ),
		'not_found_in_trash' 	=> __( 'No Directory Filter found in Trash' ),
	);
	
	$pages = array('people');
				
	$args = array(
		'labels' 			=> $labels,
		'singular_label' 	=> __('Directory Filter'),
		'public' 			=> true,
		'show_ui' 			=> true,
		'hierarchical' 		=> true,
		'show_tagcloud' 	=> false,
		'show_in_nav_menus' => false,
		'rewrite' 			=> array('slug' => 'filter', 'with_front' => false ),
	 );
	register_taxonomy('filter', $pages, $args);
}
add_action('init', 'register_filter_tax');

//Add Personal details metabox
$personaldetails_3_metabox = array( 
	'id' => 'personaldetails',
	'title' => 'Personal Details',
	'page' => array('people'),
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(

				
				array(
					'name' 			=> 'Last Name (For Indexing)',
					'desc' 			=> '',
					'id' 			=> 'ecpt_people_alpha',
					'class' 		=> 'ecpt_people_alpha',
					'type' 			=> 'text',
					'rich_editor' 	=> 0,			
					'max' 			=> 0,
					'std'			=> ''													
				),
															
				array(
					'name' 			=> 'Position/Title',
					'desc' 			=> '',
					'id' 			=> 'ecpt_position',
					'class' 		=> 'ecpt_position',
					'type' 			=> 'text',
					'rich_editor' 	=> 0,			
					'max' 			=> 0,
					'std'			=> ''													
				),
															
				array(
					'name' 			=> 'Degrees',
					'desc' 			=> '',
					'id' 			=> 'ecpt_degrees',
					'class' 		=> 'ecpt_degrees',
					'type' 			=> 'text',
					'rich_editor' 	=> 0,			
					'max' 			=> 0,
					'std'			=> ''													
				),
															
				array(
					'name' 			=> 'Expertise/Research Interests',
					'desc' 			=> '',
					'id' 			=> 'ecpt_expertise',
					'class' 		=> 'ecpt_expertise',
					'type' 			=> 'text',
					'rich_editor' 	=> 0,			
					'max' 			=> 0,
					'std'			=> ''													
				),
															
				array(
					'name' 			=> 'Phone Number',
					'desc' 			=> '',
					'id' 			=> 'ecpt_phone',
					'class' 		=> 'ecpt_phone',
					'type' 			=> 'text',
					'rich_editor' 	=> 0,			
					'max' 			=> 0,
					'std'			=> ''													
				),
				array(
					'name' 			=> 'Fax Number',
					'desc' 			=> '',
					'id' 			=> 'ecpt_fax',
					'class' 		=> 'ecpt_fax',
					'type' 			=> 'text',
					'rich_editor' 	=> 0,			
					'max' 			=> 0,
					'std'			=> ''													
				),
				array(
					'name' 			=> 'Email Address',
					'desc' 			=> '',
					'id' 			=> 'ecpt_email',
					'class' 		=> 'ecpt_email',
					'type' 			=> 'text',
					'rich_editor' 	=> 0,			
					'max' 			=> 0,
					'std'			=> ''													
				),
															
				array(
					'name' 			=> 'Office Location',
					'desc' 			=> '',
					'id' 			=> 'ecpt_office',
					'class' 		=> 'ecpt_office',
					'type' 			=> 'text',
					'rich_editor' 	=> 0,			
					'max' 			=> 0,
					'std'			=> ''													
				),
															
				array(
					'name' 			=> 'Office Hours',
					'desc' 			=> '',
					'id' 			=> 'ecpt_hours',
					'class' 		=> 'ecpt_hours',
					'type' 			=> 'text',
					'rich_editor' 	=> 0,			
					'max' 			=> 0,
					'std'			=> ''													
				),
															
				array(
					'name' 			=> 'Personal Website',
					'desc' 			=> '',
					'id' 			=> 'ecpt_website',
					'class' 		=> 'ecpt_website',
					'type' 			=> 'text',
					'rich_editor' 	=> 0,			
					'max' 			=> 0,
					'std'			=> ''													
				),
				array(
					'name' 			=> 'Lab Website',
					'desc' 			=> '',
					'id' 			=> 'ecpt_lab_website',
					'class' 		=> 'ecpt_lab_website',
					'type' 			=> 'text',
					'rich_editor' 	=> 0,			
					'max' 			=> 0,
					'std'			=> ''													
				),
				array(
					'name' 			=> 'Microsoft Academic Author ID',
					'desc' 			=> 'Enter only your ID number.  For example if your url is http://academic.research.microsoft.com/Author/1944573/author-name you will enter only 1944573',
					'id' 			=> 'ecpt_microsoft_id',
					'class' 		=> 'ecpt_microsoft_id',
					'type' 			=> 'text',
					'rich_editor' 	=> 0,			
					'max' 			=> 0,
					'std'			=> ''													
				),												)
);			
			
add_action('admin_menu', 'ecpt_add_personaldetails_3_meta_box');
function ecpt_add_personaldetails_3_meta_box() {

	global $personaldetails_3_metabox;		

	foreach($personaldetails_3_metabox['page'] as $page) {
		add_meta_box($personaldetails_3_metabox['id'], $personaldetails_3_metabox['title'], 'ecpt_show_personaldetails_3_box', $page, 'normal', 'high', $personaldetails_3_metabox);
	}
}

// function to show meta boxes
function ecpt_show_personaldetails_3_box()	{
	global $post;
	global $personaldetails_3_metabox;
	global $ecpt_prefix;
	global $wp_version;
	
	// Use nonce for verification
	echo '<input type="hidden" name="ecpt_personaldetails_3_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
	
	echo '<table class="form-table">';

	foreach ($personaldetails_3_metabox['fields'] as $field) {
		// get current post meta data

		$meta = get_post_meta($post->ID, $field['id'], true);
		
		echo '<tr>',
				'<th style="width:20%"><label for="', $field['id'], '">', $field['name'], '</label></th>',
				'<td class="ecpt_field_type_' . str_replace(' ', '_', $field['type']) . '">';
		switch ($field['type']) {
			case 'text':
				echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" size="30" style="width:97%" /><br/>', '', $field['desc'];
				break;
		}
		echo     '<td>',
			'</tr>';
	}
	
	echo '</table>';
}	

add_action('save_post', 'ecpt_personaldetails_3_save');

// Save data from meta box
function ecpt_personaldetails_3_save($post_id) {
	global $post;
	global $personaldetails_3_metabox;
	
	// verify nonce
	if (!isset($_POST['ecpt_personaldetails_3_meta_box_nonce']) || !wp_verify_nonce($_POST['ecpt_personaldetails_3_meta_box_nonce'], basename(__FILE__))) {
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
	
	foreach ($personaldetails_3_metabox['fields'] as $field) {
	
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

//Add faculty info metabox
$facultyinformation_4_metabox = array( 
	'id' => 'facultyinformation',
	'title' => 'Faculty and Leadership Information',
	'page' => array('people'),
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(

				
				array(
					'name' 			=> 'Biography',
					'desc' 			=> '',
					'id' 			=> 'ecpt_bio',
					'class' 		=> 'ecpt_bio',
					'type' 			=> 'textarea',
					'rich_editor' 	=> 1,			
					'max' 			=> 0,
					'std'			=> ''													
				),
															
				array(
					'name' 			=> 'Research',
					'desc' 			=> '',
					'id' 			=> 'ecpt_research',
					'class' 		=> 'ecpt_research',
					'type' 			=> 'textarea',
					'rich_editor' 	=> 1,			
					'max' 			=> 0,
					'std'			=> ''													
				),
															
				array(
					'name' 			=> 'Teaching',
					'desc' 			=> '',
					'id' 			=> 'ecpt_teaching',
					'class' 		=> 'ecpt_teaching',
					'type' 			=> 'textarea',
					'rich_editor' 	=> 1,			
					'max' 			=> 0,
					'std'			=> ''													
				),
															
				array(
					'name' 			=> 'Publications',
					'desc' 			=> 'If you use Microsoft Academic do not enter anything here',
					'id' 			=> 'ecpt_publications',
					'class' 		=> 'ecpt_publications',
					'type' 			=> 'textarea',
					'rich_editor' 	=> 1,			
					'max' 			=> 0,
					'std'			=> ''													
				),
															
				array(
					'name' 			=> 'Extra Tab Title',
					'desc' 			=> '',
					'id' 			=> 'ecpt_extra_tab_title',
					'class' 		=> 'ecpt_extra_tab_title',
					'type' 			=> 'text',
					'rich_editor' 	=> 0,			
					'max' 			=> 0,
					'std'			=> ''													
				),
															
				array(
					'name' 			=> 'Extra Tab Content',
					'desc' 			=> '',
					'id' 			=> 'ecpt_extra_tab',
					'class' 		=> 'ecpt_extra_tab',
					'type' 			=> 'textarea',
					'rich_editor' 	=> 1,			
					'max' 			=> 0,
					'std'			=> ''													
				),
				array(
					'name' 			=> 'Extra Tab Title Two',
					'desc' 			=> '',
					'id' 			=> 'ecpt_extra_tab_title2',
					'class' 		=> 'ecpt_extra_tab_title2',
					'type' 			=> 'text',
					'rich_editor' 	=> 0,			
					'max' 			=> 0,
					'std'			=> ''
				),
															
				array(
					'name' 			=> 'Extra Tab Content Two',
					'desc' 			=> '',
					'id' 			=> 'ecpt_extra_tab2',
					'class' 		=> 'ecpt_extra_tab2',
					'type' 			=> 'textarea',
					'rich_editor' 	=> 1,			
					'max' 			=> 0,
					'std'			=> ''													
				),												)
);			
			
add_action('admin_menu', 'ecpt_add_facultyinformation_4_meta_box');
function ecpt_add_facultyinformation_4_meta_box() {

	global $facultyinformation_4_metabox;		

	foreach($facultyinformation_4_metabox['page'] as $page) {
		add_meta_box($facultyinformation_4_metabox['id'], $facultyinformation_4_metabox['title'], 'ecpt_show_facultyinformation_4_box', $page, 'normal', 'high', $facultyinformation_4_metabox);
	}
}

// function to show meta boxes
function ecpt_show_facultyinformation_4_box()	{
	global $post;
	global $facultyinformation_4_metabox;
	global $ecpt_prefix;
	global $wp_version;
	
	// Use nonce for verification
	echo '<input type="hidden" name="ecpt_facultyinformation_4_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
	
	echo '<table class="form-table">';

	foreach ($facultyinformation_4_metabox['fields'] as $field) {
		// get current post meta data

		$meta = get_post_meta($post->ID, $field['id'], true);
		
		echo '<tr>',
				'<th style="width:20%"><label for="', $field['id'], '">', $field['name'], '</label></th>',
				'<td class="ecpt_field_type_' . str_replace(' ', '_', $field['type']) . '">';
		switch ($field['type']) {
			case 'text':
				echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" size="30" style="width:97%" /><br/>', '', $field['desc'];
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
		}
		echo     '<td>',
			'</tr>';
	}
	
	echo '</table>';
}	

add_action('save_post', 'ecpt_facultyinformation_4_save');

// Save data from meta box
function ecpt_facultyinformation_4_save($post_id) {
	global $post;
	global $facultyinformation_4_metabox;
	// verify nonce
	if (!isset($_POST['ecpt_facultyinformation_4_meta_box_nonce']) || !wp_verify_nonce($_POST['ecpt_facultyinformation_4_meta_box_nonce'], basename(__FILE__))) {
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
	
	foreach ($facultyinformation_4_metabox['fields'] as $field) {
	
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

//Add uploads box
$uploadsforprofile_5_metabox = array( 
	'id' => 'uploadsforprofile',
	'title' => 'Uploads for Profile',
	'page' => array('people'),
	'context' => 'side',
	'priority' => 'default',
	'fields' => array(

				
				array(
					'name' 			=> 'Profile Photo',
					'desc' 			=> '465x700',
					'id' 			=> 'ecpt_people_photo',
					'class' 		=> 'ecpt_people_photo',
					'type' 			=> 'upload',
					'rich_editor' 	=> 0,			
					'max' 			=> 0,
					'std'			=> ''													
				),
															
				array(
					'name' 			=> 'Upload CV',
					'desc' 			=> '',
					'id' 			=> 'ecpt_cv',
					'class' 		=> 'ecpt_cv',
					'type' 			=> 'upload',
					'rich_editor' 	=> 0,			
					'max' 			=> 0,
					'std'			=> ''													
				),
															
				array(
					'name' 			=> 'Upload Abstract (for Job Candidates)',
					'desc' 			=> '',
					'id' 			=> 'ecpt_job_abstract',
					'class' 		=> 'ecpt_job_abstract',
					'type' 			=> 'upload',
					'rich_editor' 	=> 0,			
					'max' 			=> 0,
					'std'			=> ''													
				),
												)
);			
			
add_action('admin_menu', 'ecpt_add_uploadsforprofile_5_meta_box');
function ecpt_add_uploadsforprofile_5_meta_box() {

	global $uploadsforprofile_5_metabox;		

	foreach($uploadsforprofile_5_metabox['page'] as $page) {
		add_meta_box($uploadsforprofile_5_metabox['id'], $uploadsforprofile_5_metabox['title'], 'ecpt_show_uploadsforprofile_5_box', $page, 'side', 'default', $uploadsforprofile_5_metabox);
	}
}

// function to show meta boxes
function ecpt_show_uploadsforprofile_5_box()	{
	global $post;
	global $uploadsforprofile_5_metabox;
	global $ecpt_prefix;
	global $wp_version;
	
	// Use nonce for verification
	echo '<input type="hidden" name="ecpt_uploadsforprofile_5_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
	
	echo '<table class="form-table">';

	foreach ($uploadsforprofile_5_metabox['fields'] as $field) {
		// get current post meta data

		$meta = get_post_meta($post->ID, $field['id'], true);
		
		echo '<tr>',
				'<th style="width:20%"><label for="', $field['id'], '">', $field['name'], '</label></th>',
				'<td class="ecpt_field_type_' . str_replace(' ', '_', $field['type']) . '">';
		switch ($field['type']) {
			case 'upload':
				echo '<input type="text" class="ecpt_upload_field" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" size="30" style="width:80%" /><input class="ecpt_upload_image_button" type="button" value="Upload" /><br/>', '', $field['desc'];
				break;
		}
		echo     '<td>',
			'</tr>';
	}
	
	echo '</table>';
}	

add_action('save_post', 'ecpt_uploadsforprofile_5_save');

// Save data from meta box
function ecpt_uploadsforprofile_5_save($post_id) {
	global $post;
	global $uploadsforprofile_5_metabox;
	
	// verify nonce
	if (!isset($_POST['ecpt_uploadsforprofile_5_meta_box_nonce']) || !wp_verify_nonce($_POST['ecpt_uploadsforprofile_5_meta_box_nonce'], basename(__FILE__))) {
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
	
	foreach ($uploadsforprofile_5_metabox['fields'] as $field) {
	
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
?>