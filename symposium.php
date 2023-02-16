<?php
/**
 * Template Name: Symposium
 *
 * @package uds-wordpress-theme
 */

get_header();
?>

<!-- Markup for the page -->
<div class="wrapper" id="page-wrapper">
	<div class="container" id="main-content">
		<div class="row">

			<div class="col-lg-9 order-2" id="symposium-grid-col">
				<div class="row" id="symposium-grid">

					<?php
					$term_ids = get_active_symposium_terms();

					// Separate query arguments for a few of the select box terms.
					$filterargs = '';
					$filterargs = array(
						'post_type' => 'furiproject',
						'posts_per_page' => -1,
						'fields' => 'ids',
						'tax_query' => array(
							array(
								'taxonomy' => 'symposium_date',
								'terms'    => $term_ids,
							),
						),
					);


					// The actual query.
					$args = '';
					$featured = new WP_Query( $args );

					$args = array(
						'post_type' => 'furiproject',
						'posts_per_page' => -1,
						'tax_query' => array(
							array(
								'taxonomy' => 'symposium_date',
								'terms'    => $term_ids,
							),
						),
						'orderby' => 'rand',
					);

					$query = new WP_Query( $args );

					p2p_type( 'participants_to_projects' )->each_connected( $query );

					// The Loop.
					if ( $query->have_posts() ) {

						$titleselect = '';
						$participantselect = '';

						while ( $query->have_posts() ) {
							$query->the_post();

							$project_classes = 'project-' . $post->ID . ' ';
							$titleselect .= '<option value=".' . $project_classes . '">' . get_the_title() . '</option>';
							$project_classes .= asufse_symposium_tax_filteritem_classes();

							$projecttitle = get_the_title();
							$projectexcerpt = get_the_excerpt();
							$projectimpact = get_field( '_furiproject_impact_statement' );
							$projectlink = get_the_permalink();

							$researchtheme = wp_strip_all_tags( get_the_term_list( $post->ID, 'research_theme', '', ', ', '' ) );
							$mentorlist = get_the_term_list( $post->ID, 'faculty_mentor', '', ', ', '' );
							$presentationtype = wp_strip_all_tags( get_the_term_list( $post->ID, 'presentation_type', '', ', ', '' ) );
							$symposiumdate = wp_strip_all_tags( get_the_term_list( $post->ID, 'symposium_date', '', ', ', '' ) );

							// Link destination for each project. Set all at once according to the symposium it belongs to.
							$thissymposium = '';
							$thissymposium = get_the_terms( $post->ID, 'symposium_date' );

							$presentationtype = wp_strip_all_tags( get_the_term_list( $post->ID, 'presentation_type', '', ', ', '' ) );
							$projectimpact = get_field( '_furiproject_impact_statement', $post->ID );
							$projectclassname = get_research_theme_class_names( $post->ID );

							$featured_yn = get_field( '_furiproject_featured' );
							$featured_thumb = '';
							if (has_post_thumbnail( $post->ID ) ) {
								$featured_thumb = get_the_post_thumbnail( $post->ID, 'large', array( 'class' => 'img-fluid featured' ) );
							}
							
							// Get Connected particpant data and assign variables.
							foreach ( $post->connected as $post ) :
								setup_postdata( $post );

								$relatedparticipant = furi_participant_name( $post->ID );
								$participantlink = get_the_permalink();
								$major = wp_strip_all_tags( get_the_term_list( $post->ID, 'degree_program', '', ', ', '' ) );

								$program = wp_strip_all_tags( get_the_term_list( $post->ID, 'degree_program', '', ', ', '' ) );

								$participant_classes = 'participant-' . $post->ID . ' ';
								$participantselect .= '<option value=".' . $participant_classes . '">' . get_field( '_participant_last_name' ) . ', ' . get_field( '_participant_first_name' ) . '</option>';
								$participant_classes .= asufse_symposium_tax_filteritem_classes();

								$participant_ids[] = $post->ID;

							endforeach;

							wp_reset_postdata(); // Set $post back to original post.

							// Output a single card with correct classes for filtering. Check for featured card format.
							if ( ( $featured_yn ) && ( $featured_thumb ) ) {
								?>
								<div class="col-md-8 grid-item <?php echo esc_html( $project_classes . $participant_classes ); ?>">
									<div class="card card-hover card-featured-symposium">
										
										<a href="<?php echo esc_url( $participantlink ); ?>" rel="bookmark">
											<?php echo $featured_thumb; ?>
										</a>
										
										<div class="card card-foldable card-symposium">
											<div class="card-header <?php echo esc_html( $projectclassname ); ?>">
												<a class="<?php echo esc_html( $projectclassname ); ?>" id="example-header-1" class="collapsed" data-toggle="collapse" href="#example-content-1" role="button" aria-expanded="false" aria-controls="example-content-1">
													<h4 class="participant"><?php echo esc_html( $relatedparticipant ); ?></h3>
													<h5 class="major"><?php echo esc_html( $major ); ?></h5>	
													<span class="fas fa-chevron-up"></span>
												</a>
											</div>
											<div id="example-content-1" class="card-body collapse" aria-labelledby="example-header-1">
												<h5 class="card-title">
													<a href="<?php echo esc_url( $participantlink ); ?>" rel="bookmark">
														<?php echo esc_html( $projecttitle ); ?>
													</a>
												</h5>
												<p class="card-text"><?php echo esc_html( $projectimpact ); ?></p>
												<p class="card-text project-mentor">
													<strong>Mentor: </strong><?php echo wp_kses_post( $mentorlist ); ?>
												</p>
												<p class="card-text project-type">
													<strong>Program: </strong><?php echo esc_html( $presentationtype ); ?>
												</p>
											</div>
										</div>
									</div>
								</div>
								<?php
							} else {
								?>
								<div class="col-md-4 grid-item <?php echo esc_html( $project_classes . $participant_classes ); ?>">
									<div class="card card-hover card-symposium">
										<div class="card-header <?php echo esc_html( $projectclassname ); ?>">
											<a class="<?php echo esc_html( $projectclassname ); ?>" href="<?php echo esc_url( $participantlink ); ?>" rel="bookmark">
												<h4 class="participant"><?php echo esc_html( $relatedparticipant ); ?></h3>
												<h5 class="major"><?php echo esc_html( $major ); ?></h5>
											</a>
										</div>
										<div class="card-body">
											<h5 class="card-title">
												<a href="<?php echo esc_url( $participantlink ); ?>" rel="bookmark">
													<?php echo esc_html( $projecttitle ); ?>
												</a>
											</h5>
											<p class="card-text"><?php echo esc_html( $projectimpact ); ?></p>
											<p class="card-text project-mentor">
												<strong>Mentor: </strong><?php echo wp_kses_post( $mentorlist ); ?>
											</p>
											<p class="card-text project-type">
												<strong>Program: </strong><?php echo esc_html( $presentationtype ); ?>
											</p>
										</div>
									</div>
								</div>
								<?php
							} // end featured_yn check.

						} // end while.
					}
					// Restore original post data.
					wp_reset_postdata();

					?>
				</div><!-- end #symposium-grid -->
			</div><!-- end col-md-8 -->

			<div class="col-lg-3 order-1">
				<div class="above-filters">
					<h4 class="symposium-date"><?php the_title(); ?></h4>
					<p class="filter-count"><?php echo esc_html( $query->found_posts ); ?> projects</p>
					<button id="filter-mobile-panel" class="btn btn-maroon btn-sm" value="shuffle">
						<span class="fas fa-filter" title="Show/Hide mobile panel"></span>Filters
					</button>
				</div>
				<div class="filter-group">
					<div class="filter-container">
						
						<form id="research-theme-filters">
							<label for="filter-research_theme">Research Themes</label>
							<?php echo get_research_theme_radios( $filterargs, 'research_theme', 'Research Theme' ); ?>
						</form>
					
						<form id="presentation-type-filters">
							<label for="filter-presentation_type">Presentation Types</label>
							<?php echo get_project_type_radios( $filterargs, 'presentation_type', 'Presentation Type' ); ?>
						</form>

						<label class="pt-2">Additional Filters</label>
						
						<form class="form-inline">
							<!-- <label data-placeholder="Search for a Participant" for="filter-participant">Participant Name</label> -->
							<select id="filter-participant" class="filter" multiple title="Select a participant">
								<?php echo $participantselect; ?>
							</select>
						</form>

						<form class="form-inline">
							<!-- <label for="filter-titles">Project Title</label> -->
							<select id="filter-titles" class="filter" multiple title="Select a project">
								<?php echo $titleselect; ?>
							</select>
						</form>
					
						<form class="form-inline">
							<!-- <label for="filter-degree_program">Degree Program</label> -->
							<?php echo get_all_participant_tax_terms( $participant_ids, 'degree_program', 'degree program' ); ?>
						</form>
						
						<form class="form-inline">
							<!-- <label for="filter-faculty_mentor">Faculty Mentor</label> -->
							<?php echo get_all_project_tax_terms( $filterargs, 'faculty_mentor', 'faculty mentor' ); ?>
						</form>
						
						<form class="form-inline">
							<!-- <label for="filter-symposium_group">Symposium Group</label> -->
							<?php echo get_all_project_tax_terms( $filterargs, 'symposium_group', 'symposium group' ); ?>
						</form>

						<form class="form-inline">
							<!-- <label for="filter-symposium_group">Symposium Group</label> -->
							<?php echo get_all_participant_tax_terms( $participant_ids, 'participant_details', 'student demographic' ); ?>
						</form>

						<button id="filter-reset" class="btn btn-dark btn-sm" type="reset" value="reset">
							<span class="fas fa-undo" title="Reset filters"></span>Reset
						</button>

						<button id="filter-shuffle" class="btn btn-dark btn-sm" value="shuffle">
							<span class="fas fa-random" title="Shuffle"></span>Shuffle
						</button>
					</div>
				</div><!-- end .filter-group -->
			</div><!-- end .col -->

		</div><!-- end .row -->

	</div><!-- Container end -->

</div><!-- Wrapper end -->

<?php get_footer(); ?>
