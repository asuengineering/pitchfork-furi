<?php
/**
 * Block template: Sponsored Projects
 *
 * @package pitchfork-furi
 */

// Load selected values from block.
$headline = get_field('block_generic_headline');
$description = get_field('block_generic_description');
$sponsors = get_field('block_project_sponsors_select');
$colclass = get_field('block_project_sponsors_columns');

if ( $sponsors ) {

    echo '<section id="sponsored-students">';
    echo '<div class="container">';
    echo '<div class="row row-header">';
    echo '<div class="col-md-8">';
    echo '<h2>' . $headline . '</h2>';
    echo wp_kses_post($description);
    echo '</div></div>';
    echo '<div class="row">';

    foreach ($sponsors as $sponsor) {
        echo '<div class="' . $colclass . '">';
        echo '<h3>' . esc_html( $sponsor->name ) . '</h3>';
        echo '<p>' . wp_kses_post( $sponsor->description ) . '</p>';
        echo '<p><a class="btn btn-md btn-maroon" href="'. get_term_link($sponsor) . '" title="' . esc_html( $sponsor->name ). '">View sponsored projects</a></p>';
        echo '</div>';
    }

    echo '</div></div></section>';
}

?>
