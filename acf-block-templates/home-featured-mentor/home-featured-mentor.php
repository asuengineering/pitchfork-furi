<?php
/**
 * Block template: Featured Mentors
 *
 * @package pitchfork-furi
 */

$mentors = get_field('block_featured_mentor_select');

if ( $mentors ) {

    echo '<section id="featured-mentors">';


	/**
	 * First foreach loop gathers ASURITE IDs from the WP Term objects returned by ACF.
	 * Then, call the ASU Search API once for all people in the block
	 * A second foreach loop cycles through the returned API data to build the <img> profile image markup.
	 */

	$asurite_array = array();
	$mentor_photos = array();

	foreach ($mentors as $mentor_asurite) {
		$mentorID = get_field( '_mentor_asurite', $mentor_asurite );
		$asurite_array[] = $mentorID;
	}

	$asurite_query = implode(',' , array_unique($asurite_array));
	$api_query = get_asu_search_profile_results($asurite_query);
	$profiles = $api_query->results;

	foreach ($profiles as $profile) {
		$mentor_photos[$profile->asurite_id->raw] = '<img src="' . $profile->photo_url->raw . '?blankImage2=1" class="profile-img img-fluid" alt="Portrait of ' . $profile->display_name->raw . '" decoding="async" loading="lazy">';
	}

	// Uses $mentor_photos profile image array to output the results.
    foreach ($mentors as $mentor) {

        $mentorprogram = get_field( '_mentor_featured_program', $mentor );
		$mentor_asurite = get_field( '_mentor_asurite', $mentor );

        echo '<div class="mentor">';
        echo '<h3><a href="'. get_term_link($mentor) . '" title="' . esc_html( $mentor->name ). '">' . esc_html( $mentor->name ) . '</a>, featured ' . esc_html( $mentorprogram->name ) . ' mentor</h3>';

		echo $mentor_photos[$mentor_asurite];

        // Which content should be displayed? The quote or the post?
        $mentor_use_quote = get_field( '_mentor_use_quote_yn', $mentor );

        if ( $mentor_use_quote ) {

            // Use the quote + the description for the term.
            $mentorquote = get_field( '_mentor_featured_quote', $mentor);
            $mentorlinkcite = get_field( '_mentor_featured_linked_citation', $mentor );

            if ( !empty ($mentorquote)) {
                echo '<div class="uds-blockquote accent-gold">';
                echo '<svg title="Open quote" role="decorative" viewBox="0 0 302.87 245.82">';
                echo '<path d="M113.61,245.82H0V164.56q0-49.34,8.69-77.83T40.84,35.58Q64.29,12.95,100.67,0l22.24,46.9q-34,11.33-48.72,31.54T58.63,132.21h55Zm180,0H180V164.56q0-49.74,8.7-78T221,35.58Q244.65,12.95,280.63,0l22.24,46.9q-34,11.33-48.72,31.54t-15.57,53.77h55Z"></path>';
                echo '</svg>';
                echo '<blockquote>';
                echo '<p>' . wp_kses_post( $mentorquote ) .'</p>';
				echo '<div class="citation"><div class="citation-content">';

                if ( ! empty( $mentorlinkcite )) {

                    $citedname = furi_participant_name( $mentorlinkcite->ID );
                    $citedmajor = wp_strip_all_tags( get_the_term_list( $mentorlinkcite->ID, 'degree_program', '', ', ', '' ) );

					echo '<cite class="name">';
                    echo '<a href="' . esc_url( get_permalink( $mentorlinkcite ) ) . '" title="' . esc_html( $citedname ) . '">';
                    echo  esc_html( trim( $citedname ) ) . '</a>';
                    echo '</cite>';
                    echo '<cite class="description">' . esc_html( $citedmajor ) . '</cite>';

                } else {

                    $mentorcitename = get_field( '_mentor_featured_citation_name', $mentor );
                    $mentorcitedesc = get_field( '_mentor_featured_citation_description', $mentor );

                    echo '<cite class="name">' . wp_kses_post( $mentorcitename ) . '</cite>';
                    echo '<cite class="description">' . wp_kses_post( $mentorcitedesc ) . '</cite>';
                }

				echo '</div></div>';
                echo '</blockquote>';
                echo '</div>';
            }

        } else {

            // Use the content from the blog post.
            $mentorpost = get_field( '_mentor_featured_post', $mentor );

            if ( !empty ($mentorpost)) {
                echo '<div class="featured-mentor-post">';
                $mentorpost_excerpt = apply_filters( 'the_excerpt', $mentorpost->post_excerpt );
                $mentorpost_readmore = '<a href="' . esc_url(get_permalink($mentorpost->ID)) . '" class="read-more btn btn-maroon">Read more</a>';
                echo $mentorpost_excerpt . '<p class="read-more">' . $mentorpost_readmore . '</p>';
                echo '</div>';
            }

        }

        echo '</div>';  // End .mentor
    }
    echo '</section>'; // End section.
}

