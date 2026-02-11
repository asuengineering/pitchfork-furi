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
$show_filters = get_field('furi_mentor_ready_show_filter');


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

	// Build program and project term maps for filter UI
	$program_terms_map = array(); // slug => WP_Term
	$project_terms_map = array(); // slug => WP_Term

	foreach ( $term_array as $asurite => $term ) {
		$progs = get_field('_mentor_ready_programs', $term);
		if ( ! empty( $progs ) && is_array( $progs ) ) {
			foreach ( $progs as $p ) {
				$program_terms_map[ $p->slug ] = $p;
			}
		}

		$themes = get_field('_mentor_ready_project_type', $term);
		if ( ! empty( $themes ) && is_array( $themes ) ) {
			foreach ( $themes as $t ) {
				$project_terms_map[ $t->slug ] = $t;
			}
		}
	}

	// Sort maps alphabetically by term name for nicer UI order
	uasort( $program_terms_map, function($a, $b){ return strcasecmp($a->name, $b->name); });
	uasort( $project_terms_map, function($a, $b){ return strcasecmp($a->name, $b->name); });


} else {
	// Return info box for block editor only saying there are no mentors in ready status.
}


/**
 * Build UI for filtering in $output wrapper.
 */

$output = '<aside class="mentor-ready-filters-wrapper">';
$output .= '<form class="mentor-ready-filters uds-form">';

// Project type select
$output .= '<div class="form-group form-group-researchtheme">';
$output .= '<label for="mentor-filter-project" class="form-label">Filter by research theme</label>';
$output .= '<select id="mentor-filter-project" class="form-select" aria-label="Filter mentors by research theme">';
$output .= '<option value="">All project types</option>';

if ( ! empty( $project_terms_map ) ) {
    foreach ( $project_terms_map as $slug => $term ) {
        $val   = esc_attr( $slug );
        $label = esc_html( $term->name );
        $output .= '<option value="' . $val . '">' . $label . '</option>';
    }
}

$output .= '</select>';
$output .= '</div>'; // close form-group-researchtheme

// Program select
$output .= '<div class="form-group form-group-program">';
$output .= '<label for="mentor-filter-program" class="form-label">Filter by program</label>';
$output .= '<select id="mentor-filter-program" class="form-select" aria-label="Filter mentors by program">';
$output .= '<option value="">All programs</option>';

if ( ! empty( $program_terms_map ) ) {
    foreach ( $program_terms_map as $slug => $term ) {
        $val   = esc_attr( $slug );
        $label = esc_html( $term->name );
        $output .= '<option value="' . $val . '">' . $label . '</option>';
    }
}

$output .= '</select>';
$output .= '</div>'; // close form-group-program

// Reset filters button for convienence.
$output .= '<button id="filter-reset" class="btn btn-dark btn-md" type="reset" value="reset">';
$output .= '<span class="fas fa-undo" title="Reset filters"></span>Reset</button>';
$output .= '</form>'; // close mentor-ready-filter-wrap

$output .= '<p class="ready-mentor-count">Displaying all mentors.</p>';
$output .= '</aside>'; // close block

// If $show_filters is true, render filters. If not, omit.
echo '<div class="mentor-ready-block">';
if ( ! empty( $show_filters ) ) {
    echo $output;
}


/**
 * Loop through returned taxonomy loop results and echo each profile.
 * References $term_array for get_field calls for term data stored in the taxonomy.
 */

echo '<div class="uds-profile-grid col-two">';
foreach ($profiles as $profile) {

	$asurite 		= $profile->asurite_id->raw;
	$displayname 	= $profile->display_name->raw ?? '';
	$dept			= $profile->primary_department->raw ?? '';

	$ready_project_types = get_field('_mentor_ready_project_type', $term_array[$asurite] );
	$ready_program_types = get_field('_mentor_ready_programs', $term_array[$asurite] );
	$ready_description = get_field('_mentor_ready_description', $term_array[$asurite] );

	// Build comma-separated slug lists for data attributes (programs and projects)
	$program_slugs = array();
	if ( ! empty( $ready_program_types ) && is_array( $ready_program_types ) ) {
		foreach ( $ready_program_types as $pt ) {
			if ( isset( $pt->slug ) ) $program_slugs[] = $pt->slug;
		}
	}
	$project_slugs = array();
	if ( ! empty( $ready_project_types ) && is_array( $ready_project_types ) ) {
		foreach ( $ready_project_types as $t ) {
			if ( isset( $t->slug ) ) $project_slugs[] = $t->slug;
		}
	}

	$data_programs = esc_attr( implode( ',', $program_slugs ) );
	$data_projects = esc_attr( implode( ',', $project_slugs ) );

	// Open the mentor card with data attributes for client-side filtering
	$person = '<div class="mentor-card uds-person-profile ready-mentor is-style-small"'
			. ' data-asurite="' . esc_attr($asurite) . '"'
			. ' data-programs="' . $data_programs . '"'
			. ' data-projects="' . $data_projects . '">';


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

// Close grid wrapper(s) opened above
if ( ! empty( $show_filters ) ) {
    echo '</div></div>';
} else {
    echo '</div>';
}



