<?php
/**
 * Block template: Mentor List
 *
 * Renders a list of faculty mentors associated with a particular symposium event.
 * Includes options for block layouts and current symposium selection.
 *
 * @package pitchfork-furi
 */

// Load selected values from block.
$expodate = get_field('furi_mentorlist_select_expo');

/**
 * Query loop to gather projects associated with the given symposium dates
 * Return associated faculty mentors from that query as array of ASURITE IDs
 * Query ASU Search API for the whole list of returned profiles.
 * Output Names + profile details + images + possibly school affiliation
 */

// WP_Query arguments
$args = array(
	'post_type' => 'furiproject',
	'tax_query' => array(
		array(
		'taxonomy' => 'symposium_date',
		'field'    => 'term_id',
		'terms'    => $expodate,
		'operator' => 'IN',
		),
	),
);

 // The Query
$query = new WP_Query($args);
// do_action('qm/debug', $query);

 // Array to store matched terms
$asurite_array = array();
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

echo '<div class="uds-profile-grid col-four">';

$asurite = implode(',' , array_unique($asurite_array));
// do_action('qm/debug', $asurite);
// do_action('qm/debug', $missing_ids);

$api_query = get_asu_search_profile_results($asurite);
$profiles = $api_query->results;
do_action('qm/debug', $profiles);

foreach ($profiles as $profile) {
	echo '<div class="uds-person-profile is-style-vertical">';

	echo '<div class="profile-img-container"><div class="profile-img-placeholder">';
	echo '<img src="' . $profile->photo_url->raw . '" class="profile-img" alt="Portrait of ' . $profile->display_name->raw . '" decoding="async" loading="lazy">';
	echo '</div></div>';

	// echo pfpeople_disply_profile_image($profile, true);
	echo pfpeople_card_displayname($profile, 'vertical');
	echo pfpeople_card_email_only( $profile );
	echo '</div>';
}

echo '</div>';




