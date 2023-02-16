<?php
/**
 * UDS WordPress FURI child theme functions and definitions
 *
 * @package uds-wordpress-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

add_action( 'wp_enqueue_scripts', 'uds_wordpress_child_dequeue', 100 );
/**
 * Dequeue theme assets with a very late priority.
 */
function uds_wordpress_child_dequeue() {
	wp_dequeue_script( 'uds-wordpress-fa-scripts');
}

add_action( 'wp_enqueue_scripts', 'uds_wordpress_child_scripts' );
/**
 * Enqueue theme assets.
 */
function uds_wordpress_child_scripts() {
	// Get the theme data.
	$the_theme     = wp_get_theme();
	$theme_version = $the_theme->get( 'Version' );

	$css_child_version = $theme_version . '.' . filemtime( get_stylesheet_directory() . '/css/child-theme.min.css' );
	wp_enqueue_style( 'uds-wordpress-child-styles', get_stylesheet_directory_uri() . '/css/child-theme.min.css', array( 'uds-wordpress-styles' ), $css_child_version );

	$js_child_version = $theme_version . '.' . filemtime( get_stylesheet_directory() . '/js/child-theme.js' );
	wp_enqueue_script( 'uds-wordpress-child-script', get_stylesheet_directory_uri() . '/js/child-theme.js', array( 'jquery' ), $js_child_version );
	
	// Load Font Awesome 5, from our kit.
	wp_enqueue_script( 'uds-furi-fontawesome-pro', 'https://kit.fontawesome.com/3fdebab6fc.js', array(), null, false );
}



/**
 * Enqueue scripts and styles.
 */
function furi_child_theme_enqueue_styles() {

	// Get the theme data.
	$the_theme = wp_get_theme();
	$theme_version = $the_theme->get( 'Version' );

	// Check for page templates and load more things.
	if ( is_page() ) {
		global $wp_query;
		$template_name = get_post_meta( $wp_query->post->ID, '_wp_page_template', true );

		if ( 'symposium.php' == $template_name ) {
			wp_enqueue_style( 'bs-select', 'https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.18/dist/css/bootstrap-select.min.css', array(), null );
			wp_enqueue_script( 'isotope', 'https://unpkg.com/isotope-layout@3/dist/isotope.pkgd.min.js', array(), '', true );
			wp_enqueue_script( 'bs-select', 'https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.18/dist/js/bootstrap-select.min.js', array( 'jquery' ), '', true );
			wp_enqueue_script( 'scotch-panels', get_stylesheet_directory_uri() . '/node_modules/scotch-panels/dist/scotchPanels.min.js', array( 'jquery' ), '', true );
			wp_enqueue_script( 'furi-symposium', get_stylesheet_directory_uri() . '/js/custom-symposium.js', array( 'jquery' ), $theme_version, true );
		}

		if ( 'fullpage-about.php' == $template_name ) {
			wp_enqueue_script( 'google-charts', 'https://www.gstatic.com/charts/loader.js', array(), $theme_version, true );
			wp_enqueue_script( 'furi-about', get_stylesheet_directory_uri() . '/js/custom-charts.js', array( 'google-charts' ), $theme_version, true );
		}

		// Check for symposium-date archive pages and load DataTables JS.
		if ( 'data-dump.php' == $template_name ) {
			wp_enqueue_style( 'datatables-bootstrap4', '//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css', array(), null );
			wp_enqueue_script( 'datatables-js', '//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js', array(), '', true );
			wp_enqueue_script( 'datatables-bootstrap4-js', '//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js', array(), '', true );
			wp_enqueue_script( 'custom-datatables-js', get_stylesheet_directory_uri() . '/js/custom-datatables.js', array( 'jquery' ), $theme_version, true );
		}
	}

	// Check for the home page (front page) and load the animate CSS library.
	// if ( is_front_page() ) {
	// 	wp_enqueue_style( 'animate', 'https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css', array(), null );
	// }

	// Check for symposium-date archive pages and load DataTables JS.
	if ( is_tax( 'symposium_date' ) ) {
		wp_enqueue_style( 'datatables-bootstrap4', '//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css', array(), null );
		wp_enqueue_script( 'datatables-js', '//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js', array(), '', true );
		wp_enqueue_script( 'datatables-bootstrap4-js', '//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js', array(), '', true );
		wp_enqueue_script( 'custom-datatables-js', get_stylesheet_directory_uri() . '/js/custom-datatables.js', array( 'jquery' ), $theme_version, true );
	}
}

add_action( 'wp_enqueue_scripts', 'furi_child_theme_enqueue_styles' );


// Included files. The order is important.
// ===============================================

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
		wp_add_inline_style( 'uds-wordpress-child-styles', $output );
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