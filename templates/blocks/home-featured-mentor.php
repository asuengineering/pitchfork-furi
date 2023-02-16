<?php
/**
 * Block template: Featured Mentors
 *
 * @package uds-wordpress-furi
 */

$headline = get_field('block_generic_headline');
$description = get_field('block_generic_description');
$mentors = get_field('block_featured_mentor_select');

if ( $mentors ) {

    echo '<section id="featured-mentors">';
    echo '<div class="container">';
    echo '<div class="row row-header">';
    echo '<div class="col-md-8">';
    echo '<h2>' . $headline . '</h2>';
    echo wp_kses_post($description);
    echo '</div></div>';
    echo '<div class="row">';

    foreach ($mentors as $mentor) {
                        
        $mentorprogram = get_field( '_mentor_featured_program', $mentor );
        $mentorimage = get_field( '_mentor_acf_thumbnail', $mentor );

        echo '<div class="row">';

        if ( ! empty( $mentorimage ) ) {
            echo '<div class="col-md-3">';
            echo '<img class="img-fluid" src="' . esc_html( $mentorimage ) . '" alt="' . esc_html( $mentor->name ) . '" />';
            echo '</div>';
        }
        
        echo '<div class="col-md-9">';
        echo '<h3><a href="'. get_term_link($mentor) . '" title="' . esc_html( $mentor->name ). '">' . esc_html( $mentor->name ) . '</a>, featured ' . esc_html( $mentorprogram->name ) . ' mentor</h3>';
        
        // Which content should be displayed? The quote or the post?
        $mentor_use_quote = get_field( '_mentor_use_quote_yn', $mentor );
        
        if ( $mentor_use_quote ) {

            // Use the quote + the description for the term.
            $mentorquote = get_field( '_mentor_featured_quote', $mentor);
            $mentorlinkcite = get_field( '_mentor_featured_linked_citation', $mentor );

            if ( !empty ($mentorquote)) {
                echo '<figure class="uds-blockquote accent-gold bq-color-padding">';
                echo '<div class="feature-wrapper">';
                echo '<svg title="Open quote" role="decorative" viewBox="0 0 302.87 245.82">';
                echo '<path d="M113.61,245.82H0V164.56q0-49.34,8.69-77.83T40.84,35.58Q64.29,12.95,100.67,0l22.24,46.9q-34,11.33-48.72,31.54T58.63,132.21h55Zm180,0H180V164.56q0-49.74,8.7-78T221,35.58Q244.65,12.95,280.63,0l22.24,46.9q-34,11.33-48.72,31.54t-15.57,53.77h55Z"></path>';
                echo '</svg>';
                echo '</div>';
                echo '<div class="content-wrapper">';
                echo '<blockquote>';
                echo '<p>' . wp_kses_post( $mentorquote ) .'</p>';
                echo '</blockquote>';
                echo '<figcaption>';

                if ( ! empty( $mentorlinkcite )) {

                    $citedname = furi_participant_name( $mentorlinkcite->ID );
                    $citedmajor = wp_strip_all_tags( get_the_term_list( $mentorlinkcite->ID, 'degree_program', '', ', ', '' ) );
                
                    echo '<cite class="name">';
                    echo '<a href="' . esc_url( get_permalink( $mentorlinkcite ) ) . '" title="' . esc_html( $citedname ) . '">';
                    echo  esc_html( trim( $citedname ) ) . '</a>';
                    echo '</cite>';
                    echo '<cite class="description">' . esc_html( $citedmajor ) . '</cite>';

                } else {

                    $mentorcitename = get_field( '_mentor_featured_citation_name', $mentor );
                    $mentorcitedesc = get_field( '_mentor_featured_citation_description', $mentor );

                    echo '<cite class="name">' . wp_kses_post( $mentorcitename ) . '</cite>';
                    echo '<cite class="description">' . wp_kses_post( $mentorcitedesc ) . '</cite>';
                }

                echo '</figcaption>';
                echo '</div>';
                echo '</figure>';
            }

        } else {

            // Use the content from the blog post.
            $mentorpost = get_field( '_mentor_featured_post', $mentor );
            
            if ( !empty ($mentorpost)) {
                do_action( 'qm/debug', $mentorpost);
                echo '<div class="featured-mentor-post">';
                echo '<span class="fas fa-stars"></span>';
                $mentorpost_excerpt = apply_filters( 'the_excerpt', $mentorpost->post_excerpt );
                $mentorpost_readmore = '<a href="' . esc_url(get_permalink($mentorpost->ID)) . '" class="read-more btn btn-maroon">Read more</a>';
                echo '<p class="excerpt">' . $mentorpost_excerpt . '</p><p class="read-more">' . $mentorpost_readmore . '</p>';
                echo '</div>';
            }

            

        }

        echo '</div>';  // End col.
        echo '</div>'; // End row.
    }

    echo '</div>';  // End container.
    echo '</section>'; // End section.
}

