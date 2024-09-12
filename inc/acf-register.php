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
		'about-life-after-furi',
		'home-featured-mentor',
		'home-featured-project-carousel',
		'home-program-descriptions',
		'home-project-graph',
		'home-research-themes',
		'home-snapshot-current',
		'home-sponsored-projects',
		'mentor-list',
		'mentor-ready-list'
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
add_action( 'enqueue_block_assets', 'pitchfork_furi_register_additional_assets' );

/**
 * Explicitly define /acf-json as load point in both the parent theme and the child theme.
 *
 * @param  mixed $paths // path to ACF load point.
 * @return $paths
 */
function pitchfork_furi_acf_json_load_point( $paths ) {
	$paths[] = get_template_directory()  . '/acf-json';
	$paths[] = get_stylesheet_directory() . '/acf-json';
	return $paths;
}
add_filter( 'acf/settings/load_json', 'pitchfork_furi_acf_json_load_point' );
