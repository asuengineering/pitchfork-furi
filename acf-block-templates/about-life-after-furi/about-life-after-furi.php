<?php
/**
 * Block template: About Page, Life after FURI graph
 *
 * @package pitchfork-furi
 */

// Load selected values from block.
$description = get_field('life_after_furi_description');

echo '<section id="life-after-furi">';
echo '<div class="container">';
echo '<div class="row">';
echo '<div class="col-md-12">';
echo '<div class="col-wrap">';
echo '<h4>Life After<span>FURI</span></h4>';
echo '<p class="intro">' . $description . '</p>';
echo '<div id="donutchart"></div>';

// Check for signee rows and output the correct strings.
if( have_rows('life_after_furi_callout_row') ):

    echo '<div class="distribution d-flex flex-row justify-content-between">';

    while( have_rows('life_after_furi_callout_row') ) : the_row();

        $callout_percentage = get_sub_field('life_after_furi_callout_percentage');
        $callout_label = get_sub_field('life_after_furi_callout_label');

        echo '<div>' . $callout_percentage . '<p>' . $callout_label . '</p></div>';

    endwhile;

    echo '</div>';

endif;

echo '</div></div></div></div>';
echo '</section>';

// Build the array to send to the Google charts init script.
if( have_rows('life_after_furi_segments') ):

    $segment_values = array();
    $segment_values[] = array(
        'Response',
        'Percentage'
    );
    $segment_colors = array();

    while( have_rows('life_after_furi_segments') ) : the_row();

        $segment_count = get_sub_field('life_after_furi_segment_count');
        $segment_label = get_sub_field('life_after_furi_segment_label');
        $segment_color = get_sub_field('life_after_furi_segment_color');

        // Converts a string to a number.
        $segment_count = $segment_count + 0;

        $segment_values[] = array(
            $segment_label,
            $segment_count
        );

        array_push($segment_colors, $segment_color);

    endwhile;

    wp_localize_script('furi-life-after', 'segmentsArray', array(
        'segments' => $segment_values,
        'colors' => $segment_colors,
    ));

endif;
