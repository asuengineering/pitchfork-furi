<?php

/**
 * The template for displaying sponsored projects from a particular sponsor.
 * Used as an archive listing for all projects in the DB.
 */

get_header();
$term = get_queried_object();
$web_url = get_field( 'sponsor_url', $term);
?>

<div class="wrapper" id="page-wrapper">

	<div class="container" id="content">

		<main class="site-main" id="main">

			<div class="row">
				<div class="col-md-12">
					<h1 class="sponsor-name"><?php echo $term->name; ?></h1>
				</div>
			</div>
			<div class="row">
				<div class="col-md-7">
					<p class="lead"><?php echo wp_kses_post( $term->description ); ?></p>
					<?php
					if (! empty( $web_url )) {
						echo '<p><a class="btn btn-maroon" href="' . esc_html( $web_url ) . '" target="_blank"><span class="fa fa-globe"></span>Website</a></p>';
					}
					?>
				</div>
			</div>
		</div><!-- end .container -->

		<?php
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
							'taxonomy' => 'industry_sponsor',
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
