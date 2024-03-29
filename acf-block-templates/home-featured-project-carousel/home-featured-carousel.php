<?php
/**
 * Block template: Featured Project Carousel
 *
 * @package uds-wordpress-furi
 */

$display_date = get_field('block_featured_project_carousel_year');
$display_all = get_field('block_featured_project_carousel_include_all');
$display_number = get_field('block_featured_project_carousel_include_count');

pitchfork_blocks_acf_calculate_spacing($block);

if ( $display_date ) {
    echo '<div id="featured-carousel">';
    echo '<div id="featured-projects" class="carousel carousel-animation slide" data-bs-ride="carousel" data-bs-interval="10000">';
    echo '<div class="carousel-inner">';

    // Set posts_per_page. Default for $display_all control is true. Default value = -1.
    $ppp = -1;
    if (! $display_all) {
        $ppp = $display_number;
    }

    $args = array(
        'post_type' => 'furiproject',
        'posts_per_page' => $ppp,
        'tax_query' => array(
            array(
                'taxonomy' => 'symposium_date',
                'terms'    => $display_date,
            ),
        ),
        'meta_query' => array(
            array(
                'key' => '_furiproject_featured',
                'value' => true,
                'compare' => '=',
            )
            ),
        'orderby' => 'rand',
    );

    $query = new WP_Query( $args );
    p2p_type( 'participants_to_projects' )->each_connected( $query, array(), 'relatedparticipants' );

    $activeclass = 0;
    $indicators = '';
	$mobile_proj_list = '';
    while ( $query->have_posts() ) :

        $query->the_post();
        global $post;
        // echo '<h1>The Post</h1>';
        // print_r($post);

        // echo '<h1>The Query</h1>';
        // print("<pre>".print_r($query,true)."</pre>");
        // print_r($query->ID);

        $featured_thumb = get_the_post_thumbnail( $query->ID, array(950,700), array( 'class' => 'img-fluid' ) );

        // Grab all related participants from the projects query above.
        foreach ( $post->relatedparticipants as $related ) :
            setup_postdata( $related );

            $relatedparticipant = furi_participant_name( $related->ID );
            $major = wp_strip_all_tags( get_the_term_list( $related->ID, 'degree_program', '', ', ', '' ) );
            $participantlink = get_permalink( $related->ID );

        endforeach;

        $presentationtype = wp_strip_all_tags( get_the_term_list( $query->ID, 'presentation_type', '', ', ', '' ) );
        $projectimpact = get_field( '_furiproject_impact_statement', get_the_id() );
        $projectclassname = get_research_theme_class_names( $query->ID );

        $indicators .= '<button aria-label="Slide ' . $activeclass . '" data-bs-target="#featured-projects" data-bs-slide-to="' . $activeclass . '"';
        if ( 0 == $activeclass ) {
            $indicators .= ' class="active" aria-current="true"';
        }
        $indicators .= "></button>";

        // Output.

        if ( 0 == $activeclass ) {
            echo '<div class="carousel-item active">';
        } else {
            echo '<div class="carousel-item">';
        }
        echo '<div class="animate__animated featured-image">';
        echo $featured_thumb;
        echo '</div>';

        ?>

        <div class="card card-symposium card-home animate__animated">
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
                    <strong>Mentor: </strong><?php echo get_the_term_list( $query->ID, 'faculty_mentor', '', ', ', '' ); ?>
                </p>
                <p class="card-text project-type">
                    <strong>Program: </strong><?php echo esc_html( $presentationtype ); ?>
                </p>
            </div>
		</div>
		</div>

        <?php

		$mobile_proj_list .= '<li><a href="' . esc_url( $participantlink ) . '">' . get_the_title() . '</a> by ' . esc_html( $relatedparticipant ) . '</li>';

    $activeclass ++;
    endwhile;

    // Close up the carousel-inner div, add controls.
    ?>
    </div><!-- end .carousel-inner -->

    <div class="carousel-controls">
        <button type="button" class="btn btn-circle btn-circle-alt-white" data-bs-target="#featured-projects" data-bs-slide="prev">
            <i class="fas fa-chevron-left"></i>
            <span class="sr-only" >Previous</span>
        </button>

        <button type="button" class="btn btn-circle btn-circle-alt-white" data-bs-target="#featured-projects" data-bs-slide="next">
            <i class="fas fa-chevron-right"></i>
            <span class="sr-only" >Next</span>
        </button>

        <div class="carousel-indicators">
            <?php echo $indicators; ?>
		</div>
    </div>

    <?php
    echo '</div><!-- end #featured-projects -->';

	echo '<ul id="carousel-mobile-content" class="uds-list">' . $mobile_proj_list . '</ul>';

    echo '</div><!-- end #featured-carousel --> ';
}
