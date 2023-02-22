<?php
/**
 * Block template: Project Categories
 *
 * @package pitchfork-furi
 */

$use_active = get_field('project_graph_display_active_event');
$event_dates = get_field('project_graph_select_dates');

/**
 * Either gets the active term dates or uses additional control
 * to select the correct dates to display.
 */
if ($use_active) {
	$term_ids = get_active_symposium_terms();
} else {
	$term_ids = $event_dates;
}

$args = '';
$bar_segment = array();

echo '<section id="project-graph">';

/**
 * For each selected symposium date, we're just going to check all possible themes.
 * Since there's not that many of them, this is OK in terms of effeciency.
 */
$themes = get_terms( array('taxonomy' => 'research_theme') );

foreach ($themes as $theme) {

	// Prepare the data for bar chart below.
	$args = array(
		'post_type' => 'furiproject',
		'posts_per_page' => -1,
		'tax_query' => array(
			'relation' => 'AND',
			array(
				'taxonomy' => 'symposium_date',
				'terms'    => $term_ids,
			),
			array(
				'taxonomy' => 'research_theme',
				'terms' => $theme->term_id,
			)
		),
	);

	$query = new WP_Query( $args );
	$themecount = $query->found_posts;
	$themecolor = get_field('researchtheme_bg_color', $theme);

	/** Bar segment definitions from Google. */
	$bar_segment[] = array(
		$theme->name,
		$themecount,
		'color: ' . $themecolor,
		$theme->name,
	);
}

wp_localize_script('furi-project-category', 'barsArray', $bar_segment);

echo '<div id="subject-chart"></div>';
echo '</section>';
