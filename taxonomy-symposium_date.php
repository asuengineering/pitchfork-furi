<?php
/**
 * The template for displaying results from a specific symposium date.
 * Used as an archive listing for all projects in the DB.
 *
 * @package UDS-WordPress-FURI
 */

get_header();
$term = get_queried_object();
?>

<main>

<h1 class="page-title entry-title pt-8"><?php echo $term->name; ?> symposium archive</h1>

<?php

p2p_type( 'participants_to_projects' )->each_connected( $wp_query );

if ( $wp_query->have_posts() ) :

	?>
	<section id="table-wrapper" class="mb-12">
		<table class="symposium-archive table table-striped table-bordered">

			<thead>
				<tr>
					<th>Project Title</th>
					<th>Participant</th>
					<th>Mentor</th>
					<th>Focus</th>
					<th>Type</th>
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
							<?php echo wp_strip_all_tags( get_the_term_list( $post->ID, 'research_theme', '', ', ', '' ) ); ?>
						</td>

						<td>
							<?php echo wp_strip_all_tags( get_the_term_list( $post->ID, 'presentation_type', '', ', ', '' ) ); ?>
						</td>
					</tr>

				<?php endwhile; ?>

			</tbody>
		</table>
	</section>

<?php endif; ?>

</main><!-- #main -->

<?php get_footer(); ?>
