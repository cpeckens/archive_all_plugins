<?php

CREATE OR REPLACE VIEW temp_global_people_posts
AS
SELECT kr13g3rd3v_global_term_relationships.object_id, kr13g3rd3v_global_posts.*, kr13g3rd3v_global_term_taxonomy.term_id, kr13g3rd3v_global_term_taxonomy.taxonomy, GROUP_CONCAT(kr13g3rd3v_global_terms.slug SEPARATOR ' ') AS slugs, GROUP_CONCAT(kr13g3rd3v_global_term_relationships.term_taxonomy_id SEPARATOR ' ') AS taxonomy_ids
FROM kr13g3rd3v_global_term_relationships
JOIN kr13g3rd3v_global_posts
ON kr13g3rd3v_global_posts.ID = kr13g3rd3v_global_term_relationships.object_id
JOIN kr13g3rd3v_global_term_taxonomy
ON kr13g3rd3v_global_term_taxonomy.term_taxonomy_id = kr13g3rd3v_global_term_relationships.term_taxonomy_id
JOIN kr13g3rd3v_global_terms
ON kr13g3rd3v_global_term_taxonomy.term_id = kr13g3rd3v_global_terms.term_id
WHERE kr13g3rd3v_global_posts.blog_id = kr13g3rd3v_global_term_relationships.blog_id
AND kr13g3rd3v_global_posts.blog_id = kr13g3rd3v_global_term_taxonomy.blog_id
AND kr13g3rd3v_global_posts.blog_id = kr13g3rd3v_global_terms.blog_id
AND kr13g3rd3v_global_posts.post_type = 'people'
AND (kr13g3rd3v_global_term_taxonomy.taxonomy ='role' OR kr13g3rd3v_global_term_taxonomy.taxonomy ='academicdepartment' OR kr13g3rd3v_global_term_taxonomy.taxonomy ='affiliation')
GROUP BY object_id



//STEP 2 - WORKING
CREATE OR REPLACE VIEW temp_people_with_meta
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
FROM temp_global_people_posts, kr13g3rd3v_global_postmeta
WHERE kr13g3rd3v_global_postmeta.post_id = temp_global_people_posts.ID 


//STEP 3
CREATE OR REPLACE VIEW kr13g3rd3v_global_people
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

//SCRATCH
