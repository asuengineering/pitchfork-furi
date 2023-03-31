<?php
/**
 * Template Name: Questions/Answers
 * Template Post Type: post
 *
 * The default template for displaying all single posts.
 * References content-single for formatting of info within the loop.
 *
 * @package pitchfork-furi
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

get_header();
?>

	<main id="skip-to-content">

		<header id="article-header" class="alignfull">
			<div class="entry">
				<?php
					the_title( '<h1 class="entry-title">', '</h1>' );
					echo '<p>Posted on: ' . get_the_date() . '</p>';
				?>
			</div>
			<div class="featured-mentor">
				<?php
					// Retrieve details about a possible linked mentor.
					$post_id = get_the_ID();
					$mentor = get_the_terms( $post_id, 'faculty_mentor');

					// Produce a linked faculty mentor portrait & details if one is available.
					if ( ! empty ( $mentor ) ) {

						$mentorID = 'term_' . $mentor[0]->term_id;
						$mentor_asurite = get_field( '_mentor_asurite', $mentorID );
						if (! empty( $mentor_asurite )) {
							$search_json = 'https://search.asu.edu/api/v1/webdir-profiles/faculty-staff/filtered?asurite_ids=' . $mentor_asurite . '&size=1&client=fse_furl';

							$search_request = wp_safe_remote_get( $search_json );

							// Error check for invalid JSON.
							if ( is_wp_error( $search_request ) ) {
								return false; // Bail early.
							}

							$search_body   = wp_remote_retrieve_body( $search_request );
							$search_data   = json_decode( $search_body );

							if ( ! empty( $search_data ) ) {
								$path = $search_data->results[0];
								$mentor_photo = $path->photo_url->raw;
							}

						}

						// Check for featured mentor status. Should be set at this point.
						$mentorstring = '';
						$mentorprogram = get_field( '_mentor_featured_program', $mentorID );
						if ( ! empty ( $mentorprogram )) {
							$mentorstring = '<h3><span class="highlight-gold">Featured mentor, ' . esc_html( $mentorprogram->name ) . '</span></h3>';
						}

						$portrait = '';
						// Check for featured image or Search photo
						if (! empty( $mentor_photo ) ) {
							$portrait = '<img class="isearch-image img-fluid" src="' . $mentor_photo . '" alt="Portrait of ' . $mentor[0]->name . '"/>';
						}

						echo $portrait;
						echo '<div class="mentor-name"><h2>' . $mentor[0]->name . '</h2>';
						echo $mentorstring;
						echo '<p><a href="' . get_term_link( $mentor[0]->term_id ) . '">View mentored projects</a></p></div>';

					}

				?>
			</div>
		</header>


		<article id="post-<?php the_ID(); ?>" <?php post_class('alignfull'); ?>>
			<section id="content">
				<?php

					while ( have_posts() ) {
						the_post();
					}

					the_content();

					echo '<footer class="entry-footer default-max-width">';
						pitchfork_entry_footer();
					echo '</footer>';

					pitchfork_the_post_navigation();

					// Comments template would go here.

				?>
			</section>
			<aside id="sidebar">
				<h4>Additional featured mentors</h4>
				<?php

					$articlelist = '';
					$mentorargs = array(
						'category_name' => 'featured-mentor',
						'numberposts' => -1,
					);

					$mentorposts = get_posts($mentorargs);

					if( ! empty( $mentorposts ) ){
						$articlelist = '<ul>';
						foreach ( $mentorposts as $mentorpost ) {
							do_action('qm/debug', $mentorpost);
							$articlelist .= '<li><a href="' . get_permalink( $mentorpost->ID ) . '">' . $mentorpost->post_title . '</a></li>';
						}
						$articlelist .= '</ul>';
					}

					echo $articlelist;
				?>
			</aside>
		</article>
	</main>
<?php
get_footer();
