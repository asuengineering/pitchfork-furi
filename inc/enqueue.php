<?php
/**
 * Helping functions for various symposium detail outputs.
 *
 * @package uds-wordpress-furi
 */

add_action( 'enqueue_block_assets', 'pitchfork_wordpress_child_scripts' );
/**
 * Enqueue theme assets.
 */
function pitchfork_wordpress_child_scripts() {
	// Get the theme data.
	$the_theme     = wp_get_theme();
	$theme_version = $the_theme->get( 'Version' );

	$css_child_version = $theme_version . '.' . filemtime( get_stylesheet_directory() . '/dist/css/child-theme.css' );
	wp_enqueue_style( 'pitchfork-furi-child-styles', get_stylesheet_directory_uri() . '/dist/css/child-theme.css', array(), $css_child_version );

	$js_child_version = $theme_version . '.' . filemtime( get_stylesheet_directory() . '/dist/js/child-theme.js' );
	wp_enqueue_script( 'pitchfork-furi-child-script', get_stylesheet_directory_uri() . '/dist/js/child-theme.js', array( 'jquery' ), $js_child_version );
}

/**
 * Enqueue scripts and styles.
 */
function pitchfork_furi_enqueue_styles() {

	// Get the theme data.
	$the_theme = wp_get_theme();
	$theme_version = $the_theme->get( 'Version' );

	// Check for page templates and load more things.
	if ( is_page() ) {
		global $wp_query;
		$template_name = get_post_meta( $wp_query->post->ID, '_wp_page_template', true );

		if ( 'symposium.php' == $template_name ) {
			wp_enqueue_style( 'bs-select', 'https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/css/bootstrap-select.min.css', array(), null );
			wp_enqueue_script( 'isotope', 'https://unpkg.com/isotope-layout@3/dist/isotope.pkgd.min.js', array(), '', true );
			wp_enqueue_script( 'bs-select', 'https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js', array( 'jquery' ), '', true );
			// wp_enqueue_script( 'scotch-panels', get_stylesheet_directory_uri() . '/node_modules/scotch-panels/dist/scotchPanels.min.js', array( 'jquery' ), '', true );
			wp_enqueue_script( 'furi-symposium', get_stylesheet_directory_uri() . '/dist/js/custom-symposium.js', array( 'jquery' ), $theme_version, true );
		}

		if ( 'fullpage-about.php' == $template_name ) {
			wp_enqueue_script( 'google-charts', 'https://www.gstatic.com/charts/loader.js', array(), $theme_version, true );
			wp_enqueue_script( 'furi-about', get_stylesheet_directory_uri() . '/dist/js/custom-charts.js', array( 'google-charts' ), $theme_version, true );
		}

		// Check for symposium-date archive pages and load DataTables JS.
		if ( 'data-dump.php' == $template_name ) {
			wp_enqueue_style( 'datatables-bootstrap4', '//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css', array(), null );
			wp_enqueue_script( 'datatables-js', '//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js', array(), '', true );
			wp_enqueue_script( 'datatables-bootstrap4-js', '//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js', array(), '', true );
			wp_enqueue_script( 'custom-datatables-js', get_stylesheet_directory_uri() . '/dist/js/custom-datatables.js', array( 'jquery' ), $theme_version, true );
		}
	}

	// Check for the home page (front page) and load the animate CSS library.
	// if ( is_front_page() ) {
	// 	wp_enqueue_style( 'animate', 'https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css', array(), null );
	// }

	// Check for filterable archive pages and load DataTables JS.
	if ( ( is_tax( 'symposium_date' )) || ( is_tax( 'research_theme' )) || ( is_tax( 'presentation_type' ))  ) {
		wp_enqueue_style( 'datatables-bootstrap4', '//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css', array(), null );
		wp_enqueue_script( 'datatables-js', '//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js', array(), '', true );
		wp_enqueue_script( 'datatables-bootstrap4-js', '//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js', array(), '', true );
		wp_enqueue_script( 'custom-datatables-js', get_stylesheet_directory_uri() . '/dist/js/custom-datatables.js', array( 'jquery' ), $theme_version, true );
	}
}

add_action( 'wp_enqueue_scripts', 'pitchfork_furi_enqueue_styles' );
