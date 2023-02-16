<?php
/**
 * Related Projects. Pull three at random from currently displayed symposium, same topic.
 *
 * @package uds-wordpress-theme
 */

$active = get_active_symposium_terms();

$relatedprojects = new WP_Query(
	array(
		'post_type' => 'furiproject',
		'posts_per_page' => 3,
		'post_status' => 'publish',
		'post__not_in' => array( get_the_ID() ),
		'tax_query' => array(
			array(
				'taxonomy' => 'symposium_date',
				'terms'    => $active,
			),
		),
		'orderby' => 'rand',
	)
);

// Grab all related participants from the projects query above.
p2p_type( 'participants_to_projects' )->each_connected( $relatedprojects, array(), 'relatedparticipants' );

if ( $relatedprojects->have_posts() ) :
	?>
	<div class="section-head" id="related-projects-title">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<!-- <h3>More projects from the + echo esc_html( get_active_symposium_names() ); + symposium</h3> -->
					<h3>More projects from the current symposium</h3>
				</div>
			</div>
		</div>
	</div>

	<section id="related-projects">
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
								<p class="card-text project-mentor">
									<strong>Mentor: </strong><?php echo get_the_term_list( $post->ID, 'faculty_mentor', '', ', ', '' ); ?>
								</p>
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

	wp_reset_postdata();

endif;

?>
