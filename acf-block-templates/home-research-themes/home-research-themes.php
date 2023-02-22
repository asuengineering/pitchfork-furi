<?php
/**
 * Block template: Project Categories
 *
 * @package pitchfork-furi
 */

// Load selected values from block.
$themes = get_field('block_research_themes_select');

if ( $themes ) {

    echo '<section id="research-themes">';
    echo '<div class="container">';
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
    }
    ?>

    </div><!-- end .row -->
    </div><!-- end .container -->
    </section>

    <?php
}
