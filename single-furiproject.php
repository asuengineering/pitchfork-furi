<?php
/**
 * Single FURI Project
 *
 * @package uds-wordpress-theme
 */

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

get_header();
?>

<!-- Markup for the page -->
<div class="wrapper" id="page-wrapper">

	<div class="container" id="main-content">

		<div class="row">

			<div class="col-md-8">
			
			<?php
			while ( have_posts() ) :
				the_post();

				$symposiumdate = wp_strip_all_tags( get_the_term_list( $post->ID, 'symposium_date', '', ', ', '' ) );
				$presentationtype = wp_strip_all_tags( get_the_term_list( $post->ID, 'presentation_type', '', ', ', '' ) );
				$researchtheme = wp_strip_all_tags( get_the_term_list( $post->ID, 'research_theme', '', ', ', '' ) );

				?>

				<h4 class="presentation-details">
					<span class="highlight-gold">
						<?php echo esc_html( $presentationtype ) . ' | ' . esc_html( $symposiumdate ); ?>
					</span>
				</h4>

				<h1 class="project-name article"><?php the_title(); ?></h1>

			</div>

			<div id="project-icon-column" class="col-md-2 offset-md-2">
				<?php
				$projectthemes = get_the_terms( $post->ID, 'research_theme' );
				foreach ( $projectthemes as $projecttheme ) {
					$themeicon = get_field( 'researchtheme_icon', $projecttheme );
					echo '<img class="theme-icon img-fluid" src="' . esc_url( $themeicon['url'] ) . '" alt="' . esc_attr( $themeicon['alt'] ) . '" />';
				}
				?>
			</div>

		</div>

		<div class="row">
			<div class="col-md-8">

				<?php the_content(); ?>

				<div class="cta-buttons">
					<?php
						// The conference session button.
						echo wp_kses_post( get_symposium_status_url( $post->ID ) );

						// Is there an uploaded research poster?
						$poster = get_field( '_furiproject_poster', $post->ID );
					if ( ! empty( $poster ) ) {
						$postermarkup = '<a class="btn btn-maroon btn-poster" href="' . esc_url( $poster['url'] );
						$postermarkup .= '" target="_blank">View the poster<span class="fas fa-external-link-alt"></span></a>';
						echo $postermarkup;
					}
					?>
				</div>
			
			</div>

			<div class="col-md-3 offset-md-1" id="secondary">
				<?php

				// Find connected participants.
				$author = new WP_Query(
					array(
						'connected_type' => 'participants_to_projects',
						'connected_items' => get_queried_object(),
						'posts_per_page' => 1,
					)
				);

				// Display the first connected participant as a card.
				if ( $author->have_posts() ) :
					?>

					<h4><span class="highlight-gold">Student researcher</span></h4>
					
					<?php
					while ( $author->have_posts() ) :
						$author->the_post();

						echo '<div class="card">';

						if ( has_post_thumbnail() ) {
							echo get_the_post_thumbnail( $post->ID, 'large', array( 'class' => 'card-img-top' ) );
						}

						echo '<div class="card-body">';
						echo '<h3 class="participant-title">';
						echo '<a href="' . get_the_permalink() . '">';
						echo esc_html( furi_participant_name( $author->ID ) );
						echo '</a>';
						echo '</h3>';


						echo '<p class="card-text participant-major">' . esc_html( wp_strip_all_tags( get_the_term_list( $post->ID, 'degree_program', '', ', ', '' ) ) ) . '</p>';
						echo '<p class="card-text hometown"><strong>Hometown: </strong>' . esc_html( furi_participant_location_details() ) . '</p>';
						echo '<p class="card-text graduation-date"><strong>Graduation date: </strong>' . esc_html( wp_strip_all_tags( get_the_term_list( $post->ID, 'graduation_date', '', ', ', '' ) ) ) . '</p>';
						echo '</div>';

						echo '</div>';

					endwhile;
					wp_reset_postdata();
				endif;

				?>

			</div>

		</div><!-- end .row -->
	</div><!-- end .container -->

				<?php get_template_part( 'templates/related-projects' ); ?>

</div><!-- Wrapper end -->

<?php endwhile; // end of the loop. ?>

<?php get_footer(); ?>
