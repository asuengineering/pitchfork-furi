<?php
/**
 * Additional functions for Gravity Forms within this site.
 *
 * Contents:
 *   - Add taxonomy items to intake form's dropdown fields.
 *   - Enqueue additional CSS file for UDS
 *
 * @package uds-wordpress-furi
 */


// Populate faculty mentor dropdown with taxonomy terms.
// ===============================================
add_filter('gform_pre_render_1', 'furi_abstract_submission_populate_mentors');
function furi_abstract_submission_populate_mentors($form){

    $terms = get_terms( array(
        'taxonomy' => 'faculty_mentor',
        'hide_empty' => false,
        'orderby'   =>'title',
        'order'   =>'ASC',
    ) );

    // Creating drop down item array.
    $items = array();

    // Adding initial blank value.
    $items[] = array("text" => "", "value" => "");

    // Adding tag names to the items array
    foreach($terms as $term) {
        $items[] = array(
           "value" => $term->term_id,
           "text" =>  $term->name
      );
    }

    // Adding items to field id 60
    foreach($form["fields"] as &$field) {
        if($field["id"] == 60 ) {
            $field["type"] = "select";
            $field["choices"] = $items;
        }
    }

    return $form;
}

// Assign faculty mentor to submitted project.
// ===============================================
add_action( 'gform_advancedpostcreation_post_after_creation_1', 'furi_abstract_assign_misc_data', 10, 4 );
function furi_abstract_assign_misc_data($post_id, $feed, $entry, $form) {

    do_action( 'qm/debug', $entry);

    // Check feed ID first. That will let us know which post_id object was created.
    // Feed ID #1 - Create New Participant
    // Feed ID #2 - Create New Project

    $feed_id = rgar( $feed, 'id');

    // Assign faculty_mentor term to the correct taxonomy.
    if (2 == $feed_id) {
        $mentor = rgar ( $entry, '60');
        wp_set_object_terms( $post_id, intval($mentor), 'faculty_mentor');
    }

    // TODO: Assign featured image with $feed_id = 1;
}

// Create participant_to_project connections after post submission.
// ===============================================
add_action( 'gform_advancedpostcreation_post_after_creation_1', 'create_project_connections', 10, 4 );
function create_project_connections( $post_id, $feed, $entry, $form ) {

	// gf_advancedpostcreation()->log_debug( __METHOD__ . "(): Created Posts: " . print_r($created_posts, true));
	// gf_advancedpostcreation()->log_debug( __METHOD__ . "(): Entry Data: " . print_r($entry, true));
	// gf_advancedpostcreation()->log_debug( __METHOD__ . "(): Feed: " . print_r($feed, true));

	gf_advancedpostcreation()->log_debug( __METHOD__ . "(): Checking feed to establish posts 2 posts connections.");

	/**
	 * Determine feed ID and process additional instructions conditionally.
	 * Feed ID=1 is Create New Participant
	 * Feed ID=2 is Create New Project
	 *
	 * Logic: No action needed for new participants.
	 * If new project, determine if there is data in $entry[5] which is the id of an existing participant.
	 *  - If found, create a connection between that ID and the new $post_id
	 *  - If not found, create a connection between the two items found within $created_posts
	 */
	if ($feed['id'] == 2 ) {

		// Assigning to/from values as if there was just a project submitted.
		$from = $post_id;
		$to = $entry[5];

		// When there's no value in the entry for an additional connected project, assume one has been created.
		if ( empty($to) ) {

			// All of the created post ids are stored as an array in the entry meta
			$created_posts = gform_get_meta( $entry['id'], 'gravityformsadvancedpostcreation_post_id' );

			// Still checking if there is more than one item in the $created_posts array due to async processing possibilities.
			if ( count( $created_posts ) > 1 ) {

				// Overwrite both previous values for to/from with whatever is in the array.
				// Prevents $from from being duplicated if the processes run out of order.
				$from = $created_posts[0]['post_id'];
				$to = $created_posts[1]['post_id'];

			}

		}

		// Create the connection within post-2-post
		p2p_type( 'participants_to_projects' )->connect( $from, $to, array( 'date' => current_time( 'mysql' ) ) );

	}

}
