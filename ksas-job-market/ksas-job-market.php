<?php
/*
Plugin Name: KSAS Job Market Candidate
Plugin URI: http://krieger2.jhu.edu/comm/web/plugins/people
Description: The people plugin must also be activated.  This adds the metabox and widget for job market candidates.  Built originally for the Department of Economics
Version: 1.0
Author: Cara Peckens
Author URI: mailto:cpeckens@jhu.edu
License: GPL2
*/

//Add Job Candidate Metabox
$jobcandidatedetails_6_metabox = array( 
	'id' => 'jobcandidatedetails',
	'title' => 'Job Candidate Details',
	'page' => array('people'),
	'context' => 'normal',
	'priority' => 'low',
	'fields' => array(

				
				array(
					'name' 			=> 'Thesis Title',
					'desc' 			=> '',
					'id' 			=> 'ecpt_thesis',
					'class' 		=> 'ecpt_thesis',
					'type' 			=> 'text',
					'rich_editor' 	=> 0,			
					'max' 			=> 0,
					'std'			=> ''													
				),
															
				array(
					'name' 			=> 'Fields',
					'desc' 			=> '',
					'id' 			=> 'ecpt_fields',
					'class' 		=> 'ecpt_fields',
					'type' 			=> 'text',
					'rich_editor' 	=> 0,			
					'max' 			=> 0,
					'std'			=> ''													
				),
															
				array(
					'name' 			=> 'Main Advisor',
					'desc' 			=> '',
					'id' 			=> 'ecpt_advisor',
					'class' 		=> 'ecpt_advisor',
					'type' 			=> 'text',
					'rich_editor' 	=> 0,			
					'max' 			=> 0,
					'std'			=> ''													
				),
															
				array(
					'name' 			=> 'Research',
					'desc' 			=> '',
					'id' 			=> 'ecpt_job_research',
					'class' 		=> 'ecpt_job_research',
					'type' 			=> 'textarea',
					'rich_editor' 	=> 1,			
					'max' 			=> 0,
					'std'			=> ''													
				),
															
				array(
					'name' 			=> 'Teaching',
					'desc' 			=> '',
					'id' 			=> 'ecpt_job_teaching',
					'class' 		=> 'ecpt_job_teaching',
					'type' 			=> 'textarea',
					'rich_editor' 	=> 1,			
					'max' 			=> 0,
					'std'			=> ''													
				),
															
				array(
					'name' 			=> 'References',
					'desc' 			=> '',
					'id' 			=> 'ecpt_references',
					'class' 		=> 'ecpt_references',
					'type' 			=> 'textarea',
					'rich_editor' 	=> 1,			
					'max' 			=> 0,
					'std'			=> ''													
				),
															
				array(
					'name' 			=> 'Extra Tab Title',
					'desc' 			=> '',
					'id' 			=> 'ecpt_job_extra_tab_title',
					'class' 		=> 'ecpt_job_extra_tab_title',
					'type' 			=> 'text',
					'rich_editor' 	=> 0,			
					'max' 			=> 0,
					'std'			=> ''													
				),
															
				array(
					'name' 			=> 'Extra Tab Content',
					'desc' 			=> '',
					'id' 			=> 'ecpt_job_extra_tab',
					'class' 		=> 'ecpt_job_extra_tab',
					'type' 			=> 'textarea',
					'rich_editor' 	=> 1,			
					'max' 			=> 0,
					'std'			=> ''													
				),
												)
);			
			
add_action('admin_menu', 'ecpt_add_jobcandidatedetails_6_meta_box');
function ecpt_add_jobcandidatedetails_6_meta_box() {

	global $jobcandidatedetails_6_metabox;		

	foreach($jobcandidatedetails_6_metabox['page'] as $page) {
		add_meta_box($jobcandidatedetails_6_metabox['id'], $jobcandidatedetails_6_metabox['title'], 'ecpt_show_jobcandidatedetails_6_box', $page, 'normal', 'low', $jobcandidatedetails_6_metabox);
	}
}

// function to show meta boxes
function ecpt_show_jobcandidatedetails_6_box()	{
	global $post;
	global $jobcandidatedetails_6_metabox;
	global $ecpt_prefix;
	global $wp_version;
	
	// Use nonce for verification
	echo '<input type="hidden" name="ecpt_jobcandidatedetails_6_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
	
	echo '<table class="form-table">';

	foreach ($jobcandidatedetails_6_metabox['fields'] as $field) {
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

add_action('save_post', 'ecpt_jobcandidatedetails_6_save');

// Save data from meta box
function ecpt_jobcandidatedetails_6_save($post_id) {
	global $post;
	global $jobcandidatedetails_6_metabox;
	
	// verify nonce
	if (!isset($_POST['ecpt_jobcandidatedetails_6_meta_box_nonce']) || !wp_verify_nonce($_POST['ecpt_jobcandidatedetails_6_meta_box_nonce'], basename(__FILE__))) {
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
	
	foreach ($jobcandidatedetails_6_metabox['fields'] as $field) {
	
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

//Add Job Candidate Widget
add_action('widgets_init', 'ksas_register_jobcandidate_widgets');
	function ksas_register_jobcandidate_widgets() {
		register_widget('job_candidate_Widget');
	}
// Define job candidate widget
class job_candidate_Widget extends WP_Widget {

	function job_candidate_Widget() {
		$widget_ops = array('classname' => 'widget_job_candidate', 'description' => __( "Job Candidate Profile") );
		$this->WP_Widget('job-candidate-widget', 'Job Candidate Profile', $widget_ops);
	}

	function widget( $args, $instance ) {
		extract($args);
		$title = apply_filters('widget_title', $instance['title'], $instance, $this->id_base);

		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title;                     
	

		global $post; ?>
		<?php $job_candidate_query = new WP_Query('post-type=people&role=job-market-candidate&orderby=rand&posts_per_page=1'); ?>
					<?php while ($job_candidate_query->have_posts()) : $job_candidate_query->the_post(); ?>            
    	<div class="profile_box">
    	<div class="jobmarket"></div>
		<a href="<?php bloginfo('url'); ?>/directoryindex/job-market/"><img src="<?php echo get_post_meta($post->ID, 'ecpt_people_photo', true); ?>" /></a>
    	<h4><a href="<?php bloginfo('url'); ?>/directoryindex/job-market/"><?php the_title(); ?></a></h4>
    	<p><strong>Thesis:</strong> <?php echo get_post_meta($post->ID, 'ecpt_thesis', true); ?></p>
    	<p><a href="<?php bloginfo('url'); ?>/directoryindex/job-market/">Click here</a> to view all of our 2011-2012 job market candidates.</p>
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