<?php
/**
 * Pitchfork child theme functions
 *
 * @package pitchfork-child
 */

 // Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


// Included files. The order is important.
// ===============================================

require get_stylesheet_directory() . '/inc/enqueue.php';
require get_stylesheet_directory() . '/inc/custom-post-types.php';
require get_stylesheet_directory() . '/inc/posts-to-posts.php';
require get_stylesheet_directory() . '/inc/acf-register.php';
require get_stylesheet_directory() . '/inc/options-qrcode.php';
require get_stylesheet_directory() . '/inc/data-helpers.php';
require get_stylesheet_directory() . '/inc/gravity-forms.php';

/**
 * Prints inline styles for research project categories.
 * Useful for color coding elements on the fly with a CSS class instead of inline styles.
 * Produces classes in the pattern of ".theme-{$term->slug}-bg"
 */
function furiproject_research_category_colors() {

	$output = '';

	$terms = get_terms(
		array(
			'taxonomy'   => 'research_theme', // Swap in your custom taxonomy name
			'hide_empty' => true,
		)
	);

	// Loop through all terms with a foreach loop
	foreach ( $terms as $term ) {
		$bg_hexvalue = get_field( 'researchtheme_bg_color', $term );
		$text_hexvalue = get_field( 'researchtheme_text_color', $term );
		if ( ! empty( $bg_hexvalue ) ) {
			$output .= '.theme-' . $term->slug . '-bg { background-color: ' . esc_attr( $bg_hexvalue ) . ' !important; } ';
			$output .= '.theme-' . $term->slug . '-bg:hover { background-color: ' . esc_attr( $bg_hexvalue ) . ' !important; } ';
			$output .= '.theme-' . $term->slug . '-text, .theme-' . $term->slug . ':visited { color: ' . esc_attr( $text_hexvalue ) . ' !important; } ';
			$output .= '.theme-' . $term->slug . '-text:hover { color: ' . esc_attr( $text_hexvalue ) . ' !important; } ';
		}
	}

	if ( ! empty( $output ) ) {
		wp_add_inline_style( 'pitchfork-furi-child-styles', $output );
	}

}
add_action( 'wp_enqueue_scripts', 'furiproject_research_category_colors' );


/**
 * Adds a section of content right above the global footer.
 */
function uds_furi_add_symposium_totals() {
	get_template_part( 'templates/snapshot', 'footer' );
}
add_action( 'uds_wp_before_global_footer', 'uds_furi_add_symposium_totals' );

/**
 * Adds support for post thumbnails to participant CPT.
 * CPT definition defines functionality, this enables it across the whole theme.
 */
function uds_furi_enable_post_thumbnails() {
	add_theme_support( 'post-thumbnails', array( 'participant', 'furiproject' ) );
}
add_action( 'after_setup_theme', 'uds_furi_enable_post_thumbnails' );
