<?php
/**
 * Template Name: FURI Data Extract
 *
 * Generic listing for all project data for a given symposium date.
 * More detailed than the taxonomy pages and also with no links.
 */
get_header();
?>

<div class="wrapper" id="page-wrapper">

	<div class="container-fluid" id="content">

		<main class="site-main" id="main">

			<div class="row">
				<div class="col-md-12">
					<h1 class="page-title entry-title">Data Extract Page</h1>
                    <p>Please make sure that this page is always in draft mode instead of a published page.</p>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">

						<?php
							// Get list of terms with term meta 'furi_symposium_session_display' = 'yes'
							$term_args = array( 'taxonomy' => 'symposium_date' );
							$terms = get_terms( $term_args );

							$term_ids = array();
							$term_names = "";

							foreach( $terms as $term ) {
								$key = get_term_meta( $term->term_id, 'furi_symposium_extract_display', true );

								if( $key ) {
									// push the ID into the array
									$term_ids[] = $term->term_id;
									// Add term name to string.
									$term_names = $term_names . $term->name . ' ';
								}
							};

							// The Query
							$args = "";
							$featured = new WP_Query( $args );

							// The Query
							$args = array(
								'post_type' => 'furiproject',
                                'posts_per_page' => -1,
                                'post_status' => array( 'any' ),
								'tax_query' => array(
									array(
										'taxonomy' => 'symposium_date',
										'terms'    => $term_ids,
									),
								),
							);

							$query = new WP_Query( $args );

							p2p_type( 'participants_to_projects' )->each_connected( $query );

							// The Loop
							if ( $query->have_posts() ) {

								$titleselect = "";
                                $participantselect = "";
                                $impactstatement = "";

                                // Render the table header row and structure
                                ?>
                                <table class="data-dump table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="symp-date">Symposium Date</th>
                                            <th class="theme">Theme</th>
                                            <th class="program">Program</th>
                                            <th class="first">First Name</th>
                                            <th class="last">Last Name</th>
                                            <th class="graddate">Grad Date</th>
                                            <th class="major">Major</th>
                                            <th class="city">City</th>
                                            <th class="state">State</th>
                                            <th class="country">Country</th>
                                            <th class="title">Title</h4>
                                            <th class="impact">Impact Statement</th>
                                            <th class="mentor">Mentor List</th>
                                            <th class="abstract">Abstract</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                <?php

								while ( $query->have_posts() ) {
									$query->the_post();

									$project_classes = 'project-' . $post->ID . " ";
									$titleselect .= '<option value=".' . $project_classes . '">' . get_the_title() . '</option>';
									$project_classes .= asufse_symposium_tax_filteritem_classes();

									$projecttitle = get_the_title();
                                    $projectabstract = get_the_content();
                                    $impactstatement = get_field ( '_furiproject_impact_statement' );

									$researchtheme = wp_strip_all_tags(get_the_term_list( $post->ID, 'research_theme', '', ', ' , '' ));
									$mentorlist = wp_strip_all_tags(get_the_term_list( $post->ID, 'faculty_mentor', '', ', ' , '' ));
									$presentationtype = wp_strip_all_tags(get_the_term_list( $post->ID, 'presentation_type', '', ', ' , '' ));
									$symposiumdate = wp_strip_all_tags(get_the_term_list( $post->ID, 'symposium_date', '', ', ' , '' ));

                                    // Resets all variables from related participants, in case the connection hasn't been made.
                                    $participant_first = "";
                                    $participant_last = "";
                                    $participantlink = "";
                                    $participant_city = "";
                                    $participant_state = "";
                                    $participant_country = "";
                                    $program = "";
                                    $participant_graddate = "";

									// Get Connected particpant data and assign variables.
									foreach ( $post->connected as $post ) : setup_postdata( $post );

                                        $participant_first = get_field( '_participant_first_name' );
                                        $participant_last = get_field( '_participant_last_name' );
                                        $participant_link = get_the_permalink();
                                        $participant_city = get_field( '_participant_city' );
                                        $participant_state = get_field( '_participant_state' );
                                        $participant_country = get_field( '_participant_country' );

                                        $program = wp_strip_all_tags(get_the_term_list( $post->ID, 'degree_program', '', ', ' , '' ));
                                        $participant_graddate = wp_strip_all_tags(get_the_term_list( $post->ID, 'graduation_date', '', ', ' , '' ));

									endforeach;

									wp_reset_postdata(); // set $post back to original post

									// The Output
									?>
                                            <tr>
                                                <!-- Symposium Date -->
                                                <td><?php echo $symposiumdate; ?></td>
                                                <!-- Theme -->
                                                <td><?php echo $researchtheme; ?></td>
                                                <!-- Program -->
                                                <td><?php echo $presentationtype; ?></td>
                                                <!-- First Name -->
                                                <td><?php echo $participant_first; ?></td>
                                                <!-- Last Name -->
                                                <td><a href="<?php echo $participant_link; ?>"><?php echo $participant_last; ?></a></td>
                                                <!-- Graduation Date -->
                                                <td><?php echo $participant_graddate; ?></td>
                                                <!-- Major -->
                                                <td><?php echo $program; ?></td>
                                                <!-- Hometown City -->
                                                <td><?php echo $participant_city; ?></td>
                                                <!-- State -->
                                                <td><?php echo $participant_state; ?></td>
                                                <!-- Country -->
                                                <td><?php echo $participant_country; ?></td>
                                                <!-- Country -->
                                                <td><?php echo $projecttitle; ?></td>
                                                <!-- Impact Statement -->
                                                <td><?php echo $impactstatement; ?></td>
                                                <!-- Mentor -->
                                                <td><?php echo $mentorlist; ?></td>
                                                <!-- Abstract -->
                                                <td><?php echo $projectabstract; ?></td>
                                            </tr>
									<?php
								}

							} else {
								// no posts found
							}
							/* Restore original Post Data */
                            wp_reset_postdata();

                            /* Close the table */
                        ?>
                            </tbody>
                        </table>

			</main><!-- #main -->

		</div><!-- #primary -->

</div><!-- Wrapper end -->

<?php get_footer(); ?>