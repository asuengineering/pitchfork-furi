<?php
/**
 * Single Furi Participant
 *
 * @package uds-wordpress-theme
 *
 */

get_header();

while ( have_posts() ) :
	the_post();

	/**
	 * Gathers meta data relates to a furi participant and returns a formatted location string.
	 *
	 * @return $location
	 */
	function furi_participant_location_details() {
		$location = '';

		if ( ! empty( get_field( '_participant_city' ) ) ) {
			$location .= get_field( '_participant_city' ) . ', ';
		}

		if ( ! empty( get_field( '_participant_state' ) ) ) {
			$location .= get_field( '_participant_state' ) . ', ';
		}

		if ( ! empty( get_field( '_participant_country' ) ) ) {
			$location .= get_field( '_participant_country' ) . ', ';
		}

		$location = trim( $location, ', ' );
		return $location;
	}

	/**
	 * Create social media icon row based on profile data.
	 *
	 * @return $participantsocial;
	 */
	function furi_participant_social_icons() {

		$participant_fb = get_field( '_participant_facebook' );
		$participant_li = get_field( '_participant_linkedin' );
		$participant_tw = get_field( '_participant_twitter' );
		$participant_ig = get_field( '_participant_instagram' );

		$participantsocial = '';

		if ( ! empty( $participant_tw ) ) {
			$participantsocial .= '<li><a href="' . $participant_tw . '" target=_blank><i class="fab fa-twitter"></i></a></li>';
		}

		if ( ! empty( $participant_li ) ) {
			$participantsocial .= '<li"><a href="' . $participant_li . '" target=_blank><i class="fab fa-linkedin"></i></a></li>';
		}

		if ( ! empty( $participant_fb ) ) {
			$participantsocial .= '<li><a href="' . $participant_fb . '" target=_blank><i class="fab fa-facebook"></i></a></li>';
		}

		if ( ! empty( $participant_ig ) ) {
			$participantsocial .= '<li><a href="' . $participant_ig . '" target=_blank><i class="fab fa-instagram"></i></a></li>';
		}

		if ( ! empty( $participantsocial ) ) {
			$participantsocial = '<ul class="participant-social">' . $participantsocial . '</ul>';
		}

		return $participantsocial;
	}
	?>

<!-- <div class="container-fluid">
	<button id="openModalButton" class="btn btn-dark">Show modal</button>
    <div id="uds-modal" class="uds-modal">
        <div class="uds-modal-container">
			<button id="closeModalButton" class="uds-modal-close-btn">
				<i class="fas fa-times fa-stack-1x"></i><span class="sr-only">Close</span></button>
            <h1>Content</h1>
        </div>
    </div>
</div> -->

<!-- Markup for the page -->
<div class="wrapper" id="page-wrapper">

	<div class="container" id="main-content">

		<div class="row">

			<div class="col-md-10">

				<h1 class="participant-name"><?php echo esc_html( furi_participant_name( $post->ID ) ); ?></h1>

				<h3 class="participant-major">
					<?php echo esc_html( wp_strip_all_tags( get_the_term_list( $post->ID, 'degree_program', '', ', ', '' ) ) ); ?>
				</h3>

				<p class="hometown">
					<strong>Hometown: </strong>
					<?php echo esc_html( furi_participant_location_details() ); ?>
				</p>
				<p class="graduation-date">
					<strong>Graduation date: </strong>
					<?php echo esc_html( wp_strip_all_tags( get_the_term_list( $post->ID, 'graduation_date', '', ', ', '' ) ) ); ?>
				</p>

				<?php
				$details = '';
				$details = wp_strip_all_tags( get_the_term_list( $post->ID, 'participant_details', '', ', ', '' ) );
				if ( $details ) {
					echo '<p class="participant-details"><strong>Additional details: </strong>';
					echo esc_html( $details );
					echo '</p>';
				}
				?>

			</div>

			<div id="project-icon-column" class="col-md-2"></div>

		</div>

		<!-- Featured Image -->
		<div class="row">

			<?php

			if ( has_post_thumbnail() ) {
				echo '<div class="col-md-4">';
				echo get_the_post_thumbnail( $post->ID, 'large', array( 'class' => 'img-fluid' ) );
				echo wp_kses_post( furi_participant_social_icons() );
				echo '</div>';
			}

			?>

			<div class="col-md-8">

				<?php
				// Find connected projects.
				$firstconnected = new WP_Query(
					array(
						'connected_type' => 'participants_to_projects',
						'connected_items' => get_queried_object(),
						'posts_per_page' => 1,
					)
				);

				// Display the first connected project with greater detail.
				if ( $firstconnected->have_posts() ) :
					while ( $firstconnected->have_posts() ) :
						$firstconnected->the_post();

						$symposiumdate = wp_strip_all_tags( get_the_term_list( $post->ID, 'symposium_date', '', ', ', '' ) );
						$presentationtype = wp_strip_all_tags( get_the_term_list( $post->ID, 'presentation_type', '', ', ', '' ) );
						$researchtheme = wp_strip_all_tags( get_the_term_list( $post->ID, 'research_theme', '', ', ', '' ) );

						$projectthemes = get_the_terms( $post->ID, 'research_theme' );
						foreach ( $projectthemes as $projecttheme ) {
							$themeicon = get_field( 'researchtheme_icon', $projecttheme );
							echo '<div class="project-icon"><img class="img-fluid" src="' . esc_url( $themeicon['url'] ) . '" alt="' . esc_attr( $themeicon['alt'] ) . '" /></div>';
						}
						?>

						<h3 class="presentation-details">
							<span class="highlight-gold">
								<?php echo esc_html( $presentationtype ) . ' | ' . esc_html( $symposiumdate ); ?>
							</span>
						</h3>

						<h2 class="project-title">
							<?php the_title(); ?>
						</h2>

						<?php the_content(); ?>

						<p class="project-mentor"><strong>Mentor: </strong><?php echo wp_kses_post( get_the_term_list( $post->ID, 'faculty_mentor', '', ', ', '' ) ); ?></p>

						<div class="cta-buttons">
							<?php
							// The conference session button.
							echo wp_kses_post( get_symposium_status_url( $post->ID ) );

							// Is there an uploaded research poster?
							$poster = get_field( '_furiproject_poster', $post->ID );

							if ( ! empty( $poster ) ) {
								$postermarkup = '<a class="btn btn-maroon btn-poster" href="' . esc_url( $poster['url'] );
								$postermarkup .= '" target="_blank">View the poster<span class="fas fa-external-link-alt"></span></a>';
								echo wp_kses_post( $postermarkup );
							}

							// insert the QR code modal button and modal window.
							echo qr_code_modal_window();
							?>

						</div>

						<?php

						$featured_yn  = get_field( '_furiproject_featured', $post->ID );
						$sponsored_yn = get_field( '_furiproject_sponsored', $post->ID );

						if ( $featured_yn || $sponsored_yn ) {
							// Let's close the current row and start a new one.
							echo '</div></div><!-- end .col / .row -->';
							echo '<div class="row row-featured">';

							if ($featured_yn) {

								$featured_content = get_field( '_furiproject_featured_text' );
								if (! empty( $featured_content ) ) {

									echo '<div class="col-md-8">';
									echo '<h3><span class="highlight-gold">Featured project | ' . esc_html( $symposiumdate ) . '</span></h3>';
									echo wp_kses_post( $featured_content );
									echo '</div>';
								}

								$featured_images = get_field( '_furiproject_featured_images' );
								if (! empty( $featured_images ) ) {

									echo '<div class="col-md-12">';
									echo '<div class="featured-image-set d-md-flex flex-md-row">';
									foreach( $featured_images as $image ):
										?>
										<a href="<?php echo esc_url($image['url']); ?>" class="featured-image" target="_blank">
										<img src="<?php echo esc_url($image['sizes']['medium']); ?>" class="uds-img figure-img" alt="<?php echo esc_attr($image['alt']); ?>"></img>
										</a>
										<?php
									endforeach;
									echo '</div></div>';
								}
							}

							if ($sponsored_yn) {

								$sponsored_content = get_field( '_furiproject_sponsored_text' );
								if (! empty( $sponsored_content ) ) {
									echo '<div class="col-md-8">';
									echo '<h3><span class="highlight-gold">Sponsored project | ' . esc_html( $symposiumdate ) . '</span></h3>';
									echo wp_kses_post( $sponsored_content );
									echo '</div>';
								}

								$sponsored_images = get_field( '_furiproject_sponsored_images' );
								if (! empty( $sponsored_images ) ) {
									echo '<div class="col-md-12">';
									echo '<div class="d-md-flex flex-md-row">';
									foreach( $sponsored_images as $image ):
										?>
										<a href="<?php echo esc_url($image['url']); ?>" class="featured-image" target="_blank">
										<img src="<?php echo esc_url($image['sizes']['medium']); ?>" class="uds-img figure-img" alt="<?php echo esc_attr($image['alt']); ?>"></img>
										</a>
										<?php
									endforeach;
									echo '</div></div>';
								}
							}
						}

					endwhile;
					wp_reset_postdata();
				endif;

				?>

			</div>

		</div><!-- end .row -->
	</div><!-- end .container -->

	<!-- Other projects by the same person. -->

	<?php

	// Find the rest of the connected projects.
	$otherconnected = new WP_Query(
		array(
			'connected_type' => 'participants_to_projects',
			'connected_items' => get_queried_object(),
			'posts_per_page' => 10,
			'offset' => 1,
		)
	);

	if ( $otherconnected->have_posts() ) :
		?>

		<div class="section-head" id="additional-projects-title">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<h3>Additional projects from this student</h3>
					</div>
				</div>
			</div>
		</div>

		<section id="additional-projects">
			<div class="container">
				<div class="row">

				<?php
				while ( $otherconnected->have_posts() ) :
					$otherconnected->the_post();

					$symposiumdate = wp_strip_all_tags( get_the_term_list( $post->ID, 'symposium_date', '', ', ', '' ) );
					$presentationtype = wp_strip_all_tags( get_the_term_list( $post->ID, 'presentation_type', '', ', ', '' ) );
					$projectimpact = get_field( '_furiproject_impact_statement' );

					?>
					<div class="col-sm-4">
						<div class="card card-hover card-additional-projects">
							<div class="card-header">
								<h3 class="card-title project-title">
									<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a>
								</h3>
							</div>
							<div class="card-body">
								<p class="card-text">
									<?php echo esc_html( $projectimpact ); ?>
								</p>
								<p class="card-text project-mentor">
									<strong>Mentor: </strong><?php echo get_the_term_list( $post->ID, 'faculty_mentor', '', ', ', '' ); ?>
								</p>
							</div>
							<ul class="card-tags">
								<li class="btn btn-tag btn-tag-alt-white"><?php echo esc_html( $presentationtype ); ?></li>
								<li class="btn btn-tag btn-tag-alt-white"><?php echo esc_html( $symposiumdate ); ?></li>
							</ul>
						</div>
					</div><!-- end .col -->

					<?php

				endwhile;

				echo '</div><!-- end .row -->';
				echo '</div><!-- end .container -->';
				echo '</section><!-- end #additional-projects-->';

				wp_reset_postdata();

	endif;

	get_template_part( 'templates/related-projects' );

	?>

</div><!-- Wrapper end -->

<?php endwhile; // end of the loop. ?>

<?php get_footer(); ?>
