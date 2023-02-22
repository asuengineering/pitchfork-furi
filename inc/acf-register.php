<?php
/**
 * Additional functions for Advanced Custom Fields.
 *
 * Contents:
 *   - Load path for ACF groups from the child theme.
 *   - Register custom blocks for the theme.
 *
 * @package pitchfork-furi
 */

// /**
//  * Register a new save point for the Local JSON feature for this theme.
//  *
//  * @param  mixed $path // path to ACF save point.
//  * @return $path
//  */
// function pitchfork_furi_acf_json_save_point( $path ) {
// 	$path = get_stylesheet_directory_uri() . '/acf-json';
// 	return $path;
// }
// add_filter( 'acf/settings/save_json', 'pitchfork_furi_acf_json_save_point' );

/**
 * Register a new load point for the Local JSON feature for this theme.
 *
 * @param  mixed $path // path to ACF save point.
 * @return $path
 */
function pitchfork_furi_acf_json_load_point( $path ) {
	$path = get_stylesheet_directory_uri() . '/acf-json';
	return $path;
}
add_filter( 'acf/settings/load_json', 'pitchfork_furi_acf_json_load_point' );


/**
 * Register a block category
 *
 * @return $categories
 */
add_filter( 'block_categories_all' , function( $categories ) {
	$categories[] = array(
		'slug'  => 'pitchfork-furi',
		'title' => 'Pitchfork FURI'
	);

	return $categories;
});

/**
 * Register blocks associated with the child theme.
 */
function pitchfork_furi_register_blocks() {

	$furi_svg_icon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><defs><style>.fa-secondary{opacity:.5}</style></defs><path class="fa-primary" d="M380.7 185.8c5.1-6.7 4.2-16.2-2.1-21.8s-15.9-5.3-21.9 .7l-179 179-13 13c-3 3-4.7 7.1-4.7 11.3v8 56 48c0 13.2 8.1 25 20.3 29.8s26.2 1.6 35.2-8.1L284 427.7l-60-25V389.4L380.7 185.8z"/><path class="fa-secondary" d="M498.1 5.6c10.1 7 15.4 19.1 13.5 31.2l-64 416c-1.5 9.7-7.4 18.2-16 23s-18.9 5.4-28 1.6L224 402.7V389.4L380.7 185.8c5.2-6.7 4.2-16.4-2.3-21.9s-16.1-5.1-22 1.1L178.8 350.6l-14.1 14.1c-3 3-4.7 7.1-4.7 11.3l-28.3-11.8-112-46.7C8.4 312.8 .8 302.2 .1 290s5.5-23.7 16.1-29.8l448-256c10.7-6.1 23.9-5.5 34 1.4z"/></svg>';

	$paths = array(
		'about-director-message',
		'about-life-after-furi',
		'home-featured-mentor',
		'home-featured-project-carousel',
		'home-program-descriptions',
		'home-project-graph',
		'home-research-themes',
		'home-snapshot-current',
		'home-sponsored-projects',
	);

	foreach ($paths as $path) {
		register_block_type(
			get_stylesheet_directory() . '/acf-block-templates/' . $path,
			array('icon' => $furi_svg_icon)
		);
	}
}
add_action( 'init', 'pitchfork_furi_register_blocks' );


/**
 * Register additional assets for when a block calls for them.
 *
 * Registered: Animate.css
 * Registered: Google charts + supporting init scripts within the theme.
 *
 */
function pitchfork_furi_register_additional_assets() {
	wp_register_style( 'pitchfork-furi-animate', 'https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css', array(), null );

	$the_theme = wp_get_theme();
	$theme_version = $the_theme->get( 'Version' );

	wp_register_script( 'google-charts', 'https://www.gstatic.com/charts/loader.js', array(), null , true );
	wp_register_script( 'furi-project-category', get_stylesheet_directory_uri() . '/js/custom-chart-project-category.js', array( 'google-charts' ), $theme_version, true );
	wp_register_script( 'furi-life-after', get_stylesheet_directory_uri() . '/js/custom-chart-life-after-furi.js', array( 'google-charts' ), $theme_version, true );

}
add_action( 'wp_enqueue_scripts', 'pitchfork_furi_register_additional_assets' );

// /**
//  * Register additional custom blocks for the theme.
//  */
// add_action('acf/init', 'furi_acf_init_block_types');
// function furi_acf_init_block_types() {

//     // Check function exists.
//     if( function_exists('acf_register_block_type') ) {

// 		// Home Page - Program Descriptions
// 		acf_register_block_type(array(
// 			'name'              => 'home_featured_project_carousel',
// 			'title'             => __('Featured Project Carousel'),
// 			'description'       => __('Display the featured image + project card in a carousel for the home page.'),
// 			'render_template'   => 'templates/blocks/home-featured-carousel.php',
// 			'category'          => 'FURI',
// 			'icon' 				=> array(
// 									'foreground' => '#8c1d40',
// 									'src' => 'admin-comments',
// 								),
// 			'keywords'          => array( 'home', 'carousel', 'featured' ),
// 			'enqueue_assets'	=> 'furi_enqueue_animate_css',
// 		));

//         // Home Page - Program Descriptions
//         acf_register_block_type(array(
//             'name'              => 'home_program_descriptions',
//             'title'             => __('Program Descriptions'),
//             'description'       => __('Display program descriptions from term meta data.'),
// 			'render_template'   => 'templates/blocks/home-program-desc.php',
//             'category'          => 'FURI',
// 			'icon' 				=> array(
// 									'foreground' => '#8c1d40',
// 									'src' => 'admin-comments',
// 								),
//             'keywords'          => array( 'home', 'program', 'description' ),
// 		));

// 		// Home Page - snapshot data, current symposium
// 		acf_register_block_type(array(
// 			'name'              => 'home_snapshot_current',
// 			'title'             => __('Symposium Snapshot (Current)'),
// 			'description'       => __('Display a row of data pertaining to the current symposium.'),
// 			'render_template'   => 'templates/blocks/home-snapshot-current.php',
// 			'category'          => 'FURI',
// 			'icon' 				=> array(
// 									'foreground' => '#8c1d40',
// 									'src' => 'admin-comments',
// 								),
// 			'keywords'          => array( 'home', 'snapshot' ),
// 		));

// 		// Home Page - Project Sponsors
// 		acf_register_block_type(array(
// 			'name'              => 'home_sponsored_projects',
// 			'title'             => __('Sponsored Projects'),
// 			'description'       => __('Display a layout of companies/organizations that sponsor FURI projects.'),
// 			'render_template'   => 'templates/blocks/home-sponsored-projects.php',
// 			'category'          => 'FURI',
// 			'icon' 				=> array(
// 									'foreground' => '#8c1d40',
// 									'src' => 'admin-comments',
// 								),
// 			'keywords'          => array( 'home', 'sponsored', 'project' ),
// 		));

// 		// Home Page - Featured Mentor
// 		acf_register_block_type(array(
// 			'name'              => 'home_featured_mentor',
// 			'title'             => __('Featured Mentor'),
// 			'description'       => __('A formatted display of a featured mentor & arbitrary quote.'),
// 			'render_template'   => 'templates/blocks/home-featured-mentor.php',
// 			'category'          => 'FURI',
// 			'icon' 				=> array(
// 									'foreground' => '#8c1d40',
// 									'src' => 'admin-comments',
// 								),
// 			'keywords'          => array( 'home', 'featured', 'mentor' ),
// 		));

// 		// About Page - Letter from director
// 		acf_register_block_type(array(
// 			'name'              => 'about_director_message',
// 			'title'             => __('Director Message'),
// 			'description'       => __('Displays the message from the director with a full width background image.'),
// 			'render_template'   => 'templates/blocks/about-director-message.php',
// 			'category'          => 'FURI',
// 			'icon' 				=> array(
// 									'foreground' => '#8c1d40',
// 									'src' => 'admin-comments',
// 								),
// 			'keywords'          => array( 'about', 'director' ),
// 			'supports'		=> [
// 				'customClassName'	=> true,
// 				'jsx' 				=> true,
// 			]
// 		));

// 		// Home Page - research themes, project count graph
// 		acf_register_block_type(array(
// 			'name'              => 'home_research_themes',
// 			'title'             => __('Research Themes / Project Graph'),
// 			'description'       => __('Display a layout of reearch themes and the "project count by theme" graph.'),
// 			'render_template'   => 'templates/blocks/home-project-categories.php',
// 			'category'          => 'FURI',
// 			'icon' 				=> array(
// 									'foreground' => '#8c1d40',
// 									'src' => 'admin-comments',
// 								),
// 			'keywords'          => array( 'home', 'themes', 'graph' ),
// 			'enqueue_assets'	=> 'furi_enqueue_google_bar_chart',
// 		));

// 		// About Page - Life after FURI
// 		acf_register_block_type(array(
// 			'name'              => 'about_life_after_furi',
// 			'title'             => __('Life After FURI'),
// 			'description'       => __('Circle graph of survey results for alumni.'),
// 			'render_template'   => 'templates/blocks/about-life-after-furi.php',
// 			'category'          => 'FURI',
// 			'icon' 				=> array(
// 									'foreground' => '#8c1d40',
// 									'src' => 'admin-comments',
// 								),
// 			'keywords'          => array( 'about', 'alumni', 'graph' ),
// 			'enqueue_assets'	=> 'furi_enqueue_google_pie_chart',
// 		));
//     }
// }

// // Circle chart. Enqueue google charts + correct chart init file when a block calls for those assets.
// function furi_enqueue_google_pie_chart() {
// 	$the_theme = wp_get_theme();
// 	$theme_version = $the_theme->get( 'Version' );

// 	wp_enqueue_script( 'google-charts', 'https://www.gstatic.com/charts/loader.js', array(), $theme_version, true );
// 	wp_enqueue_script( 'furi-life-after', get_stylesheet_directory_uri() . '/js/custom-chart-life-after-furi.js', array( 'google-charts' ), $theme_version, true );
// }

// // Bar chart. Enqueue google charts + correct chart init file when a block calls for those assets.
// function furi_enqueue_google_bar_chart() {
// 	$the_theme = wp_get_theme();
// 	$theme_version = $the_theme->get( 'Version' );

// 	wp_enqueue_script( 'google-charts', 'https://www.gstatic.com/charts/loader.js', array(), $theme_version, true );
// 	wp_enqueue_script( 'furi-project-category', get_stylesheet_directory_uri() . '/js/custom-chart-project-category.js', array( 'google-charts' ), $theme_version, true );
// }

