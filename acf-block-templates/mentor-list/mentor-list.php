<?php
/**
 * Block template: Mentor List
 *
 * Renders a list of faculty mentors associated with a particular symposium event.
 * Includes options for block layouts and current symposium selection.
 *
 * @package pitchfork-furi
 */

/**
 * Query loop to gather projects associated with the given symposium dates
 * Return associated faculty mentors from that query as array of ASURITE IDs
 * Query ASU Search API for the whole list of returned profiles.
 * Output Names + profile details + images + possibly school affiliation
 */

// Gather term names from ACF, Overwrite with current expo terms if necessary.
$termlist = get_field('furi_mentorlist_select_expo');
$display = get_field('furi_mentorlist_display_options');

if ( 'current' == $display ) {
	$current_terms = get_active_symposium_terms();
	$termlist = implode(',', $current_terms);
}

$args = array(
	'post_type' => 'furiproject',
	'tax_query' => array(
		array(
		'taxonomy' => 'symposium_date',
		'field'    => 'term_id',
		'terms'    => $termlist,
		'operator' => 'IN',
		),
	),
);

 // The Query. Only executed if the term list has something within it.
if (empty ($termlist)) {

	if ( $is_preview ) {
		// Show a warning to the logged in user. Display nothing if on the front end.
		echo '<div class="mentor-list-warning"><h3>No terms selected.</h3>';
		echo '<p class="lead">Please select an expo date from the sidebar.</p></div>';
	} else {
		// Silence is golden.
	}

} else {

	$query = new WP_Query($args);
	// do_action('qm/debug', $query);

	// Array to store matched terms
	$asurite_array = array();
	$termlink_array = array();
	$missing_ids = array();

	// The Loop
	if ($query->have_posts()) {
		while ($query->have_posts()) {
			$query->the_post();

			// Get the terms associated with the current post from the "faculty_mentors" taxonomy
			$faculty_mentor = wp_get_post_terms(get_the_ID(), 'faculty_mentor');

			// Check if there are any terms returned
			if (!empty($faculty_mentor) && !is_wp_error($faculty_mentor)) {
				foreach ($faculty_mentor as $mentor) {

					// Reference ACF field for ASURITE IDs for faculty mentors.
					$mentor_asurite = get_field( '_mentor_asurite', $mentor );
					if (! empty($mentor_asurite)) {
						$asurite_array[] = $mentor_asurite;
						$termlink_array[$mentor_asurite] = get_term_link($mentor);
					} else {
						$missing_ids[] = $mentor->name;
					}

				}
			}

			// Do other things with your post here, like displaying it
			// Example: the_title();
		}

	} else {
		// No posts found

	}

	/**
	 * Call Search API and retrieve object which includes profile details.
	 * Uses functions from Pitchfork People, so this creates a dependency for production.
	*/

	$columns = get_field('furi_mentorlist_columns');
	echo '<div class="uds-profile-grid ' . $columns . '">';

	$asurite_query = implode(',' , array_unique($asurite_array));

	$api_query = get_asu_search_profile_results($asurite_query);
	$profiles = $api_query->results;

	// Alphabetize the returned results prior to looping through.
	usort($profiles, function($a, $b) {
		// Compare last names first
		$lastNameComparison = strcmp($a->display_last_name->raw, $b->display_last_name->raw);

		// If last names are the same, compare by first name
		if ($lastNameComparison === 0) {
			return strcmp($a->first_name->raw, $b->first_name->raw);
		}

		return $lastNameComparison;
	});

	foreach ($profiles as $profile) {

		$asurite 		= $profile->asurite_id->raw;
		$displayname 	= $profile->display_name->raw ?? '';
		$dept			= $profile->primary_department->raw ?? '';

		$person = '<div class="uds-person-profile is-style-vertical"><div class="acf-innerblocks-container">';

		$person .= '<div class="profile-img-container"><div class="profile-img-placeholder">';
		$person .= '<img src="' . $profile->photo_url->raw . '?blankImage2=1" class="profile-img" alt="Portrait of ' . $profile->display_name->raw . '" decoding="async" loading="lazy">';
		$person .= '</div></div>';

		$person .= '<h3 class="person-name"><a href="' . $termlink_array[$asurite] . '">' . $displayname . '</a></h3>';

		if ( ! empty ( $dept )) {
			$person .= '<p class="person-dept"><strong>' . $dept . '</strong></p>';
		}

		$person .= '</div></div>';

		echo $person;
	}

	echo '</div>';

}


