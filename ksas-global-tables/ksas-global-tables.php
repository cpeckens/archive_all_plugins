<?php
/*
Plugin Name: Build Global tables for Global queries
Description: Creates SQL tables of all posts and taxonomy relationships.  For searching and querying all sites. Basted off of Tristan Min's plugin Multisite Lastest Posts Widget.
Author: Cara Peckens
Version: 1.0
Plugin URI: http://krieger.dev/plugins/global-tables
*/

/*  
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/**
 * Builds a view that contains posts from all blogs.
 * Views are built by activate_blog, desactivate_blog, archive_blog, unarchive_blog, delete_blog and wpmu_new_blog hooks.
 */
register_activation_hook( __FILE__, 'latest_post_build_views_add' );
add_action ('wpmu_new_blog', 'latest_post_build_views_add');
add_action ('delete_blog', 'latest_post_build_views_drop', 10, 1);
add_action ('archive_blog', 'latest_post_build_views_drop', 10, 1);
add_action ('unarchive_blog', 'latest_post_build_views_unarchive', 10, 1);
add_action ('activate_blog', 'latest_post_build_views_activate', 10, 1);
add_action ('deactivate_blog', 'latest_post_build_views_drop', 10, 1);

if(!function_exists('latest_post_build_views_drop')) {
        function latest_post_build_views_drop($trigger) {
            global $wpdb;

            $blogs = $wpdb->get_results( $wpdb->prepare("SELECT blog_id, domain, path FROM {$wpdb->blogs} WHERE blog_id != {$trigger} AND site_id = {$wpdb->siteid} AND archived = '0' AND mature = '0' AND spam = '0' AND deleted = '0' ORDER BY registered DESC"));
            latest_post_v_query($blogs);
        }
}

if(!function_exists('latest_post_build_views_add')) {
        function latest_post_build_views_add() {
            global $wpdb;

            $blogs = $wpdb->get_results( $wpdb->prepare("SELECT blog_id, domain, path FROM {$wpdb->blogs} WHERE site_id = {$wpdb->siteid} AND archived = '0' AND mature = '0' AND spam = '0' AND deleted = '0' ORDER BY registered DESC"));
            latest_post_v_query($blogs);
        }
}

if(!function_exists('latest_post_build_views_activate')) {
        function latest_post_build_views_activate($trigger) {
            global $wpdb;

            $blogs = $wpdb->get_results( $wpdb->prepare("SELECT blog_id, domain, path FROM {$wpdb->blogs} WHERE (blog_id = {$trigger} AND archived = '0' AND mature = '0' AND spam = '0') OR (site_id = {$wpdb->siteid} AND public = '1' AND archived = '0' AND mature = '0' AND spam = '0' AND deleted = '0') ORDER BY registered DESC"));

            latest_post_v_query($blogs);
        }
}

if(!function_exists('latest_post_build_views_unarchive')) {
        function latest_post_build_views_unarchive($trigger) {
            global $wpdb;

            $blogs = $wpdb->get_results( $wpdb->prepare("SELECT blog_id, domain, path FROM {$wpdb->blogs} WHERE (blog_id = {$trigger} AND deleted = '0' AND mature = '0' AND spam = '0') OR (site_id = {$wpdb->siteid} AND public = '1' AND archived = '0' AND mature = '0' AND spam = '0' AND deleted = '0') ORDER BY registered DESC"));
            latest_post_v_query($blogs);
        }
}

if(!function_exists('latest_post_v_query')) {
        function latest_post_v_query($blogs) {
            global $wpdb;

            $i = 0;
            $posts_query = '';

            foreach ($blogs as $blog) {
                if ($i != 0) {
                    $posts_query    .= ' UNION ';
                }

                if($blog->blog_id == 1) {
                        $posts_query    .= " (SELECT '{$blog->blog_id}' AS blog_id, '{$blog->domain}' AS domain, '{$blog->path}' AS path, posts{$blog->blog_id}.* FROM {$wpdb->base_prefix}posts posts{$blog->blog_id} WHERE posts{$blog->blog_id}.post_type != 'revision' AND posts{$blog->blog_id}.post_status = 'publish') ";
                } else {
                        $posts_query    .= " (SELECT '{$blog->blog_id}' AS blog_id, '{$blog->domain}' AS domain, '{$blog->path}' AS path, posts{$blog->blog_id}.* FROM {$wpdb->base_prefix}{$blog->blog_id}_posts posts{$blog->blog_id} WHERE posts{$blog->blog_id}.post_type != 'revision' AND posts{$blog->blog_id}.post_status = 'publish') ";
                }
                $i++;
            }

                $v_query1  = "CREATE OR REPLACE VIEW `{$wpdb->base_prefix}global_posts` AS ".$posts_query;
                $wpdb->query($wpdb->prepare($v_query1));
        }
}

//Create Global Terms Table
register_activation_hook( __FILE__, 'latest_term_build_views_add' );
add_action ('wpmu_new_blog', 'latest_term_build_views_add');
add_action ('delete_blog', 'latest_term_build_views_drop', 10, 1);
add_action ('archive_blog', 'latest_term_build_views_drop', 10, 1);
add_action ('unarchive_blog', 'latest_term_build_views_unarchive', 10, 1);
add_action ('activate_blog', 'latest_term_build_views_activate', 10, 1);
add_action ('deactivate_blog', 'latest_term_build_views_drop', 10, 1);

if(!function_exists('latest_term_build_views_drop')) {
        function latest_term_build_views_drop($trigger) {
            global $wpdb;

            $terms = $wpdb->get_results( $wpdb->prepare("SELECT blog_id FROM {$wpdb->blogs} WHERE blog_id != {$trigger} AND site_id = {$wpdb->siteid} "));
            latest_term_v_query($terms);
        }
}

if(!function_exists('latest_term_build_views_add')) {
        function latest_term_build_views_add() {
            global $wpdb;

            $terms = $wpdb->get_results( $wpdb->prepare("SELECT blog_id FROM {$wpdb->blogs} WHERE site_id = {$wpdb->siteid} "));
            latest_term_v_query($terms);
        }
}

if(!function_exists('latest_term_build_views_activate')) {
        function latest_term_build_views_activate($trigger) {
            global $wpdb;

            $terms = $wpdb->get_results( $wpdb->prepare("SELECT blog_id FROM {$wpdb->blogs} WHERE (blog_id = {$trigger} "));

            latest_term_v_query($terms);
        }
}

if(!function_exists('latest_term_build_views_unarchive')) {
        function latest_term_build_views_unarchive($trigger) {
            global $wpdb;

            $terms = $wpdb->get_results( $wpdb->prepare("SELECT blog_id FROM {$wpdb->blogs} WHERE (blog_id = {$trigger} "));
            latest_term_v_query($terms);
        }
}

if(!function_exists('latest_term_v_query')) {
        function latest_term_v_query($terms) {
            global $wpdb;

            $i = 0;
            $term_query = '';

            foreach ($terms as $term) {
                if ($i != 0) {
                    $term_query    .= ' UNION ';
                }

                if($term->blog_id == 1) {
                        $term_query    .= " (SELECT '{$term->blog_id}' AS blog_id, term{$term->blog_id}.* FROM {$wpdb->base_prefix}terms term{$term->blog_id}) ";
                } else {
                        $term_query    .= " (SELECT '{$term->blog_id}' AS blog_id, term{$term->blog_id}.* FROM {$wpdb->base_prefix}{$term->blog_id}_terms term{$term->blog_id}) ";
                }
                $i++;
            }

                $termv_query  = "CREATE OR REPLACE VIEW `{$wpdb->base_prefix}global_terms` AS ".$term_query;
                $wpdb->query($wpdb->prepare($termv_query));
        }
}
//Create Global Term taxonomy table
register_activation_hook( __FILE__, 'latest_tax_build_views_add' );
add_action ('wpmu_new_blog', 'latest_tax_build_views_add');
add_action ('delete_blog', 'latest_tax_build_views_drop', 10, 1);
add_action ('archive_blog', 'latest_tax_build_views_drop', 10, 1);
add_action ('unarchive_blog', 'latest_tax_build_views_unarchive', 10, 1);
add_action ('activate_blog', 'latest_tax_build_views_activate', 10, 1);
add_action ('deactivate_blog', 'latest_tax_build_views_drop', 10, 1);

if(!function_exists('latest_tax_build_views_drop')) {
        function latest_tax_build_views_drop($trigger) {
            global $wpdb;

            $taxs = $wpdb->get_results( $wpdb->prepare("SELECT blog_id FROM {$wpdb->blogs} WHERE blog_id != {$trigger} AND site_id = {$wpdb->siteid} "));
            latest_tax_v_query($taxs);
        }
}

if(!function_exists('latest_tax_build_views_add')) {
        function latest_tax_build_views_add() {
            global $wpdb;

            $taxs = $wpdb->get_results( $wpdb->prepare("SELECT blog_id FROM {$wpdb->blogs} WHERE site_id = {$wpdb->siteid} "));
            latest_tax_v_query($taxs);
        }
}

if(!function_exists('latest_tax_build_views_activate')) {
        function latest_tax_build_views_activate($trigger) {
            global $wpdb;

            $taxs = $wpdb->get_results( $wpdb->prepare("SELECT blog_id FROM {$wpdb->blogs} WHERE (blog_id = {$trigger} "));

            latest_tax_v_query($taxs);
        }
}

if(!function_exists('latest_tax_build_views_unarchive')) {
        function latest_tax_build_views_unarchive($trigger) {
            global $wpdb;

            $taxs = $wpdb->get_results( $wpdb->prepare("SELECT blog_id FROM {$wpdb->blogs} WHERE (blog_id = {$trigger} "));
            latest_tax_v_query($taxs);
        }
}

if(!function_exists('latest_tax_v_query')) {
        function latest_tax_v_query($taxs) {
            global $wpdb;

            $i = 0;
            $tax_query = '';

            foreach ($taxs as $tax) {
                if ($i != 0) {
                    $tax_query    .= ' UNION ';
                }

                if($tax->blog_id == 1) {
                        $tax_query    .= " (SELECT '{$tax->blog_id}' AS blog_id, tax{$tax->blog_id}.* FROM {$wpdb->base_prefix}term_taxonomy tax{$tax->blog_id}) ";
                } else {
                        $tax_query    .= " (SELECT '{$tax->blog_id}' AS blog_id, tax{$tax->blog_id}.* FROM {$wpdb->base_prefix}{$tax->blog_id}_term_taxonomy tax{$tax->blog_id}) ";
                }
                $i++;
            }

                $taxv_query  = "CREATE OR REPLACE VIEW `{$wpdb->base_prefix}global_term_taxonomy` AS ".$tax_query;
                $wpdb->query($wpdb->prepare($taxv_query));
        }
}

//Create Global Terms Relationship Table
register_activation_hook( __FILE__, 'latest_relations_build_views_add' );
add_action ('wpmu_new_blog', 'latest_relations_build_views_add');
add_action ('delete_blog', 'latest_relations_build_views_drop', 10, 1);
add_action ('archive_blog', 'latest_relations_build_views_drop', 10, 1);
add_action ('unarchive_blog', 'latest_relations_build_views_unarchive', 10, 1);
add_action ('activate_blog', 'latest_relations_build_views_activate', 10, 1);
add_action ('deactivate_blog', 'latest_relations_build_views_drop', 10, 1);

if(!function_exists('latest_relations_build_views_drop')) {
        function latest_relations_build_views_drop($trigger) {
            global $wpdb;

            $relations = $wpdb->get_results( $wpdb->prepare("SELECT blog_id FROM {$wpdb->blogs} WHERE blog_id != {$trigger} AND site_id = {$wpdb->siteid} "));
            latest_relations_v_query($relations);
        }
}

if(!function_exists('latest_relations_build_views_add')) {
        function latest_relations_build_views_add() {
            global $wpdb;

            $relations = $wpdb->get_results( $wpdb->prepare("SELECT blog_id FROM {$wpdb->blogs} WHERE site_id = {$wpdb->siteid} "));
            latest_relations_v_query($relations);
        }
}

if(!function_exists('latest_relations_build_views_activate')) {
        function latest_relations_build_views_activate($trigger) {
            global $wpdb;

            $relations = $wpdb->get_results( $wpdb->prepare("SELECT blog_id FROM {$wpdb->blogs} WHERE (blog_id = {$trigger} "));

            latest_relations_v_query($relations);
        }
}

if(!function_exists('latest_relations_build_views_unarchive')) {
        function latest_relations_build_views_unarchive($trigger) {
            global $wpdb;

            $relations = $wpdb->get_results( $wpdb->prepare("SELECT blog_id FROM {$wpdb->blogs} WHERE (blog_id = {$trigger} "));
            latest_relations_v_query($relations);
        }
}

if(!function_exists('latest_relations_v_query')) {
        function latest_relations_v_query($relations) {
            global $wpdb;

            $i = 0;
            $relation_query = '';

            foreach ($relations as $relation) {
                if ($i != 0) {
                    $relation_query    .= ' UNION ';
                }

                if($relation->blog_id == 1) {
                        $relation_query    .= " (SELECT '{$relation->blog_id}' AS blog_id, relation{$relation->blog_id}.* FROM {$wpdb->base_prefix}term_relationships relation{$relation->blog_id} ) ";
                } else {
                        $relation_query    .= " (SELECT '{$relation->blog_id}' AS blog_id, relation{$relation->blog_id}.* FROM {$wpdb->base_prefix}{$relation->blog_id}_term_relationships relation{$relation->blog_id} ) ";
                }
                $i++;
            }

                $relationv_query  = "CREATE OR REPLACE VIEW `{$wpdb->base_prefix}global_term_relationships` AS ".$relation_query;
                $wpdb->query($wpdb->prepare($relationv_query));
        }
}
//Create Global Postmeta Table
register_activation_hook( __FILE__, 'latest_postmetas_build_views_add' );
add_action ('wpmu_new_blog', 'latest_postmetas_build_views_add');
add_action ('delete_blog', 'latest_postmetas_build_views_drop', 10, 1);
add_action ('archive_blog', 'latest_postmetas_build_views_drop', 10, 1);
add_action ('unarchive_blog', 'latest_postmetas_build_views_unarchive', 10, 1);
add_action ('activate_blog', 'latest_postmetas_build_views_activate', 10, 1);
add_action ('deactivate_blog', 'latest_postmetas_build_views_drop', 10, 1);

if(!function_exists('latest_postmetas_build_views_drop')) {
        function latest_postmetas_build_views_drop($trigger) {
            global $wpdb;

            $postmetas = $wpdb->get_results( $wpdb->prepare("SELECT blog_id FROM {$wpdb->blogs} WHERE blog_id != {$trigger} AND site_id = {$wpdb->siteid} "));
            latest_postmetas_v_query($postmetas);
        }
}

if(!function_exists('latest_postmetas_build_views_add')) {
        function latest_postmetas_build_views_add() {
            global $wpdb;

            $postmetas = $wpdb->get_results( $wpdb->prepare("SELECT blog_id FROM {$wpdb->blogs} WHERE site_id = {$wpdb->siteid} "));
            latest_postmetas_v_query($postmetas);
        }
}

if(!function_exists('latest_postmetas_build_views_activate')) {
        function latest_postmetas_build_views_activate($trigger) {
            global $wpdb;

            $postmetas = $wpdb->get_results( $wpdb->prepare("SELECT blog_id FROM {$wpdb->blogs} WHERE (blog_id = {$trigger} "));

            latest_postmetas_v_query($postmetas);
        }
}

if(!function_exists('latest_postmetas_build_views_unarchive')) {
        function latest_postmetas_build_views_unarchive($trigger) {
            global $wpdb;

            $postmetas = $wpdb->get_results( $wpdb->prepare("SELECT blog_id FROM {$wpdb->blogs} WHERE (blog_id = {$trigger} "));
            latest_postmetas_v_query($postmetas);
        }
}

if(!function_exists('latest_postmetas_v_query')) {
        function latest_postmetas_v_query($postmetas) {
            global $wpdb;

            $i = 0;
            $postmeta_query = '';

            foreach ($postmetas as $postmeta) {
                if ($i != 0) {
                    $postmeta_query    .= ' UNION ';
                }

                if($postmeta->blog_id == 1) {
                        $postmeta_query    .= " (SELECT '{$postmeta->blog_id}' AS blog_id, postmeta{$postmeta->blog_id}.* FROM {$wpdb->base_prefix}postmeta postmeta{$postmeta->blog_id} ) ";
                } else {
                        $postmeta_query    .= " (SELECT '{$postmeta->blog_id}' AS blog_id, postmeta{$postmeta->blog_id}.* FROM {$wpdb->base_prefix}{$postmeta->blog_id}_postmeta postmeta{$postmeta->blog_id} ) ";
                }
                $i++;
            }

                $postmetav_query  = "CREATE OR REPLACE VIEW `{$wpdb->base_prefix}global_postmeta` AS ".$postmeta_query;
                $wpdb->query($wpdb->prepare($postmetav_query));
        }
}

//Create Temp Global People Table
register_activation_hook( __FILE__, 'latest_temppeoples_build_views_add' );
add_action ('wpmu_new_blog', 'latest_temppeoples_build_views_add');
add_action ('delete_blog', 'latest_temppeoples_build_views_drop', 10, 1);
add_action ('archive_blog', 'latest_temppeoples_build_views_drop', 10, 1);
add_action ('unarchive_blog', 'latest_temppeoples_build_views_unarchive', 10, 1);
add_action ('activate_blog', 'latest_temppeoples_build_views_activate', 10, 1);
add_action ('deactivate_blog', 'latest_temppeoples_build_views_drop', 10, 1);

if(!function_exists('latest_temppeoples_build_views_drop')) {
        function latest_temppeoples_build_views_drop($trigger) {
            global $wpdb;
            latest_temppeoples_v_query();
        }
}

if(!function_exists('latest_temppeoples_build_views_add')) {
        function latest_temppeoples_build_views_add() {
            global $wpdb;
            latest_temppeoples_v_query();
        }
}

if(!function_exists('latest_temppeoples_build_views_activate')) {
        function latest_temppeoples_build_views_activate($trigger) {
            global $wpdb;
            latest_temppeoples_v_query();
        }
}

if(!function_exists('latest_temppeoples_build_views_unarchive')) {
        function latest_temppeoples_build_views_unarchive($trigger) {
            global $wpdb;
            latest_temppeoples_v_query();
        }
}

if(!function_exists('latest_temppeoples_v_query')) {
        function latest_temppeoples_v_query() {
            global $wpdb;
                $temppeoplev_query  = "CREATE OR REPLACE VIEW temp_global_people_posts
										AS
										SELECT {$wpdb->base_prefix}global_term_relationships.object_id, {$wpdb->base_prefix}global_posts.*, {$wpdb->base_prefix}global_term_taxonomy.term_id, {$wpdb->base_prefix}global_term_taxonomy.taxonomy, GROUP_CONCAT({$wpdb->base_prefix}global_terms.slug SEPARATOR ' ') AS slugs, GROUP_CONCAT({$wpdb->base_prefix}global_term_relationships.term_taxonomy_id SEPARATOR ' ') AS taxonomy_ids
										FROM {$wpdb->base_prefix}global_term_relationships
										JOIN {$wpdb->base_prefix}global_posts
										ON {$wpdb->base_prefix}global_posts.ID = {$wpdb->base_prefix}global_term_relationships.object_id
										JOIN {$wpdb->base_prefix}global_term_taxonomy
										ON {$wpdb->base_prefix}global_term_taxonomy.term_taxonomy_id = {$wpdb->base_prefix}global_term_relationships.term_taxonomy_id
										JOIN {$wpdb->base_prefix}global_terms
										ON {$wpdb->base_prefix}global_term_taxonomy.term_id = {$wpdb->base_prefix}global_terms.term_id
										WHERE {$wpdb->base_prefix}global_posts.blog_id = {$wpdb->base_prefix}global_term_relationships.blog_id
										AND {$wpdb->base_prefix}global_posts.blog_id = {$wpdb->base_prefix}global_term_taxonomy.blog_id
										AND {$wpdb->base_prefix}global_posts.blog_id = {$wpdb->base_prefix}global_terms.blog_id
										AND {$wpdb->base_prefix}global_posts.post_type = 'people'
										AND ({$wpdb->base_prefix}global_term_taxonomy.taxonomy ='role' OR {$wpdb->base_prefix}global_term_taxonomy.taxonomy ='academicdepartment' OR {$wpdb->base_prefix}global_term_taxonomy.taxonomy ='affiliation')
										GROUP BY object_id";
                $wpdb->query($wpdb->prepare($temppeoplev_query));
        }
}

//Create Temp Global People Table with Metadata
register_activation_hook( __FILE__, 'latest_temppeoplesmeta_build_views_add' );
add_action ('wpmu_new_blog', 'latest_temppeoplesmeta_build_views_add');
add_action ('delete_blog', 'latest_temppeoplesmeta_build_views_drop', 10, 1);
add_action ('archive_blog', 'latest_temppeoplesmeta_build_views_drop', 10, 1);
add_action ('unarchive_blog', 'latest_temppeoplesmeta_build_views_unarchive', 10, 1);
add_action ('activate_blog', 'latest_temppeoplesmeta_build_views_activate', 10, 1);
add_action ('deactivate_blog', 'latest_temppeoplesmeta_build_views_drop', 10, 1);

if(!function_exists('latest_temppeoplesmeta_build_views_drop')) {
        function latest_temppeoplesmeta_build_views_drop($trigger) {
            global $wpdb;
            latest_temppeoplesmeta_v_query();
        }
}

if(!function_exists('latest_temppeoplesmeta_build_views_add')) {
        function latest_temppeoplesmeta_build_views_add() {
            global $wpdb;
            latest_temppeoplesmeta_v_query();
        }
}

if(!function_exists('latest_temppeoplesmeta_build_views_activate')) {
        function latest_temppeoplesmeta_build_views_activate($trigger) {
            global $wpdb;
            latest_temppeoplesmeta_v_query();
        }
}

if(!function_exists('latest_temppeoplesmeta_build_views_unarchive')) {
        function latest_temppeoplesmeta_build_views_unarchive($trigger) {
            global $wpdb;
            latest_temppeoplesmeta_v_query();
        }
}

if(!function_exists('latest_temppeoplesmeta_v_query')) {
        function latest_temppeoplesmeta_v_query() {
            global $wpdb;
                $temppeoplesmetav_query  = "CREATE OR REPLACE VIEW temp_people_with_meta
											AS
											SELECT post_title, guid, ID, post_id, slugs,
											CASE WHEN meta_key = 'ecpt_people_photo' THEN meta_value END AS ecpt_people_photo,
											CASE WHEN meta_key = 'ecpt_position' THEN meta_value END AS ecpt_position,
											CASE WHEN meta_key = 'ecpt_degrees' THEN meta_value END AS ecpt_degrees,
											CASE WHEN meta_key = 'ecpt_expertise' THEN meta_value END AS ecpt_expertise,
											CASE WHEN meta_key = 'ecpt_phone' THEN meta_value END AS ecpt_phone,
											CASE WHEN meta_key = 'ecpt_email' THEN meta_value END AS ecpt_email,
											CASE WHEN meta_key = 'ecpt_office' THEN meta_value END AS ecpt_office,
											CASE WHEN meta_key = 'ecpt_people_alpha' THEN meta_value END AS ecpt_people_alpha
											FROM temp_global_people_posts, {$wpdb->base_prefix}global_postmeta
											WHERE {$wpdb->base_prefix}global_postmeta.post_id = temp_global_people_posts.ID 
";
                $wpdb->query($wpdb->prepare($temppeoplesmetav_query));
        }
}
//Create Global People Posts table with all necessary info
register_activation_hook( __FILE__, 'latest_global_people_posts_build_views_add' );
add_action ('wpmu_new_blog', 'latest_global_people_posts_build_views_add');
add_action ('delete_blog', 'latest_global_people_posts_build_views_drop', 10, 1);
add_action ('archive_blog', 'latest_global_people_posts_build_views_drop', 10, 1);
add_action ('unarchive_blog', 'latest_global_people_posts_build_views_unarchive', 10, 1);
add_action ('activate_blog', 'latest_global_people_posts_build_views_activate', 10, 1);
add_action ('deactivate_blog', 'latest_global_people_posts_build_views_drop', 10, 1);

if(!function_exists('latest_global_people_posts_build_views_drop')) {
        function latest_global_people_posts_build_views_drop($trigger) {
            global $wpdb;
            latest_global_people_posts_v_query();
        }
}

if(!function_exists('latest_global_people_posts_build_views_add')) {
        function latest_global_people_posts_build_views_add() {
            global $wpdb;
            latest_global_people_posts_v_query();
        }
}

if(!function_exists('latest_global_people_posts_build_views_activate')) {
        function latest_global_people_posts_build_views_activate($trigger) {
            global $wpdb;
            latest_global_people_posts_v_query();
        }
}

if(!function_exists('latest_global_people_posts_build_views_unarchive')) {
        function latest_global_people_posts_build_views_unarchive($trigger) {
            global $wpdb;
            latest_global_people_posts_v_query();
        }
}

if(!function_exists('latest_global_people_posts_v_query')) {
        function latest_global_people_posts_v_query() {
            global $wpdb;
                $global_people_postsv_query  = "CREATE OR REPLACE VIEW {$wpdb->base_prefix}global_people
											AS
											SELECT post_title, guid, slugs,
											GROUP_CONCAT(ecpt_people_photo) AS ecpt_people_photo,
											GROUP_CONCAT(ecpt_position) AS ecpt_position,
											GROUP_CONCAT(ecpt_degrees) AS ecpt_degrees,
											GROUP_CONCAT(ecpt_expertise) AS ecpt_expertise,
											GROUP_CONCAT(ecpt_phone) AS ecpt_phone,
											GROUP_CONCAT(ecpt_email) AS ecpt_email,
											GROUP_CONCAT(ecpt_office) AS ecpt_office,
											GROUP_CONCAT(ecpt_people_alpha) AS ecpt_people_alpha
											FROM temp_people_with_meta
											WHERE post_id = ID
											GROUP BY post_id
";
                $wpdb->query($wpdb->prepare($global_people_postsv_query));
        }
}
?>