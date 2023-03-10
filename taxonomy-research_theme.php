<?php
/**
 * The template for displaying results from a specific research theme.
 *
 * @package pitchfork-furi
 */

get_header();
$term = get_queried_object();
$themeicon = get_field( 'researchtheme_icon', $term );

?>

<main>
	<section class="archive-header">
		<img class="theme-icon img-fluid" src="<?php echo esc_url( $themeicon['url'] ); ?>'" alt="<?php echo esc_attr( $themeicon['alt'] ); ?>" />
		<div class="subtitle"><span class="highlight-gold">Research Theme</span></div>
		<h1 class="page-title entry-title"><?php echo $term->name; ?></h1>
		<p class="lead"><?php echo wp_kses_post( $term->description ); ?></p>
	</section>

<?php

p2p_type( 'participants_to_projects' )->each_connected( $wp_query );

if ( $wp_query->have_posts() ) :

	?>
	<section class="uds-section alignfull allowed-after-hero bg-color bg-gray-1">
		<div class="container" id="table-wrapper">

			<table class="symposium-archive table table-striped table-bordered">

				<thead>
					<tr>
						<th>Project Title</th>
						<th>Participant</th>
						<th>Mentor</th>
						<th>Type</th>
						<th>Year</th>
					</tr>
				</thead>
				<tbody>

					<?php
					while ( $wp_query->have_posts() ) :
						$wp_query->the_post();
						?>

						<tr <?php post_class(); ?> id="post-<?php the_ID(); ?>">

							<td>
								<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a>
							</td>

							<td>
								<?php
								foreach ( $post->connected as $post ) :
									setup_postdata( $post );

									$participant = get_field( '_participant_first_name', $post->ID ) . ' ' . get_field( '_participant_last_name', $post->ID );
									$participantlink = get_the_permalink();

									echo '<a href="' . $participantlink . '" title="Participant: ' . $participant . '">' . $participant . '</a>';

								endforeach;

								wp_reset_postdata(); // set $post back to original post
								?>
							</td>

							<td>
								<?php echo get_the_term_list( $post->ID, 'faculty_mentor', '', ', ', '' ); ?>
							</td>

							<td>
								<?php echo wp_strip_all_tags( get_the_term_list( $post->ID, 'presentation_type', '', ', ', '' ) ); ?>
							</td>

							<td>
								<?php echo wp_strip_all_tags( get_the_term_list( $post->ID, 'symposium_date', '', ', ', '' ) ); ?>
							</td>
						</tr>

					<?php endwhile; ?>

				</tbody>
			</table>
		</div>
	</section>

<?php endif; ?>

</main><!-- #main -->

<?php get_footer(); ?>
