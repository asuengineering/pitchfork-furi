<?php
/**
 * Block template: Mentor Ready List
 *
 * Renders a list of faculty mentors flagged as "ready to mentor."
 * Returns links to faculty profile + details about featured programs and research areas.
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
$columns = get_field('furi_mentor_ready_columns');

$args = array(
    'taxonomy'   => 'faculty_mentor',
    'hide_empty' => false,  // Set to false to return all terms regardless of whether they have posts
    'meta_query' => array(
        array(
            'key'     => '_mentor_ready_yn',
            'value'   => '1',  // true is stored as '1' in ACF
            'compare' => '='
        ),
    ),
);

// Get terms
$mentors = get_terms( $args );

/**
 * asurite_id = single dimention array of ASURITE IDs. No keys
 * $term_array = ASURITE ID as key, $WP_Term object $mentor as value.
 */
$asurite_array = array();
$term_array = array();

// First loop of $mentors builds the query string for the API call to ASU Search
if (! empty ($mentors)) {
	foreach ($mentors as $mentor) {

		// Reference ACF field for ASURITE IDs for faculty mentors.
		$mentor_asurite = get_field( '_mentor_asurite', $mentor );
		if (! empty($mentor_asurite)) {
			$asurite_array[] = $mentor_asurite;
			$term_array[$mentor_asurite] = $mentor;
		}

	}

	/**
	 * Call Search API and retrieve object which includes profile details.
	 * Uses functions from Pitchfork People, so this creates a dependency for production.
	*/

	$asurite_query = implode(',' , array_unique($asurite_array));
	$api_query = get_asu_search_profile_results($asurite_query);
	$profiles = $api_query->results;

	// Alphabetize the returned results prior to using them in the next loop.
	usort($profiles, function($a, $b) {
		// Compare last names first
		$lastNameComparison = strcmp($a->display_last_name->raw, $b->display_last_name->raw);

		// If last names are the same, compare by first name
		if ($lastNameComparison === 0) {
			return strcmp($a->first_name->raw, $b->first_name->raw);
		}

		return $lastNameComparison;
	});

} else {
	// Return info box for block editor only saying there are no mentors in ready status.
}


/**
 * Loop through returned API results and echo each profile.
 * References $term_array for get_field calls for term data stored in the taxonomy.
 */

echo '<div class="uds-profile-grid ' . $columns . '">';
foreach ($profiles as $profile) {

	$asurite 		= $profile->asurite_id->raw;
	$displayname 	= $profile->display_name->raw ?? '';
	$dept			= $profile->primary_department->raw ?? '';

	$ready_project_types = get_field('_mentor_ready_project_type', $term_array[$asurite] );
	$ready_program_types = get_field('_mentor_ready_programs', $term_array[$asurite] );
	$ready_description = get_field('_mentor_ready_description', $term_array[$asurite] );

	$person = '<div class="uds-person-profile ready-mentor is-style-small">';

	$person .= '<div class="profile-img-container"><div class="profile-img-placeholder">';
	$person .= '<img src="' . $profile->photo_url->raw . '?blankImage2=1" class="profile-img" alt="Portrait of ' . $profile->display_name->raw . '" decoding="async" loading="lazy">';
	$person .= '</div></div>';
	$person .= '<div class="person">';
	$person .= '<h3 class="person-name"><a href="' . get_term_link($term_array[$asurite]) . '">' . $displayname . '</a></h3>';

	if ( ! empty ( $dept )) {
		$person .= '<p class="person-dept"><strong>' . $dept . '</strong></p>';
	}

	if ( (! empty ($ready_project_types)) || (! empty ($ready_program_types)) || (! empty ($ready_description)) ) {

		if ( ( ! empty ($ready_project_types)) || ( ! empty ($ready_program_types)) ) {
			$interests = '<div class="interests"><p><strong>Interests:</strong></p>';

			foreach ($ready_program_types as $prog) {
				$interests .= '<a class="program" href="' . get_term_link($prog) . '"><strong>' . $prog->name . '</strong></a>';
			}

			foreach ($ready_project_types as $theme) {
				$theme_icon = get_field( 'researchtheme_icon', $theme );
				$interests .= '<a class="themeicon" href="' . get_term_link($theme) . '">';
				$interests .= '<img src="' . esc_url($theme_icon['url']) . '" alt=' . esc_attr($theme_icon['alt']) . '" /></a>';
			}

			$interests .= '</div>';
			$person .= $interests;
		}

		if (! empty ($ready_description)) {

			$person .= '<div class="mentor-ready-text">' . wp_trim_words($ready_description, 30, '...') . '</div>';
		}
	}

	$person .= '</div></div>';

	echo $person;
}
echo '</div>';


