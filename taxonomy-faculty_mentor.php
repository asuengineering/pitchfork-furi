<?php
/**
 * Displays an individual term from faculty_mentor. 
 * Pulls demographic data from iSearch, including current school affiliation.
 * Displays a featured quote or the excerpt/link to a featured post.
 */

get_header();
$term = get_queried_object();

function get_isearch_data($term_id) {
	
	// Parse results based on supplied email address.
	// If the email address is blank, attempt to query for the address from the old ASU Solr feed. 
	$mentor_email = get_field( '_mentor_email', $term_id );

	if ( empty( $mentor_email )) {
		// Let's dig for an email address associated with the eid from the Search URL.
		// Pattern for old iSearch URL should be: https://isearch.asu.edu/profile/####
		$mentor_isearch_url = get_field( '_mentor_isearch', $term_id );
		$mentor_eid = substr(strrchr($mentor_isearch_url, "/"), 1);
		$mentor_isearch_json = 'https://asudir-solr.asu.edu/asudir/directory/select?q=eid:' . $mentor_eid . '&wt=json';

		$solr_request = wp_safe_remote_get( $mentor_isearch_json );

		// Error check for invalid JSON.
		if ( is_wp_error( $solr_request ) ) {
			return false; // Bail early.
		}

		$solr_body   = wp_remote_retrieve_body( $solr_request );
		$solr_data   = json_decode( $solr_body );
		$solr_output = array();
		do_action('qm/debug', $mentor_isearch_url);

		if ( ! empty( $solr_data ) ) {
			// Check # of responses to see if the returned result actually has data.
			if ( $solr_data->response->numFound) {
				$solr_path = $solr_data->response->docs[0];
				$solr_email = $solr_path->emailAddress;
			} else {
				// The call was successful, but there's no data.
			}
		} else {
			// The call was successful, but $solr_data was empty.
		}

		// If after all of that, we've obtained an email address, update the ACF field with it.
		update_field( '_mentor_email', $solr_email, $term_id );
	}

	// Go ahead and get the field again. There's a good chance it was updated just now.
	$mentor_email = get_field( '_mentor_email', $term_id );
	if (! empty( $mentor_email )) {
		// Parse the identifier from the email address, use it as the search term.
		$search_term = strstr($mentor_email, "@", true);
		$search_json = 'https://search.asu.edu/api/v1/webdir-profiles/faculty-staff?query=' . $search_term . '&size=1';
		// do_action('qm/debug', $search_json);
		
		$search_request = wp_safe_remote_get( $search_json );
		
		// Error check for invalid JSON.
		if ( is_wp_error( $search_request ) ) {
			return false; // Bail early.
		}

		$search_body   = wp_remote_retrieve_body( $search_request );
		$search_data   = json_decode( $search_body );
		$search_output = array();

		if ( ! empty( $search_data ) ) {
			$path = $search_data->results[0];
			// do_action('qm/debug', $path);

			$output['title'] = $path->primary_title->raw[0];
			$output['school'] = $path->primary_department->raw;
			$output['photo'] = $path->photo_url->raw;
			$output['bio'] = wp_kses_post($path->bio->raw);
			$output['expertise_areas'] = $path->expertise_areas->raw;
			$output['email_address'] = $path->email_address->raw;
			$output['research_website'] = $path->research_website->raw;
			$output['facebook'] = $path->facebook->raw;
			$output['twitter'] = $path->twitter->raw;
			$output['linkedin'] = $path->linkedin->raw;
			$output['eid'] = $path->eid->raw;
			$output['deptid'] = $path->primary_deptid->raw;
			$output['department'] = $path->primary_department->raw;

			// Get link to affiliated school based on recorded department ID.
			// We can pre-determine the department IDs for common results and look up the rest.
			$dept = $path->primary_deptid->raw;
			$dept_links = array(
				'1659' => 'https://sbhse.engineering.asu.edu',
				'1660' => 'https://ssebe.engineering.asu.edu',
				'1662' => 'https://semte.engineering.asu.edu',
				'1405' => 'https://ecee.engineering.asu.edu',
				'1661' => 'https://scai.engineering.asu.edu',
				'35480' => 'https://poly.engineering.asu.edu',

				// Poly has multiple subdepartments, each should also link back to the main site.
				'35488' => 'https://poly.engineering.asu.edu',
				'35489' => 'https://poly.engineering.asu.edu',
				'35490' => 'https://poly.engineering.asu.edu',
				'35491' => 'https://poly.engineering.asu.edu',
				'35492' => 'https://poly.engineering.asu.edu',
				'35493' => 'https://poly.engineering.asu.edu',
				'35494' => 'https://poly.engineering.asu.edu',
				'35495' => 'https://poly.engineering.asu.edu',
				'35496' => 'https://poly.engineering.asu.edu',
				'35497' => 'https://poly.engineering.asu.edu',
				'35498' => 'https://poly.engineering.asu.edu',
				'35499' => 'https://poly.engineering.asu.edu',
				'35558' => 'https://poly.engineering.asu.edu',
				'35559' => 'https://poly.engineering.asu.edu',
				'35560' => 'https://poly.engineering.asu.edu',

				// 'currently null' => 'https://msn.engineering.asu.edu',
				// 'defaults back to SCAI' => 'https://cidse.engineering.asu.edu',
			);

			if (array_key_exists( $dept, $dept_links)) {
				// The department ID from $data has a hardcoded URL in the array above.
				$output['deptURL'] = $dept_links[$dept];
			} else {
				// Not found. Eventually we can include the code to look it up from the API.
				// https://search.asu.edu/api/v1/webdir-departments
			}

		}
	}

	// do_action('qm/debug', $output);
	return $output;
}

$demos = get_isearch_data($term);

?>
<main class="site-main" id="main">

	<div class="container" id="page-header">

		<div class="row">

			<?php
			// Deal with mentor profile images. Uploaded image overrides default iSearch portrait.
			$portrait = '';				

			// Check if iSearch has an image for us and if it's available for display.
			do_action( 'qm/debug', $demos);
			if (! empty( $demos['photo'])) {

				$portrait = '<img class="isearch-image img-fluid" src="' . $demos['photo'] . '" alt="Portrait of ' . get_queried_object()->term_name . '"/>';
				$portrait = '<div class="col-md-3">' . $portrait . '</div>';

			}

			// There's been a photo uploaded. Overwrite the variable and use it instead.
			$portrait_acf = get_field( '_mentor_acf_thumbnail', $term );
			if ( ! empty( $portrait_acf )) {
				$portrait = '<img class="acf-image img-fluid" src="' . $portrait_acf . '" alt="Portrait of ' . get_queried_object()->term_name . '"/>';
				$portrait = '<div class="col-md-4">' . $portrait . '</div>';
			}

			// As long as we have something in $portrait, output the scaffolding + the image.
			if ( ! empty($portrait)) {
				echo $portrait;
				echo '<div class="col-md">';
			} else {
				// No portrait, but we want to constrain the following column to 3/4 width.
				echo '<div class="col-md-8">';
			}

			?>

			<h1 class="mentor-name"><?php echo $term->name; ?></h1>

			<?php

			// Check for featured mentor status. Output highlight label if so.
			$mentorstring = '';
			$mentorprogram = get_field( '_mentor_featured_program', $term );
			if ( !empty ( $mentorprogram )) {
				$mentorstring = '<h2><span class="highlight-gold">Featured mentor, ' . esc_html( $mentorprogram->name ) . '</span></h2>';
				echo $mentorstring;
			}
			
			// Build job title and linked school affiliation.
			if ($demos['deptURL']) {
				$schoollink = '<a href="' . $demos['deptURL'] . '">' . $demos['school'] . '</a>';
			} else {
				$schoollink = $demos['school'];
			}

			echo '<p class="lead">' . $demos['title'] . ', ' . $schoollink . '</p>';
			echo '<p class="lead">Total mentored projects: ' . $wp_query->post_count . '</p>';	

			// If there's a custom portrait, assume it's vertical and therefore we have more space for the bio.
			$bio = '';
			if ( ! empty( $portrait_acf )) {
				$bio = $demos['bio'];
			} else {
				$bio = '<p>' . $demos['shortBio'] . '</p>';
			}

			// Override whatever is happening with the bios iSearch with the tag description.
			$description = get_the_archive_description();
			if ($description) {
				$bio = $description;
			}

			echo wp_kses_post($bio);

			// div.infobar: Social media icons, email address and isearch button.
			$isearch_btn = '<a class="isearch btn btn-md btn-gray" href="https://search.asu.edu/profile/' . $demos['eid'] . '" target="_blank">ASU Search</a>';
			$email_btn = '<a class="email btn btn-md btn-gray" href="mailto:' . $demos['email_address'] . '" target=_blank><span class="fas fa-envelope"></span>Email</a>';
			$socialbar = '';

			if ( ! empty( trim($demos['twitter'] ) ) ) {
				$socialbar .= '<li><a href="' . $demos['twitter'] . '" target=_blank><span class="fab fa-twitter"></span></a></li>';
			}

			if ( ! empty( trim($demos['linkedin'] ) ) ) {
				$socialbar .= '<li><a href="' . $demos['linkedin'] . '" target=_blank><span class="fab fa-linkedin"></span></a></li>';
			}

			if ( ! empty( trim($demos['facebook'] ) ) ) {
				$socialbar .= '<li><a href="' . $demos['facebook'] . '" target=_blank><span class="fab fa-facebook"></span></a></li>';
			}

			if ( ! empty( trim($demos['research_website'] ) ) ) {
				$socialbar .= '<li><a href="' . $demos['research_website'] . '" target=_blank><span class="fas fa-globe"></span></a></li>';
			}

			if ( ! empty( $socialbar ) ) {
				$socialbar =  '<ul class="social-icons">' . $socialbar . '</ul>';
			}

			echo '<div class="info-bar">' . $isearch_btn . $email_btn . $socialbar . '</div>';

			?>

			</div>
		</div>
	</div>

	<?php
	// Is this person a featured mentor? (Look at $mentorprogram from above again.)
	// If so, which content should be displayed? The quote or the post?
	$mentor_use_quote = get_field( '_mentor_use_quote_yn', $term );
	
	if ( ! empty( $mentorprogram )) {

		if ( $mentor_use_quote ) {

			// Display a quote. 
			echo '<div class="container" id="quote-content" >';
			echo '<div class="row">';
			echo '<div class="col-md-8">';

			// Use the quote + the description for the term.
			$mentorquote = get_field( '_mentor_featured_quote', $term );
			$mentorlinkcite = get_field( '_mentor_featured_linked_citation', $term );

			if ( !empty ($mentorquote)) {
				echo '<figure class="uds-blockquote accent-gold bq-color-padding">';
				echo '<div class="feature-wrapper">';
				echo '<svg title="Open quote" role="decorative" viewBox="0 0 302.87 245.82">';
				echo '<path d="M113.61,245.82H0V164.56q0-49.34,8.69-77.83T40.84,35.58Q64.29,12.95,100.67,0l22.24,46.9q-34,11.33-48.72,31.54T58.63,132.21h55Zm180,0H180V164.56q0-49.74,8.7-78T221,35.58Q244.65,12.95,280.63,0l22.24,46.9q-34,11.33-48.72,31.54t-15.57,53.77h55Z"></path>';
				echo '</svg>';
				echo '</div>';
				echo '<div class="content-wrapper">';
				echo '<blockquote>';
				echo '<p>' . wp_kses_post( $mentorquote ) .'</p>';
				echo '</blockquote>';
				echo '<figcaption>';

				if ( ! empty( $mentorlinkcite )) {
					
					$citedname = furi_participant_name( $mentorlinkcite->ID );
					$citedmajor = wp_strip_all_tags( get_the_term_list( $mentorlinkcite->ID, 'degree_program', '', ', ', '' ) );
				
					echo '<cite class="name">';
					echo '<a href="' . esc_url( get_permalink( $mentorlinkcite ) ) . '" title="' . esc_html( $citedname ) . '">';
					echo  esc_html( trim( $citedname ) ) . '</a>';
					echo '</cite>';
					echo '<cite class="description">' . esc_html( $citedmajor ) . '</cite>';

				} else {

					$mentorcitename = get_field( '_mentor_featured_citation_name', $term );
					$mentorcitedesc = get_field( '_mentor_featured_citation_description', $term );

					echo '<cite class="name">' . wp_kses_post( $mentorcitename ) . '</cite>';
					echo '<cite class="description">' . wp_kses_post( $mentorcitedesc ) . '</cite>';
				}

				echo '</figcaption>';
				echo '</div>';
				echo '</figure>';
			}	

			echo '</div>';
			echo '</div><!-- end .row -->';
			echo '</div><!-- end .quote-content -->';

		} else {
			
			// Display the blog post excerpt for the featured person.
			echo '<div class="container" id="blog-post-excerpt">';
			echo '<div class="row">';
			echo '<div class="col-md-8">';

			// Use the content from the blog post.
			$mentorpost = get_field( '_mentor_featured_post', $term );
			
			$mentor_excerpt = '';	
			if ( !empty ($mentorpost)) {
				$mentor_excerpt = apply_filters( 'the_excerpt', $mentorpost->post_excerpt );
				echo '<div class="post-teaser-box">';
				echo '<h3>Featured Mentor Q&amp;A</h3>';
				echo '<p class="lead"> ' . wp_kses_post( $mentor_excerpt ) .'</p>';
				echo '<p class="read-more-button"><a href="' . esc_url(get_permalink($mentorpost->ID)) . '" class="btn btn-gold">Read more</a></p>';
				echo '</div>';
			}

			echo '</div>';
			echo '</div><!-- end .row -->';
			echo '</div><!-- end #blog-post-excerpt -->';
		}

	} else {
		// This person isn't a featured mentor. 
	}
	
	// Begin related projects
	$_events = get_terms(
		'symposium_date',
		array(
			'orderby' => 'ID',
			'order' => 'DESC',
		)
	);

	foreach ( $_events as $event ) :

		$event_slug = $event->slug;

		$relatedprojects = new WP_Query(
			array(
				'post_type' => 'furiproject',
				'posts_per_page' => -1,
				'post_status' => 'publish',
				'tax_query' => array(
					'relation' => 'AND',
					array(
						'taxonomy' => 'symposium_date',
						'field' => 'slug',
						'terms'    => $event_slug,
					),
					array(
						'taxonomy' => 'faculty_mentor',
						'field'    => 'slug',
						'terms'    => $term->slug,
					),
				),
				'orderby' => '',
			)
		);

		// Grab all related participants from the projects query above.
		p2p_type( 'participants_to_projects' )->each_connected( $relatedprojects, array(), 'relatedparticipants' );

		if ( $relatedprojects->have_posts() ) :
			?>
			<div class="section-head">
				<div class="container">
					<div class="row">
						<div class="col-md-12">
							<h3><?php echo esc_html( $event->name ); ?></h3>
						</div>
					</div>
				</div>
			</div>
		
			<section class="related-projects">
				<div class="container">
					<div class="row">
		
						<?php

						while ( $relatedprojects->have_posts() ) :
							$relatedprojects->the_post();

							foreach ( $post->relatedparticipants as $related ) :
								setup_postdata( $related );

								$relatedparticipant = furi_participant_name( $related->ID );
								$major = wp_strip_all_tags( get_the_term_list( $related->ID, 'degree_program', '', ', ', '' ) );
								$participantlink = get_permalink( $related->ID );

							endforeach;

							$presentationtype = wp_strip_all_tags( get_the_term_list( $post->ID, 'presentation_type', '', ', ', '' ) );
							$projectimpact = get_field( '_furiproject_impact_statement', $post->ID );
							$projectclassname = get_research_theme_class_names( $post->ID );

							?>
							<div class="col-md-4">
								<div class="card card-hover card-symposium">
									<div class="card-header <?php echo esc_html( $projectclassname ); ?>">
										<h3 class="participant"><?php echo esc_html( $relatedparticipant ); ?></h3>
										<h5 class="major"><?php echo esc_html( $major ); ?></h5>
									</div>
									<div class="card-body">
										<?php
										the_title(
											sprintf( '<h4 class="card-title"><a href="%s" rel="bookmark">', esc_url( $participantlink ) ),
											'</a></h4>'
										);
										?>
										<p class="card-text"><?php echo esc_html( $projectimpact ); ?></p>
										<p class="card-text project-type">
											<strong>Program: </strong><?php echo esc_html( $presentationtype ); ?>
										</p>
									</div>
								</div>
							</div>
		
							<?php

						endwhile;

						?>
		
					</div><!-- end .row -->
				</div><!-- end .container -->
			</section><!-- end #related-projects-->
		
			<?php

		endif;
		wp_reset_postdata();
	endforeach;
	?>

	</div><!-- end #main -->

</div><!-- Container end -->

</div><!-- Wrapper end -->

<?php get_footer(); ?>
