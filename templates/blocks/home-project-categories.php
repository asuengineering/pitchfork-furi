<?php
/**
 * Block template: Project Categories
 *
 * @package uds-wordpress-furi
 */

// Load selected values from block.
$headline = get_field('block_generic_headline');
$description = get_field('block_generic_description');
$bg_class = get_field('block_research_theme_background_class');
$themes = get_field('block_research_themes_select');

if ( $themes ) {

    // Get the active symposium id's 
    $term_ids = get_active_symposium_terms();
    $args = '';
    $bar_segment = array();
    // $bar_segment[] = array(
    //     'Research Topic',
    //     'Project Count',
    //     "{ role: 'style' }",
    //     "{ role: 'annotation' }",
    // );

    echo '<section id="research-themes" class="' . $bg_class . '">';
    echo '<div class="container">';
    echo '<div class="row">';
    echo '<div class="col-md-8">';
    echo '<h2>' . $headline . '</h2>';
    echo wp_kses_post($description);
    echo '</div></div>';
    echo '<div class="row">';

    foreach ($themes as $theme) {
        // Output the description.
        $themeicon  = get_field( 'researchtheme_icon', $theme );
        $themecolor = get_field( 'researchtheme_bg_color', $theme );
        echo '<div class="col-md-6"><div class="media">';
        echo '<img class="img-fluid mr-3" src="' . esc_url( $themeicon['url'] ) . '" alt="' . esc_attr( $themeicon['alt'] ) . '" />';
        echo '<div class="media-body"><h3 class="mt-0">' . esc_html( $theme->name ) . '</h3>';
        echo wp_kses_post( $theme->description );
        echo '</div></div></div>';

        // Prepare the data for bar chart below.
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
                    'taxonomy' => 'research_theme',
                    'terms' => $theme->term_id,
                )
            ),
        );

        $query = new WP_Query( $args );
        $themecount = $query->found_posts;

        $bar_segment[] = array(
            $theme->name,
            $themecount,
            'color: ' . $themecolor,
            $theme->name,
        );
    }

    wp_localize_script('furi-project-category', 'barsArray', $bar_segment);
    ?>

    </div><!-- end .row -->
    <div class="row">
        <div class="col-md-12">
            <h3>Project count in the current symposium</h3>
            <div id="subject-chart"></div>
        </div>
    </div>

    </div><!-- end .container -->
    </section>
    
    <?php
}
