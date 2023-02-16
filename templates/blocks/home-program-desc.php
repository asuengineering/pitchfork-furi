<?php
/**
 * Block template: Program Description (Home)
 *
 * @package uds-wordpress-furi
 */

// Load selected values from block.
$colmodifier = get_field('block_progdesc_layout');
$programs = get_field('block_progdesc_terms');

if ( $programs ) {
    ?>
    <div class="container" id="program-descriptions">
        <div class="row">
            <?php

            foreach ($programs as $program) {
                $longname = get_field('presentertype_program_name' , $program );
                echo '<div class="' . $colmodifier . '">';
                echo '<h4>' . $longname . '</h4>';
                echo '<p>' . wp_kses_post( $program->description ) . '</p>';
                echo '</div>';
            }

            ?>
        </div>
    </div>
    <?php
}