<?php
/*
Plugin Name: KSAS Global Shortcodes
Plugin URI: http://krieger2.jhu.edu/comm/web/plugins/shortcodes
Description: This plugin should be network activated.  Provides shortcodes for accordions, read more/read less, iframes, and SE calendar display.
Version: 1.0
Author: Cara Peckens
Author URI: mailto:cpeckens@jhu.edu
License: GPL2
*/

//******************Accordion Shortcode************************//
function acc_title_shortcode( $attr, $content = null ) {
  return '<h3 class="acc_trigger"><a href="#">' . $content . '</a></h3>';
}
add_shortcode('acctitle', 'acc_title_shortcode'); 

function acc_content_shortcode( $attr, $content = null ) {
  return '<div class="acc_container">' . $content . '</div>';
}
add_shortcode('acccontent', 'acc_content_shortcode'); 

//******************Read More/Read Less Shortcode************************//

function readmore_title_shortcode( $attr, $content = null ) {
  return '<div class="wysiwyg-more-less"><div class="wysiwyg-more-less-top"><h4>' . $content . '<span class="readmore">&nbsp;[<a class="wysiwyg-read-more-link" href="#">Read More</a>]</span></h4></div>';
}
add_shortcode('readmoretitle', 'readmore_title_shortcode'); 

function readmore_content_shortcode( $attr, $content = null ) {
  return '<div class="wysiwyg-more-less-toggle">' . $content . '</div></div>';
}
add_shortcode('readmorecontent', 'readmore_content_shortcode'); 

//******************Expand All/Collapse All Button************************//

function expand_all_shortcode() {
  return '<h3 class="expand"><a class="acc_expandall">[Expand All]</a></h3>';
}
add_shortcode('expandall', 'expand_all_shortcode'); 

//******************iFrame Shortcode************************//
function iframe_shortcode($atts, $content=null) {
 
	extract(shortcode_atts(array(
	 
	'url'   => '',
	'scrolling'     => 'no',
	'width'     => '100%',
	'height'    => '500',
	'frameborder'   => '0',
	'marginheight'  => '0',
	 
	), $atts));
	 
	if (empty($url)) return 'http://';
	 
	return '<iframe src="'.$url.'" title="" scrolling="'.$scrolling.'" width="'.$width.'" height="'.$height.'" frameborder="'.$frameborder.'" marginheight="'.$marginheight.'">'.$content.'</iframe>';
	 
	}
 
add_shortcode('iframe','iframe_shortcode');

//******************SE Calendar Shortcode************************//
function secalendar_shortcode($atts, $content=null) {
 
	extract(shortcode_atts(array(
	 
	'url'   => '',
	'scrolling'     => 'no',
	'width'     => '100%',
	'height'    => '1200',
	'frameborder'   => '0',
	'marginheight'  => '0',
	 
	), $atts));
	 
	if (empty($url)) return 'http://';
	 
	return '<iframe src="'.$url.'" title="" scrolling="'.$scrolling.'" width="'.$width.'" height="'.$height.'" frameborder="'.$frameborder.'" marginheight="'.$marginheight.'">'.$content.'</iframe>';
	 
	}
 
add_shortcode('secalendar','secalendar_shortcode');

//******************Create WYSIWYG Buttons for Shortcodes************************//

function ksasaca_add_shortcode_buttons() {
   if ( current_user_can('edit_posts') &&  current_user_can('edit_pages') )
   {
     add_filter('mce_external_plugins', 'ksasaca_add_plugin');
     add_filter('mce_buttons_3', 'ksasaca_register_buttons');
   }
}

function ksasaca_register_buttons($buttons) {
   array_push($buttons, "|", "acctitle", "acccontent", "readmoretitle", "readmorecontent");
   return $buttons;
}

function ksasaca_add_plugin($plugin_array) {
   $plugin_array['acctitle'] = plugins_url( 'js/ksasaca_shortcodes.js', __FILE__ );
   $plugin_array['acccontent'] = plugins_url( 'js/ksasaca_shortcodes.js', __FILE__ );
   $plugin_array['readmoretitle'] = plugins_url( 'js/ksasaca_shortcodes.js', __FILE__ );
   $plugin_array['readmorecontent'] = plugins_url( 'js/ksasaca_shortcodes.js', __FILE__ );
   return $plugin_array;
}

 add_action('init', 'ksasaca_add_shortcode_buttons');
?>