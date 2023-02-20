<?php
/**
 * Block template: Symposium Snapshot (Current)
 *
 * @package pitchfork-furi
 */

// Load selected values from block.
$headline = get_field('block_generic_headline');
$description = get_field('block_generic_description');
$programs = get_field('block_snapshot_current_programs');

// Get the active symposium id's
$term_ids = get_active_symposium_terms();
$args = '';

if ( $programs ) {

    echo '<div class="container" id="current-snapshot">';
    echo '<div class="row">';
    echo '<div class="col-md-8">';
    echo '<h2>' . $headline . '</h2>';
    echo wp_kses_post($description);
    echo '</div></div>';
    echo '<div class="row">';

    foreach ($programs as $program) {

        $args = array(
            'post_type' => 'furiproject',
            'posts_per_page' => -1,
            'tax_query' => array(
                'relation' => 'AND',
                array(
                    'taxonomy' => 'symposium_date',
                    'terms'    => $term_ids,
                ),
                array(
                    'taxonomy' => 'presentation_type',
                    'terms' => $program->term_id,
                )
            ),
        );

        $query = new WP_Query( $args );
        $projectcount = $query->found_posts;

        $mentor_count = array();

        // Loop through the current query, building an array of all mentor IDs
        if (!empty( $query->posts )){
            foreach ($query->posts as $project){

                $mentor_ids = get_the_terms( $project->ID, 'faculty_mentor');

                foreach ($mentor_ids as $mentor_id) {
                    $term = $mentor_id->term_id;
                    if (!in_array( $term, $mentor_count )) {
                        $mentor_count[] = $term;
                    }
                }
            }
        }

        // Output for the actual boxes
        ?>
        <div class="col">
            <div class="stat-package">
                <h3><span><?php echo esc_html( $program->name );?></span>projects</h3>
                <div class="counter" data-count="<?php echo $projectcount; ?>">0</div>
            </div>
            <div class="stat-package">
                <h3><span><?php echo esc_html( $program->name );?></span>mentors</h3>
                <div class="counter" data-count="<?php echo count( $mentor_count); ?>">0</div>
            </div>
        </div>
        <?php
    }

    // Clean up the open row/container.
    echo '</div></div>';
}
