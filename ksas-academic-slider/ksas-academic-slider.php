<?php
/*
Plugin Name: KSAS Homepage Slider for Academic Template
Plugin URI: http://krieger2.jhu.edu/comm/web/plugins/slider
Description: Creates a custom post type for homepage slider.  This plugin is currently configured to only work with the Academic Template
Version: 1.0
Author: Cara Peckens
Author URI: mailto:cpeckens@jhu.edu
License: GPL2
*/
// registration code for slider post type
	function register_slider_posttype() {
		$labels = array(
			'name' 				=> _x( 'Slides', 'post type general name' ),
			'singular_name'		=> _x( 'Slide', 'post type singular name' ),
			'add_new' 			=> _x( 'Add New', 'Slide'),
			'add_new_item' 		=> __( 'Add New Slide '),
			'edit_item' 		=> __( 'Edit Slide '),
			'new_item' 			=> __( 'New Slide '),
			'view_item' 		=> __( 'View Slide '),
			'search_items' 		=> __( 'Search Slides '),
			'not_found' 		=>  __( 'No Slide found' ),
			'not_found_in_trash'=> __( 'No Slides found in Trash' ),
			'parent_item_colon' => ''
		);
		
		$taxonomies = array();
		
		$supports = array('title', 'editor','revisions');
		
		$post_type_args = array(
			'labels' 			=> $labels,
			'singular_label' 	=> __('Slide'),
			'public' 			=> true,
			'show_ui' 			=> true,
			'publicly_queryable'=> true,
			'query_var'			=> true,
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
			'rewrite' 			=> array('slug' => 'slider', 'with_front' => false ),
			'supports' 			=> $supports,
			'menu_position' 	=> 5,
			'taxonomies'		=> $taxonomies
		 );
		 register_post_type('slider',$post_type_args);
	}
	add_action('init', 'register_slider_posttype');

//Add Slider details metabox
$sliderinfo_2_metabox = array( 
	'id' => 'sliderinfo',
	'title' => 'Slider Info',
	'page' => array('slider'),
	'context' => 'normal',
	'priority' => 'default',
	'fields' => array(

				
				array(
					'name' 			=> 'Slide Image',
					'desc' 			=> 'Image needs to be 670x360',
					'id' 			=> 'ecpt_slideimage',
					'class' 		=> 'ecpt_slideimage',
					'type' 			=> 'upload',
					'rich_editor' 	=> 1,			
					'max' 			=> 0,
					'std'			=> ''													
				),
															
				array(
					'name' 			=> 'Slide Color',
					'desc' 			=> 'Choose yellow, blue, green. This is only relevant for the \"Blue\" variation of this theme.',
					'id' 			=> 'ecpt_slidecolor',
					'class' 		=> 'ecpt_slidecolor',
					'type' 			=> 'radio',
					'rich_editor' 	=> 1,			
					'options' => array('blueslide','yellowslide','greenslide'),
					'max' 			=> 0,
					'std'			=> ''													
				),
															
				array(
					'name' 			=> 'URL Destination',
					'desc' 			=> 'Enter url of destination page',
					'id' 			=> 'ecpt_urldestination',
					'class' 		=> 'ecpt_urldestination',
					'type' 			=> 'text',
					'rich_editor' 	=> 0,			
					'max' 			=> 0,
					'std'			=> ''													
				),
															
				array(
					'name' 			=> 'Find Out More Button',
					'desc' 			=> 'Add a Find Out More button after the caption',
					'id' 			=> 'ecpt_button',
					'class' 		=> 'ecpt_button',
					'type' 			=> 'checkbox',
					'rich_editor' 	=> 1,			
					'max' 			=> 0,
					'std'			=> ''													
				),
												)
);			
			
add_action('admin_menu', 'ecpt_add_sliderinfo_2_meta_box');
function ecpt_add_sliderinfo_2_meta_box() {

	global $sliderinfo_2_metabox;		

	foreach($sliderinfo_2_metabox['page'] as $page) {
		add_meta_box($sliderinfo_2_metabox['id'], $sliderinfo_2_metabox['title'], 'ecpt_show_sliderinfo_2_box', $page, 'normal', 'default', $sliderinfo_2_metabox);
	}
}

// function to show meta boxes
function ecpt_show_sliderinfo_2_box()	{
	global $post;
	global $sliderinfo_2_metabox;
	global $ecpt_prefix;
	global $wp_version;
	
	// Use nonce for verification
	echo '<input type="hidden" name="ecpt_sliderinfo_2_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
	
	echo '<table class="form-table">';

	foreach ($sliderinfo_2_metabox['fields'] as $field) {
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
				echo '<input type="text" class="ecpt_upload_field" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" size="30" style="width:80%" /><input class="ecpt_upload_image_button" type="button" value="Upload Image" /><br/>', '', stripslashes($field['desc']);
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

add_action('save_post', 'ecpt_sliderinfo_2_save');

// Save data from meta box
function ecpt_sliderinfo_2_save($post_id) {
	global $post;
	global $sliderinfo_2_metabox;
	
	// verify nonce
	if (!isset($_POST['ecpt_sliderinfo_2_meta_box_nonce']) || !wp_verify_nonce($_POST['ecpt_sliderinfo_2_meta_box_nonce'], basename(__FILE__))) {
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
	
	foreach ($sliderinfo_2_metabox['fields'] as $field) {
	
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