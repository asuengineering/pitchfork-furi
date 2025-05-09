<?php
/**
 * Template Name: Symposium
 *
 * @package uds-wordpress-theme
 */

get_header();
?>

<main id="skip-to-content" <?php post_class(); ?>>

	<?php

	while ( have_posts() ) {

		the_post();

		// Parse through the blocks on the page.
		// If an acf/hero block is in the #1 position, do nothing.
		// Otherwise display the post title.
		$content_blocks = parse_blocks( $post->post_content );
		$first_block_names = array('acf/hero', 'acf/hero-video', 'acf/hero-post', 'core/post-title');

		if ( ! in_array( $content_blocks[0]['blockName'], $first_block_names )) {
			the_title( '<div class="page-title"><h1 class="entry-title">', '</h1></div>' );
		}

		the_content();
	}

	?>

</main><!-- #main -->


<!-- Markup for the symposium listing -->
<section class="wrapper" id="page-wrapper">\
	<div class="container-lg" id="symposium-content">
		<div class="row">
			<div class="col-lg-9 order-2" id="symposium-grid-col">
				<div class="row">
					<div class="col">
						<div id="pre-grid-filters">
							<div class="d-flex align-items-center">
								<h4>Search<span class="fa-solid fa-magnifying-glass"></span></h4>
								<form id="keyword" class="uds-form flex-grow-1">
									<input id="keyword-filter" type="text" class="quicksearch form-control" placeholder="Type a keyword" />
								</form>
								<button id="show-offcanvas" class="btn btn-md btn-dark d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#filter-offcanvas">
									<span class="fa-solid fa-filters"></span>
									Show filters
								</button>
							</div>
							<p class="filter-count">Displaying all projects.</p>
						</div>
					</div>
				</div>
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
								<div class="col-md-8 grid-item card-ranking large-image <?php echo esc_html( $project_classes . $participant_classes ); ?>">
									<?php echo $featured_thumb; ?>
									<div class="info-layer">
										<div class="content">
											<div class="header">
												<h4 class="participant">
													<a aria-label="Read more" href="<?php echo esc_url( $participantlink ); ?>">
														<?php echo esc_html( $relatedparticipant ); ?>
													</a>
													<span class="major"><?php echo esc_html( $major ); ?></span>
												</h4>
												<button class="btn btn-circle btn-alt-white btn-expand" aria-label="Expand ranking" type="button" aria-expanded="false">
													<span class="fa-solid fa-chevron-up"></span>
													<span class="sr-only">Expand</span>
												</button>
											</div>
											<div class="body">
												<p class="project-impact"><?php echo esc_html( $projectimpact ); ?></p>
												<p class="project-mentor"><strong>Mentor: </strong><?php echo wp_kses_post( $mentorlist ); ?></p>
												<p class="project-type"><strong>Program: </strong><?php echo esc_html( $presentationtype ); ?></p>
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

			<div id="filter-sidebar" class="col-lg-3 d-none d-lg-block order-1">
				<aside id="filter-wrap">

					<div id="filter-header">
						<div class="d-flex align-items-center justify-content-between">
							<h3><span class="highlight-gold">Filters</span></h3>
							<button id="offcanvas-close" type="button" class="btn btn-md btn-dark" data-bs-dismiss="offcanvas"><span class="fas fa-times"></span>Close</button>
						</div>
						<p class="filter-count sidebar-filter-count">Displaying all projects.</p>
					</div>

					<div class="filter-group radios">
						<form id="research-theme-filters">
							<label for="filter-research_theme">Research Themes</label>
							<?php echo get_research_theme_radios( $filterargs, 'research_theme', 'Research Theme' ); ?>
						</form>

						<form id="presentation-type-filters">
							<label for="filter-presentation_type">Presentation Types</label>
							<?php echo get_project_type_radios( $filterargs, 'presentation_type', 'Presentation Type' ); ?>
						</form>
					</div>
					<div class="filter-group selects">
						<form id="degree" class="uds-form"><div classs="form-group">
							<label for="filter-degree_program">Degree Program</label>
							<?php echo get_all_participant_tax_terms( $participant_ids, 'degree_program', 'degree program' ); ?>
						</div></form>

						<form id="faculty" class="uds-form"><div classs="form-group">
							<label for="filter-faculty_mentor">Faculty Mentor</label>
							<?php echo get_all_project_tax_terms( $filterargs, 'faculty_mentor', 'faculty mentor' ); ?>
						</div></form>

						<form id="group" class="uds-form"><div classs="form-group">
							<label for="filter-symposium_group">Symposium Group</label>
							<?php echo get_all_project_tax_terms( $filterargs, 'symposium_group', 'symposium group' ); ?>
						</div></form>

						<form id="details" class="uds-form"><div classs="form-group">
							<label for="filter-participant_details">Participant Details</label>
							<?php echo get_all_participant_tax_terms( $participant_ids, 'participant_details', 'student demographic' ); ?>
						</div></form>

						<button id="filter-reset" class="btn btn-dark btn-sm" type="reset" value="reset">
							<span class="fas fa-undo" title="Reset filters"></span>Reset
						</button>

						<button id="filter-shuffle" class="btn btn-dark btn-sm" value="shuffle">
							<span class="fas fa-random" title="Shuffle"></span>Shuffle
						</button>
					</div><!-- end .filter-group -->
				</aside>
			</div><!-- end .col -->

		</div><!-- end .row -->

	</div><!-- Container end -->

	<!-- Offcanvas for mobile -->
	<div class="offcanvas offcanvas-start d-lg-none" tabindex="-1" id="filter-offcanvas">
		<div class="offcanvas-body" id="filter-offcanvas-body"></div>
	</div>

</section><!-- Wrapper end -->

<?php get_footer(); ?>
