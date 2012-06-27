<?php
/*
Plugin Name: KSAS Homepage Accordion for Centers and Programs Template
Plugin URI: http://krieger2.jhu.edu/comm/web/plugins/accordion
Description: Creates a custom post type for homepage accordion.  This plugin is currently configured to only work with the Centers/Programs Template
Version: 1.0
Author: Cara Peckens
Author URI: mailto:cpeckens@jhu.edu
License: GPL2
*/

// registration code for accordion post type
	function register_accordion_posttype() {
		$labels = array(
			'name' 				=> _x( 'Accordions', 'post type general name' ),
			'singular_name'		=> _x( 'Accordion', 'post type singular name' ),
			'add_new' 			=> _x( 'Add New', 'Accordion'),
			'add_new_item' 		=> __( 'Add New Accordion '),
			'edit_item' 		=> __( 'Edit Accordion '),
			'new_item' 			=> __( 'New Accordion '),
			'view_item' 		=> __( 'View Accordion '),
			'search_items' 		=> __( 'Search Accordions '),
			'not_found' 		=>  __( 'No Accordion found' ),
			'not_found_in_trash'=> __( 'No Accordions found in Trash' ),
			'parent_item_colon' => ''
		);
		
		$taxonomies = array();
		
		$supports = array('title','editor','custom-fields','revisions');
		
		$post_type_args = array(
			'labels' 			=> $labels,
			'singular_label' 	=> __('Accordion'),
			'public' 			=> true,
			'show_ui' 			=> true,
			'publicly_queryable'=> true,
			'capability_type'   => 'slide',
			'capabilities' => array(
				'publish_posts' => 'publish_slides',
				'edit_posts' => 'edit_slides',
				'edit_others_posts' => 'edit_others_slides',
				'delete_posts' => 'delete_slides',
				'delete_others_posts' => 'delete_others_slides',
				'read_private_posts' => 'read_private_slides',
				'edit_post' => 'edit_slide',
				'delete_post' => 'delete_slide',
				'read_post' => 'read_slide',),			
			'has_archive' 		=> false,
			'hierarchical' 		=> false,
			'rewrite' 			=> array('slug' => 'accordions', 'with_front' => false ),
			'supports' 			=> $supports,
			'menu_position' 	=> 5,
			'taxonomies'		=> $taxonomies
		 );
		 register_post_type('accordion',$post_type_args);
	}
	add_action('init', 'register_accordion_posttype');		

$accordion_details_9_metabox = array( 
	'id' => 'accordion_details',
	'title' => 'accordion_details',
	'page' => array('accordion'),
	'context' => 'normal',
	'priority' => 'core',
	'fields' => array(

				
				array(
					'name' 			=> 'Redirect Destination',
					'desc' 			=> 'URL of the page you would like the slide redirect to. Must include http://',
					'id' 			=> 'ecpt_accordion_destination',
					'class' 		=> 'ecpt_accordion_destination',
					'type' 			=> 'text',
					'rich_editor' 	=> 0,			
					'max' 			=> 0													
				),
															
				array(
					'name' 			=> 'Accordion Photo Full',
					'desc' 			=> '(750px by 420px)',
					'id' 			=> 'ecpt_accordion_photo',
					'class' 		=> 'ecpt_accordion_photo',
					'type' 			=> 'upload',
					'rich_editor' 	=> 0,			
					'max' 			=> 0													
				),
															
				array(
					'name' 			=> 'Accordion Photo Strip',
					'desc' 			=> '240px by 422px. Cropped version of full photo',
					'id' 			=> 'ecpt_accordion_strip',
					'class' 		=> 'ecpt_accordion_strip',
					'type' 			=> 'upload',
					'rich_editor' 	=> 0,			
					'max' 			=> 0													
				),
												)
);			
			
add_action('admin_menu', 'ecpt_add_accordion_details_9_meta_box');
function ecpt_add_accordion_details_9_meta_box() {

	global $accordion_details_9_metabox;		

	foreach($accordion_details_9_metabox['page'] as $page) {
		add_meta_box($accordion_details_9_metabox['id'], $accordion_details_9_metabox['title'], 'ecpt_show_accordion_details_9_box', $page, 'normal', 'core', $accordion_details_9_metabox);
	}
}

// function to show meta boxes
function ecpt_show_accordion_details_9_box()	{
	global $post;
	global $accordion_details_9_metabox;
	global $ecpt_prefix;
	global $wp_version;
	
	// Use nonce for verification
	echo '<input type="hidden" name="ecpt_accordion_details_9_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
	
	echo '<table class="form-table">';

	foreach ($accordion_details_9_metabox['fields'] as $field) {
		// get current post meta data

		$meta = get_post_meta($post->ID, $field['id'], true);
		
		echo '<tr>',
				'<th style="width:20%"><label for="', $field['id'], '">', stripslashes($field['name']), '</label></th>',
				'<td class="ecpt_field_type_' . str_replace(' ', '_', $field['type']) . '">';
		switch ($field['type']) {
			case 'text':
				echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" size="30" style="width:97%" /><br/>', '', stripslashes($field['desc']);
				break;
			case 'date':
				if($meta) { $value = ecpt_timestamp_to_date($meta); } else {  $value = ''; }
				echo '<input type="text" class="ecpt_datepicker" name="' . $field['id'] . '" id="' . $field['id'] . '" value="'. $value . '" size="30" style="width:97%" />' . '' . stripslashes($field['desc']);
				break;
			case 'upload':
				echo '<input type="text" class="ecpt_upload_field" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" size="30" style="width:80%" /><input class="ecpt_upload_image_button" type="button" value="Upload" /><br/>', '', stripslashes($field['desc']);
				break;
			case 'textarea':
			
				if($field['rich_editor'] == 1) {
					if($wp_version >= 3.3) {
						echo wp_editor($meta, $field['id'], array('textarea_name' => $field['id']));
					} else {
						// older versions of WP
						$editor = '';
						if(!post_type_supports($post->post_type, 'editor')) {
							$editor = wp_tiny_mce(true, array('editor_selector' => $field['class'], 'remove_linebreaks' => false) );
						}
						$field_html = '<div style="width: 97%; border: 1px solid #DFDFDF;"><textarea name="' . $field['id'] . '" class="' . $field['class'] . '" id="' . $field['id'] . '" cols="60" rows="8" style="width:100%">'. $meta . '</textarea></div><br/>' . __(stripslashes($field['desc']));
						echo $editor . $field_html;
					}
				} else {
					echo '<div style="width: 100%;"><textarea name="', $field['id'], '" class="', $field['class'], '" id="', $field['id'], '" cols="60" rows="8" style="width:97%">', $meta ? $meta : $field['std'], '</textarea></div>', '', stripslashes($field['desc']);				
				}
				
				break;
			case 'select':
				echo '<select name="', $field['id'], '" id="', $field['id'], '">';
				foreach ($field['options'] as $option) {
					echo '<option value="' . $option . '"', $meta == $option ? ' selected="selected"' : '', '>', $option, '</option>';
				}
				echo '</select>', '', stripslashes($field['desc']);
				break;
			case 'radio':
				foreach ($field['options'] as $option) {
					echo '<input type="radio" name="', $field['id'], '" value="', $option, '"', $meta == $option ? ' checked="checked"' : '', ' />&nbsp;', $option;
				}
				echo '<br/>' . stripslashes($field['desc']);
				break;
			case 'checkbox':
				echo '<input type="checkbox" name="', $field['id'], '" id="', $field['id'], '"', $meta ? ' checked="checked"' : '', ' />&nbsp;';
				echo stripslashes($field['desc']);
				break;
			case 'slider':
				echo '<input type="text" rel="' . $field['max'] . '" name="' . $field['id'] . '" id="' . $field['id'] . '" value="' . $meta . '" size="1" style="float: left; margin-right: 5px" />';
				echo '<div class="ecpt-slider" rel="' . $field['id'] . '" style="float: left; width: 60%; margin: 5px 0 0 0;"></div>';		
				echo '<div style="width: 100%; clear: both;">' . stripslashes($field['desc']) . '</div>';
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

add_action('save_post', 'ecpt_accordion_details_9_save');

// Save data from meta box
function ecpt_accordion_details_9_save($post_id) {
	global $post;
	global $accordion_details_9_metabox;
	
	// verify nonce
	if (!wp_verify_nonce($_POST['ecpt_accordion_details_9_meta_box_nonce'], basename(__FILE__))) {
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
	
	foreach ($accordion_details_9_metabox['fields'] as $field) {
	
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

								
				