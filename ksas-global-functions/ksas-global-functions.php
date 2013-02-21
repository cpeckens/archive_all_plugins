<?php
/*
Plugin Name: KSAS Global Functions
Plugin URI: http://krieger.jhu.edu/news/communications/web/plugins/global
Description: This plugin should be network activated.  Provides functions for creating the Academic Department and Affiliation Taxonomies, formats meta boxes, provides upload capability, change "Posts" labels to "News", removes unnecessary classes from navigation menus, removes unwanted widgets, responsive images (remove width/height attributes) function, add custom post type capabilities for user roles. and global security needs.
Version: 1.1
Author: Cara Peckens
Author URI: mailto:cpeckens@jhu.edu
License: GPL2
*/

/*****************TABLE OF CONTENTS***************
	1.0 Security and Performance Functions
		1.1 Prevent Login Errors
		1.2 Block Malicious Queries
		1.3 Remove Junk from Head
	2.0 Taxonomies
		2.1 academicdepartment Taxonomy
		2.2 academicdepartment Terms
		2.3 affiliation Taxonomy
		2.4 affiliation Terms
	3.0 Custom Post Type UI Functions *NOTE - Check these when Easy Content Types plugin is updated
	4.0 Responsive Images
	5.0 Remove Unwanted Widgets
	6.0 Change Posts Labels to News
	7.0 User Capabilities for Custom Post Types
	8.0 Theme Functions
		8.1 Pagination
		8.2 Return the slug
		8.3 Subpage of conditional statement
		8.4 Get page id from page slug
		8.5 In taxonomy conditional statement	
9.0 Navigation and Menus
		9.1 Walker class for foundation
		9.2 Walker class for mobile dropdowns
		9.3 Remove CSS classes from menu
		9.4 Walker class for breadcrumbs
		9.5 Walker class for tertiary

/*****************1.0 SECURITY AND PERFORMANCE FUNCTIONS*****************************/
	// 1.1 Prevent login errors - attacker prevention
		add_filter('login_errors', create_function('$a', "return null;"));
	
	// 1.2 Block malicious queries - Based on http://perishablepress.com/press/2009/12/22/protect-wordpress-against-malicious-url-requests/
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
	// 1.3 remove junk from head
		remove_action('wp_head', 'rsd_link');
		remove_action('wp_head', 'wp_generator');
		remove_action('wp_head', 'feed_links', 2);
		remove_action('wp_head', 'index_rel_link');
		remove_action('wp_head', 'wlwmanifest_link');
		remove_action('wp_head', 'feed_links_extra', 3);
		remove_action('wp_head', 'start_post_rel_link', 10, 0);
		remove_action('wp_head', 'parent_post_rel_link', 10, 0);
		remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0);
	
		//remove version info from head and feeds
		    function complete_version_removal() {
		    	return '';
		    }
		    add_filter('the_generator', 'complete_version_removal');

/*****************2.0 TAXONOMIES*****************************/
	// 2.1 registration code for academicdepartment taxonomy
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
			
			$pages = array('courses','people','profile','post', 'studyfields', 'deptextra');
						
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
	
	// 2.2 Prepopulate choices for academicdepartment taxonomy
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

	// 2.3 registration code for affiliation taxonomy
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
			
			$pages = array('people','post', 'studyfields', 'deptextra');
						
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
	// 2.4 Prepopulate choices for affiliation taxonomy
		function add_affiliation_terms() {
			wp_insert_term('Advanced Media Studies', 'affiliation',  array('slug' => 'cams'));
			wp_insert_term('Africana Studies', 'affiliation',  array('slug' => 'africana'));
			wp_insert_term('Archaeology', 'affiliation',  array('slug' => 'archaeology'));	
			wp_insert_term('Behavioral Biology', 'affiliation',  array('slug' => 'behavbio'));
			wp_insert_term('China STEM', 'affiliation',  array('slug' => 'chinastem'));
			wp_insert_term('Dance', 'affiliation',  array('slug' => 'dance'));
			wp_insert_term('Engineering', 'affiliation',  array('slug' => 'engineering'));
			wp_insert_term('East Asian', 'affiliation',  array('slug' => 'eastasian'));
			wp_insert_term('Embryology', 'affiliation',  array('slug' => 'embryo'));
			wp_insert_term('Expository Writing', 'affiliation',  array('slug' => 'ewp'));
			wp_insert_term('Film and Media', 'affiliation',  array('slug' => 'film'));
			wp_insert_term('Financial Economics', 'affiliation',  array('slug' => 'cfe'));
			wp_insert_term('Global Studies', 'affiliation',  array('slug' => 'arrighi'));
			wp_insert_term('International Studies', 'affiliation',  array('slug' => 'international'));
			wp_insert_term('Jewish Studies', 'affiliation',  array('slug' => 'jewish'));
			wp_insert_term('Language Education', 'affiliation',  array('slug' => 'cledu'));
			wp_insert_term('Latin American Studies', 'affiliation',  array('slug' => 'plas'));
			wp_insert_term('Mind Brain Institute', 'affiliation',  array('slug' => 'mindbrain'));
			wp_insert_term('Modern German Thought', 'affiliation',  array('slug' => 'maxkade'));
			wp_insert_term('Museums and Society', 'affiliation',  array('slug' => 'museums'));
			wp_insert_term('Music', 'affiliation',  array('slug' => 'music'));
			wp_insert_term('Neuroscience', 'affiliation',  array('slug' => 'neuroscience'));
			wp_insert_term('Post-Bac Pre-Med', 'affiliation',  array('slug' => 'pbpm'));
			wp_insert_term('Pre-Law', 'affiliation',  array('slug' => 'prelaw'));
			wp_insert_term('Pre-Med', 'affiliation',  array('slug' => 'premed'));
			wp_insert_term('Premodern Europe', 'affiliation',  array('slug' => 'singleton'));
			wp_insert_term('Public Health', 'affiliation',  array('slug' => 'publichealth'));
			wp_insert_term('Quantum Matter', 'affiliation',  array('slug' => 'quantum'));
			wp_insert_term('Theatre Arts', 'affiliation',  array('slug' => 'theatre'));
			wp_insert_term('Visual Arts', 'affiliation',  array('slug' => 'visual'));
			wp_insert_term('Women Gender and Sexuality', 'affiliation',  array('slug' => 'wgs'));
			wp_insert_term('Writing Center', 'affiliation',  array('slug' => 'writingcenter'));
		}
		add_action('init', 'add_affiliation_terms');

/*****************3.0 CUSTOM POST TYPE UI FUNCTIONS*****************************/
	//**NOTE** Check these functions when the Easy Content Types Plugin is updated 
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
						var send_attachment_bkp = wp.media.editor.send.attachment;
						var button = $(this);
	
	        wp.media.editor.send.attachment = function(props, attachment) {
	
	            $(button).prev().prev().attr('src', attachment.url);
	            $(button).prev().val(attachment.url);
	
	            wp.media.editor.send.attachment = send_attachment_bkp;
	        }
	
	        wp.media.editor.open(button);
	
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

/*****************4.0 RESPONSIVE IMAGES - REMOVES WIDTH/HEIGHT ATTRIBUTES FROM IMAGE INSERT*****************************/
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

/*****************5.0 REMOVE UNWANTED WIDGETS*****************************/

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

/*****************6.0 CHANGE POSTS LABELS TO NEWS*****************************/
	function change_post_menu_label() {
		global $menu;
		global $submenu;
		$menu[5][0] = 'News';
		$submenu['edit.php'][5][0] = 'News';
		$submenu['edit.php'][10][0] = 'Add News';
		$submenu['edit.php'][16][0] = 'News Tags';
		echo '';
	}
	function change_post_object_label() {
		global $wp_post_types;
		$labels = &$wp_post_types['post']->labels;
		$labels->name = 'News';
		$labels->singular_name = 'News';
		$labels->add_new = 'Add News';
		$labels->add_new_item = 'Add News';
		$labels->edit_item = 'Edit News';
		$labels->new_item = 'News';
		$labels->view_item = 'View News';
		$labels->search_items = 'Search News';
		$labels->not_found = 'No News found';
		$labels->not_found_in_trash = 'No News found in Trash';
	}
	add_action( 'init', 'change_post_object_label' );
	add_action( 'admin_menu', 'change_post_menu_label' );


/*****************7.0 USER - ADD CUSTOM POST TYPE CAPABILITIES*****************************/

/*****************8.0 THEME FUNCTIONS*****************************/
	//***8.1 Pagination
		function flagship_pagination($pages = '', $range = 2)
		{  
		     $showitems = ($range * 2)+1;  
		
		     global $paged;
		     if(empty($paged)) $paged = 1;
		
		     if($pages == '')
		     {
		         global $wp_query;
		         $pages = $wp_query->max_num_pages;
		         if(!$pages)
		         {
		             $pages = 1;
		         }
		     }   
		
		     if(1 != $pages)
		     {
		         echo "<div class='pagination two columns centered mobile-four'>";
		         if($paged > 2 && $paged > $range+1 && $showitems < $pages) echo "<a href='".get_pagenum_link(1)."'>&laquo;</a>";
		         if($paged > 1 && $showitems < $pages) echo "<a href='".get_pagenum_link($paged - 1)."'>&lsaquo;</a>";
		
		         for ($i=1; $i <= $pages; $i++)
		         {
		             if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
		             {
		                 echo ($paged == $i)? "<span class='current'>".$i."</span>":"<a href='".get_pagenum_link($i)."' class='inactive' >".$i."</a>";
		             }
		         }
		
		         if ($paged < $pages && $showitems < $pages) echo "<a href='".get_pagenum_link($paged + 1)."'>&rsaquo;</a>";  
		         if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) echo "<a href='".get_pagenum_link($pages)."'>&raquo;</a>";
		         echo "</div>\n";
		     }
		}

	//***8.2 to generate Slug as a class - useful for background images
		function the_slug() {
		    $post_data = get_post($post->ID, ARRAY_A);
		    $slug = $post_data['post_name'];
		    return $slug; 
		}
	
	//***8.3 add is subpage of conditional statement
		function ksas_is_subpage_of( $parentpage = '' ) {
			$posts = $GLOBALS['posts'];
			if ( is_numeric($parentpage) ) {
				if ( $parentpage == $posts[0]->post_parent ) {
					return true;
				} else {
					is_subpage_of( $posts[0]->post_parent );
				}
			} else {
				return false;
			}
		}

	//***8.4 Get page ID from page slug - Used to generate left side nav on some pages
		function ksas_get_page_id($page_name){
			global $wpdb;
			$page_name = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_name = '".$page_name."'");
			return $page_name;
		}
		
	//***8.5 Conditional function to check if post belongs to term in a custom taxonomy. 
		function ksas_in_taxonomy($tax, $term, $_post = NULL) {
			// if neither tax nor term are specified, return false
			if ( !$tax || !$term ) { return FALSE; }
			// if post parameter is given, get it, otherwise use $GLOBALS to get post
			if ( $_post ) {
			$_post = get_post( $_post );
			} else {
			$_post =& $GLOBALS['post'];
			}
			// if no post return false
			if ( !$_post ) { return FALSE; }
			// check whether post matches term belongin to tax
			$return = is_object_in_term( $_post->ID, $tax, $term );
			// if error returned, then return false
			if ( is_wp_error( $return ) ) { return FALSE; }
		return $return;
		}


/*******************9.0 NAVIGATION & MENU FUNCTIONS & HELPERS******************/

	//***9.1 Menu Walker to add Foundation CSS classes
		class foundation_navigation extends Walker_Nav_Menu
		{
		      function start_el(&$output, $item, $depth, $args)
		      {
					global $wp_query;
					$indent = ( $depth ) ? str_repeat( "", $depth ) : '';
					
					$class_names = $value = '';
					
					// If the item has children, add the dropdown class for bootstrap
					if ( $args->has_children ) {
						$class_names = "has-flyout ";
					}
								
					$classes = empty( $item->classes ) ? array() : (array) $item->classes;
					
					$class_names .= join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
					$class_names = ' class="'. esc_attr( $class_names ) . '"';
		           
		           	$output .= $indent . '<li id="menu-item-'. $item->ID . '"' . $value . $class_names .'>';
		
		           	$attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
		           	$attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
		           	$attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
		           	$attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
		           	// if the item has children add these two attributes to the anchor tag
		           	if ( $args->has_children ) {
						$attributes .= 'data-toggle="dropdown"';
					}
		
		            $item_output = $args->before;
		            $item_output .= '<a'. $attributes .'>';
		            $item_output .= $args->link_before .apply_filters( 'the_title', $item->title, $item->ID );
		            $item_output .= $args->link_after;
		            $item_output .= '</a>';
		            $item_output .= $args->after;
		
		            $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
		            }
		            
		function start_lvl(&$output, $depth) {
			$output .= "\n<ul class=\"flyout up\">\n";
		}
		            
		      	function display_element( $element, &$children_elements, $max_depth, $depth=0, $args, &$output )
		      	    {
		      	        $id_field = $this->db_fields['id'];
		      	        if ( is_object( $args[0] ) ) {
		      	            $args[0]->has_children = ! empty( $children_elements[$element->$id_field] );
		      	        }
		      	        return parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
		      	    }
		      	
		            
		}
		
		// Add a class to the wp_page_menu fallback
		function foundation_page_menu_class($ulclass) {
			return preg_replace('/<ul>/', '<ul class="nav-bar">', $ulclass, 1);
		}
		
		add_filter('wp_page_menu','foundation_page_menu_class');


	//***9.2 Menu Walker to create a dropdown menu for mobile devices	
		class mobile_select_menu extends Walker_Nav_Menu{
		    function start_lvl(&$output, $depth){
		      $indent = str_repeat("\t", $depth); // don't output children opening tag (`<ul>`)
		    }
		
		    function end_lvl(&$output, $depth){
		      $indent = str_repeat("\t", $depth); // don't output children closing tag
		    }
		
		    function start_el(&$output, $item, $depth, $args){
		      // add spacing to the title based on the depth
		      $item->title = str_repeat("&nbsp;", $depth * 4).$item->title;
		
		      parent::start_el(&$output, $item, $depth, $args);
		
		      // no point redefining this method too, we just replace the li tag...
		      $output = str_replace('<li', '<option value="'. esc_attr( $item->url        ) .'"', $output);
		    }
		
		    function end_el(&$output, $item, $depth){
		      $output .= "</option>\n"; // replace closing </li> with the option tag
		    }
		}

	//***9.3 remove unneccessary classes for navigation menus
		function ksasaca_css_attributes_filter($var) {
			 $newnavclasses = is_array($var) ? array_intersect($var, array(
	                'current_page_item',
	                'current_page_parent',
	                'current_page_ancestor',
	                'first',
	                'last',
	                'vertical',
	                'horizontal',
	                'children',
	                'logo',
	                'external'
			 )) : '';
			 return $newnavclasses;
		}
		add_filter('nav_menu_css_class', 'ksasaca_css_attributes_filter', 100, 1);
		add_filter('page_css_class', 'ksasaca_css_attributes_filter', 100, 1);
	//***9.4 Menu Walker for breadcrumbs
		class flagship_bread_crumb extends Walker{
		    var $tree_type = array( 'post_type', 'taxonomy', 'custom' );
		    var $db_fields = array( 'parent' => 'menu_item_parent', 'id' => 'db_id' );
		    var $delimiter = '';
		    function start_el(&$output, $item, $depth, $args) {
		
		        //Check if menu item is an ancestor of the current page
		        $classes = empty( $item->classes ) ? array() : (array) $item->classes;
		        $current_identifiers = array( 'current-menu-item', 'current-menu-parent', 'current-menu-ancestor' ); 
		        $ancestor_of_current = array_intersect( $current_identifiers, $classes );     
		
		
		        if( $ancestor_of_current ){
		            $title = apply_filters( 'the_title', $item->title, $item->ID );
		
		            //Preceed with delimter for all but the first item.
		            if( 0 != $depth )
		                $output .= $this->delimiter;
		
		            //Link tag attributes
		            $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
		            $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
		            $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
		            $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
		
		            //Add to the HTML output
		            $output .= '<li><a'. $attributes .'>'.$title.'</a></li>';
		        }
		    }
		}
		
	//***9.5 Menu Walker for Tertiary links
add_filter( 'wp_nav_menu_objects', 'submenu_limit', 10, 2 );

function submenu_limit( $items, $args ) {

    if ( empty($args->submenu) )
        return $items;

    $parent_id = array_pop( wp_filter_object_list( $items, array( 'title' => $args->submenu ), 'and', 'ID' ) );
    $children  = submenu_get_children_ids( $parent_id, $items );

    foreach ( $items as $key => $item ) {

        if ( ! in_array( $item->ID, $children ) )
            unset($items[$key]);
    }

    return $items;
}

function submenu_get_children_ids( $id, $items ) {

    $ids = wp_filter_object_list( $items, array( 'menu_item_parent' => $id ), 'and', 'ID' );

    foreach ( $ids as $id ) {

        $ids = array_merge( $ids, submenu_get_children_ids( $id, $items ) );
    }

    return $ids;
}

	//***9.6 Menu Walker to add page IDs as classes
		class page_id_classes extends Walker_Nav_Menu{
	        /**
	         *      Walker object, appends page id to data-url attribute on link
	         */
	        function start_el(&$output, $item, $depth, $args) {
	                
	           global $wp_query;
	                
	           $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
	
	           $class_names = $value = '';
	
					$classes = empty( $item->classes ) ? array() : (array) $item->classes;
					
					$class_names .= join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
					$class_names = ' class="'. esc_attr( $class_names ) . ' page-id-' . esc_attr( $item->object_id ) .'"';
		           
					$output .= $indent . '<li id="menu-item-'. $item->ID . '"' . $value . $class_names .'>';
	
	           $attributes  = ! empty( $item->attr_title )   ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
	           $attributes .= ! empty( $item->target ) ? ' target="'  . esc_attr( $item>-target     ) .'"' : '';
	           $attributes .= ! empty( $item->xfn )  ? ' rel="' . esc_attr( $item->xfn        ) .'"' : '';
	           $attributes .= ! empty( $item->url ) ? ' href="'  . esc_attr( $item->url        ) .'"' : '';
	           $attributes .= ! empty( $item->object_id )    ? ' data-id="' . esc_attr( $item->object_id )  .'"' : '';
	           $item_output = $args->before;
	           $item_output .= '<a'. $attributes .'>';
	           $item_output .= $args->link_before .apply_filters( 'the_title', $item->title, $item->ID );
	           $item_output .= $args->link_after;
	           $item_output .= '</a>';
	           $item_output .= $args->after;
	
	           $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	            
	                        
	                }
	      }
?>