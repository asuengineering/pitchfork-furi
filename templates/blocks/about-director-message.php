<?php
/**
 * Block template: About, Director Message
 *
 * @package uds-wordpress-furi
 */

$headline = get_field('director_message_headline');
$background = get_field('director_message_background_image');

$allowed_blocks = array( 'core/heading', 'core/paragraph' );

$template = array(
    array( 'core/paragraph', array(
        'content' => 'This is the text of the letter or other content. Replace this generic content prior to publication.',
    ) )
);

if ( $background ) {

    echo '<section id="director-message" style="background: url(' . $background . ') top center no-repeat;" >';
    echo '<div class="container">';
    echo '<div class="row row-title">';
    echo '<div class="col-md-12">';
    echo '<h2>' . $headline . '</h2>';
    echo '</div></div>';
    echo '<div class="row row-letter">';
    echo '<div class="col-md-10 offset-md-1 bg-white">';

    echo '<InnerBlocks allowedBlocks="' . esc_attr( wp_json_encode( $allowed_blocks ) ) . '" template="' . esc_attr( wp_json_encode( $template ) ) . '" />';

    echo '</div></div>';
    echo '<div class="row row-signature">';
    echo '<div class="col-md-10 offset-md-1 bg-white">';

    // Check for signee rows and output the correct strings.
    if( have_rows('director_message_signature_row') ):

        echo '<div class="signatures">';

        while( have_rows('director_message_signature_row') ) : the_row();

            $signee_name = get_sub_field('director_message_signee_name');
            $signee_title = get_sub_field('director_message_signee_title');
            $signee_position = get_sub_field('director_message_signee_position');

            echo '<p class="signee"><span>' . $signee_name . '</span>' . $signee_title . '</br>' . $signee_position . '</p>';

        endwhile;

        echo '</div>';

    endif;

    echo '</div></div>';
    echo '</section>';

} // end if background.

    