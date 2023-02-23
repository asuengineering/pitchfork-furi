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


/**
 * Rewrite of footer_action_row function from parent theme.
 * Includes count-up feature in place of link columns.
 */
function pitchfork_furi_render_footer_action_row() {
	$action_row_status = get_theme_mod( 'footer_row_actions' );

	$multisite_enhance = false;
	$multisite_enhance = get_field('pitchfork_options_enhanced_multisite', 'option');

	$multisite_enhance_footer = false;
	$multisite_enhance_footer = get_field('pitchfork_options_root_footer', 'option');

	// Enhanced multisite check. Do we want to use the "root" footer links menu?
	if( (is_multisite()) && ( $multisite_enhance ) && ( $multisite_enhance_footer ) ) {
		// Switch our database context to the 'main' blog of our multisite.
		switch_to_blog( get_main_site_id() );
	}

	if ( 'enabled' === $action_row_status ) {
		?>
		<nav aria-label="Footer">
			<div class="container" id="footer-columns">
				<div class="row">

					<div class="col-lg-3" id="info-column">
						<h5><span class="footer-site-name" id="footer-unit-text"><?php uds_wp_render_footer_unit_name(); ?></span></h5>
						<div class="contact-wrapper">
							<?php uds_wp_render_contact_link(); ?>
						</div>

						<div class="contribute-wrapper">
							<?php uds_wp_render_contribute_button(); ?>
						</div>
					</div>
					<?php
					// include get_template_directory() . '/template-parts/asu-footer-menu.php';
					get_template_part( 'templates/snapshot', 'footer' );
					?>
				</div> <!-- row -->
			</div> <!-- footer-columns -->
		</nav>
		<?php
	}

	/**
	 * Because we may have switched blog IDs earlier, switch back to the current
	 * blog, just in case.
	 */
	if( is_multisite() && ms_is_switched() ) {
		restore_current_blog();
	}

}
