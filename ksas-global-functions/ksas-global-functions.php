<?php
/*
Plugin Name: KSAS Global Functions
Plugin URI: http://krieger2.jhu.edu/comm/web/plugins/people
Description: This plugin should be network activated.  Provides functions for creating the Academic Department Taxonomy, and formatting meta boxes, providing upload capability, and global security needs.
Version: 1.0
Author: Cara Peckens
Author URI: mailto:cpeckens@jhu.edu
License: GPL2
*/

/*****************SECURITY AND PERFORMANCE FUNCTIONS*****************************/
//Prevent login errors - attacker prevention
	add_filter('login_errors', create_function('$a', "return null;"));

//Block malicious queries - Based on http://perishablepress.com/press/2009/12/22/protect-wordpress-against-malicious-url-requests/
	global $user_ID;
	
	if($user_ID) {
	  if(!current_user_can('level_10')) {
	    if (strlen($_SERVER['REQUEST_URI']) > 255 ||
	      strpos($_SERVER['REQUEST_URI'], "eval(") ||
	      strpos($_SERVER['REQUEST_URI'], "CONCAT") ||
	      strpos($_SERVER['REQUEST_URI'], "UNION+SELECT") ||
	      strpos($_SERVER['REQUEST_URI'], "base64")) {
	        @header("HTTP/1.1 414 Request-URI Too Long");
		@header("Status: 414 Request-URI Too Long");
		@header("Connection: Close");
		@exit;
	    }
	  }
	}
// remove junk from head
	remove_action('wp_head', 'rsd_link');
	remove_action('wp_head', 'wp_generator');
	remove_action('wp_head', 'feed_links', 2);
	remove_action('wp_head', 'index_rel_link');
	remove_action('wp_head', 'wlwmanifest_link');
	remove_action('wp_head', 'feed_links_extra', 3);
	remove_action('wp_head', 'start_post_rel_link', 10, 0);
	remove_action('wp_head', 'parent_post_rel_link', 10, 0);
	remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0);

// remove version info from head and feeds
    function complete_version_removal() {
    	return '';
    }
    add_filter('the_generator', 'complete_version_removal');

//removes admin bar from front end
	/* add_filter( 'show_admin_bar', '__return_false' ); */

//remove unneccessary classes for navigation menus
	add_filter('nav_menu_css_class', 'ksasaca_css_attributes_filter', 100, 1);
	add_filter('nav_menu_item_id', 'ksasaca_css_attributes_filter', 100, 1);
	add_filter('page_css_class', 'ksasaca_css_attributes_filter', 100, 1);
	function ksasaca_css_attributes_filter($var) {
		 $newnavclasses = is_array($var) ? array_intersect($var, array('current-menu-item', 'current_page_item', 'current-page-ancestor', 'current-page-parent')) : '';
		 return $newnavclasses;
	}

/*****************TAXONOMIES*****************************/
// registration code for academicdepartment taxonomy
	function register_academicdepartment_tax() {
		$labels = array(
			'name' 					=> _x( 'Departments', 'taxonomy general name' ),
			'singular_name' 		=> _x( 'Department', 'taxonomy singular name' ),
			'add_new' 				=> _x( 'Add New Department', 'Department'),
			'add_new_item' 			=> __( 'Add New Department' ),
			'edit_item' 			=> __( 'Edit Department' ),
			'new_item' 				=> __( 'New Department' ),
			'view_item' 			=> __( 'View Department' ),
			'search_items' 			=> __( 'Search Departments' ),
			'not_found' 			=> __( 'No Department found' ),
			'not_found_in_trash' 	=> __( 'No Department found in Trash' ),
		);
		
		$pages = array('courses','people','profile','post');
					
		$args = array(
			'labels' 			=> $labels,
			'singular_label' 	=> __('Department'),
			'public' 			=> true,
			'show_ui' 			=> true,
			'hierarchical' 		=> true,
			'show_tagcloud' 	=> false,
			'show_in_nav_menus' => false,
			'rewrite' 			=> array('slug' => 'department', 'with_front' => false ),
		 );
		register_taxonomy('academicdepartment', $pages, $args);
	}
	add_action('init', 'register_academicdepartment_tax');
	
	//Prepopulate choices
	function add_academicdepartment_terms() {
		wp_insert_term('Anthropology', 'academicdepartment',  array('description'=> '','slug' => 'anthropology'));
		wp_insert_term('Biology', 'academicdepartment',  array('description'=> '','slug' => 'bio'));
		wp_insert_term('Biophysics', 'academicdepartment',  array('description'=> '','slug' => 'biophysics'));
		wp_insert_term('Chemistry', 'academicdepartment',  array('description'=> '','slug' => 'chemistry'));
		wp_insert_term('Classics', 'academicdepartment',  array('description'=> '','slug' => 'classics'));
		wp_insert_term('Cognitive Science', 'academicdepartment',  array('description'=> '','slug' => 'cogsci'));
		wp_insert_term('Earth and Planetary Sciences', 'academicdepartment',  array('description'=> '','slug' => 'eps'));
		wp_insert_term('Economics', 'academicdepartment',  array('description'=> '','slug' => 'econ'));
		wp_insert_term('English', 'academicdepartment',  array('description'=> '','slug' => 'english'));
		wp_insert_term('German and Romance Languages', 'academicdepartment',  array('description'=> '','slug' => 'grll'));
		wp_insert_term('History', 'academicdepartment',  array('description'=> '','slug' => 'history'));
		wp_insert_term('History of Art', 'academicdepartment',  array('description'=> '','slug' => 'arthist'));
		wp_insert_term('History of Science and Technology', 'academicdepartment',  array('description'=> '','slug' => 'host'));
		wp_insert_term('Humanities', 'academicdepartment',  array('description'=> '','slug' => 'humanities'));
		wp_insert_term('Mathematics', 'academicdepartment',  array('description'=> '','slug' => 'math'));
		wp_insert_term('Near Eastern Studies', 'academicdepartment',  array('description'=> '','slug' => 'neareast'));
		wp_insert_term('Philosophy', 'academicdepartment',  array('description'=> '','slug' => 'philosophy'));
		wp_insert_term('Physics and Astronomy', 'academicdepartment',  array('description'=> '','slug' => 'physics'));
		wp_insert_term('Political Science', 'academicdepartment',  array('description'=> '','slug' => 'polisci'));
		wp_insert_term('Psychological and Brain Sciences', 'academicdepartment',  array('description'=> '','slug' => 'pbs'));
		wp_insert_term('Sociology', 'academicdepartment',  array('description'=> '','slug' => 'soc'));
		wp_insert_term('Writing Seminars', 'academicdepartment',  array('description'=> '','slug' => 'writing'));
	}
	add_action('init', 'add_academicdepartment_terms');

// registration code for affiliation taxonomy
	function register_affiliation_tax() {
		$labels = array(
			'name' 					=> _x( 'Affiliations', 'taxonomy general name' ),
			'singular_name' 		=> _x( 'Affiliation', 'taxonomy singular name' ),
			'add_new' 				=> _x( 'Add New Affiliation', 'Affiliation'),
			'add_new_item' 			=> __( 'Add New Affiliation' ),
			'edit_item' 			=> __( 'Edit Affiliation' ),
			'new_item' 				=> __( 'New Affiliation' ),
			'view_item' 			=> __( 'View Affiliation' ),
			'search_items' 			=> __( 'Search Affiliations' ),
			'not_found' 			=> __( 'No Affiliation found' ),
			'not_found_in_trash' 	=> __( 'No Affiliation found in Trash' ),
		);
		
		$pages = array('people');
					
		$args = array(
			'labels' 			=> $labels,
			'singular_label' 	=> __('Affiliation'),
			'public' 			=> true,
			'show_ui' 			=> true,
			'hierarchical' 		=> true,
			'show_tagcloud' 	=> false,
			'show_in_nav_menus' => false,
			'rewrite' 			=> array('slug' => 'affiliation', 'with_front' => false ),
		 );
		register_taxonomy('affiliation', $pages, $args);
	}
	add_action('init', 'register_affiliation_tax');
	
	function add_affiliation_terms() {
		wp_insert_term('Africana Studies', 'affiliation',  array('slug' => 'africana'));
		wp_insert_term('Archaeology', 'affiliation',  array('slug' => 'archaeology'));	
		wp_insert_term('Astrophysical Sciences', 'affiliation',  array('slug' => 'astro'));
		wp_insert_term('Behavioral Biology', 'affiliation',  array('slug' => 'behavbio'));
		wp_insert_term('Biophysical Research', 'affiliation',  array('slug' => 'biophys'));
		wp_insert_term('Financial Economics', 'affiliation',  array('slug' => 'cfe'));
		wp_insert_term('China STEM', 'affiliation',  array('slug' => 'chinastem'));
		wp_insert_term('CMDB Program', 'affiliation',  array('slug' => 'cmdb'));
		wp_insert_term('East Asian', 'affiliation',  array('slug' => 'eastasian'));
		wp_insert_term('Embryology', 'affiliation',  array('slug' => 'embryo'));
		wp_insert_term('Expository Writing', 'affiliation',  array('slug' => 'ewp'));
		wp_insert_term('Film and Media', 'affiliation',  array('slug' => 'film'));
		wp_insert_term('Applied Economics', 'affiliation',  array('slug' => 'iae'));
		wp_insert_term('International Studies', 'affiliation',  array('slug' => 'international'));
		wp_insert_term('Jewish Studies', 'affiliation',  array('slug' => 'jewish'));
		wp_insert_term('Language Education', 'affiliation',  array('slug' => 'cledu'));
		wp_insert_term('Latin American Studies', 'affiliation',  array('slug' => 'plas'));
		wp_insert_term('Materials Research', 'affiliation',  array('slug' => 'materials'));
		wp_insert_term('Mind Brain Institute', 'affiliation',  array('slug' => 'mindbrain'));
		wp_insert_term('Modern German Thought', 'affiliation',  array('slug' => 'maxkade'));
		wp_insert_term('Museums and Society', 'affiliation',  array('slug' => 'museums'));
		wp_insert_term('Neuroscience', 'affiliation',  array('slug' => 'neuroscience'));
		wp_insert_term('Odyssey', 'affiliation',  array('slug' => 'odyssey'));
		wp_insert_term('Osher Lifelong', 'affiliation',  array('slug' => 'osher'));
		wp_insert_term('Policy Studies', 'affiliation',  array('slug' => 'policystudies'));
		wp_insert_term('Premodern Europe', 'affiliation',  array('slug' => 'singleton'));
		wp_insert_term('Public Health', 'affiliation',  array('slug' => 'publichealth'));
		wp_insert_term('Theatre Arts', 'affiliation',  array('slug' => 'theatre'));
		wp_insert_term('Women Gender and Sexuality', 'affiliation',  array('slug' => 'wgs'));
		wp_insert_term('Writing Center', 'affiliation',  array('slug' => 'writingcenter'));
	}
	add_action('init', 'add_affiliation_terms');

/*****************CUSTOM POST TYPE UI FUNCTIONS*****************************/
function ecpt_export_ui_scripts() {

	global $ecpt_options;
?> 
<script type="text/javascript">
		jQuery(document).ready(function($)
		{
			
			if($('.form-table .ecpt_upload_field').length > 0 ) {
				// Media Uploader
				window.formfield = '';

				$('.ecpt_upload_image_button').live('click', function() {
				window.formfield = $('.ecpt_upload_field',$(this).parent());
            	tb_show('', 'media-upload.php?post_id='+post_vars.post_id+'&TB_iframe=true');
									return false;
					});

					window.original_send_to_editor = window.send_to_editor;
					window.send_to_editor = function(html) {
						if (window.formfield) {
							imgurl = $('a','<div>'+html+'</div>').attr('href');
							window.formfield.val(imgurl);
							tb_remove();
						}
						else {
							window.original_send_to_editor(html);
						}
						window.formfield = '';
						window.imagefield = false;
					}
			}
			if($('.form-table .ecpt-slider').length > 0 ) {
				$('.ecpt-slider').each(function(){
					var $this = $(this);
					var id = $this.attr('rel');
					var val = $('#' + id).val();
					var max = $('#' + id).attr('rel');
					max = parseInt(max);
					//var step = $('#' + id).closest('input').attr('rel');
					$this.slider({
						value: val,
						max: max,
						step: 1,
						slide: function(event, ui) {
							$('#' + id).val(ui.value);
						}
					});
				});
			}
			
			if($('.form-table .ecpt_datepicker').length > 0 ) {
				var dateFormat = 'mm/dd/yy';
				$('.ecpt_datepicker').datepicker({dateFormat: dateFormat});
			}
			
			// add new repeatable field
			$(".ecpt_add_new_field").on('click', function() {					
				var field = $(this).closest('td').find("div.ecpt_repeatable_wrapper:last").clone(true);
				var fieldLocation = $(this).closest('td').find('div.ecpt_repeatable_wrapper:last');
				// get the hidden field that has the name value
				var name_field = $("input.ecpt_repeatable_field_name", ".ecpt_field_type_repeatable:first");
				// set the base of the new field name
				var name = $(name_field).attr("id");
				// set the new field val to blank
				$('input', field).val("");
				
				// set up a count var
				var count = 0;
				$('.ecpt_repeatable_field').each(function() {
					count = count + 1;
				});
				name = name + '[' + count + ']';
				$('input', field).attr("name", name);
				$('input', field).attr("id", name);
				field.insertAfter(fieldLocation, $(this).closest('td'));

				return false;
			});		

			// add new repeatable upload field
			$(".ecpt_add_new_upload_field").on('click', function() {	
				var container = $(this).closest('tr');
				var field = $(this).closest('td').find("div.ecpt_repeatable_upload_wrapper:last").clone(true);
				var fieldLocation = $(this).closest('td').find('div.ecpt_repeatable_upload_wrapper:last');
				// get the hidden field that has the name value
				var name_field = $("input.ecpt_repeatable_upload_field_name", container);
				// set the base of the new field name
				var name = $(name_field).attr("id");
				// set the new field val to blank
				$('input[type="text"]', field).val("");
				
				// set up a count var
				var count = 0;
				$('.ecpt_repeatable_upload_field', container).each(function() {
					count = count + 1;
				});
				name = name + '[' + count + ']';
				$('input', field).attr("name", name);
				$('input', field).attr("id", name);
				field.insertAfter(fieldLocation, $(this).closest('td'));

				return false;
			});
			
			// remove repeatable field
			$('.ecpt_remove_repeatable').on('click', function(e) {
				e.preventDefault();
				var field = $(this).parent();
				$('input', field).val("");
				field.remove();				
				return false;
			});											
													
		});
  </script>
<?php
}

function ecpt_export_datepicker_ui_scripts() {
	global $ecpt_base_dir, $post;
	wp_enqueue_script('thickbox');
	wp_enqueue_script('media-upload');
	wp_enqueue_script('ecpt-ui', $ecpt_base_dir . 'includes/js/ui-scripts.js', array('jquery')); 
	wp_localize_script( 'ecpt-ui', 'post_vars', 
		array( 
			'post_id' => $post->ID
		) 
	);
	wp_enqueue_script('jquery-ui-datepicker');
	wp_enqueue_script('jquery-ui-slider');
}
function ecpt_export_datepicker_ui_styles() {
	global $ecpt_base_dir;
	wp_enqueue_style('jquery-ui-css', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css', false, '1.8', 'all');
}

// these are for newest versions of WP
add_action('admin_print_scripts-post.php', 'ecpt_export_datepicker_ui_scripts');
add_action('admin_print_scripts-edit.php', 'ecpt_export_datepicker_ui_scripts');
add_action('admin_print_scripts-post-new.php', 'ecpt_export_datepicker_ui_scripts');
add_action('admin_print_styles-post.php', 'ecpt_export_datepicker_ui_styles');
add_action('admin_print_styles-edit.php', 'ecpt_export_datepicker_ui_styles');
add_action('admin_print_styles-post-new.php', 'ecpt_export_datepicker_ui_styles');

if ((isset($_GET['post']) && (isset($_GET['action']) && $_GET['action'] == 'edit') ) || (strstr($_SERVER['REQUEST_URI'], 'wp-admin/post-new.php')))
{
	add_action('admin_head', 'ecpt_export_ui_scripts');
}

// converts a time stamp to date string for meta fields
if(!function_exists('ecpt_timestamp_to_date')) {
	function ecpt_timestamp_to_date($date) {
		
		return date('m/d/Y', $date);
	}
}
if(!function_exists('ecpt_format_date')) {
	function ecpt_format_date($date) {

		$date = strtotime($date);
		
		return $date;
	}
}

/*****************RESPONSIVE IMAGES - REMOVES WIDTH/HEIGHT ATTRIBUTES FROM IMAGE INSERT*****************************/
function ksas_responsive_images( $value = false, $id, $size ) {
    if ( !wp_attachment_is_image($id) )
        return false;

    $img_url = wp_get_attachment_url($id);
    $is_intermediate = false;
    $img_url_basename = wp_basename($img_url);

    // try for a new style intermediate size
    if ( $intermediate = image_get_intermediate_size($id, $size) ) {
        $img_url = str_replace($img_url_basename, $intermediate['file'], $img_url);
        $is_intermediate = true;
    }
    elseif ( $size == 'thumbnail' ) {
        // Fall back to the old thumbnail
        if ( ($thumb_file = wp_get_attachment_thumb_file($id)) && $info = getimagesize($thumb_file) ) {
            $img_url = str_replace($img_url_basename, wp_basename($thumb_file), $img_url);
            $is_intermediate = true;
        }
    }

    // We have the actual image size, but might need to further constrain it if content_width is narrower
    if ( $img_url) {
        return array( $img_url, 0, 0, $is_intermediate );
    }
    return false;
}

add_filter( 'image_downsize', 'ksas_responsive_images', 1, 3 );

/*****************REMOVE UNWANTED WIDGETS*****************************/

function unregister_default_wp_widgets() {
	unregister_widget('WP_Widget_Pages');
	unregister_widget('WP_Widget_Calendar');
	unregister_widget('WP_Widget_Archives');
	unregister_widget('WP_Widget_Meta');
	unregister_widget('WP_Widget_Categories');
	unregister_widget('WP_Widget_Recent_Comments');
	unregister_widget('WP_Widget_RSS');
	unregister_widget('WP_Widget_Tag_Cloud');
}
add_action('widgets_init', 'unregister_default_wp_widgets', 1);

?>