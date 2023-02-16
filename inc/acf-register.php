<?php
/**
 * Additional functions for Advanced Custom Fields.
 * 
 * Contents:
 *   - Load path for ACF groups from the parent.
 *   - Register custom blocks for the theme.
 *
 * @package uds-wordpress-furi
 */


/**
 * Add additional loading point for the parent theme's ACF groups.
 *
 * @return $paths
 */
add_filter( 'acf/settings/load_json', 'furi_parent_theme_field_groups' );
function furi_parent_theme_field_groups( $paths ) {
	$path = get_template_directory() . '/acf-json';
	$paths[] = $path;
	return $paths;
}

/**
 * Register additional custom blocks for the theme.
 */
add_action('acf/init', 'furi_acf_init_block_types');
function furi_acf_init_block_types() {

    // Check function exists.
    if( function_exists('acf_register_block_type') ) {

		// Home Page - Program Descriptions
		acf_register_block_type(array(
			'name'              => 'home_featured_project_carousel',
			'title'             => __('Featured Project Carousel'),
			'description'       => __('Display the featured image + project card in a carousel for the home page.'),
			'render_template'   => 'templates/blocks/home-featured-carousel.php',
			'category'          => 'FURI',
			'icon' 				=> array(
									'foreground' => '#8c1d40',
									'src' => 'admin-comments',
								),
			'keywords'          => array( 'home', 'carousel', 'featured' ),
			'enqueue_assets'	=> 'furi_enqueue_animate_css',
		));

        // Home Page - Program Descriptions
        acf_register_block_type(array(
            'name'              => 'home_program_descriptions',
            'title'             => __('Program Descriptions'),
            'description'       => __('Display program descriptions from term meta data.'),
			'render_template'   => 'templates/blocks/home-program-desc.php',
            'category'          => 'FURI',
			'icon' 				=> array(
									'foreground' => '#8c1d40',
									'src' => 'admin-comments',
								),
            'keywords'          => array( 'home', 'program', 'description' ),
		));
		
		// Home Page - snapshot data, current symposium
		acf_register_block_type(array(
			'name'              => 'home_snapshot_current',
			'title'             => __('Symposium Snapshot (Current)'),
			'description'       => __('Display a row of data pertaining to the current symposium.'),
			'render_template'   => 'templates/blocks/home-snapshot-current.php',
			'category'          => 'FURI',
			'icon' 				=> array(
									'foreground' => '#8c1d40',
									'src' => 'admin-comments',
								),
			'keywords'          => array( 'home', 'snapshot' ),
		));

		// Home Page - Project Sponsors
		acf_register_block_type(array(
			'name'              => 'home_sponsored_projects',
			'title'             => __('Sponsored Projects'),
			'description'       => __('Display a layout of companies/organizations that sponsor FURI projects.'),
			'render_template'   => 'templates/blocks/home-sponsored-projects.php',
			'category'          => 'FURI',
			'icon' 				=> array(
									'foreground' => '#8c1d40',
									'src' => 'admin-comments',
								),
			'keywords'          => array( 'home', 'sponsored', 'project' ),
		));

		// Home Page - Featured Mentor
		acf_register_block_type(array(
			'name'              => 'home_featured_mentor',
			'title'             => __('Featured Mentor'),
			'description'       => __('A formatted display of a featured mentor & arbitrary quote.'),
			'render_template'   => 'templates/blocks/home-featured-mentor.php',
			'category'          => 'FURI',
			'icon' 				=> array(
									'foreground' => '#8c1d40',
									'src' => 'admin-comments',
								),
			'keywords'          => array( 'home', 'featured', 'mentor' ),
		));

		// About Page - Letter from director
		acf_register_block_type(array(
			'name'              => 'about_director_message',
			'title'             => __('Director Message'),
			'description'       => __('Displays the message from the director with a full width background image.'),
			'render_template'   => 'templates/blocks/about-director-message.php',
			'category'          => 'FURI',
			'icon' 				=> array(
									'foreground' => '#8c1d40',
									'src' => 'admin-comments',
								),
			'keywords'          => array( 'about', 'director' ),
			'supports'		=> [
				'customClassName'	=> true,
				'jsx' 				=> true,
			]
		));

		// Home Page - research themes, project count graph
		acf_register_block_type(array(
			'name'              => 'home_research_themes',
			'title'             => __('Research Themes / Project Graph'),
			'description'       => __('Display a layout of reearch themes and the "project count by theme" graph.'),
			'render_template'   => 'templates/blocks/home-project-categories.php',
			'category'          => 'FURI',
			'icon' 				=> array(
									'foreground' => '#8c1d40',
									'src' => 'admin-comments',
								),
			'keywords'          => array( 'home', 'themes', 'graph' ),
			'enqueue_assets'	=> 'furi_enqueue_google_bar_chart',
		));

		// About Page - Life after FURI
		acf_register_block_type(array(
			'name'              => 'about_life_after_furi',
			'title'             => __('Life After FURI'),
			'description'       => __('Circle graph of survey results for alumni.'),
			'render_template'   => 'templates/blocks/about-life-after-furi.php',
			'category'          => 'FURI',
			'icon' 				=> array(
									'foreground' => '#8c1d40',
									'src' => 'admin-comments',
								),
			'keywords'          => array( 'about', 'alumni', 'graph' ),
			'enqueue_assets'	=> 'furi_enqueue_google_pie_chart',
		));
    }
}

// Circle chart. Enqueue google charts + correct chart init file when a block calls for those assets.
function furi_enqueue_google_pie_chart() {
	$the_theme = wp_get_theme();
	$theme_version = $the_theme->get( 'Version' );

	wp_enqueue_script( 'google-charts', 'https://www.gstatic.com/charts/loader.js', array(), $theme_version, true );
	wp_enqueue_script( 'furi-life-after', get_stylesheet_directory_uri() . '/js/custom-chart-life-after-furi.js', array( 'google-charts' ), $theme_version, true );
}

// Bar chart. Enqueue google charts + correct chart init file when a block calls for those assets.
function furi_enqueue_google_bar_chart() {
	$the_theme = wp_get_theme();
	$theme_version = $the_theme->get( 'Version' );

	wp_enqueue_script( 'google-charts', 'https://www.gstatic.com/charts/loader.js', array(), $theme_version, true );
	wp_enqueue_script( 'furi-project-category', get_stylesheet_directory_uri() . '/js/custom-chart-project-category.js', array( 'google-charts' ), $theme_version, true );
}

// Enqueue animate.css when a block calls for those assets.
function furi_enqueue_animate_css() {
	wp_enqueue_style( 'animate', 'https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css', array(), null );
}

