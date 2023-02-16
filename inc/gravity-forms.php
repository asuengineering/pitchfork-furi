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
add_action( 'gform_after_submission_1', 'create_project_connections', 10, 2 );
function create_project_connections( $entry, $form ) {

	// More than one post may be created for a form submission.
	// The created post ids are stored as an array in the entry meta
	$created_posts = gform_get_meta( $entry['id'], 'gravityformsadvancedpostcreation_post_id' );

	// Count the number of things in the array.
	if ( count( $created_posts ) > 1 ) {
		// Greater than one item in the array means it created a project & a person.
		// Get both items in the array and create a connection.
		$from = $created_posts[0]['post_id'];
		$to = $created_posts[1]['post_id'];
	} else {
		// The form submission created exactly one post object. It will be a project. Get the id.
		// Also get the value of the post ID already selected in the form. That's stored in fieldID=5
		$from = $created_posts[0]['post_id'];
		$to = $entry[5];
	}

	// Create the connection.
	p2p_type( 'participants_to_projects' )->connect( $from, $to, array( 'date' => current_time( 'mysql' ) ) );

}